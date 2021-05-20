<?php

namespace UserManager;

require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../utils.php';
require_once __DIR__ . '/dbconnection_data.php';

use User;

function createUser(User $user): ?User
{
    $link = getConnection();
    createUserTableIfNotExist($link);
    $email = $user->email ? $user->email : 'NULL';
    $test = $user->password;
    $hashedPass = hash("md5",$test);

    $query = "INSERT INTO WT_13.users(username, password, email) VALUES('"
        .$user->username."', '".$hashedPass."', '".$email."');";
    $result = mysqli_query($link, $query);
    if ($result) {
        $user->id = $link->insert_id;
        mysqli_close($link);
        return $user;
    }
    mysqli_close($link);
    return null;
}

function usernameExists($username): bool
{
    $link = getConnection();
    createUserTableIfNotExist($link);
    $query = "SELECT * FROM WT_13.users WHERE username='$username'";
    $result = mysqli_query($link, $query);
    if (mysqli_num_rows($result) > 0) {
        mysqli_close($link);
        return true;
    }
    mysqli_close($link);
    return false;
}

function emailExists($email): bool
{
    if (empty($email)) return false;
    $link = getConnection();
    createUserTableIfNotExist($link);
    $query = "SELECT * FROM WT_13.users WHERE email='$email'";
    $result = mysqli_query($link, $query);
    mysqli_close($link);
    if (mysqli_num_rows($result) > 0) {
        return true;
    }
    return false;
}

function getUserForCredentials(string $username, string $password): ?User
{
    $link = getConnection();
    createUserTableIfNotExist($link);
    $query = "SELECT * FROM WT_13.users WHERE username = '$username';";
    $result = mysqli_query($link, $query);
    $data = mysqli_fetch_array($result, MYSQLI_NUM);
    mysqli_close($link);
    if ($data[1] == $username && $data[2] == hash("md5",$password)) {
        return User::fromDataArray($data);
    }
    return null;
}

function getUserById(?string $id): ?User
{
    $link = getConnection();
    createUserTableIfNotExist($link);
    $query = "SELECT * FROM WT_13.users WHERE id = '$id';";
    $result = mysqli_query($link, $query);
    $data = mysqli_fetch_array($result, MYSQLI_NUM);
    mysqli_close($link);
    if ($result) {
        return User::fromDataArray($data);
    }
    return null;
}

function createUserTableIfNotExist($link)
{
    $query = "CREATE TABLE IF NOT EXISTS WT_13.users (
    id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR (45) NOT NULL, 
    password VARCHAR (45) NOT NULL, 
    email VARCHAR (45),
    PRIMARY KEY (id));";
    mysqli_query($link, $query);
}
