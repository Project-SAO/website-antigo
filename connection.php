<?php

$db =  mysqli_connect('servidor-202', '12L', 'Pa$$w0rd', 'project_sao');
if (!$db) die('Failed to connect to database server!<br>'.mysql_error());
mysqli_set_charset($db,'utf8');

/* define o fuso horário */

date_default_timezone_set('Europe/Lisbon');
?>