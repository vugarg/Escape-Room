<?php

use Twig\TemplateWrapper;

require_once 'utils.php';
require_once 'session-manager.php';
require_once 'db/user-manager.php';

startSessionWithSavePath();
$template = getTwigTemplate('login.html.twig');

if (isPost()) {
    handlePost($template);
} else {
    handleGet($template);
}

function handlePost(TemplateWrapper $template) {
    $post = postSanitized();
    $username = $post['username'];
    $password = $post['password'];
    $user = UserManager\getUserForCredentials($username, $password);
    if(!$user) {
        echo createFailureTemplate($template, $username, $password);
    } else {
        setCurrentUser($user);
        redirectToMyAccount();
    }
}

function handleGet(TemplateWrapper $template) {
    echo $template->render();
}

function createFailureTemplate(TemplateWrapper $template, $username, $password): ?string
{
    $userExists = UserManager\usernameExists($username);
    if (!$userExists) {
        $errorMessage = 'User with such username doesn\'t exist!';
    } else {
        $errorMessage = 'Invalid credentials!';
    }
    return $errorMessage ? $template->render(['error' => $errorMessage, 'username' => $username]) : null;
}

function redirectToMyAccount()
{
    header("Location: myaccount.php");
    exit();
}
