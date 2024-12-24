<?php
include 'config.php';
$name=$_POST["id"];
$file=$path.$name;

$orden='ffmpeg -i "'.$file.'" -f ffmetadata 2>&1';
$salida=shell_exec($orden);

$patron = '/.*Chapter #0:(\d+): start (.*), end (.*)\n.*Metadata:\n.*title.*: (.*)/';
preg_match_all($patron, $salida, $capitulos, PREG_SET_ORDER);

$patron = '/([^-]*) - (.*)\.MKA/';
preg_match($patron, $name, $filename);

$patron = '/Stream #0:0.*: Audio: (.*)/';
preg_match($patron, $salida, $informacion);

$res->name=$name;
$res->artist=$filename[1];
$res->album=$filename[2];

$res->info=$informacion[1];

$i=0;
foreach ($capitulos as $val) {
    $res->caps[$i]->cap=$val[1]+1;
    $res->caps[$i]->ini=round($val[2]);
    $res->caps[$i]->fin=round($val[3]);
    $res->caps[$i]->tit=$val[4];
    ++$i;
}

//$res.name
//$res.artist
//$res.album
//$res.info[0-4]
//$res.caps[] cap,ini,fin,tit
echo json_encode($res);
