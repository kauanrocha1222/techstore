
<?php
require_once "../config/session.php";
require_once "../config/database.php";

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header("Location: ../pages/login.php");
    exit;
}

// Aceita exclusão somente por POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: produtos.php");
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: produtos.php");
    exit;
}

try {
    $stmt = $pdo->prepare(
        "DELETE FROM produtos WHERE id_produto = ?"
    );

    $stmt->execute([$id]);

    header("Location: produtos.php?excluido=sucesso");
    exit;

} catch (PDOException $e) {
    header("Location: produtos.php?erro=exclusao");
    exit;
}
