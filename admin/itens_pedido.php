
<?php
require_once "../config/session.php";
require_once "../config/database.php";

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header("Location: ../pages/login.php");
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: pedidos.php");
    exit;
}

// Busca os dados do pedido
$sqlPedido = "SELECT p.*, u.nome AS nome_cliente, u.email
              FROM pedidos p
              INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
              WHERE p.id_pedido = ?";

$stmtPedido = $pdo->prepare($sqlPedido);
$stmtPedido->execute([$id]);
$pedido = $stmtPedido->fetch(PDO::FETCH_ASSOC);

if (!$pedido) {
    header("Location: pedidos.php");
    exit;
}

// Busca os produtos do pedido
$sqlItens = "SELECT
                ip.quantidade,
                ip.preco_unitario,
                pr.nome
             FROM itens_pedido ip
             INNER JOIN produtos pr ON ip.id_produto = pr.id_produto
             WHERE ip.id_pedido = ?";

$stmtItens = $pdo->prepare($sqlItens);
$stmtItens->execute([$id]);
$itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Pedido - TechStore</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">Painel TechStore</a>
        <a href="pedidos.php" class="btn btn-outline-light">Voltar aos pedidos</a>
    </div>
</nav>

<main class="container mt-5">

    <h1>Pedido #<?= (int) $pedido['id_pedido'] ?></h1>

    <div class="card p-3 mb-4">
        <h5>Informações do cliente</h5>

        <p><strong>Nome:</strong>
            <?= htmlspecialchars($pedido['nome_cliente']) ?>
        </p>

        <p><strong>E-mail:</strong>
            <?= htmlspecialchars($pedido['email']) ?>
        </p>

        <p><strong>Data:</strong>
            <?= htmlspecialchars($pedido['data_pedido']) ?>
        </p>

        <p><strong>Pagamento:</strong>
            <?= htmlspecialchars($pedido['forma_pagamento']) ?>
        </p>

        <p><strong>Total:</strong>
            R$ <?= number_format($pedido['valor_total'], 2, ',', '.') ?>
        </p>
    </div>

    <h3>Produtos comprados</h3>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">

            <thead class="table-dark">
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Preço unitário</th>
                    <th>Subtotal</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($itens as $item): ?>

                    <tr>
                        <td><?= htmlspecialchars($item['nome']) ?></td>

                        <td><?= (int) $item['quantidade'] ?></td>

                        <td>
                            R$ <?= number_format($item['preco_unitario'], 2, ',', '.') ?>
                        </td>

                        <td>
                            R$ <?= number_format(
                                $item['preco_unitario'] * $item['quantidade'],
                                2,
                                ',',
                                '.'
                            ) ?>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>
    </div>

</main>

</body>
</html>
