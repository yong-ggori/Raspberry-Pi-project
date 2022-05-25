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
		#home{
			border-radius: 5px;
			border: 2px solid skyblue;
			background-color: rgba(0,0,0,0);
			color: skyblue;
			padding: 5px;
		}
		#home:hover{
			color:white;
			background-color:skyblue;
		}
		table{
			width: 80%;
			border-collapse: collapse;
		}
		th, td{
			padding: 5px;
		}
	</style>
</head>
<body>
	<h1 align = "center" style="border-bottom: 1px solid #87CEEB; padding: 1.0em;">CO2 & Gas detect data</h1>
	<div align = "center">
			<button type="button" id="home" onclick="location.href='project_sqltable.php'">home</button>
		<br><br>
	<table border = '1' align = "center">

	<tr align = "center">
		<th>ID</th>
		<th>DATE</th>
		<th>TIME</th>
		<th>CO2</th>
		<th>GAS</th>
	</tr>
	<?php
		$conn = mysqli_connect("localhost", "root", "kcci");
		mysqli_select_db($conn, "pi_project");
		$result = mysqli_query($conn, "select * from sensing");

		while($row = mysqli_fetch_array($result)) {
		echo "<tr align = center>";
		echo '<td>'.$row['ID'].'</td>';
		echo '<td>'.$row['DATE'].'</td>';
		echo '<td>'.$row['TIME'].'</td>';
		echo '<td>'.$row['CO2'].'</td>';
		echo '<td>'.$row['GAS'].'</td>';
		echo "</tr>";
		mysqli_close($conn);
		}
	?>
	</table>
</body>
</html>
