
<?php
require_once __DIR__ . '/config.php';

try {
    // Define a porta padrão do PostgreSQL.
    $port = defined('DB_PORT') ? DB_PORT : '5432';

    // Monta a conexão com o banco de dados.
    $dsn = "pgsql:host=" . DB_HOST
         . ";port=" . $port
         . ";dbname=" . DB_NAME;

    // Cria a conexão usando o nome esperado pelo projeto.
    $conexao = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

} catch (PDOException $e) {
    error_log("Erro ao conectar ao PostgreSQL: " . $e->getMessage());
    die("Erro ao conectar ao banco de dados.");
}
?>

