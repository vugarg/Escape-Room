<?php

require_once __DIR__ . '/../utils.php';

class ContactMessage
{
    public $name;
    public $email;
    public $subject;

    private function __construct(string $name, string $email, string $subject)
    {
        $this->name = $name;
        $this->email = $email;
        $this->subject = $subject;
    }

    public static function create(?string $name, ?string $email, ?string $content)
    {
        $errors = array();
        if (!self::isNameValid($name)) array_push($errors, "Name is invalid!");
        if (!self::isEmailValid($email)) array_push($errors, "Email is invalid!");
        if (!self::isContentValid($content)) array_push($errors, "Content is invalid!");
        return empty($errors) ? new ContactMessage($name, $email, $content) : $errors;
    }

    private static function isContentValid(?string $content): bool {
        return !empty($content);
    }

    private static function isNameValid(?string $name): bool
    {
        if (preg_match("/^[a-zA-Z-' ]+$/", $name)) return true;
        return false;
    }

    private static function isEmailValid(?string $email): bool
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) return true;
        return false;
    }
}