
<?php
require_once "../config/database.php";
require_once "../config/session.php";

$erro = "";

if (isset($_GET['cadastro']) && $_GET['cadastro'] === 'sucesso') {
    $mensagem = "Cadastro realizado! Agora entre na sua conta.";
} else {
    $mensagem = "";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    $stmt = $pdo->prepare(
        "SELECT * FROM usuarios WHERE email = :email"
    );

    $stmt->execute([':email' => $email]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {

        session_regenerate_id(true);

        $_SESSION['usuario_id'] = $usuario['id_usuario'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_tipo'] = $usuario['tipo'];

        header("Location: ../index.php");
        exit;

    } else {
        $erro = "E-mail ou senha incorretos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | TechStore</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

    <header class="cabecalho">
        <div class="container">
            <h1 class="logo">Tech<span>Store</span></h1>
        </div>
    </header>

    <main class="container my-5" style="max-width: 500px;">

        <h2 class="mb-4">Entrar na minha conta</h2>

        <?php if ($mensagem !== ''): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <?php if ($erro !== ''): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($erro) ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Senha</label>
                <input
                    type="password"
                    name="senha"
                    class="form-control"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary w-100">
                Entrar
            </button>

        </form>

        <p class="mt-3 text-center">
            Ainda não tem conta?
            <a href="cadastro.php">Cadastre-se</a>
        </p>

    </main>

</body>
</html>
