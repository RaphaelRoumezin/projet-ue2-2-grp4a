<?php

    // Effectue un appel à l'API Discord. Retourne la réponse décodée en tableau associatif.
    function discord_api_call($endpoint, $method = 'GET', $data = null) {
        $token = parse_ini_file('../env.ini')['DISCORD_TOKEN'];
        
        $url = "https://discord.com/api/v10/" . $endpoint;
        $headers = [
            "Authorization: Bot " . $token,
            "Content-Type: application/json"
        ];

        $context = stream_context_create([
            'http' => [
                'method' => $method,
                'header' => implode("\r\n", $headers),
                'content' => $data ? json_encode($data) : null
            ]
        ]);

        $response = file_get_contents($url, false, $context);
        return json_decode($response, true);
    }

    // Appelle l'API Discord pour créer un canal DM avec un utilisateur donné.
    // Le channel sera créé par Discord s'il n'existe pas déjà, et l'ID du canal sera retourné dans tous les cas.
    function discord_create_dm_channel($user_id) {
        $data = ['recipient_id' => $user_id];
        return discord_api_call('users/@me/channels', 'POST', $data);
    }

    // Appelle l'API Discord pour envoyer un message texte simple dans un canal donné.
    function discord_send_message($channel_id, $message) {
        $data = ['content' => $message];
        return discord_api_call("channels/{$channel_id}/messages", 'POST', $data);
    }

    // Appelle l'API Discord pour envoyer un message avec embed dans un canal donné.
    function discord_send_message_with_embed($channel_id, $message, $embeds) {
        $data = ['content' => $message, 'embeds' => $embeds];
        return discord_api_call("channels/{$channel_id}/messages", 'POST', $data);
    }
?>