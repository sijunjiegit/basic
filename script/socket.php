<?php
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
$res = socket_connect($socket, 'smtp.163.com', '25');
var_dump($res);exit;
$in = "helo abc\n";
$in .= "auth login\n";
$in .= base64_encode("sijunjiede163@163.com") . "\n";
$in .= base64_encode("abc521702525sjj") . "\n";
$in .= "mail from:<sijunjiede163@163.com>\n";
$in .= "rcpt to:<1195499776@qq.com>\n";
$in .= "data\n";
$in .= "From:sijunjiede163@163.com\n";
$in .= "To:1195499776@qq.com\n";
$in .= "Subject:test\n";
$in .= "\n";
$in .= "test";
$in .= "\n";
$in .= ".\n";


socket_write($socket, $in, strlen($in));
while ($out = socket_read($socket, 8192)) {
    echo $out;
}
socket_close($socket);
