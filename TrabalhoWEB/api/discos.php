<?php 
header('Content-Type: text/html; charset=utf-8'); 
include_once 'conectaBanco.php';
$pdo = conectaBanco();
$discos = [];
$sql = "SELECT * FROM discos ORDER BY disco_titulo ASC";
$stmt = $pdo->query($sql);
if ($stmt) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $discos[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="telaHome.css"> <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Sebo Online - Discos</title>
</head>

<body>
    <nav class="navbar-custom px-3 py-3 d-flex flex-column" style="background-color: rgb(2, 153, 133);">
        <div class="w-100 d-flex justify-content-between align-items-center mb-2">
            <h1 class="titulo-navbar mb-0" style="margin-left: 15px; font-size: 62px; color: aliceblue;">Entre Páginas</h1>
        </div>
        <div class="d-flex gap-3">
            <span class="nav-item" id="nav-inicio">Início</span>
            <span class="nav-item" id="nav-livros" >Livros</span>
             <span class="nav-item" id="nav-discos" style="background-color: #333;">Discos</span>
        </div>
    </nav>
    <div class="row col-md-12 justify-content-end d-flex m-2">
        <div class="col-md-3">
            <input type="text" id="campoPesquisar" class="form-control m-2" placeholder="Pesquisar Discos...">
        </div>
        <div class="col-md-3">
            <button class="btn btn-secondary m-2">Pesquisar</button>
        </div>
        <div class="col-md-4 justify-content-end d-flex m-2">
            <button type="button" id="btnCadastrar" class="btn btn-primary me-3" onclick="window.location.href='cadastrarDisco.php'">Cadastrar</button>
        </div>
    </div>

    <div class="container mt-4">
        <h1 class="mb-4" style="margin-left: 10px;">Todos os Discos</h1>
        <hr style="width: 72%; margin-left: 10px;">
        <div class="row card-container gap-0" id="discos-container" style="justify-content: flex-start;">
        <?php if (empty($discos)) { ?>
            <div class="col-md-3 mb-4">
                <div class="card h-100 border-0 shadow-lg rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #007bff 80%, #0056b3 100%); color: #fff; cursor:pointer; transition: transform 0.2s, box-shadow 0.2s;" onclick="window.location.href='cadastrarDisco.php'">
                    <div class="d-flex flex-column align-items-center justify-content-center p-4" style="height: 100%;">
                        <i class="bi bi-plus-circle" style="font-size: 4rem; color: #fff;"></i>
                        <h5 class="card-title fw-bold mt-3" style="color: #fff;">Adicionar Novo Disco</h5>
                        <p class="card-text" style="color: #e0f2f7;">Clique aqui para cadastrar o primeiro disco do sebo!</p>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <?php foreach ($discos as $disco) { ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100 highlight-card border-0 shadow-lg rounded-4 overflow-hidden" style="transition: transform 0.2s, box-shadow 0.2s;">
                        <?php
                        $caminhoBase = 'capas/';
                        $capa = isset($disco['disco_capa']) && $disco['disco_capa'] ? $disco['disco_capa'] : '';
                        $caminhoCapa = $capa ? $caminhoBase . $capa : '';
                        $caminhoFisico = __DIR__ . '/' . $caminhoCapa;
                        if ($capa && file_exists($caminhoFisico)) {
                            $src = $caminhoCapa;
                        } else {
                            $src = 'placeholder-capa.jpg';
                        }
                        ?>
                        <br><img src="<?php echo htmlspecialchars($src); ?>" class="card-img-top card-img-livro" alt="Capa do disco <?php echo htmlspecialchars($disco['disco_titulo']); ?>">
                        <div class="card-body bg-white rounded-bottom-4">
                            <h5 class="card-title fw-bold text-primary"><?php echo htmlspecialchars($disco['disco_titulo']); ?></h5>
                            <p class="card-text text-secondary mb-1"><b>Artista:</b> <?php echo htmlspecialchars($disco['disco_artista']); ?></p>
                            <p class="card-text text-secondary mb-1"><b>Faixas:</b> <?php echo htmlspecialchars($disco['disco_faixas']); ?></p>
                            <p class="card-text text-secondary mb-1"><b>Gravadora:</b> <?php echo htmlspecialchars($disco['disco_gravadora']); ?></p>
                            <p class="card-text text-secondary mb-1"><b>Ano:</b> <?php echo htmlspecialchars($disco['disco_ano']); ?></p>
                            <p class="card-text text-secondary mb-1"><b>Data Cadastro:</b> <?php echo htmlspecialchars($disco['disco_data_cadastro']); ?></p>
                            <p class="card-text text-success fw-bold fs-5">R$ <?php echo number_format($disco['disco_preco'], 2, ',', '.'); ?></p>
                            <div class="d-flex justify-content-between gap-2 mt-2">
                                <a href="cadastrarDisco.php?id=<?php echo urlencode($disco['disco_id']); ?>" class="btn btn-warning btn-sm rounded-pill px-3">Editar</a>
                                <a href="cadastrarDisco.php?excluir=<?php echo urlencode($disco['disco_id']); ?>" class="btn btn-danger btn-sm rounded-pill px-3" onclick="return confirm('Deseja realmente excluir este disco?');">Excluir</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="col-md-3 mb-4">
                <div class="card h-100 border-0 shadow-lg rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #007bff 80%, #0056b3 100%); color: #fff; cursor:pointer; transition: transform 0.2s, box-shadow 0.2s;" onclick="window.location.href='cadastrarDisco.php'">
                    <div class="d-flex flex-column align-items-center justify-content-center p-4" style="height: 100%;">
                        <i class="bi bi-plus-circle" style="font-size: 4rem; color: #fff;"></i>
                        <h5 class="card-title fw-bold mt-3" style="color: #fff;">Adicionar Novo Disco</h5>
                        <p class="card-text" style="color: #e0f2f7;">Clique aqui para cadastrar mais um disco!</p>
                    </div>
                </div>
            </div>
        <?php } ?>
        </div>
    </div>

    <footer class="text-center py-4" style="background-color: rgb(2, 153, 133);">
        <p class="mb-0 text-white">@ 2025 Entre Páginas</p>
        <p class="mb-0 text-white justify-content-center d-flex">Desenvolvido por;</p>
        <p class="mb-0 text-white">Thiago Rodrigues Rovina | ra124257 </p>
        <p class="mb-0 text-white">Felipe Matheus Alves André | ra107496</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const campoPesquisar = document.getElementById('campoPesquisar');
        const discosContainer = document.getElementById('discos-container');

        document.getElementById('nav-inicio').onclick = function () { window.location.href = 'telaHome.php'; };
        document.getElementById('nav-livros').onclick = function () { window.location.href = 'livros.php'; };
        document.getElementById('nav-discos').onclick = function () { window.location.href = 'discos.php'; };
        if (!campoPesquisar || !discosContainer) return;

        campoPesquisar.addEventListener('input', function() {
            const termo = campoPesquisar.value.trim().toLowerCase();
            const cards = discosContainer.querySelectorAll('.card');
            cards.forEach(card => {
                const titulo = card.querySelector('.card-title');
                if (titulo && titulo.textContent) {
                    const textoTitulo = titulo.textContent.toLowerCase();
                    if (textoTitulo.includes('adicionar novo disco')) {
                        card.parentElement.style.display = '';
                    } else {
                        if (textoTitulo.includes(termo)) {
                            card.parentElement.style.display = '';
                        } else {
                            card.parentElement.style.display = 'none';
                        }
                    }
                }
            });
        });
    });
    </script>

</body>


</html>
