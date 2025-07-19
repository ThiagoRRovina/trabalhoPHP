<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="telaLogin.css">  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Entre Paginas</title>
</head>
<body class="body-p ">
        <form action="" method="post" class="form-container" id="form-login">
            <h2 class="pb-3">Entre Páginas</h2>
            <div class="mb-3 ">
                <label for="">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3 pb-3">
                <label for="">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" required>  
            </div>
            <p>Email: sebo@gmail.com  | senha: 123</p>
            <button type="submit" class="btn btn-primary w-100">Entrar</button> 
        </form>
    </div>
    <?php 
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';
            if ($email === 'sebo@gmail.com' && $senha === '123') {
                header('Location: telaHome.php');
                exit;
            } else {
                echo "<script>alert('Erro, usuário ou senha inválidos!');</script>";
            }
        }
    ?>

</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</html>