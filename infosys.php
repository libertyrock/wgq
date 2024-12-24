<?php
$res->info='Temp: '.shell_exec("awk '{printf(\"%.1f\",$1/1000)}' /etc/armbianmonitor/datasources/soctemp").'º';

echo json_encode($res);
//$res.info
