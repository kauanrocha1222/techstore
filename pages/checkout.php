
<?php
require_once "../config/session.php";
require_once "../config/database.php";

// Verifica se o cliente está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Verifica se o carrinho não está vazio
$carrinho = $_SESSION['carrinho'] ?? [];

if (empty($carrinho)) {
    header("Location: carrinho.php");
    exit;
}

// Busca os produtos do carrinho
$ids = array_keys($carrinho);
$placeholders = implode(',', array_fill(0, count($ids), '?'));

$sql = "SELECT * FROM produtos WHERE id_produto IN ($placeholders)";
$stmt = $pdo->prepare($sql);
$stmt->execute($ids);

$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;

foreach ($produtos as $produto) {
    $id = $produto['id_produto'];
    $quantidade = $carrinho[$id];

    $total += $produto['preco'] * $quantidade;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Finalizar compra | TechStore</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

    <header class="cabecalho">
        <div class="container">
            <h1 class="logo">Tech<span>Store</span></h1>
        </div>
    </header>

    <main class="container my-5">

        <h2>Finalizar compra</h2>

        <h4 class="mt-4">Resumo do pedido</h4>

        <?php foreach ($produtos as $produto): ?>

            <?php
            $id = $produto['id_produto'];
            $quantidade = $carrinho[$id];
            $subtotal = $produto['preco'] * $quantidade;
            ?>

            <div class="card mb-3">
                <div class="card-body">

                    <h5><?= htmlspecialchars($produto['nome']) ?></h5>

                    <p>Quantidade: <?= (int) $quantidade ?></p>

                    <p>
                        Subtotal:
                        R$ <?= number_format($subtotal, 2, ',', '.') ?>
                    </p>

                </div>
            </div>

        <?php endforeach; ?>

        <h3 class="my-4">
            Total: R$ <?= number_format($total, 2, ',', '.') ?>
        </h3>

        <form action="finalizar_compra.php" method="POST">

            <h4>Forma de pagamento</h4>

            <div class="form-check mb-2">
                <input
                    class="form-check-input"
                    type="radio"
                    name="pagamento"
                    value="PIX"
                    id="pix"
                    required
                >
                <label class="form-check-label" for="pix">
                    PIX
                </label>
            </div>

            <div class="form-check mb-2">
                <input
                    class="form-check-input"
                    type="radio"
                    name="pagamento"
                    value="Boleto"
                    id="boleto"
                >
                <label class="form-check-label" for="boleto">
                    Boleto
                </label>
            </div>

            <div class="form-check mb-3">
                <input
                    class="form-check-input"
                    type="radio"
                    name="pagamento"
                    value="Cartão"
                    id="cartao"
                >
                <label class="form-check-label" for="cartao">
                    Cartão
                </label>
            </div>

            <button type="submit" class="btn btn-primary">
                Confirmar compra
            </button>

            <a href="carrinho.php" class="btn btn-outline-secondary">
                Voltar ao carrinho
            </a>

        </form>

    </main>

</body>
</html>
