
<?php
require_once "../config/session.php";
require_once "../config/database.php";

// Verifica se o cliente está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Verifica se o carrinho possui produtos
$carrinho = $_SESSION['carrinho'] ?? [];

if (empty($carrinho)) {
    header("Location: carrinho.php");
    exit;
}

// Verifica a forma de pagamento
$pagamento = $_POST['pagamento'] ?? '';

$formasPermitidas = ['PIX', 'Boleto', 'Cartão'];

if (!in_array($pagamento, $formasPermitidas, true)) {
    die("Forma de pagamento inválida.");
}

try {

    $pdo->beginTransaction();

    $total = 0;
    $produtosCompra = [];

    // Confere estoque e calcula o valor total
    foreach ($carrinho as $id => $quantidade) {

        $stmt = $pdo->prepare(
            "SELECT * FROM produtos WHERE id_produto = :id FOR UPDATE"
        );

        $stmt->execute([':id' => $id]);

        $produto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$produto) {
            throw new Exception("Produto não encontrado.");
        }

        if ($quantidade < 1 || $quantidade > $produto['estoque']) {
            throw new Exception(
                "Estoque insuficiente para o produto: " . $produto['nome']
            );
        }

        $total += $produto['preco'] * $quantidade;

        $produtosCompra[] = [
            'produto' => $produto,
            'quantidade' => $quantidade
        ];
    }

    // Cria o pedido
    $sqlPedido = "INSERT INTO pedidos
        (id_usuario, forma_pagamento, valor_total)
        VALUES (:usuario, :pagamento, :total)";

    $stmtPedido = $pdo->prepare($sqlPedido);

    $stmtPedido->execute([
        ':usuario' => $_SESSION['usuario_id'],
        ':pagamento' => $pagamento,
        ':total' => $total
    ]);

    $idPedido = $pdo->lastInsertId();

    // Registra os produtos comprados e diminui o estoque
    foreach ($produtosCompra as $item) {

        $produto = $item['produto'];
        $quantidade = $item['quantidade'];

        $sqlItem = "INSERT INTO itens_pedido
            (id_pedido, id_produto, quantidade, preco_unitario)
            VALUES (:pedido, :produto, :quantidade, :preco)";

        $stmtItem = $pdo->prepare($sqlItem);

        $stmtItem->execute([
            ':pedido' => $idPedido,
            ':produto' => $produto['id_produto'],
            ':quantidade' => $quantidade,
            ':preco' => $produto['preco']
        ]);

        $sqlEstoque = "UPDATE produtos
            SET estoque = estoque - :quantidade
            WHERE id_produto = :id";

        $stmtEstoque = $pdo->prepare($sqlEstoque);

        $stmtEstoque->execute([
            ':quantidade' => $quantidade,
            ':id' => $produto['id_produto']
        ]);
    }

    // Confirma todas as operações
    $pdo->commit();

    // Limpa o carrinho
    unset($_SESSION['carrinho']);

    $compraFinalizada = true;

} catch (Exception $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $erro = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Compra | TechStore</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

    <main class="container my-5 text-center">

        <?php if (isset($compraFinalizada)): ?>

            <h1 class="text-success">Compra realizada!</h1>

            <p class="mt-3">
                Seu pedido foi registrado com sucesso.
            </p>

            <p>
                Número do pedido: <?= (int) $idPedido ?>
            </p>

            <p>
                Forma de pagamento: <?= htmlspecialchars($pagamento) ?>
            </p>

            <p>
                Total: R$ <?= number_format($total, 2, ',', '.') ?>
            </p>

            <a href="../index.php" class="btn btn-primary mt-3">
                Voltar para a loja
            </a>

        <?php else: ?>

            <h1 class="text-danger">Não foi possível finalizar a compra.</h1>

            <p><?= htmlspecialchars($erro ?? 'Ocorreu um erro.') ?></p>

            <a href="carrinho.php" class="btn btn-primary">
                Voltar ao carrinho
            </a>

        <?php endif; ?>

    </main>

</body>
</html>
