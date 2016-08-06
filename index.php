<?php 
/*
    Disclamer : You will puke by reading this code. Fixng it could lead to critical heart failure.
    FFS Skulmasher this your future self you suck at this. Better start all over again.
 */

require __DIR__ . '/vendor/koraktor/steam-condenser/lib/steam-condenser.php';

$hls_server_ip = '91.121.154.84';

// json from Valve Master server
$hls_steamapi_url = 'http://api.steampowered.com/ISteamApps/GetServersAtAddress/v0001?addr=' . $hls_server_ip . '&format=json';
$hls_steamapi_json = json_decode(file_get_contents($hls_steamapi_url), true);
$hls_online_servers = $hls_steamapi_json['response']['servers'];
