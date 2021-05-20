<?php

require_once __DIR__ . '/model/ContactMessage.php';
require_once __DIR__ . '/session-manager.php';
require_once __DIR__ . '/db/user-manager.php';

use Twig\Environment;

startSessionWithSavePath();

$twig = getTwigEnvironment();

if (isPost()) {
    handlePost($twig);
} else {
    handleGet($twig);
}

function handleGet(Environment $twig)
{
    $user = UserManager\getUserById(getCurrentUserId());
    $params = ['email' => $user->email, 'username' => $user->username];
    echo $twig->render('contact-us.html.twig', $params);
}

function handlePost(Environment $twig)
{
    $post = postSanitized();
    $name = $post['name'];
    $email = $post['email'];
    $subject = $post['subject'];
    echo createResultTemplate($twig, $name, $email, $subject);
}

function createResultTemplate(Environment $twig, string $name, string $email, string $subject): string
{
    $template = $twig->load('contact-us-result.html.twig');
    $message = ContactMessage::create($name, $email, $subject);
    if (is_array($message)) {
        return $template->render(['errors' => $message]);
    }
    $isMessageSent = sendMessage($message);
    return $template->render(['isSuccess' => $isMessageSent]);
}

function sendMessage(ContactMessage $message): bool
{
    $supportEmail = 'lita.kornilova@gmail.com';
    $headers = 'From: Escape Games';
    $body = $message->subject . "\n\nReply to " . $message->email;
    mail($supportEmail, 'Customer email', $body, $headers);
    return true;
}