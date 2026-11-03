
<?php
require_once "../config/database.php";

if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    die("Produto não encontrado.");
}

$id = (int) $_GET['id'];

$sql = "SELECT * FROM produtos WHERE id_produto = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$stmt->execute();

$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    die("Produto não encontrado.");
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($produto['nome']) ?> | TechStore</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

    <header class="cabecalho">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">

                <h1 class="logo">Tech<span>Store</span></h1>

                <nav class="menu">
                    <a href="../index.php">Início</a>
                    <a href="produtos.php">Produtos</a>
                    <a href="login.php">Entrar</a>
                    <a href="carrinho.php">🛒 Carrinho</a>
                </nav>

            </div>
        </div>
    </header>

    <main class="container my-5">

        <a href="../index.php" class="btn btn-outline-secondary mb-4">
            Voltar para a loja
        </a>

        <div class="row g-4">

            <div class="col-md-6">

                <div class="produto-imagem">
                    <?php if (!empty($produto['imagem'])): ?>
                        <img
                            src="../assets/images/<?= htmlspecialchars($produto['imagem']) ?>"
                            alt="<?= htmlspecialchars($produto['nome']) ?>"
                            class="img-fluid"
                        >
                    <?php else: ?>
                        📦
                    <?php endif; ?>
                </div>

            </div>

            <div class="col-md-6">

                <h2><?= htmlspecialchars($produto['nome']) ?></h2>

                <p>
                    <?= htmlspecialchars($produto['descricao'] ?? '') ?>
                </p>

                <h3 class="text-primary">
                    R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                </h3>

                <?php if ($produto['estoque'] > 0): ?>

                    <p class="text-success">
                        Produto disponível em estoque.
                    </p>

                <?php else: ?>

                    <p class="text-danger">
                        Produto indisponível no momento.
                    </p>

                <?php endif; ?>

                <p>
                    Quantidade disponível: <?= (int) $produto['estoque'] ?>
                    <?php if ($produto['estoque'] > 0): ?>

<form action="adicionar_carrinho.php" method="POST">

    <input
        type="hidden"
        name="id_produto"
        value="<?= (int) $produto['id_produto'] ?>"
    >

    <label for="quantidade">Quantidade:</label>

    <input
        type="number"
        id="quantidade"
        name="quantidade"
        value="1"
        min="1"
        max="<?= (int) $produto['estoque'] ?>"
        class="form-control mb-3"
        style="max-width: 120px;"
        required
    >

    <button type="submit" class="btn btn-primary">
        Adicionar ao carrinho
    </button>

</form>

<?php endif; ?>
                </p>

            </div>

        </div>

    </main>

    <footer>
        <p>© 2026 TechStore - Todos os direitos reservados.</p>
    </footer>

</body>
</html>
