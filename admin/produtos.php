
<?php
require_once "../config/session.php";
require_once "../config/database.php";

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header("Location: ../pages/login.php");
    exit;
}

$sql = "SELECT * FROM produtos ORDER BY id_produto DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Produtos - TechStore</title>

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

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gerenciar produtos</h1>

        <a href="adicionar_produto.php" class="btn btn-success">
            + Adicionar produto
        </a>
    </div>

    <?php if (count($produtos) > 0): ?>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Preço</th>
                        <th>Estoque</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($produtos as $produto): ?>
                        <tr>
                            <td><?= (int) $produto['id_produto'] ?></td>

                            <td>
                                <?= htmlspecialchars($produto['nome']) ?>
                            </td>

                            <td>
                                R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                            </td>

                            <td><?= (int) $produto['estoque'] ?></td>

                            <td>
                                <a href="editar_produto.php?id=<?= (int) $produto['id_produto'] ?>"
                                   class="btn btn-primary btn-sm">
                                    Editar
                                </a>

                                <form action="excluir_produto.php" method="POST" class="d-inline"
      onsubmit="return confirm('Tem certeza que deseja excluir este produto?')">

    <input type="hidden" name="id"
           value="<?= (int) $produto['id_produto'] ?>">

    <button type="submit" class="btn btn-danger btn-sm">
        Excluir
    </button>

</form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php else: ?>

        <p>Nenhum produto cadastrado.</p>

    <?php endif; ?>

</main>

</body>
</html>
