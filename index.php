<?php
require 'connection.php';

$connection = new Connection();

$users = $connection->query("SELECT * FROM users");

echo "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Lista de Usuários</title>
    <!-- Adicionando o Bootstrap CSS -->
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css' rel='stylesheet'>
    <style>
        /* Adicione estilos personalizados aqui se necessário */
    </style>
</head>
<body>
<div class='container'>
    <h1>Lista de Usuários</h1>
    <table class='table table-striped'>
        <thead>
            <tr>
                <th scope='col'>ID</th>
                <th scope='col'>Nome</th>
                <th scope='col'>Email</th>
                <th scope='col'>Cores Associadas</th>
                <th scope='col'>Ação</th>
            </tr>
        </thead>
        <tbody>";

foreach ($users as $user) {
    echo "<tr>";
    echo "<td>{$user->id}</td>";
    echo "<td>{$user->name}</td>";
    echo "<td>{$user->email}</td>";

    $statementCoresUsuario = $connection->prepare("SELECT c.name FROM user_colors uc JOIN colors c ON uc.color_id = c.id WHERE uc.user_id = :user_id");
    $statementCoresUsuario->bindParam(':user_id', $user->id);
    $statementCoresUsuario->execute();
    $coresUsuario = $statementCoresUsuario->fetchAll(PDO::FETCH_COLUMN);

    echo "<td>" . implode(", ", $coresUsuario) . "</td>";

    echo "<td>";
    echo "<a href='views/edit_user.php?id={$user->id}' class='btn btn-primary'>Edit</a>";
    echo "<a href='views/delete_user.php?id={$user->id}' class='btn btn-danger'>Delete</a>";
    echo "</td>";
    echo "</tr>";
}

echo "
        </tbody>
    </table>
    
    <form action='views/create_user.php' method='post'>
        <div class='mb-3'>
            <label for='nome' class='form-label'>Nome:</label>
            <input type='text' class='form-control' id='nome' name='nome' required>
        </div>
        
        <div class='mb-3'>
            <label for='email' class='form-label'>Email:</label>
            <input type='email' class='form-control' id='email' name='email' required>
        </div>
        
        <div class='mb-3'>
            <label for='cores' class='form-label'>Selecione as Cores:</label><br>";
    
    $statementCores = $connection->query("SELECT * FROM colors");
    $coresDisponiveis = $statementCores->fetchAll(PDO::FETCH_ASSOC);
    foreach ($coresDisponiveis as $cor) {
        echo "<input type='checkbox' id='cor_" . $cor['id'] . "' name='cores[]' value='" . $cor['id'] . "' class='form-check-input'>";
        echo "<label for='cor_" . $cor['id'] . "' class='form-check-label'>" . $cor['name'] . "</label><br>";
    }

echo "
        </div>
        <button type='submit' class='btn btn-success'>Criar Usuário</button>
    </form>
</div>

<!-- Adicionando o Bootstrap JS (opcional, se você precisar de funcionalidades do Bootstrap que dependem de JavaScript) -->
<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js'></script>
</body>
</html>";
?>

