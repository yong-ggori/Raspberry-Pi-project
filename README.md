# Raspberry-Pi

라즈베리파이에서 서버를 구축한다.

Coretex - M4 에서 mq135, mq2 센서 데이터를 시리얼 통신으로 라즈베리파이에 전달하고 
라즈베리파이에서 mariaDB에 데이터를 저장한다.

저장된 데이터는 웹서버에서 그래프 및 테이블로 출력해서 보여준다.

+ Coretex - M4 에서는 두 센서 외에 LCD, LED, Servo moter를 제어한다.
  LCD에는 두 센서 데이터가 출력되고 LED는 일정 값 이상일 때 점멸되며 servo moter는 창문을 여 닫는 모습을 구현했다.
++ M4 보드에 Fan도 달려 있지만 Fan의 제어는 웹 서버의 버튼에서 이루어 진다.
