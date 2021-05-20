<?php

use Twig\TemplateWrapper;

require_once __DIR__ . '/session-manager.php';
require_once __DIR__ . '/utils.php';
require_once __DIR__ . '/db/user-manager.php';
require_once __DIR__ . '/db/reservation-manager.php';
require_once __DIR__ . '/db/room-manager.php';

startSessionWithSavePath();

if ($_POST['log-out']) {
    logOut();
}
$template = getTwigTemplate('myaccount.html.twig');
$user = UserManager\getUserById(getCurrentUserId());

if ($user) {
    if ($_POST['delete']) {
        deleteReservation($_POST['delete']);
    } else if ($_POST['change']) {
        changeReservation($_POST['change']);
    }
    echo getMyAccountTemplate($template, $user);
} else {
    redirectToLogin();
}

function getMyAccountTemplate(TemplateWrapper $template, User $user): string
{
    $reservations = ReservationManager\getReservationsForUserId($user->id);
    $upcomingReservations = array();
    $pastReservations = array();

    foreach ($reservations as $reservation) {
        $reservation->roomName = RoomManager\getRoomById($reservation->roomId)->title;
        if ($reservation->dateTime > new DateTime()) {
            array_push($upcomingReservations, $reservation);
        } else {
            array_push($pastReservations, $reservation);
        }
    }

    return $template->render([
        'username' => $user->username,
        'upcomingReservations' => $upcomingReservations,
        'pastReservations' => $pastReservations
    ]);
}

function redirectToLogin()
{
    header("Location: login.php");
    exit();
}

function deleteReservation(string $reservationId) {
    ReservationManager\deleteReservationWhereId($reservationId);
}

function changeReservation(string $reservationId) {
    header("Location: book.php?reservationId=".$reservationId);
    exit();
}

function logOut()
{
    setCurrentUser(null);
    redirectToLogin();
}