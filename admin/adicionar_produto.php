
<?php
require_once "../config/session.php";
require_once "../config/database.php";

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header("Location: ../pages/login.php");
    exit;
}

$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $preco = str_replace(',', '.', $_POST['preco'] ?? '');
    $estoque = filter_var($_POST['estoque'] ?? '', FILTER_VALIDATE_INT);
    $categoria = filter_var($_POST['categoria'] ?? '', FILTER_VALIDATE_INT);

    if ($nome === '' || !is_numeric($preco) || $preco < 0 || $estoque === false || $estoque < 0 || $categoria === false) {
        $mensagem = "Preencha todos os campos corretamente.";
    } else {
        $sql = "INSERT INTO produtos (nome, descricao, preco, estoque, id_categoria)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $descricao, $preco, $estoque, $categoria]);

        header("Location: produtos.php");
        exit;
    }
}

$stmtCategorias = $pdo->prepare("SELECT * FROM categorias ORDER BY nome");
$stmtCategorias->execute();
$categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Produto - TechStore</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="container mt-5">

    <h1>Adicionar produto</h1>

    <?php if ($mensagem): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($mensagem) ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <div class="mb-3">
            <label class="form-label">Nome do produto</label>
            <input type="text" name="nome" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea name="descricao" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Preço (ex.: 1499.90)</label>
            <input type="number" name="preco" class="form-control" step="0.01" min="0" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Estoque</label>
            <input type="number" name="estoque" class="form-control" min="0" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Categoria</label>

            <select name="categoria" class="form-select" required>
                <option value="">Selecione uma categoria</option>

                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= (int) $categoria['id_categoria'] ?>">
                        <?= htmlspecialchars($categoria['nome']) ?>
                    </option>
                <?php endforeach; ?>

            </select>
        </div>

        <button type="submit" class="btn btn-success">
            Cadastrar produto
        </button>

        <a href="produtos.php" class="btn btn-secondary">
            Cancelar
        </a>

    </form>

</div>

</body>
</html>
