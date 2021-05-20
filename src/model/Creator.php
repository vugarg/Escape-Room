<?php

class Creator
{
    public $name;
    public $role;
    public $description;
    public $email;
    public $imagePath;

    public function __construct(string $name, string $role, string $description, string $email, string $imagePath)
    {
        $this->name = $name;
        $this->role = $role;
        $this->description = $description;
        $this->email = $email;
        $this->imagePath = $imagePath;
    }

    static function createRashad(): Creator
    {
        return new Creator(
            'Rashad Gafarli',
            'CEO & Founder',
            'Some text that describes me lorem ipsum ipsum lorem.',
            'jane@example.com',
            'creator-1.webp'
        );
    }

    static function createVugar(): Creator
    {
        return new Creator(
            'Vugar Gafarli',
            'Art Director',
            'Some text that describes me lorem ipsum ipsum lorem.',
            'mike@example.com',
            'creator-2.jpg'
        );
    }

    static function createLita(): Creator
    {
        return new Creator(
            'Lita Korniliva',
            'Designer',
            'Some text that describes me lorem ipsum ipsum lorem.',
            'john@example.com',
            'creator-3.webp'
        );
    }
}