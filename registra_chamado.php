<?php
session_start();

// Funcao que gera um ID unico
function gerarNovoId($arquivo) {
    $ids = [];
    // Aqui ele obtem os IDs existentes
    if (file_exists($arquivo)) {
        $linhas = file($arquivo);
        foreach ($linhas as $linha) {
            $chamado_dados = explode('#', trim($linha));
            if (isset($chamado_dados[0])) {
                $ids[] = (int)$chamado_dados[0];
            }
        }
    }
    return empty($ids) ? 1 : max($ids) + 1; // Aqui ele comeca em 1 caso nao exista ID
}

// Montar o texto
$titulo = str_replace('#', '-', $_POST['titulo']);
$categoria = str_replace('#', '-', $_POST['categoria']);
$descricao = str_replace('#', '-', $_POST['descricao']);
$status = "Pendente";


$idChamado = gerarNovoId('./app_help_desk/arquivo.hd');

// Montar o texto do chamado
$texto = $idChamado . '#' . $titulo . '#' . $categoria . '#' . $descricao . '#' . $status . PHP_EOL;

$arquivo = fopen('./app_help_desk/arquivo.hd', 'a');
fwrite($arquivo, $texto);
fclose($arquivo);

header('Location: home.php');
exit;
?>