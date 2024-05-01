<?php

require '../connection.php';

if(isset($_GET['id'])) {
    $id = $_GET['id'];

    $connection = new Connection();

    $statement = $connection->prepare("DELETE FROM users WHERE id = :id");
    $statement->bindParam(':id', $id);

    $result = $statement->execute();

    if ($result) {
        echo "Usuário excluído com sucesso!";
        header("Location: ../index.php");
        exit;
    } else {
        echo "Erro ao excluir usuário.";
    }
} else {
    echo "ID do usuário não fornecido.";
}

?>

