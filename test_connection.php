<?php
require_once 'connection.php';

// Instancia a classe Connection
$conexao = new Connection();

// Obtém a conexão
$connection = $conexao->getConnection();

// Testa a conexão executando uma consulta simples
try {
    // Query de teste
    $query = "SELECT * FROM users LIMIT 1";
    // Executa a consulta
    $result = $connection->query($query);
    // Se chegou até aqui sem erros, a conexão está funcionando corretamente
    echo "Conexão bem-sucedida!";
} catch (PDOException $e) {
    // Se houver algum erro, exibe a mensagem de erro
    echo "Erro ao executar consulta: " . $e->getMessage();
}
?>
