<?php

function getConnection(): mysqli
{
/*    $server = '127.0.0.1';
    $user = 'root';
    $password = 'password';*/
    $server = 'anysql.itcollege.ee';
    $user = 'team13';
    $password = '4993e370b9bc';
    $database = 'WT_13';
    $link = mysqli_connect($server, $user, $password, $database);
    if (!$link) die("Connection to DB failed: " . mysqli_connect_error());
    return $link;
}
