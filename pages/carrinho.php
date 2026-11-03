
<?php
require_once "../config/session.php";
require_once "../config/database.php";

$carrinho = $_SESSION['carrinho'] ?? [];

$produtosCarrinho = [];
$total = 0;

if (!empty($carrinho)) {
    $ids = array_keys($carrinho);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $sql = "SELECT * FROM produtos WHERE id_produto IN ($placeholders)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($ids);

    $produtosCarrinho = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Carrinho | TechStore</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

    <header class="cabecalho">
        <div class="container">
            <h1 class="logo">Tech<span>Store</span></h1>
            <nav class="menu">
                <a href="../index.php">Início</a>
                <a href="carrinho.php">Carrinho</a>
            </nav>
        </div>
    </header>

    <main class="container my-5">

        <h2>Meu carrinho</h2>

        <?php if (empty($produtosCarrinho)): ?>

            <p>Seu carrinho está vazio.</p>

        <?php else: ?>

            <?php foreach ($produtosCarrinho as $produto): ?>

                <?php
                $id = $produto['id_produto'];
                $quantidade = $carrinho[$id];
                $subtotal = $produto['preco'] * $quantidade;
                $total += $subtotal;
                ?>

                <div class="card mb-3">
                    <div class="card-body">

                        <h5><?= htmlspecialchars($produto['nome']) ?></h5>

                        <p>Preço: R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>

                        
<form action="atualizar_carrinho.php" method="POST">

<input
    type="hidden"
    name="id_produto"
    value="<?= (int) $id ?>"
>

<label>Quantidade:</label>

<input
    type="number"
    name="quantidade"
    value="<?= (int) $quantidade ?>"
    min="1"
    max="<?= (int) $produto['estoque'] ?>"
    class="form-control mb-2"
    style="max-width: 120px;"
    required
>

<button
    type="submit"
    name="acao"
    value="atualizar"
    class="btn btn-primary btn-sm"
>
    Atualizar quantidade
</button>

<button
    type="submit"
    name="acao"
    value="remover"
    class="btn btn-danger btn-sm"
>
    Remover
</button>

</form>

<p class="mt-3">
Subtotal: R$ <?= number_format($subtotal, 2, ',', '.') ?>
<a href="checkout.php" class="btn btn-success mt-3">
    Finalizar compra
</a>
</p>


                    </div>
                </div>

            <?php endforeach; ?>

            <h3>Total: R$ <?= number_format($total, 2, ',', '.') ?></h3>

        <?php endif; ?>

    </main>

</body>
</html>
