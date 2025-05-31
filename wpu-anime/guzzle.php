<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;

$client = new Client();

$response = $client->request('GET', 'https://api.jikan.moe/v4/anime', [
    'query'=> [
        'q' => 'naruto'
    ]
]);

$result = json_decode($response->getBody()->getContents(), true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anime</title>
</head>
<body>
    <?php foreach ($result ['data'] as $anime): ?>
    <ul>
        <li>Title : <?=$anime ['title']; ?> </li>
        <li>Year : <?=$anime ['year']; ?> </li>
        <li>
            <img src="<?=$anime['images']['jpg']['image_url']; ?>" width="80">
        </li>
    </ul>
    <?php endforeach; ?>
</body>
</html>