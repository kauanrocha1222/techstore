
<?php

require_once "../config/session.php";
require_once "../config/database.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php");
    exit;
}

$id = filter_input(INPUT_POST, 'id_produto', FILTER_VALIDATE_INT);
$quantidade = filter_input(INPUT_POST, 'quantidade', FILTER_VALIDATE_INT);

if (!$id || !$quantidade || $quantidade < 1) {
    header("Location: ../index.php");
    exit;
}

$stmt = $pdo->prepare(
    "SELECT estoque FROM produtos WHERE id_produto = :id"
);

$stmt->execute([':id' => $id]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    die("Produto não encontrado.");
}

$quantidadeAtual = $_SESSION['carrinho'][$id] ?? 0;

if ($quantidadeAtual + $quantidade > (int) $produto['estoque']) {
    die("Quantidade maior que o estoque disponível.");
}

$_SESSION['carrinho'][$id] = $quantidadeAtual + $quantidade;

header("Location: carrinho.php");
exit;
