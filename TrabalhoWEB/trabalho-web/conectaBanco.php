<?php
function conectaBanco() {

    $db_url = getenv('DATABASE_URL');


    if (!$db_url) {
        $db_host = 'localhost';
        $db_name = 'postgres';
        $db_user = 'postgres';
        $db_password = '12345';
        $db_port = '5433';
    } else {

        $url_parts = parse_url($db_url);
        $db_host = $url_parts['host'];
        $db_port = $url_parts['port'];
        $db_user = $url_parts['user'];
        $db_password = $url_parts['pass'];
        $db_name = ltrim($url_parts['path'], '/');
    }

    try {
        $pdo = new PDO("pgsql:host=$db_host;port=$db_port;dbname=$db_name", $db_user, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Erro na conexão com o banco de dados: " . $e->getMessage());
    }
}
?>