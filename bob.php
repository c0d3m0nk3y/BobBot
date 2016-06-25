<?php

$server = 'irc.rizon.net';
$channel = '#TheBlackLodge';
$port = 6667;
$nick = 'BobBot';
$ident = 'BobBot';
$gecos = 'BobBot 1.0';

$socket = socket_create( AF_INET, SOCK_STREAM, SOL_TCP );
$error = socket_connect( $socket, $server, $port );

if( $socket === false ) {
  $errorCode = socket_last_error();
  $errorString = socket_strerror( $error_code );
  die ( "Error $errorCode : $errorString\n" );
}

socket_write( $socket, "NICK $nick\r\n" );
socket_write( $socket, "USER $ident * 8 :$gecos\r\n" );

while( is_resource( $socket ) ) {
  $data = trim( socket_read( $socket, 1024, PHP_NORMAL_READ ) );
  echo $data . "\n";

  $d = explode( ' ', $data );

  $d = array_pad( $d, 10, '' );

  if( $d[0] === 'PING' ) {
    socket_write( $socket, 'PONG ' . $d[1] . "\r\n" );
  }

  if( $d[1] === '376' || $d[1] === '422' ) {
    socket_write( $socket, 'JOIN ' . $channel . "\r\n" );
  }

  if( $d[3] == ':@moo' ) {
    $moo = "M" . str_repeat( "o", mt_rand(2, 15) );
    socket_write( $socket, 'PRIVMSG ' . $d[2] . " :moo\r\n" );
  }
}
