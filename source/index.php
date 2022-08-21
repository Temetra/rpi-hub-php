<?php
require "index.inc.php";
$data = new RpiStatus();
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Raspberry Pi services</title>
	<link rel="stylesheet" href="styles.css">
</head>

<body>
	<header>
		<h1>
			Raspberry Pi
		</h1>
		<p>
			Currently available services
		</p>
	</header>
	<article>
		<nav>
			<a href="http://pi.lan:8099/admin">
				<div class="icon">
					<ion-icon name="build-outline"></ion-icon>
				</div>
				<div>
					<h2>Pi-hole</h2>
					<p>Network-wide protection</p>
				</div>
			</a>
			<a href="http://pi.lan:8096/web/index.html#!/home.html">
				<div class="icon">
					<ion-icon name="film-outline"></ion-icon>
				</div>
				<div>
					<h2>Jellyfin</h2>
					<p>The Free Software Media System</p>
				</div>
			</a>
			<a href="http://pi.lan:8080">
				<div class="icon">
					<ion-icon name="magnet-outline"></ion-icon>
				</div>
				<div>
					<h2>qBittorrent</h2>
					<p>Open-source BitTorrent client</p>
				</div>
			</a>
		</nav>
	</article>
	<footer>
		<section>
			<div>
				<span>Net: </span>
				<span><?= $data->net_state == true ? "Up" : "Down" ?></span>
			</div>
			<div>
				<span>CPU: </span>
				<span><?= fmt_num($data->cpu_temp) ?>&deg;C</span>
			</div>
			<div>
				<span>Load: </span>
				<span><?= fmt_num($data->load_one) ?> <?= fmt_num($data->load_five) ?> <?= fmt_num($data->load_fifteen) ?></span>
			</div>
			<div>
				<span>Memory: </span>
				<span><?= fmt_mb($data->memory_total) ?> MB</span>
			</div>
			<div>
				<span>Used: </span>
				<span><?= fmt_mb($data->memory_used_noncached) ?> MB (<?= fmt_num($data->memory_percent_noncached) ?>%)</span>
			</div>
			<div>
				<span>Used (w/cache): </span>
				<span><?= fmt_mb($data->memory_used) ?> MB (<?= fmt_num($data->memory_percent) ?>%)</span>
			</div>
		</section>
	</footer>
	<script type="module" src="https://cdnjs.cloudflare.com/ajax/libs/ionicons/6.0.2/ionicons/ionicons.esm.js"></script>
	<script nomodule="" src="https://cdnjs.cloudflare.com/ajax/libs/ionicons/6.0.2/ionicons/ionicons.js"></script>
</body>

</html>
