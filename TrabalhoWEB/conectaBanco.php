<?php
function conectaBanco() {
    $db_url = getenv('DATABASE_URL');

    if (!$db_url) {
        $db_host = 'localhost';
        $db_name = 'sebo';
        $db_user = 'postgres';
        $db_password = '123';
        $db_port = '5432';
    } else {
        $url_parts = parse_url($db_url);
        $db_host = $url_parts['host'];
        $db_port = $url_parts['port'];
        $db_user = $url_parts['user'];
        $db_password = $url_parts['pass'];
        $db_name = ltrim($url_parts['path'], '/');
    }

    $conn_string = "host=$db_host port=$db_port dbname=$db_name user=$db_user password=$db_password";

    $conn = pg_connect($conn_string);

    if (!$conn) {
        die("Erro na conexão com o banco de dados: " . pg_last_error());
    }

    return $conn;
}
?>