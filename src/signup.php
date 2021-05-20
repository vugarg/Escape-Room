<?php

use Twig\TemplateWrapper;

require_once __DIR__ . '/session-manager.php';
require_once __DIR__ . '/db/user-manager.php';

startSessionWithSavePath();
$template = getTwigTemplate('signup.html.twig');

if (isPost()) {
    handlePost($template);
} else {
    handleGet($template);
}

function handlePost(TemplateWrapper $template)
{
    $post = postSanitized();
    $username = $post['username'];
    $password = $post['password'];
    $email = $post['email'];
    if ($result = createFailureTemplate($template, $username, $password, $email)) {
        echo $result;
    } else {
        createUser($username, $password, $email);
        redirectToMyAccount();
    }
}

function handleGet(TemplateWrapper $template)
{
    echo $template->render();
}

function createFailureTemplate(TemplateWrapper $template, ?string $username, ?string $password, ?string $email): ?string
{
    $errorMessage = '';
    $clearPassword = false;
    $passwordRepeat = postSanitized()['password-repeat'];

    if ($password != $passwordRepeat) {
        $errorMessage = 'Passwords don\'t match!';
        $clearPassword = true;
    } else if (strlen($password) < 6) {
        $clearPassword = true;
        $errorMessage = 'Password must be at least 6 characters long!';
    } else if (UserManager\usernameExists($username)) {
        $errorMessage = 'User with such username already exists!';
    } else if (UserManager\emailExists($email)) {
        $errorMessage = 'User with such email already exists!';
    } else if (!$_POST['agree-to-tp']) {
        $errorMessage = 'You haven\' accepted our Terms and Privacy! In order to use our service you must accept T&P.';
    }
    if (!$errorMessage) {
        return null;
    }
    $params = [
        'isValid' => false,
        'username' => $username,
        'password' => $clearPassword ? null : $password,
        'error_message' => $errorMessage
    ];
    return $template->render($params);
}

function createUser($username, $password, $email)
{
    $user = new User(null, $username, $password, $email);
    $user = UserManager\createUser($user);
    setCurrentUser($user);
}

function redirectToMyAccount()
{
    header("Location: myaccount.php");
    exit();
}