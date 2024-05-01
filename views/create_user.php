<?php
require '../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['nome']) && !empty($_POST['email'])) {
        $nome = $_POST['nome'];
        $email = $_POST['email'];

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $connection = new Connection();

            $statement = $connection->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
            $statement->bindParam(':name', $nome);
            $statement->bindParam(':email', $email);

            $result = $statement->execute();

            if ($result) {
                $userId = $connection->getConnection()->lastInsertId();

                if (isset($_POST['cores']) && is_array($_POST['cores'])) {
                    foreach ($_POST['cores'] as $corId) {
                        $statementCorUsuario = $connection->prepare("INSERT INTO user_colors (user_id, color_id) VALUES (:user_id, :color_id)");
                        $statementCorUsuario->bindParam(':user_id', $userId);
                        $statementCorUsuario->bindParam(':color_id', $corId);
                        $statementCorUsuario->execute();
                    }
                }

                header("Location: ../index.php");
                exit();
            } else {
                echo "Erro ao criar usuário. Por favor, tente novamente.";
            }
        } else {
            echo "O email inserido não é válido.";
        }
    } else {
        echo "Por favor, preencha todos os campos do formulário.";
    }
}
?>
<form action="" method="post">
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" required><br><br>
    
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>
    
    <label for="cores">Cores:</label><br>
    <?php
    
    $connection = new Connection();
    $statementCores = $connection->query("SELECT * FROM colors");
    $cores = $statementCores->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cores as $cor) {
        echo "<input type='checkbox' id='cor_" . $cor['id'] . "' name='cores[]' value='" . $cor['id'] . "'>";
        echo "<label for='cor_" . $cor['id'] . "'>" . $cor['name'] . "</label><br>";
    }
    ?>
    
    <input type="submit" value="Criar Usuário">
</form>



