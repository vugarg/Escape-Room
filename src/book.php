<?php

use Twig\Environment;
use Twig\TemplateWrapper;
use function ReservationManager\getReservationById;

require_once 'utils.php';
require_once 'session-manager.php';
require_once 'db/room-manager.php';
require_once 'db/reservation-manager.php';

startSessionWithSavePath();

$twig = getTwigEnvironment();

if (isPost()) {
    handlePost($twig);
} else {
    handleGet($twig);
}

function handlePost(Environment $twig)
{
    $post = postSanitized();
    $roomId = $post['roomId'];
    $dateTime = $post['dateTime'];
    $numPersons = $post['numPersons'];
    $reservationId = empty($post['id']) ? null : $post['id'];
    $isSuccess = createOrUpdateReservation($roomId, $numPersons, $dateTime, $reservationId);
    echo $twig->render('book-result.html.twig', ['isSuccess' => $isSuccess]);
}

function handleGet(Environment $twig)
{
    $template = $twig->load('book.html.twig');
    $reservationId = cleanInput($_GET['reservationId']);
    $reservation = $reservationId ? getReservationById(cleanInput($reservationId)) : null;
    $userId = getCurrentUserId();
    if (!$userId) {
        handleReservationCreation($template, true);
    } else if ($reservation && isUserAuthorizedToUpdate($reservation)) {
        handleReservationUpdate($template, $reservation);
    } else {
        handleReservationCreation($template);
    }
}

function handleReservationCreation(TemplateWrapper $template, bool $userNotLoggedIn = false)
{
    $rooms = RoomManager\getRooms();
    $roomId = cleanInput($_GET['roomId']);

    echo $template->render([
        'rooms' => $rooms,
        'dates' => getAvailableDates(),
        'selectedRoomId' => $roomId,
        'selectedNumPersons' => 2,
        'userNotLoggedIn' => $userNotLoggedIn
    ]);
}

function handleReservationUpdate(TemplateWrapper $template, Reservation $reservation)
{
    $rooms = RoomManager\getRooms();
    $dates = getAvailableDates();
    array_push($dates, $reservation->dateTimeFormatted);
    echo $template->render([
        'rooms' => $rooms,
        'dates' => $dates,
        'selectedDate' => $reservation->dateTimeFormatted,
        'selectedRoomId' => $reservation->roomId,
        'selectedNumPersons' => $reservation->numPersons,
        'isUpdate' => true,
        'id' => $reservation->id
    ]);
}

function createOrUpdateReservation(string $roomId, int $numPersons, string $dateTime, $reservationId = null): bool
{
    $userId = getCurrentUserId();
    $dateTime = dateTimeFromString($dateTime);
    $reservation = new Reservation($reservationId, $userId, $dateTime, $roomId, $numPersons);
    return ReservationManager\createOrUpdateReservation($reservation) != null;
}

function getAvailableDates(): array
{
    $today = new DateTime();
    $dates = array();
    for ($day = 1; $day <= 5; $day++) {
        $oneDay = new DateInterval('P' . $day . 'D');
        $date = $today->add($oneDay);
        for ($hour = 10; $hour <= 18; $hour += 3) {
            $date->setTime($hour, 0);
            array_push($dates, dateTimeToString($date));
        }
    }
    return $dates;
}

function isUserAuthorizedToUpdate(Reservation $reservation): bool {
    return getCurrentUserId() == $reservation->userId;
}