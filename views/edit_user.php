<?php
require '../connection.php';


if(isset($_GET['id'])) {
    $id = $_GET['id'];

    $connection = new Connection();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {

            $nome = $_POST['nome'] ?? '';
            $email = $_POST['email'] ?? '';
            $coresSelecionadas = isset($_POST['cores']) ? $_POST['cores'] : [];

            $statementUpdateUsuario = $connection->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");
            $statementUpdateUsuario->bindParam(':name', $nome);
            $statementUpdateUsuario->bindParam(':email', $email);
            $statementUpdateUsuario->bindParam(':id', $id);
            $statementUpdateUsuario->execute();


            $statementDeleteCores = $connection->prepare("DELETE FROM user_colors WHERE user_id = :user_id");
            $statementDeleteCores->bindParam(':user_id', $id);
            $statementDeleteCores->execute();


            foreach ($coresSelecionadas as $corId) {
                $statementInsertCor = $connection->prepare("INSERT INTO user_colors (user_id, color_id) VALUES (:user_id, :color_id)");
                $statementInsertCor->bindParam(':user_id', $id);
                $statementInsertCor->bindParam(':color_id', $corId);
                $statementInsertCor->execute();
            }

            echo "Dados do usuário atualizados com sucesso!";
            header("Location: ../index.php");
        } catch (Exception $e) {
            echo "Erro ao atualizar dados do usuário: " . $e->getMessage();
        }
    }


    $statementUsuario = $connection->prepare("SELECT * FROM users WHERE id = :id");
    $statementUsuario->bindParam(':id', $id);
    $statementUsuario->execute();
    $usuario = $statementUsuario->fetch(PDO::FETCH_ASSOC);

    $statementCores = $connection->query("SELECT * FROM colors");
    $cores = $statementCores->fetchAll(PDO::FETCH_ASSOC);

    $statementCoresUsuario = $connection->prepare("SELECT color_id FROM user_colors WHERE user_id = :user_id");
    $statementCoresUsuario->bindParam(':user_id', $id);
    $statementCoresUsuario->execute();
    $coresUsuario = $statementCoresUsuario->fetchAll(PDO::FETCH_COLUMN);

    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Editar Usuário</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
    <div class="container">
        <h2>Editar Usuário</h2>
        <form action="" method="post">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome:</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $usuario['name']; ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $usuario['email']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="cores" class="form-label">Cores:</label><br>
                <?php foreach ($cores as $cor): ?>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="cor_<?php echo $cor['id']; ?>" name="cores[]" value="<?php echo $cor['id']; ?>" <?php echo in_array($cor['id'], $coresUsuario) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="cor_<?php echo $cor['id']; ?>"><?php echo $cor['name']; ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>

    <?php
} else {
    echo "ID do usuário não fornecido.";
}
?>



