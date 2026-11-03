
<?php
require_once "../config/database.php";

$erro = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($nome === '' || $email === '' || $senha === '') {
        $erro = "Preencha todos os campos.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Digite um e-mail válido.";

    } elseif (strlen($senha) < 6) {
        $erro = "A senha precisa ter pelo menos 6 caracteres.";

    } else {

        $stmt = $pdo->prepare(
            "SELECT id_usuario FROM usuarios WHERE email = :email"
        );

        $stmt->execute([':email' => $email]);

        if ($stmt->fetch()) {
            $erro = "Este e-mail já está cadastrado.";

        } else {

            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            $sql = "INSERT INTO usuarios (nome, email, senha, tipo)
                    VALUES (:nome, :email, :senha, 'cliente')";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':senha' => $senhaHash
            ]);

            header("Location: login.php?cadastro=sucesso");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro | TechStore</title>

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

        <h2 class="mb-4">Criar minha conta</h2>

        <?php if ($erro !== ''): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($erro) ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Nome completo</label>
                <input
                    type="text"
                    name="nome"
                    class="form-control"
                    required
                >
            </div>

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
                    minlength="6"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary w-100">
                Cadastrar
            </button>

        </form>

        <p class="mt-3 text-center">
            Já tem uma conta?
            <a href="login.php">Entrar</a>
        </p>

    </main>

</body>
</html>
