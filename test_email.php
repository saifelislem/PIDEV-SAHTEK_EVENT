<?php
require 'vendor/autoload.php';

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

$dsn = 'smtp://localhost:1025';
$transport = Transport::fromDsn($dsn);
$mailer = new Mailer($transport);

$email = (new Email())
    ->from('sahtekevent@gmail.com')
    ->to('amine69souib@gmail.com')
    ->subject('Test Indépendant')
    ->html('<h1>Test</h1><p>Email envoyé depuis un script indépendant.</p>');

echo "Tentative d’envoi...\n";
$mailer->send($email);
echo "Email envoyé avec succès !\n";