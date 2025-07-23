<?php
$token = 'TOKEN_DO_DISPOSITIVO'; // ou pegue do banco
$serverKey = 'firebase-adminsdk-fbsvc@fcm-messaging-6e953.iam.gserviceaccount.com';

$notification = [
    'title' => 'Olá!',
    'body' => 'Você recebeu uma nova mensagem.',
];

$data = [
    'to' => $token,
    'notification' => $notification,
    'priority' => 'high',
];

$headers = [
    'Authorization: key=' . $serverKey,
    'Content-Type: application/json',
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
curl_close($ch);

echo $response;
?>
