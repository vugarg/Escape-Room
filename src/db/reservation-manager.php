<?php

namespace ReservationManager;
require_once __DIR__ . '/../model/Reservation.php';
require_once __DIR__ . '/../utils.php';
require_once __DIR__ . '/dbconnection_data.php';

use Reservation;

function createOrUpdateReservation(Reservation $reservation): ?Reservation
{
    $link = getConnection();
    createReservationsTableIfNotExist($link);
    if (getReservationById($reservation->id)) {
        return updateReservation($reservation, $link);
    } else {
        return createReservation($reservation, $link);
    }
}

function createReservation(Reservation $reservation, $link): ?Reservation{
    $query = "INSERT INTO WT_13.reservations(user_id, date_time, room_id, num_persons) 
                 VALUES('$reservation->userId', '$reservation->dateTimeFormatted', '$reservation->roomId', '$reservation->numPersons');";
    $result = mysqli_query($link, $query);
    if ($result) {
        $reservation->id = $link->insert_id;
        mysqli_close($link);
        return $reservation;
    }
    mysqli_close($link);
    return null;
}

function updateReservation(Reservation $reservation, $link): ?Reservation {
        $query = "UPDATE WT_13.reservations 
        SET date_time='$reservation->dateTimeFormatted', 
        room_id='$reservation->roomId', 
        num_persons='$reservation->numPersons' 
        WHERE id='$reservation->id';";

    $result = mysqli_query($link, $query);
    if ($result) {
        mysqli_close($link);
        return $reservation;
    }
    mysqli_close($link);
    return null;
}

function getReservationsForUserId(string $userId): array
{

    $link = getConnection();
    createReservationsTableIfNotExist($link);

    $query = "SELECT * FROM WT_13.reservations;";
    $result = mysqli_query($link, $query);
    $reservations = array();

    while ($data = mysqli_fetch_array($result, MYSQLI_NUM)) {
        if ($data[1] == $userId) {
            $dateTime = dateTimeFromString($data[2]);
            $reservation = new Reservation($data[0], $data[1], $dateTime, $data[3], $data[4]);
            array_push($reservations, $reservation);
        }
    }
    return $reservations;
}

function getReservationById(?int $reservationId): ?Reservation
{
    $link = getConnection();
    createReservationsTableIfNotExist($link);

    $query = "SELECT * FROM WT_13.reservations WHERE id=$reservationId;";
    $result = mysqli_query($link, $query);

    if ($result) {
        $data = mysqli_fetch_array($result, MYSQLI_NUM);
        $dateTime = dateTimeFromString($data[2]);
        return new Reservation($data[0], $data[1], $dateTime, $data[3], $data[4]);
    }
    return null;
}

function deleteReservationWhereId(string $reservationId)
{
    $link = getConnection();
    createReservationsTableIfNotExist($link);
    $query = "DELETE FROM WT_13.reservations WHERE id=$reservationId;";
    mysqli_query($link, $query);
}

function createReservationsTableIfNotExist($link)
{
    $createTableQuery = "CREATE TABLE IF NOT EXISTS reservations (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    date_time VARCHAR(50) NOT NULL,
    room_id INT NOT NULL,
    num_persons INT NOT NULL,
    PRIMARY KEY (id));";
    mysqli_query($link, $createTableQuery);
}