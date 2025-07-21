<?php
function conectaBanco() {
    $db_url = getenv('DATABASE_URL');

    if (!$db_url) {

        $db_host = 'localhost';
        $db_name = 'postgres';
        $db_user = 'postgres';
        $db_password = '12345';
        $db_port = '5433'; 
        $db_sslmode = 'disable'; 
    } else {
        $url_parts = parse_url($db_url);


        $db_scheme = $url_parts['scheme'] ?? 'postgresql'; 
        $db_host = $url_parts['host'] ?? '';
        $db_port = $url_parts['port'] ?? 5432; 
        $db_user = $url_parts['user'] ?? '';
        $db_password = $url_parts['pass'] ?? ''; 
        $db_name = ltrim($url_parts['path'] ?? '', '/'); 


        $db_sslmode = 'require';
        if (isset($url_parts['query'])) {
            parse_str($url_parts['query'], $query_params);
            if (isset($query_params['sslmode'])) {
                $db_sslmode = $query_params['sslmode'];
            }
        }
    }

    try {
        
        $dsn = "$db_scheme:host=$db_host;port=$db_port;dbname=$db_name;sslmode=$db_sslmode";

   
        error_log("Tentando conexão com DB. DSN: " . $dsn);
        error_log("Usuário: " . $db_user . " Senha: " . ($db_password ? '********' : '[Vazio/Nula]')); 

        $pdo = new PDO($dsn, $db_user, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        
        error_log("Falha na Conexão PDO. DSN usado: " . (isset($dsn) ? $dsn : 'DSN não definido'));
        error_log("Mensagem de Erro: " . $e->getMessage());
        die("Erro na conexão com o banco de dados: " . $e->getMessage());
    }
}
?>
