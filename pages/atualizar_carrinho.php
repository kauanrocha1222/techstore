
<?php

require_once "../config/session.php";
require_once "../config/database.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: carrinho.php");
    exit;
}

$acao = $_POST['acao'] ?? '';
$id = filter_input(INPUT_POST, 'id_produto', FILTER_VALIDATE_INT);

if (!$id || !isset($_SESSION['carrinho'][$id])) {
    header("Location: carrinho.php");
    exit;
}

// REMOVER PRODUTO
if ($acao === 'remover') {

    unset($_SESSION['carrinho'][$id]);

    header("Location: carrinho.php");
    exit;
}

// ATUALIZAR QUANTIDADE
if ($acao === 'atualizar') {

    $quantidade = filter_input(INPUT_POST, 'quantidade', FILTER_VALIDATE_INT);

    if (!$quantidade || $quantidade < 1) {
        header("Location: carrinho.php");
        exit;
    }

    $stmt = $pdo->prepare(
        "SELECT estoque FROM produtos WHERE id_produto = :id"
    );

    $stmt->execute([':id' => $id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produto || $quantidade > (int) $produto['estoque']) {
        die("Quantidade maior que o estoque disponível.");
    }

    $_SESSION['carrinho'][$id] = $quantidade;

    header("Location: carrinho.php");
    exit;
}

header("Location: carrinho.php");
exit;
