


<?php
require_once "config/database.php";
$sqlCategorias = "SELECT * FROM categorias";
$stmtCategorias = $pdo->prepare($sqlCategorias);
$stmtCategorias->execute();

$categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);

$busca = trim($_GET['busca'] ?? '');


$busca = trim($_GET['busca'] ?? '');
$categoriaId = $_GET['categoria'] ?? '';

$sql = "SELECT * FROM produtos WHERE 1=1";
$params = [];

if ($busca !== '') {
    $sql .= " AND (nome LIKE :busca OR descricao LIKE :busca)";
    $params[':busca'] = '%' . $busca . '%';
}

if ($categoriaId !== '' && filter_var($categoriaId, FILTER_VALIDATE_INT) !== false && (int)$categoriaId > 0) {
    $sql .= " AND id_categoria = :categoria";
    $params[':categoria'] = (int)$categoriaId;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>



<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TechStore | Loja de Tecnologia</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <!-- CABEÇALHO -->
    <header class="cabecalho">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">

                <h1 class="logo">Tech<span>Store</span></h1>

                

                <form class="pesquisa" action="index.php" method="GET">

<input
    type="text"
    name="busca"
    placeholder="O que você está procurando?"
    value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>"
>

<select name="categoria">
    <option value="">Todas as categorias</option>

    <?php foreach ($categorias as $categoria): ?>
        <option
            value="<?= (int) $categoria['id_categoria'] ?>"
            <?= (($_GET['categoria'] ?? '') == $categoria['id_categoria']) ? 'selected' : '' ?>
        >
            <?= htmlspecialchars($categoria['nome']) ?>
        </option>
    <?php endforeach; ?>

</select>

<button type="submit">Buscar</button>

</form>



                <nav class="menu">
                    <a href="index.php">Início</a>
                    <a href="pages/produtos.php">Produtos</a>
                    <a href="pages/login.php">Entrar</a>
                    <a href="pages/carrinho.php">🛒 Carrinho</a>
                </nav>

            </div>
        </div>
    </header>

    <!-- CONTEÚDO PRINCIPAL -->
    
<main>

<section class="banner container">
    <div class="banner-conteudo">
        <span class="banner-etiqueta">TECNOLOGIA PARA VOCÊ</span>

        <h2>O futuro está<br>na sua mão.</h2>

        <p>
            Encontre smartphones, computadores,
            acessórios e muito mais.
        </p>

        <a href="pages/produtos.php" class="botao-banner">
            Explorar produtos
        </a>
    </div>

    <div class="banner-visual">
        <span>TECH</span>
        <strong>STORE</strong>
        <p>INOVAÇÃO E TECNOLOGIA</p>
    </div>
</section>


    <!-- CATEGORIAS -->
    <section class="categorias container">

        
<!-- VITRINE DE PRODUTOS -->
<section class="produtos container">

<div class="titulo-secao">
    <h2>Nossos produtos</h2>
    <p>Confira alguns dos nossos destaques.</p>
</div>

<div class="row g-4">

    <?php if (count($produtos) > 0): ?>

        <?php foreach ($produtos as $produto): ?>

            <div class="col-md-4">

                <div class="card h-100">

                    <div class="produto-imagem">
                        📦
                    </div>

                    <div class="card-body">

                        <h5 class="card-title">
                            <?= htmlspecialchars($produto['nome']) ?>
                        </h5>

                        <p class="card-text">
                            <?= htmlspecialchars($produto['descricao'] ?? '') ?>
                        </p>

                        <h5 class="text-primary">
                            R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                        </h5>

                        <p>
                            Estoque: <?= (int) $produto['estoque'] ?>
                        </p>

                        <a href="pages/produto.php?id=<?= (int) $produto['id_produto'] ?>" class="btn btn-primary">
                          Ver produto
                         </a>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    <?php else: ?>

        <p>Nenhum produto cadastrado no momento.</p>

    <?php endif; ?>

</div>

</section>


    </section>


</main>


    <!-- RODAPÉ -->
    <footer>
        <p>© 2026 TechStore - Todos os direitos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>

</body>
</html>
