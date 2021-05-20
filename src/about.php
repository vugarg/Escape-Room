<?php
require 'model/Creator.php';
require_once 'utils.php';

$template = getTwigTemplate('about.html.twig');

$creators = [Creator::createRashad(), Creator::createVugar(), Creator::createLita()];

echo $template->render(['creators' => $creators]);
