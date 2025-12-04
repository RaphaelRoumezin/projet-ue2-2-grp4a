<?php
    function get_env_value($key) {
        $env = parse_ini_file(__DIR__ . '/../env.ini');
        return $env[$key] ?? null;
    }
?>