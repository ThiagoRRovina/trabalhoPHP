<?php
    include_once 'conectaBanco.php';
    $pdo = conectaBanco();
    $mensagem = '';

    // Inicializa variáveis do formulário
    $form_id = '';
    $form_titulo = '';
    $form_autor = '';
    $form_paginas = '';
    $form_editora = '';
    $form_ano = '';
    $form_data = '';
    $form_preco = '';
    $form_capa = 'placeholder-capa.jpg';

    // Se for edição, busca os dados do livro
    if (isset($_GET['id']) && $_GET['id'] !== '') {
        $idEditar = $_GET['id'];
        $sql = "SELECT * FROM livros WHERE livro_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idEditar]);
        $livro = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($livro) {
            $form_id = $livro['livro_id'];
            $form_titulo = $livro['livro_titulo'];
            $form_autor = $livro['livro_autor'];
            $form_paginas = $livro['livro_paginas'];
            $form_editora = $livro['livro_editora'];
            $form_ano = $livro['livro_ano'];
            $form_data = $livro['livro_data_cadastro'];
            $form_preco = $livro['livro_preco'];
            $form_capa = $livro['livro_capa'] ? 'capas/' . $livro['livro_capa'] : 'placeholder-capa.jpg';
        }
    }

    if (isset($_GET['excluir'])) {
        $idExcluir = $_GET['excluir'];
        $sql = "DELETE FROM livros WHERE LIVRO_ID = ?";
        if (!isset($pdo)) {
            if (isset($conn)) {
                $pdo = $conn;
            } elseif (isset($conexao)) {
                $pdo = $conexao;
            } else {
                die('====Erro=====');
            }
        }
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$idExcluir]);
        if ($result) {
            $mensagem = 'Livro excluído com sucesso!!';
            header('Location: livros.php');
            exit;
        } else {
            $mensagem = '-- Erro ao excluir livro --';
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $titulo = $_POST['titulo'];
        $autor = $_POST['autor'];
        $paginas = $_POST['paginas'];
        $editora = $_POST['editora'];
        $ano = $_POST['ano'];
        $data = $_POST['data'];
        $preco = $_POST['preco'];


        if (isset($_FILES['capa']) && $_FILES['capa']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION);
            $novoNome = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['capa']['tmp_name'], 'capas/' . $novoNome);
            $capa = $novoNome;
        } else {
            $capa = 'placeholder-capa.jpg';
        }

        if (!isset($pdo)) {
            if (isset($conn)) {
                $pdo = $conn;
            } elseif (isset($conexao)) {
                $pdo = $conexao;
            } else {
                die('Err0====');
            }
        }

        if ($id) {

            $sql = "UPDATE livros SET livro_titulo=?, livro_autor=?, livro_paginas=?, livro_editora=?, livro_ano=?, livro_data_cadastro=?, livro_preco=?, livro_capa=? WHERE livro_id=?";
            $params = array($titulo, $autor, $paginas, $editora, $ano, $data, $preco, $capa, $id);
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute($params);
            $mensagem = $result ? 'Livro editado com sucesso!' : 'Erro ao editar livro.';
        } else {

            $sql = "INSERT INTO livros (livro_titulo, livro_autor, livro_paginas, livro_editora, livro_ano, livro_data_cadastro, livro_preco, livro_capa) VALUES (?,?,?,?,?,?,?,?)";
            $params = array($titulo, $autor, $paginas, $editora, $ano, $data, $preco, $capa);
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute($params);
            $mensagem = $result ? 'Livro cadastrado com sucesso!' : 'Erro ao cadastrar livro.';
            header('Location: livros.php');
            exit;
        }
    }

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="telaHome.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Orbitron:wght@400..900&display=swap"
        rel="stylesheet">

    <title>Sebo Online</title>
</head>

