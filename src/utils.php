<?php
require __DIR__ . '/../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TemplateWrapper;

const DATE_TIME_FORMAT = 'd.m.Y H:i';

function getTwigEnvironment(): Environment
{
    $loader = new FilesystemLoader(__DIR__ . '/../templates');
    return new Environment($loader);
}

function getTwigTemplate(string $name): TemplateWrapper
{
    return getTwigEnvironment()->load($name);
}

function cleanInput(?string $data): string
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function postSanitized(): array
{
    foreach ($_POST as $key => $value) {
        $_POST[$key] = cleanInput($value);
    }
    return $_POST;
}

function getFile(string $fileName, string $mode)
{
    $dataDirPath = __DIR__ . '/../data/';
    $fileName = $dataDirPath . $fileName;
    if (!file_exists($dataDirPath)) {
        mkdir($dataDirPath, 0777, true);
    }
    if (!file_exists($fileName) && $mode == 'r') {
        return false;
    }
    return fopen($fileName, $mode);
}

function getRowsCount(string $fileName): int
{
    $path = __DIR__ . '/../data/' . $fileName;
    if (!file_exists($path)) return 0;
    return count(file(__DIR__ . '/../data/' . $fileName));
}

function isPost(): bool
{
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}

function dateTimeFromString(string $str): DateTime {
    return DateTime::createFromFormat(DATE_TIME_FORMAT, $str);
}

function dateTimeToString(DateTime $dateTime): string {
    return $dateTime->format(DATE_TIME_FORMAT);
}