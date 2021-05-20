<?php


class Room
{
    public $id;
    public $title;
    public $location;
    public $imagePath;
    public $maxPersons;
    public $minAge;
    public $duration;
    public $difficulty;
    public $description;

    /**
     * Room constructor.
     * @param $id
     * @param $title
     * @param $location
     * @param $imagePath
     * @param $maxPersons
     * @param $minAge
     * @param $duration
     * @param $difficulty
     * @param $description
     */
    public function __construct($id, $title, $location, $imagePath, $maxPersons, $minAge, $duration, $difficulty, $description)
    {
        $this->id = $id;
        $this->title = $title;
        $this->location = $location;
        $this->imagePath = 'rooms/'.$imagePath;
        $this->maxPersons = $maxPersons;
        $this->minAge = $minAge;
        $this->duration = $duration;
        $this->difficulty = $difficulty;
        $this->description = $description;
    }

    static function fromDataArray(?array $data): ?Room
    {
        if (!$data) return null;
        return new Room($data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8]);
    }
}