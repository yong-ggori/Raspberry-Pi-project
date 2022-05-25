#pragma warning(disable :4966)

#include <stdio.h>
#include <stdlib.h>
#include <errno.h>
#include <string.h>

#include <wiringPi.h>
#include <wiringSerial.h>
#include <mysql/mysql.h>


#define MAX_SIZE 100

static char *host = "localhost";
static char *user = "root";
static char *pass = "kcci";
static char *dbname = "pi_project";

char device[] = "/dev/ttyACM0";
int fd;
unsigned long baud = 9600;

int main() {
	FILE *fp;
	MYSQL *conn;
	conn = mysql_init(NULL);

	int sql_index, res = 0, flag = 0; //res = 쿼리문 반환
	int count = 0;
	char in_sql[200] = {0};
	char fan_state[MAX_SIZE], state_buffer[MAX_SIZE];

	if(!(mysql_real_connect(conn, host, user, pass, dbname, 0, NULL, 0)))
	{
		fprintf(stderr, "ERROR : %s[%d]\n", mysql_error(conn), mysql_errno(conn));
		exit(1);
	}
	else
		printf("Connection Successful\n");

	char ser_buff[10] = {0};
	int index = 0, gas = 0, str_len, fan_len, state_len;
	float co2;
	char *pArray[2] = {0};
	char *pToken;

	printf("Raspberry Pi startup\n");
	fflush(stdout);
	if((fd = serialOpen(device, baud)) < 0 ) {
		fprintf(stderr, "Unable %s\n", strerror(errno));
		exit(1);
	}
	if(wiringPiSetup() == -1)
		return 1;

	while(1)
	{
		if(serialDataAvail(fd))
		{
			ser_buff[index++] = serialGetchar(fd);
			if(ser_buff[index-1] == 'L')
			{
				flag = 1;
				ser_buff[index-1] = '\0';
				str_len = strlen(ser_buff);
				printf("ser_buff = %s\n", ser_buff);
				pToken = strtok(ser_buff, ":");
				int i = 0;
				while(pToken != NULL) {
					pArray[i] = pToken;
					if(++i > 3)
						break;
					pToken = strtok(NULL, " ");
				}
				co2 = atof(pArray[0]);
				gas = atoi(pArray[1]);
				printf("co2 = %.2f, gas = %d\n", co2, gas);
				//printf("temp = %d, humi = %d\n", temp, humi);
				for(int i = 0; i <= str_len; i++)
					ser_buff[i] = 0;

				fp = fopen("/var/www/html/fan_state.txt", "r");
				fgets(fan_state, MAX_SIZE, fp);

				if(strcmp(fan_state, "ON") == 0){
					sprintf(state_buffer, "ONP");
				} else if(strcmp(fan_state, "OFF") == 0) {
					sprintf(state_buffer, "OFFP");
				}

				fan_len = strlen(fan_state);
				state_len = strlen(state_buffer);

				while(state_buffer[count]!='\0'){
					serialPutchar(fd, state_buffer[count++]);
				}
				printf("buffer : %s\n",  state_buffer);
				for(int idx = 0; idx <= state_len; idx++)
					state_buffer[idx] = 0;
				for(int idx = 0; idx <= fan_len; idx++)
					fan_state[idx] = 0;

				index = 0;
			}
			if(co2 < 1400.0 && gas < 1000) // 이산화탄소 값 및 미세먼지 튀는 값 제외
			{
				if(flag == 1)
				{
					sprintf(in_sql, "insert into sensing(ID, DATE, TIME, CO2, GAS) values(null, curdate(), curtime(), %.2f, %d)", co2, gas);
					res = mysql_query(conn, in_sql);
					printf("res = %d\n", res);
					if(!res)
						printf("inserted %lu rows\n", (unsigned long)mysql_affected_rows(conn));
					else {
						fprintf(stderr, "ERROR : %s[%d]\n", mysql_error(conn), mysql_errno(conn));
						exit(1);
					}
				}
			}
		}
		count = 0;
		flag = 0;
		fflush(stdout);
	}
	mysql_close(conn);
	return EXIT_SUCCESS;
}
