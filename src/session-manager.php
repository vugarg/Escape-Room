<?php

require_once __DIR__ . '/model/User.php';

function startSessionWithSavePath()
{
    $cacheDirPath = __DIR__ . '/../cache';
    if (!file_exists($cacheDirPath)) {
        mkdir($cacheDirPath, 0777, true);
    }
    session_save_path($cacheDirPath);
    session_start();
}

function setCurrentUser(?User $user)
{
    $_SESSION['userId'] = $user->id;
}

function getCurrentUserId(): ?string {
    return $_SESSION['userId'];
}
