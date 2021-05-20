<?php

namespace RoomManager;
use Room;

require_once __DIR__ . '/../model/Room.php';
require_once __DIR__ . '/../utils.php';
include_once "dbconnection_data.php";


function getRooms(): array
{
    $link = getConnection();
    $query = "SELECT * FROM WT_13.rooms;";
    $result = mysqli_query($link, $query);

    $roomsTest = array();
    while($data = mysqli_fetch_array($result, MYSQLI_NUM)){
        array_push($roomsTest, Room::fromDataArray($data));
    }
    mysqli_close($link);
    return $roomsTest;
}

function getRoomById(?int $id): ?Room {
    $link = getConnection();

    $query = "SELECT * FROM WT_13.rooms WHERE id=$id;";
    $result = mysqli_query($link, $query);
    $data = mysqli_fetch_array($result, MYSQLI_NUM);
    mysqli_close($link);
    if($result){
        return Room::fromDataArray($data);
    }
    return null;
}
