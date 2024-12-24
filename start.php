<?php
include 'config.php';
exec("pkill mpv");
exec('rm ' . $pt . 'out');
$name = $_POST["id"];
$cap = $_POST["cap"];
$start = $_POST["start"];
if ($cap == 'null') {
    $posicion = $start . '%';
} else {
    $posicion = '#' . $cap;
}
$file = $path . $name;
$orden = 'mpv --input-ipc-server=' . $pt . 'tubo --term-status-msg=\'${core-idle} ${time-pos} / ${duration} (${percent-pos}%)\n\' --no-msg-color --no-video --audio-device=alsa/iec958:CARD=DAC,DEV=0 --audio-spdif=ac3,dts "' . $file . '" --start=' . $posicion . ' 1> ' . $pt . 'out 2>&1 &';
exec($orden);

$orden = 'ffmpeg -i "' . $file . '" -f ffmetadata 2>&1';
$salida = shell_exec($orden);

$patron = '/.*Chapter #0:(\d+): start (.*), end (.*)\n.*Metadata:\n.*title.*: (.*)/';
preg_match_all($patron, $salida, $capitulos, PREG_SET_ORDER);
$fv = $capitulos[count($capitulos) - 1][3]; //end (.*) último capítulo
$patron = '/([^-]*) - (.*)\.MKA/';
preg_match($patron, $name, $filename);

$patron = '/Stream #0:0.*: Audio: (.*)/';
preg_match($patron, $salida, $informacion);

$res->name = $name;
$res->artist = $filename[1];
$res->album = $filename[2];

$res->info = $informacion[1];

$i = 0;
foreach ($capitulos as $val) {
    $res->caps[$i]->cap = $val[1] + 1; // int
    $res->caps[$i]->ini = (float)($val[2]); // float
    $res->caps[$i]->fin = (float)($val[3]); // float
    $res->caps[$i]->tit = $val[4]; // string
    $res->caps[$i]->por = (float)($val[2] * 100 / $fv); // float
    ++$i;
}

//$res.name
//$res.artist
//$res.album
//$res.info[0-4]
//$res.caps[] cap,ini,fin,tit
file_put_contents($pt . 'playing', json_encode($res));
