<?php 
/*
    Disclamer : You will puke by reading this code. Fixng it could lead to critical heart failure.
    FFS Skulmasher this your future self you suck at this. Better start all over again.
 */

require __DIR__ . '/vendor/koraktor/steam-condenser/lib/steam-condenser.php';

$hls_server_ip = '91.121.154.84';

// You can either read the steamapi result from a URL or from a file.
$hls_steamapi_url = 'http://api.steampowered.com/ISteamApps/GetServersAtAddress/v0001?addr=' . $hls_server_ip . '&format=json';
$hls_steamapi_file = 'heartlessgaming-steamapi.json';

// Getting an array of the online servers from steamapi
$hls_steamapi_json = json_decode(file_get_contents($hls_steamapi_url), true);
$hls_online_servers = $hls_steamapi_json['response']['servers'];

echo '<a href="' . $hls_steamapi_url . '">'. $hls_steamapi_url . '</a>';
echo '<pre>';
var_dump($hls_online_servers);
echo '</pre>';

/*
 * Get server information using their query protocol
 * 
 * NODEJS : https://github.com/kurt-stolle/game-server-query
 *
 *
 */