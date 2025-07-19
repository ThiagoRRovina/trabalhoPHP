<?php
    include_once 'conectaBanco.php';
    $mensagem = '';
    // Excluir disco
    if (isset($_GET['excluir'])) {
        $idExcluir = $_GET['excluir'];
        $sql = "DELETE FROM discos WHERE disco_id = $1";
        $result = pg_query_params($conn, $sql, array($idExcluir));
        if ($result) {
            $mensagem = 'Disco excluído com sucesso!';
            header('Location: discos.php');
            exit;
        } else {
            $mensagem = 'Erro ao excluir disco.';
        }
    }
    // Carregar dados do disco para edição
    $discoEdit = null;
    if (isset($_GET['id'])) {
        $idEdit = $_GET['id'];
        $sql = "SELECT * FROM discos WHERE disco_id = $1";
        $result = pg_query_params($conn, $sql, array($idEdit));
        if ($result && pg_num_rows($result) > 0) {
            $discoEdit = pg_fetch_assoc($result);
        }
    }
    // Salvar ou editar disco
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $titulo = $_POST['titulo'];
        $artista = $_POST['artista'];
        $faixas = $_POST['faixas'];
        $gravadora = $_POST['gravadora'];
        $ano = $_POST['ano'];
        $data = $_POST['data'];
        $preco = $_POST['preco'];

        // Upload da imagem
        if (isset($_FILES['capa']) && $_FILES['capa']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION);
            $novoNome = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['capa']['tmp_name'], 'capas/' . $novoNome);
            $capa = $novoNome;
        } else if (!empty($_POST['capa_atual'])) {
            $capa = $_POST['capa_atual'];
        } else {
            $capa = 'placeholder-capa.jpg';
        }

        if ($id) {
            // Editar
            $sql = "UPDATE discos SET disco_titulo=$1, disco_artista=$2, disco_faixas=$3, disco_gravadora=$4, disco_ano=$5, disco_data_cadastro=$6, disco_preco=$7, disco_capa=$8 WHERE disco_id=$9";
            $params = array($titulo, $artista, $faixas, $gravadora, $ano, $data, $preco, $capa, $id);
            $result = pg_query_params($conn, $sql, $params);
            $mensagem = $result ? 'Disco editado com sucesso!' : 'Erro ao editar disco.';
        } else {
            // Novo disco
            $sql = "INSERT INTO discos (disco_titulo, disco_artista, disco_faixas, disco_gravadora, disco_ano, disco_data_cadastro, disco_preco, disco_capa) VALUES ($1,$2,$3,$4,$5,$6,$7,$8)";
            $params = array($titulo, $artista, $faixas, $gravadora, $ano, $data, $preco, $capa);
            $result = pg_query_params($conn, $sql, $params);
            $mensagem = $result ? 'Disco cadastrado com sucesso!' : 'Erro ao cadastrar disco.';
            header('Location: discos.php');
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
            style="margin-left: 15px; font-size: 62px; color: aliceblue;">Entre Páginas</h1>
        <div class="d-flex gap-3">
            <span class="nav-item" id="nav-inicio">Início</span>
            <span class="nav-item" id="nav-livros">Livros</span>
            <span class="nav-item" id="nav-discos" style="background-color: #333;">Discos</span>
        </div>
    </nav>

    <div class="container mt-3 hero-section py-5 mb-4">
        <form action="cadastrarDisco.php" method="POST" enctype="multipart/form-data" id="formCadastroItem" class="p-4">
     
            <h2 id="formTitulo" class="mb-4">Cadastrar Disco</h2>
            <div class="row mb-4">
                <div class="col-md-6">
                    <!-- Mensagem de status será exibida aqui se necessário -->
                    <input type="hidden" name="id" id="inputId" value="<?php echo $discoEdit ? htmlspecialchars($discoEdit['disco_id']) : ''; ?>">
                    <input type="hidden" name="capa_atual" value="<?php echo $discoEdit ? htmlspecialchars($discoEdit['disco_capa']) : ''; ?>">
                    <div class="mb-3">
                        <label for="inputTitulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="inputTitulo" name="titulo" required style="z-index:9999;position:relative;" value="<?php echo $discoEdit ? htmlspecialchars($discoEdit['disco_titulo']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="inputArtista" class="form-label">Artista</label>
                        <input type="text" class="form-control" id="inputArtista" name="artista" required style="z-index:9999;position:relative;" value="<?php echo $discoEdit ? htmlspecialchars($discoEdit['disco_artista']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="inputFaixas" class="form-label">Número de faixas</label>
                        <input type="number" class="form-control" id="inputFaixas" name="faixas" min="1" required style="z-index:9999;position:relative;" value="<?php echo $discoEdit ? htmlspecialchars($discoEdit['disco_faixas']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="inputGravadora" class="form-label">Gravadora</label>
                        <input type="text" class="form-control" id="inputGravadora" name="gravadora" required style="z-index:9999;position:relative;" value="<?php echo $discoEdit ? htmlspecialchars($discoEdit['disco_gravadora']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="inputAno" class="form-label">Ano de Lançamento</label>
                        <input type="text" class="form-control" id="inputAno" name="ano" min="1000" max="9999" required style="z-index:9999;position:relative;" value="<?php echo $discoEdit ? htmlspecialchars($discoEdit['disco_ano']) : ''; ?>">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="inputData" class="form-label">Data de cadastro</label>
                            <input type="date" class="form-control" id="inputData" name="data" required style="z-index:9999;position:relative;" value="<?php echo $discoEdit ? htmlspecialchars($discoEdit['disco_data_cadastro']) : ''; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="inputPreco" class="form-label">Preço</label>
                            <input type="text" step="0.01" class="form-control" id="inputPreco" name="preco" required style="z-index:9999;position:relative;" value="<?php echo $discoEdit ? htmlspecialchars($discoEdit['disco_preco']) : ''; ?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 d-flex flex-column align-items-center justify-content-center">
                    <div class="text-center p-3"
                        style="background-color: #f8f9fa; border: 1px dashed #ced4da; border-radius: 5px; width: 100%; max-width: 250px;">
                        <img id="previewCapa" src="<?php echo $discoEdit && $discoEdit['disco_capa'] ? 'capas/' . htmlspecialchars($discoEdit['disco_capa']) : 'placeholder-capa.jpg'; ?>" alt="Pré-visualização da Capa"
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