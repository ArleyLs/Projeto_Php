<?php
require_once "validador_acesso.php";

// Recuperar o índice do chamado a ser editado
$index = $_GET['index'] ?? null;
$chamados = file('./app_help_desk/arquivo.hd', FILE_IGNORE_NEW_LINES);

// Verifica se o índice é válido
if ($index !== null && isset($chamados[$index])) {
    $chamado_dados = explode('#', $chamados[$index]);
} else {
    // Redirecionar ou exibir erro
    header('Location: consultar_chamados.php');
    exit;
}
?>

<html>
<head>
    <meta charset="utf-8" />
    <title>Editar Chamado</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Editar Chamado</h2>
        <form action="atualizar_chamado.php" method="POST">
            <input type="hidden" name="index" value="<?=$index?>">
            <div class="form-group">
                <label>Título</label>
                <input type="text" name="titulo" class="form-control" value="<?=htmlspecialchars($chamado_dados[1])?>" required>
            </div>
            <div class="form-group">
                <label>Data</label>
                <input type="text" name="data" class="form-control" value="<?=htmlspecialchars($chamado_dados[2])?>" required>
            </div>
            <div class="form-group">
                <label>Descrição</label>
                <textarea name="descricao" class="form-control" required><?=htmlspecialchars($chamado_dados[3])?></textarea>
            </div>
            <button type="submit" class="btn btn-success">Atualizar</button>
            <a href="consultar_chamados.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>