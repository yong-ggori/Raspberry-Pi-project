<!DOCTYPE html>
<html>
<head>
	<meta charset = "UTF-8">
	<meta http-equiv="refresh" content="30">
	<style type = "text/css">
		.spec{
			text-align:center;
		}
		.con{
			text-align:left;
		}
	</style>
</head>
<body>
	<h1 align = "center"><span style="background: linear-gradient(to right, #ffa7a3, #5673bd); padding: 0.43em 1em; font-size: 19px; border-radius: 3px; color: #ffffff;">CO2 & Gas detect data</span></h1>
	<div class = "spec">
		# <b>The sensor value desciption</b><br>
		# 200 ~ 1400.00 CO2 (clean air = 400.00 ~ 450.00) <br>
		# 1 ~ 1000 Gas (default gas value = 200 ~ 230) <br><br>
		<A href = "/project_table.php">데이터 베이스 확인</A>
		<br><br><br><br><br>
	</div>
	<center>
		<b>Fan State</b><br>
		<form method="get" action="project_sqltable.php">
			<input type="submit" value="ON" name="ON">
			<input type="submit" value="OFF" name="OFF"><br><br><br>
		</form>
		<?php
		if(isset($_GET['ON'])){
			$fp = fopen("/var/www/html/fan_state.txt",'w');
			fwrite($fp, "ON");
			fclose($fp);
			echo "fan ON";
		}
		else if(isset($_GET['OFF'])){
			$fp = fopen("/var/www/html/fan_state.txt",'w');
			fwrite($fp, "OFF");
			fclose($fp);
			echo "fan OFF";
		}
		?>
	</center>
</body>
</html>
