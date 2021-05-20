<?php
require __DIR__ . '/src/utils.php';
require_once __DIR__ . '/src/db/room-manager.php';

$twig = getTwigEnvironment();

$rooms = array_slice(RoomManager\getRooms(), 0, 4);

echo $twig->render('index.html.twig', ['rooms' => $rooms]);