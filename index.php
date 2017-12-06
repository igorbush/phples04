<?php 
	error_reporting( E_ERROR );
	$city_get = $_GET['value'];
	if (!empty($_GET['value'])) {
		$appid = "8499bc10de19c0cbe31d89994b60834a";
		$cache_file = 'cache.txt';
		$json = file_get_contents($cache_file);
		$d = json_decode($json, true);
		$cache_city = $d[name];
		if (file_exists($cache_file) && (time() - 300) < filemtime($cache_file) && strcmp($city_get, $cache_city) == 0) {
			$json_weather = file_get_contents($cache_file);
		}
		else {$json_weather = file_get_contents("http://api.openweathermap.org/data/2.5/weather?q=$city_get&lang=ru&units=metric&appid=$appid");
			file_put_contents($cache_file, $json_weather);
		}
		$data = json_decode($json_weather, true);
		$temp = $data[main][temp];
		$desc = $data[weather][0][description];
		$hum = $data[main][humidity];
		$wind_speed = $data[wind][speed];
		$clouds = $data[clouds][all];
		$pic = $data[weather][0][icon];
		$logo = "<img src='http://openweathermap.org/img/w/" . $pic . ".png'>";
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>weather-meter</title>
	<link href="https://fonts.googleapis.com/css?family=Barlow+Semi+Condensed:400,700|Lato:400,700&amp;subset=latin-ext" rel="stylesheet">
	<style>
		* {
			margin: 0;
			padding: 0;
			font-family: 'Lato', sans-serif;
			color: #f8d9d1;
		}
		body {
			height: 85vh;
			background: url(weather_bg.png) center no-repeat;
			background-size: cover;
			/*padding-top: 180px;*/
			font-family: 'Lato', sans-serif;
		}
		.wrapper {
			max-width: 600px;
			margin: 15vh auto 0 auto;
			padding: 20px;
			background-color: rgba(83, 83, 83, 0.5);
			text-align: center;
		}
		h1 {
			margin-bottom: 20px;
			font: 700 50px 'Barlow Semi Condensed', sans-serif;
			color: #f8f7f2;
			text-transform: uppercase;
		}
		form {
			font-size: 0px;
			margin-bottom: 20px;
		}
		.city {
			display: inline-block;
			width: 60%;
			margin-right: 3%;
			font-size: 14px;
			padding: 10px;	
			color: #535353;
			text-transform: uppercase;
		}
		.city:placeholder {
			color: #535353;
		}
		.search {
			display: inline-block;
			width: 33%;
			font-size: 14px;
			padding: 10px;
			text-transform: uppercase;
			color: #535353;
			cursor: pointer;
		}
		img {
			display: inline-block;
			width: 80px;
			height: auto;
			line-height: 50px;
		}
		.temp {
			margin-left: 2%;
			display: inline-block;
			font-size: 50px;
			line-height: 50px;
			vertical-align: top;
		}
		.desc {
			margin-left: 2%;
			font-size: 30px;
			text-transform: uppercase;
		}
		h2 {
			padding: 5px;
			font-size: 24px;
			line-height: 50px;
			text-transform: uppercase;
		}
		.main-temp {
			width: 100%;
			margin: 0 auto;
			text-align: center;
			vertical-align: top;
			font-size: 0;
		}
		.other {
			display: inline-block;
			padding: 15px;
			font-size: 18px;
		}
	</style>
</head>
<body>
	<div class="wrapper">
		<h1>welcome to weather meter!</h1>
		<form action="index.php" method="get">
		<input class="city" type="text" placeholder="Введите Город" name="value">
		<input class="search" type="submit" value="узнать погоду">
		</form>
		<h2>Сегодня: <?= date("d.m.Y H:i") ?></h2>
		<?php if (!is_null($city_get)): ?>
				<h2>Город: <?= $city_get ?></h2>
				<div class='main-temp'>
					<?= $logo ?>
					<p class='temp'><?= round($temp) ?><sup> o</sup>C</p>
					<p class='desc'><?= $desc ?></p>
				</div>
				<p class='other'>Влажность: <?= $hum ?> %</p>
				<p class='other'>Скорость ветра: <?= $wind_speed ?> м/с</p>
				<p class='other'>Облачность: <?= $clouds ?> %</p>
		<?php endif; ?>
	</div>
</body>
</html>