<body>

    <nav class="navbar-custom px-3 py-3 d-flex flex-column">
        <h1 class="titulo-navbar mb-2"
            style="margin-left: 15px; font-size: 62px; color: aliceblue;">
            Entre paginas
        </h1>
        <div class="d-flex gap-3">
            <span class="nav-item" id="nav-inicio">Início</span>
            <span class="nav-item" id="nav-livros" style="background-color: #333;">Livros</span>
            <span class="nav-item" id="nav-discos">Discos</span>
        </div>
    </nav>

    <div class="container mt-3 hero-section py-5 mb-4">
        <form action="cadastrarLivro.php" method="POST" enctype="multipart/form-data" id="formCadastroItem" class="p-4">
            <h2 id="formTitulo" class="mb-4"><?php echo $form_id ? 'Editar Livro' : 'Cadastrar Livro'; ?></h2>
            <div class="row mb-4">
                <div class="col-md-6">
                    <input type="hidden" name="id" id="inputId" value="<?php echo htmlspecialchars($form_id); ?>">
                    <div class="mb-3">
                        <label for="inputTitulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="inputTitulo" name="titulo" required style="z-index:9999;position:relative;" value="<?php echo htmlspecialchars($form_titulo); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="inputAutor" class="form-label">Autor</label>
                        <input type="text" class="form-control" id="inputAutor" name="autor" required style="z-index:9999;position:relative;" value="<?php echo htmlspecialchars($form_autor); ?>">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="inputData" class="form-label">Data de cadastro</label>
                            <input type="date" class="form-control" id="inputData" name="data" required style="z-index:9999;position:relative;" value="<?php echo htmlspecialchars($form_data); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="inputPreco" class="form-label">Preço</label>
                            <input type="text" step="0.01" class="form-control" id="inputPreco" name="preco" required style="z-index:9999;position:relative;" value="<?php echo htmlspecialchars($form_preco); ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="inputPaginas" class="form-label">Número de Páginas</label>
                        <input type="text" class="form-control" id="inputPaginas" name="paginas" min="1" required style="z-index:9999;position:relative;" value="<?php echo htmlspecialchars($form_paginas); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="inputEditora" class="form-label">Editora</label>
                        <input type="text" class="form-control" id="inputEditora" name="editora" required style="z-index:9999;position:relative;" value="<?php echo htmlspecialchars($form_editora); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="inputAno" class="form-label">Ano de Publicação</label>
                        <input type="text" class="form-control" id="inputAno" name="ano" min="1000" max="9999" required style="z-index:9999;position:relative;" value="<?php echo htmlspecialchars($form_ano); ?>">
                    </div>
                </div>
                <div class="col-md-6 d-flex flex-column align-items-center justify-content-center">
                    <div class="text-center p-3"
                        style="background-color: #f8f9fa; border: 1px dashed #ced4da; border-radius: 5px; width: 100%; max-width: 250px;">
                        <img id="previewCapa" src="<?php echo htmlspecialchars($form_capa); ?>" alt="Pré-visualização da Capa"
                            class="img-fluid mb-3" style="max-height: 200px; object-fit: contain;">
                        <input type="file" id="inputCapa" name="capa" accept="image/*" class="d-none">
                        <button type="button" class="btn btn-secondary btn-sm" id="btnEscolherImagem"
                            style="z-index:9999;position:relative;">
                            <i class="bi bi-upload"></i> Escolher Imagem</button>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end gap-3 mt-4">
                <button type="submit" class="btn btn-primary" style="z-index:9999;position:relative;">Salvar</button>
                <button type="button" class="btn btn-danger" id="btnExcluir" style="z-index:9999;position:relative;display:none;">Excluir</button>
                <button type="button" class="btn btn-secondary" id="btnCancelar" style="z-index:9999;position:relative;">Cancelar</button>
            </div>
        </form>
    </div>
    <footer class="text-center py-4" style="background-color: rgb(2, 153, 133);">
        <p class="mb-0 text-white">@ 2025 Entre Páginas</p>
        <p class="mb-0 text-white justify-content-center d-flex">Desenvolvido por;</p>
        <p class="mb-0 text-white">Thiago Rodrigues Rovina | ra124257 </p>
        <p class="mb-0 text-white">Felipe Matheus Alves André | ra107496</p>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            if (window.location.search.includes('sucesso=1')) {
                window.location.href = 'livros.php';
                return;
            }

            document.getElementById('nav-inicio').onclick = function () { window.location.href = 'telaHome.php'; };
            document.getElementById('nav-livros').onclick = function () { window.location.href = 'livros.php'; };
            document.getElementById('nav-discos').onclick = function () { window.location.href = 'discos.php'; };

            const btnEscolherImagem = document.getElementById('btnEscolherImagem');
            const inputCapa = document.getElementById('inputCapa');
            const previewCapa = document.getElementById('previewCapa');
            if (btnEscolherImagem && inputCapa && previewCapa) {
                btnEscolherImagem.addEventListener('click', function () {
                    inputCapa.click();
                });
                inputCapa.addEventListener('change', function (event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            previewCapa.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            const btnCancelar = document.getElementById('btnCancelar');
            if (btnCancelar) {
                btnCancelar.onclick = function () { window.history.back(); };
            }
        });
    </script>
</body>

</html>