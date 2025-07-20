<?php
// Este bloco PHP DEVE estar no topo do arquivo, antes de qualquer HTML ou espaços.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if ($email === 'sebo@gmail.com' && $senha === '123') {
        // Redireciona para telaHome.php
        header('Location: telaHome.php');
        exit; // É crucial usar exit após um redirecionamento para garantir que nenhum código adicional seja executado.
    } else {
        // Se as credenciais estiverem incorretas, definimos uma variável de sessão
        // ou uma variável para ser usada no HTML para exibir a mensagem de erro.
        // Não podemos usar alert() diretamente aqui se já enviamos cabeçalhos,
        // mas como o HTML ainda não foi enviado, podemos usar um script.
        // No entanto, para evitar o erro de cabeçalho, é melhor evitar echo de script aqui.
        // Vamos usar uma variável para exibir a mensagem no HTML.
        $login_error = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="telaLogin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Entre Paginas</title>
</head>
<body class="body-p">
    <form action="" method="post" class="form-container" id="form-login">
        <h2 class="pb-3">Entre Páginas</h2>
        <div class="mb-3 ">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3 pb-3">
            <label for="senha">Senha</label>
            <input type="password" class="form-control" id="senha" name="senha" required>
        </div>
        <p>Email: sebo@gmail.com | senha: 123</p>
        <button type="submit" class="btn btn-primary w-100">Entrar</button>
        <?php
        // Exibe a mensagem de erro se o login falhou
        if (isset($login_error) && $login_error) {
            echo "<script>alert('Erro, usuário ou senha inválidos!');</script>";
        }
        ?>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>