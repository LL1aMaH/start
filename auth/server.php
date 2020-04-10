<?php

$chat = new Chat();

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($socket, SOL_SOCKET, SOL_REUSEADDR, 1);
socket_bind ($socket,0);
socket_listen($socket);

while (true) {

    $newSocket = socket_accept($socket);
    $header = socket_read($newSocket, 1024);
    $chat->sendHeaders($header, $newSocket, 'http://chatepam.h1n.ru');

}

socket_close($socket);

?>