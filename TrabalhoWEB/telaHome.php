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

    <nav class="navbar-custom px-3 py-3 d-flex flex-column" style="background-color: rgb(2, 153, 133); box-shadow: black;">
        <div class="w-100 d-flex justify-content-between align-items-center mb-2">
            <h1 class="titulo-navbar mb-0" style="margin-left: 15px; font-size: 62px; color: rgb(255, 255, 255);">Entre Páginas</h1>
        </div>
        <div class="d-flex gap-3">
            <span class="nav-item" id="nav-inicio">Início</span>
            <span class="nav-item" id="nav-livros">Livros</span>
            <span class="nav-item" id="nav-discos">Discos</span>
        </div>
    </nav>
    <?php
    include_once 'conectaBanco.php';
    // Livro destaque
    $livroDestaque = null;
    $sqlLivro = "SELECT * FROM livros ORDER BY livro_id ASC LIMIT 1";
    $resultLivro = pg_query($conn, $sqlLivro);
    if ($resultLivro && pg_num_rows($resultLivro) > 0) {
        $livroDestaque = pg_fetch_assoc($resultLivro);
    }
    // Disco destaque
    $discoDestaque = null;
    $sqlDisco = "SELECT * FROM discos ORDER BY disco_id ASC LIMIT 1";
    $resultDisco = pg_query($conn, $sqlDisco);
    if ($resultDisco && pg_num_rows($resultDisco) > 0) {
        $discoDestaque = pg_fetch_assoc($resultDisco);
    }
    ?>
    <main class="container-fluid p-0">
        <section class="hero-section text-center py-5 mb-4">
            <h1>Bem-vindo ao Entre Paginas!</h1>
            <p class="lead">Descubra livros e discos únicos para sua coleção.</p>
            <a href="livros.php" id="btn-explorar-livros" style="z-index:9999;position:relative;" class="btn btn-primary btn-lg me-2">Explorar Livros</a>
            <a href="discos.php" id="btn-explorar-discos" style="z-index:9999;position:relative;" class="btn btn-secondary btn-lg">Explorar Discos</a>
        </section>

        <hr class="custom-hr">
        <section class="highlights-section mb-5 py-5">
            <h2 class="text-center mb-4">Destaques da Semana</h2>
            <div class="row justify-content-center px-4">
                <div class="col-md-4 mb-4">
                    <div class="card h-100 highlight-card">
                        <?php
                        $caminhoBase = 'capas/';
                        if ($livroDestaque) {
                            $capaLivro = isset($livroDestaque['livro_capa']) && $livroDestaque['livro_capa'] ? $caminhoBase . $livroDestaque['livro_capa'] : 'placeholder-capa.jpg';
                        ?>
                        <img src="<?php echo htmlspecialchars($capaLivro); ?>" class="card-img-top" alt="Capa Livro Destaque">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($livroDestaque['livro_titulo']); ?></h5>
                            <p class="card-text">Autor: <?php echo htmlspecialchars($livroDestaque['livro_autor']); ?></p>
                            <p class="card-text text-success fw-bold">R$ <?php echo number_format($livroDestaque['livro_preco'], 2, ',', '.'); ?></p>
                            <a id="btnVerDetalhesLivros" class="btn btn-outline-dark" href="livros.php">Ver Detalhes</a>
                        </div>
                        <?php } else { ?>
                        <img src="placeholder-capa.jpg" class="card-img-top" alt="Sem livro destaque">
                        <div class="card-body">
                            <h5 class="card-title">Nenhum livro cadastrado</h5>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 highlight-card">
                        <?php
                        if ($discoDestaque) {
                            $capaDisco = isset($discoDestaque['disco_capa']) && $discoDestaque['disco_capa'] ? $caminhoBase . $discoDestaque['disco_capa'] : 'placeholder-capa.jpg';
                        ?>
                        <img src="<?php echo htmlspecialchars($capaDisco); ?>" class="card-img-top" alt="Capa Disco Destaque">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($discoDestaque['disco_titulo']); ?></h5>
                            <p class="card-text">Artista: <?php echo htmlspecialchars($discoDestaque['disco_artista']); ?></p>
                            <p class="card-text text-success fw-bold">R$ <?php echo number_format($discoDestaque['disco_preco'], 2, ',', '.'); ?></p>
                            <a id="btnVerDetalhesDiscos" class="btn btn-outline-dark" href="discos.php">Ver Detalhes</a>
                        </div>
                        <?php } else { ?>
                        <img src="placeholder-capa.jpg" class="card-img-top" alt="Sem disco destaque">
                        <div class="card-body">
                            <h5 class="card-title">Nenhum disco cadastrado</h5>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer class="text-center py-4" style="background-color: rgb(2, 153, 133);">
        <p class="mb-0 text-white">@ 2025 Entre Páginas</p>
        <p class="mb-0 text-white justify-content-center d-flex">Desenvolvido por;</p>
        <p class="mb-0 text-white">Thiago Rodrigues Rovina | ra124257 </p>
        <p class="mb-0 text-white">Felipe Matheus Alves André | ra107496</p>
        
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const navInicio = document.getElementById('nav-inicio');
            const navLivrosNavbar = document.getElementById('nav-livros');
            const navDiscosNavbar = document.getElementById('nav-discos');
            const btnVerDetalhes = document.getElementById('btnVerDetalhes');
            const btnVerDetalhesDiscos = document.getElementById('btnVerDetalhesDiscos');

            btnVerDetalhesDiscos.addEventListener('click', function () {
                window.location.href = 'discos.php';
            });

            btnVerDetalhesLivros.addEventListener('click', function () {
                window.location.href = 'livros.php';
            });
            
            navInicio.addEventListener('click', function () {
                window.location.href = 'telaHome.php';
            });

            navLivrosNavbar.addEventListener('click', function () {
                window.location.href = 'livros.php';
            });

            navDiscosNavbar.addEventListener('click', function () {
                window.location.href = 'discos.php';
            });

            if (navInicio) {
                navInicio.style.backgroundColor = '#333';
                if (navLivrosNavbar) navLivrosNavbar.style.backgroundColor = '';
                if (navDiscosNavbar) navDiscosNavbar.style.backgroundColor = '';
            }
        });
    </script>
</body>

</html>