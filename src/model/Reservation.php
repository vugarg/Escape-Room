<?php

require_once __DIR__ . '/../utils.php';

class Reservation
{
    public $id;
    public $userId;
    public $dateTime;
    public $roomId;
    public $roomName;
    public $numPersons;
    public $dateFormatted;
    public $timeFormatted;
    public $dateTimeFormatted;

    /**
     * Reservation constructor.
     * @param $id
     * @param $userId
     * @param $dateTime
     * @param $roomId
     * @param $roomName
     * @param $numPersons
     */
    public function __construct($id, $userId, $dateTime, $roomId, $numPersons, $roomName = null)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->dateTime = $dateTime;
        $this->roomId = $roomId;
        $this->roomName = $roomName;
        $this->numPersons = $numPersons;
        $this->dateFormatted = $dateTime->format('d.m.Y');
        $this->timeFormatted = $dateTime->format('H:i');
        $this->dateTimeFormatted = dateTimeToString($dateTime);
    }
}