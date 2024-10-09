<?php
session_start();

// Receber dados do formulário
$index = $_POST['index'];
$titulo = str_replace('#', '-', $_POST['titulo']);
$categoria = str_replace('#', '-', $_POST['categoria']);
$descricao = str_replace('#', '-', $_POST['descricao']);

// Ler todos os chamados
$chamados = file('./app_help_desk/arquivo.hd', FILE_IGNORE_NEW_LINES);

// Atualizar o chamado
$chamados[$index] = "{$_SESSION['id']}#{$titulo}#{$categoria}#{$descricao}";

// Salvar os chamados de volta no arquivo
file_put_contents('./app_help_desk/arquivo.hd', implode(PHP_EOL, $chamados) . PHP_EOL);

// Redirecionar de volta para a página de consulta
header('Location: consultar_chamados.php');
exit;
?>