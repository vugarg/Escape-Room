<?php
require_once 'utils.php';
require_once 'db/room-manager.php';

$twig = getTwigEnvironment();

$rooms = RoomManager\getRooms();

echo $twig->render('rooms.html.twig', ['rooms' => $rooms]);