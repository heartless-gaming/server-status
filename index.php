<?php 
/*
    Disclamer : You will puke by reading this code. Fixng it could lead to critical heart failure.
    FFS Skulmasher this your future self you suck at this. Better start all over again.
 */

require __DIR__ . '/vendor/koraktor/steam-condenser/lib/steam-condenser.php';

$hls_server_ip = '91.121.154.84';

$hls_server_map = [ 
    'csgo' => [
        [
            'servername' => 'Heartless Gaming | Public Competitive - tick 128',
            'port' => 27015
        ],[
            'servername' => 'Heartless Gaming | Deathmatch FFA Only D2 - tick 128',
            'port' => 27016  
        ]
    ],
    'insurgency' => [
        [
            'servername' => 'Heartless Gaming | Insurgency COOP - tick 128',
            'port' => 27018
        ]
    ],
    'hl2dm' => [
        [
            'servername' => 'Heartless Gaming | Half-Life 2 Deathmatch - Vanilla',
            'port' => 27021
        ]
    ],
    'bms' => [
        [
            'servername' => 'Heartless Gaming | Black Mesa Source - Vanilla',
            'port' => 27022
        ]
    ],
    'killingfloor' => [
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
    ]
];

$hls_server_map_csgo = $hls_server_map['csgo'];
$hls_server_map_ins = $hls_server_map['insurgency'];
$hls_server_map_hl2dm = $hls_server_map['hl2dm'];
$hls_server_map_bms = $hls_server_map['bms'];
$hls_server_map_kf = $hls_server_map['killingfloor'];

// You can either read the steamapi result from a URL or from a file.
$hls_steamapi_url = 'http://api.steampowered.com/ISteamApps/GetServersAtAddress/v0001?addr=' . $hls_server_ip . '&format=json';
$hls_steamapi_file = 'heartlessgaming-steamapi.json';

// Getting an array of the online servers from steamapi
$hls_steamapi_json = json_decode(file_get_contents($hls_steamapi_url), true);
$hls_online_servers = $hls_steamapi_json['response']['servers'];

// echo '<a href="' . $hls_steamapi_url . '">'. $hls_steamapi_url . '</a>';
// echo '<pre>';
// var_dump($hls_server_map_csgo);
// echo '</pre>';

/*
 * Get server information using their query protocol eventually...
 * 
 * NODEJS : https://github.com/kurt-stolle/game-server-query
 *
 *
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Server Status | Heartless Gaming</title>
</head>
<body>
    <header>
        <h1>Server Status</h1>
    </header>
    <hr>
    <main>
        <section>
            <h2>Counter Strike : Global Offensive</h2>
            <?php 
                foreach ($hls_server_map_csgo as $csgo_server_key => $csgo_server_value) {
                    echo '<p>' . $csgo_server_value['servername'] . ' - ' . $hls_server_ip . ':' . $csgo_server_value['port'] . '</p>';
                }
            ?>
        </section>
        <section>
            <h2>Insurgency</h2>
            <?php 
                foreach ($hls_server_map_ins as $ins_server_key => $ins_server_value) {
                    echo '<p>' . $ins_server_value['servername'] . ' - ' . $hls_server_ip . ':' . $ins_server_value['port'] . '</p>';
                }
            ?>
        </section>
        <section>
            <h2>Killing Floor</h2>
            <?php 
                foreach ($hls_server_map_kf as $kf_server_key => $kf_server_value) {
                    echo '<p>' . $kf_server_value['servername'] . ' - ' . $hls_server_ip . ':' . $kf_server_value['port'] . '</p>';
                }
            ?>
        </section>
        <section>
            <h2>Half-life 2 Deathmatch</h2>
            <?php 
                foreach ($hls_server_map_hl2dm as $hl2dm_server_key => $hl2dm_server_value) {
                    echo '<p>' . $hl2dm_server_value['servername'] . ' - ' . $hls_server_ip . ':' . $hl2dm_server_value['port'] . '</p>';
                }
            ?>
        </section>
        <section>
            <h2>Black Mesa Source Multiplayer</h2>
            <?php 
                foreach ($hls_server_map_bms as $bms_server_key => $bms_server_value) {
                    echo '<p>' . $bms_server_value['servername'] . ' - ' . $hls_server_ip . ':' . $bms_server_value['port'] . '</p>';
                }
            ?>
        </section>
    </main>
    <hr>
    <footer>
        <p>Feel free to <a href="mailto:contact@heartlessgaming.com">contact us</a> if you have a problem or a sugestion to make the game servers better.</p>
        <p>The source code of this website is available on <a href="https://github.com/heartless-gaming/server-status">github</a></p>
    </footer>
</body>
</html>