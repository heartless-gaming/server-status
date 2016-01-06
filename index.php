<?php 

require __DIR__ . '/vendor/koraktor/steam-condenser/lib/steam-condenser.php';

$source_gamedir = array('tf', 'csgo', 'bms');

$hls_server_ip = '188.165.231.218';
$hls_steamapi_url = 'http://api.steampowered.com/ISteamApps/GetServersAtAddress/v0001?addr=' . $hls_server_ip . '&format=json';
$hls_steamapi_json = json_decode(file_get_contents($hls_steamapi_url), true);
$hls_online_servers = $hls_steamapi_json['response']['servers'];
$hls_server_info = array();
$hls_server_ports = array();

function get_source_server_info() {
    $server = new SourceServer( $hls_server_ip, $hls_server_port );
    $server->initialize();
    array_push($hls_server_info, $server->getServerInfo() );
    
}

// Extracting & storing heartlessgaming server info from the valve masterserver
foreach ($hls_online_servers as $hls_online_server) {
    array_push($hls_server_ports, $hls_online_server['gameport']);
}

foreach ($hls_server_ports as $hls_server_port) {
    switch ($hls_server_port) {
        case 'value':
            # code...
            break;
        
        default:
            # code...
            break;
    }

}

echo "<pre>";
print_r($hls_online_servers);
echo "</pre>";

?>