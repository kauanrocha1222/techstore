
<?php
require_once "../config/session.php";
require_once "../config/database.php";

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header("Location: ../pages/login.php");
    exit;
}

// Busca os pedidos e os dados dos clientes
$sql = "SELECT
            p.id_pedido,
            p.data_pedido,
            p.valor_total,
            p.forma_pagamento,
            u.nome AS nome_cliente,
            u.email
        FROM pedidos p
        INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
        ORDER BY p.id_pedido DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos - TechStore</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">Painel TechStore</a>
        <a href="index.php" class="btn btn-outline-light">Voltar</a>
    </div>
</nav>

<main class="container mt-5">

    <h1 class="mb-4">Pedidos realizados</h1>

    <?php if (count($pedidos) > 0): ?>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">

                <thead class="table-dark">
                    <tr>
                        <th>Nº Pedido</th>
                        <th>Cliente</th>
                        <th>E-mail</th>
                        <th>Data</th>
                        <th>Pagamento</th>
                        <th>Total</th>
                        <th>Itens</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($pedidos as $pedido): ?>

                        <tr>
                            <td>#<?= (int) $pedido['id_pedido'] ?></td>

                            <td>
                                <?= htmlspecialchars($pedido['nome_cliente']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($pedido['email']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($pedido['data_pedido']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($pedido['forma_pagamento']) ?>
                            </td>

                            <td>
                                R$ <?= number_format($pedido['valor_total'], 2, ',', '.') ?>
                            </td>

                            <td>
                                <a href="itens_pedido.php?id=<?= (int) $pedido['id_pedido'] ?>"
                                   class="btn btn-primary btn-sm">
                                    Ver itens
                                </a>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>
        </div>

    <?php else: ?>

        <div class="alert alert-info">
            Nenhum pedido foi realizado ainda.
        </div>

    <?php endif; ?>

</main>

</body>
</html>
