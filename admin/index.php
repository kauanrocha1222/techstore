
<?php
require_once "../config/session.php";

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../pages/login.php");
    exit;
}

// Verifica se o usuário é administrador
if ($_SESSION['usuario_tipo'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - TechStore</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="../index.php">TechStore</a>
            <a href="../index.php" class="btn btn-outline-light">
                Voltar à loja
            </a>
        </div>
    </nav>

    <main class="container mt-5">
        <h1>Painel Administrativo</h1>

        <p>
            Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!
        </p>

        <div class="row mt-4">

            <div class="col-md-6 mb-3">
                <div class="card p-4">
                    <h4>Gerenciar produtos</h4>
                    <p>Cadastre, edite e exclua produtos da loja.</p>
                    <a href="produtos.php" class="btn btn-primary">
                        Acessar produtos
                    </a>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="card p-4">
                    <h4>Consultar pedidos</h4>
                    <p>Visualize as compras realizadas pelos clientes.</p>
                    <a href="pedidos.php" class="btn btn-success">
                        Acessar pedidos
                    </a>
                </div>
            </div>

        </div>
    </main>

</body>
</html>
