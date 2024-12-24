<?php
include 'config.php';
exec('rm '.$pt.'salida ; ls -1a '.$path.'/*.MKA | xargs -d "\n" -n 1 basename > '.$pt.'salida');
$fp = @fopen($pt.'salida', 'r');
if ($fp) {
    $i=0;
    $res->lista=array();
    while (($bufer = fgets($fp, 4096)) !== false) {
        $bufer_limpio=addslashes(trim($bufer));
        $res->lista[$i]->id=$i;
        $res->lista[$i]->nombre=$bufer_limpio;
        ++$i;
    }
    $res->count=$i;

    if (!feof($fp)) {
        echo 'Error: fallo inesperado de fgets()\n';
    }
    fclose($fp);
    
    $respuesta=json_encode($res);
    echo $respuesta;
    //$res.lista
    //$res.count
}
