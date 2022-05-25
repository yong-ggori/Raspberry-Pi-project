<?php
	$conn = mysqli_connect("localhost","root","kcci");
	mysqli_set_charset($conn, "utf8");
	mysqli_select_db($conn, "pi_project");
	$result = mysqli_query($conn, "select DATE, TIME, CO2, GAS from sensing");
	$data = array(array('pi_project','CO2(이산화탄소)','GAS(가스)'));

	if($result){
		while($row = mysqli_fetch_array($result))
		{
			array_push($data, array($row['DATE']."\n".$row[1], intval($row[2]), intval($row[3]))); //CO2, GAS 출력
		}
	}
	$options = array('title' => 'CO2 (단위 : ppm), GAS (단위 : ppm)', 'width' => 1000, 'height' => 500);
	//$options_GAS = array('title' => 'GAS (단위 : ppm)', 'width' => 1000, 'height' => 500);
?>

<script src="//www.google.com/jsapi"></script>
<script>
    let data = <?= json_encode($data) ?>;
    let options = <?= json_encode($options) ?>;
    google.load('visualization', '1.0', {'packages':['corechart']});
    google.setOnLoadCallback(function() {
        let chart = new google.visualization.LineChart(document.querySelector('#chart_div'));
        chart.draw(google.visualization.arrayToDataTable(data), options);
    });
</script>
<div id="chart_div"></div>
