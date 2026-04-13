<?php
// Sécurité : seul POST est accepté
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /contact/');
    exit;
}

// Honeypot anti-spam
if (!empty($_POST['website'])) {
    header('Location: /contact/?sent=1');
    exit;
}

// Récupération et nettoyage des champs
$nom     = trim(strip_tags($_POST['nom'] ?? ''));
$email   = trim(strip_tags($_POST['email'] ?? ''));
$sujet   = trim(strip_tags($_POST['sujet'] ?? ''));
$message = trim(strip_tags($_POST['message'] ?? ''));

// Validation basique
if (empty($nom) || empty($email) || empty($sujet) || empty($message)) {
    header('Location: /contact/?error=1');
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: /contact/?error=1');
    exit;
}
if (strlen($message) > 2000 || strlen($nom) > 100) {
    header('Location: /contact/?error=1');
    exit;
}

// Envoi de l'e-mail
$destinataire = 'contact@prorestauration.fr';
$sujet_mail   = '[ProRestauration] ' . $sujet . ' — ' . $nom;
$corps        = "Nouveau message reçu via le formulaire de contact ProRestauration.fr\n";
$corps       .= "──────────────────────────────────────\n";
$corps       .= "Nom    : " . $nom . "\n";
$corps       .= "E-mail : " . $email . "\n";
$corps       .= "Sujet  : " . $sujet . "\n";
$corps       .= "──────────────────────────────────────\n\n";
$corps       .= $message . "\n";

$headers  = "From: noreply@prorestauration.fr\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

$envoye = mail($destinataire, $sujet_mail, $corps, $headers);

if ($envoye) {
    header('Location: /contact/?sent=1');
} else {
    header('Location: /contact/?error=1');
}
exit;
