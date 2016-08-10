<?php
require __DIR__ . '/vendor/koraktor/steam-condenser/lib/steam-condenser.php';

/*
 * TODO : Make this Dynamic
 */
$hls_server_ip = '91.121.154.84';

/*
 * TODO : Grab this from a json file
 */
$hls_server_map = [
	'Counter-Strike : Global Offensive' => [
		[
			'servername' => 'Heartless Gaming | Public Competitive - tick 128',
			'port' => 27015
		],[
			'servername' => 'Heartless Gaming | Deathmatch FFA Only D2 - tick 128',
			'port' => 27016
		]
	],
	'Killing Floor' => [
		[
			'servername' => 'Heartless Gaming | HARD Long',
			'port' => 7707
		],[
			'servername' => 'Heartless Gaming | HARD Suicidal',
			'port' => 7708
		],[
			'servername' => 'Heartless Gaming | Killing Floor Private',
			'port' => 7709
		]
	],
	'Insurgency' => [
		[
			'servername' => 'Heartless Gaming | Insurgency COOP - tick 128',
			'port' => 27018
		]
	],
	'Half-life 2: Deathmatch' => [
		[
			'servername' => 'Heartless Gaming | Half-Life 2 Deathmatch - Vanilla',
			'port' => 27021
		]
	],
	'Black Mesa Source Multiplayer' => [
		[
			'servername' => 'Heartless Gaming | Black Mesa Source - Vanilla',
			'port' => 27022
		]
	]
];

/*
 * Checking if the server map can be found in the steam api request
 *
 */

// You can either read the steamapi result from a URL or from a file.
$hls_steamapi_url = 'http://api.steampowered.com/ISteamApps/GetServersAtAddress/v0001?addr=' . $hls_server_ip . '&format=json';
$hls_steamapi_file = 'heartlessgaming-steamapi.json';

// Getting an array of the online servers from steamapi
$hls_steamapi_json = json_decode(file_get_contents($hls_steamapi_file), true);
$hls_online_servers = $hls_steamapi_json['response']['servers'];
$hls_online_servers_port = [];

// Populating $hls_online_servers_port array to use for server online status detection
foreach ($hls_online_servers as $hls_online_server) {
	array_push($hls_online_servers_port, $hls_online_server['gameport']);
}

// Checking online status against the steamapi result. Also check for type int.
function check_server_status($gameport) {
	global $hls_online_servers_port;

	if (in_array($gameport, $hls_online_servers_port, TRUE)) {
		return true;
	}
}

/*
 * Get all the information of a source engine game.
 * Return : a fat array with all the server info
 * NOT USED just there for later use
 */
function get_source_server_info($port) {
	global $hls_server_ip;

	$server = new SourceServer( $hls_server_ip, $port );
	$server->initialize();
	return $server->getServerInfo();
}

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
	<?php foreach ($hls_server_map as $gamename => $hls_gameservers_info) : ?>
		<section class="game-server box">
			<header class="game-server__header">
				<h2 class="game-server__title h3-like"><?php echo $gamename ?></h2>
			</header>
		<?php foreach ($hls_gameservers_info as $game_info) : ?>
			<div class="game-server__instance flex-container">
				<p class="game-server__name"><?php echo $game_info['servername'] ?></p>
				<p class="game-server__ip"><?php echo $hls_server_ip . ':' . $game_info['port'] ?></p>
			<?php if (check_server_status($game_info['port'])) : ?>
				<a href="steam://connect/<?php echo $hls_server_ip . ':' . $game_info['port'] ?>" class="game-server__status game-server__join btn">Join</a>
			<?php else: ?>
				<p class="game-server__status game-server__offline">Offline</p>
			<?php endif; ?>
			</div>
		<?php endforeach; ?>
		</section>
	<?php endforeach; ?>
	</main>
	<footer class="page-footer box">
		<p>Feel free to contact us by email <span class="page-footer__mail">contact [at] heartlessgaming.com</span> if you have a problem or a sugestion to make the game servers better.</p>
		<p>The source code of this website is available on <a href="https://github.com/heartless-gaming/server-status">github</a></p>
		<p>Play more, Care less, Be an Heartless.</p>
	</footer>
</body>
</html>
