<?php
$config = require 'config.php';

$discordId = $_POST['discordId'] ?? '';

if (empty($discordId)) {
    die("Erreur : L'ID Discord est requis.");
}

$token = $config['token'];
$guildId = $config['guildId'];
$roleId = $config['roleId'];

$url = "https://discord.com/api/guilds/{$guildId}/members/{$discordId}";

$header = [
    "Authorization: Bot {$token}",
    "Content-Type: application/json"
];

$curl = curl_init($url);
curl_setopt_array($curl, [
    CURLOPT_HTTPHEADER => $header,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_USERAGENT => 'DiscordBot (https://discord.com, 1.0)'
]);

$response = curl_exec($curl);
$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

if ($status != 200) {
    die("Erreur : L'utilisateur n'existe pas sur le serveul ou l'ID est incorrect.");
}

// Ajouter le rôle
$data = json_encode(['roles' => array($roleId)]);
$url = "https://discord.com/api/guilds/{$guildId}/members/{$discordId}";

$curl = curl_init($url);
curl_setopt_array($curl, [
    CURLOPT_HTTPHEADER => $header,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_USERAGENT => 'DiscordBot (https://discord.am, 1.0)',
    CURLOPT_CUSTOMREQUEST => "PATCH",
    CURLOPT_POSTFIELDS => $data
]);

$response = curl_exec($curl);
$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

if ($status == 204 || $status == 200) {
    echo "Rôle ajouté avec succès!";
} else {
    echo "Erreur : Impossible d'ajouter le rôle.";
}

