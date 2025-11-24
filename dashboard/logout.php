<?php
    session_start();

    if ($_SESSION['user_id'] ?? false) {
        $_SESSION['user_id'] = null;
    }

    // Redirection vers la page de login
    header("Location: ../");
    http_response_code(302);
?>