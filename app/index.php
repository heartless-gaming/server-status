<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$server_status = json_decode(file_get_contents('json/heartlessgaming-serverstatus.json'));
$server_ip = $server_status->gameServerIp;
$games = $server_status->games;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="description" content="Game server status of the Heartless Gaming pc gaming community.">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<title>Game Server Status | Heartless Gaming</title>
</head>
<body>
	<header class="page-header flex-container box">
			<h1 class="page-header__title">Game Server Status</h1>
			<a href="https://www.heartlessgaming.com" class="page-header__link">
				<img src="img/hearltess-gaming-logo.svg" alt="Logo Heartless Gaming" class="page-header__logo">
			</a>
	</header>
	<main class="page-content">
	<?php foreach ($games as $game) : ?>
		<section class="game-server box">
			<header class="game-server__header">
				<h2 class="game-server__title h3-like"><?php echo $game->gameName ?><img src="img/arrow.svg" class="game-server__hide is-hidden js-gameserver"></h2>
			</header>
			<div class="js-gameserver-list">
			<?php foreach ($game->gameServers as $game_server) : ?>
				<div class="game-server__info flex-container">
					<p class="game-server__name"><?php echo $game_server->serverName ?></p>
					<p class="game-server__players"><?php echo $game_server->players ?></p>
					<p class="game-server__ip"><?php echo $server_ip . ':' . $game_server->port ?></p>
				<?php if ($game_server->status === 'join') : ?>
						<a href="steam://connect/<?php echo $server_ip . ':' . $game_server->port ?>" class="game-server__status game-server__join btn">join</a>
				<?php elseif ($game_server->status === 'updating') : ?>
					<p class="game-server__status game-server__updating">updating</p>
				<?php elseif ($game_server->status === 'rejected') : ?>
					<p class="game-server__status game-server__offline">Rejected</p>
				<?php else : ?>
					<p class="game-server__status game-server__offline">Offline</p>
				<?php endif; ?>
				</div>
			<?php endforeach; ?>
			</div>

		</section>
	<?php endforeach; ?>
	</main>
	<footer class="page-footer">
	<div class="grid-3-small-1">
		<div class="page-info">
			<h3 class="page-info__title">Auto Update</h3>
			<p>Numbers of players on the server are refreshed automaticaly every 30 seconds.</p>
			<p>Game servers online status are refreshed automaticaly every 10 minutes.</p>
		</div>
		<div class="page-info">
			<h3 class="page-info__title">Contact Us</h3>
			<p>Feel free to contact us by email if you have a problem or a sugestion to make the game servers better.</p>
			<p class="page-footer__mail">contact [at] heartlessgaming [dot] com</p>
		</div>
		<div class="page-info">
			<h3 class="page-info__title">Source code</h3>
			<p>The source code of this website is available on <a href="https://github.com/heartless-gaming/server-status">github</a>.</p>
			<p>Made possible by the awesome <a href="https://github.com/kurt-stolle/game-server-query">game server query</a> library by Kurt Stolle.</p>
		</div>
	</div>
	<h4 class="heartless-tagline">Play more, Care less, Be a Heartless.</h4>
	</footer>
	<script src="../node_modules/jquery/dist/jquery.min.js"></script>
	<script src="js/script.js"></script>
</body>
</html>
