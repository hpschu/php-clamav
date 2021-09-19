<?php
namespace PhpClamav;
include 'src/ClamClient.php';

$client = new ClamClient("127.0.0.1", 3310);
$stream = fopen('eicar.txt', 'r');
echo $client->scanStream($stream);
fclose($stream);
$client->close();
