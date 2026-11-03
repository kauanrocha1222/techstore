
<?php

// Caminho do arquivo .env
$envFile = dirname(__DIR__) . "/.env";

// Lê as configurações do arquivo .env
if (!file_exists($envFile)) {
    die("Arquivo .env não encontrado.");
}

$env = parse_ini_file($envFile);

$host = $env['DB_HOST'];
$dbname = $env['DB_NAME'];
$user = $env['DB_USER'];
$password = $env['DB_PASSWORD'];

try {

    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $password
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch (PDOException $e) {

    die("Erro na conexão com o banco de dados.");

}
?>
