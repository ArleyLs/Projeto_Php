<?php require_once "validador_acesso.php"; ?>

<?php
define('FILE_PATH', './app_help_desk/arquivo.hd');

// Array dos chamados
$chamados = [];

// Abrir o arquivo.hd
if (file_exists(FILE_PATH)) {
    $arquivo = fopen(FILE_PATH, 'r');
    while (!feof($arquivo)) {
        $registro = fgets($arquivo);
        if ($registro) {
            $chamados[] = trim($registro);
        }
    }
    fclose($arquivo);
}

// Funcao para criar um ID unico
function gerarNovoId($chamados) {
    if (empty($chamados)) {
        return 1; // Inicia com ID 1 caso nao exista nenhum
    }
    $ids = array_map(function($chamado) {
        return (int)explode('#', $chamado)[0];
    }, $chamados);
    return max($ids) + 1; // Aqui ele incrementa o maior ID existente
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idChamado = $_POST['chamadoId'] ?? null;

    if (isset($_POST['deletar']) && $idChamado) {
        // Deletar o chamado
        $chamados = array_filter($chamados, function($chamado) use ($idChamado) {
            return explode('#', $chamado)[0] != $idChamado;
        });

        // Atualizar o arquivo
        file_put_contents(FILE_PATH, implode(PHP_EOL, $chamados) . PHP_EOL);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } elseif ($idChamado) {
        // Logica para editar o chamado
        $titulo = $_POST['titulo'] ?? null;
        $descricao = $_POST['descricao'] ?? null;

        if ($titulo !== null && $descricao !== null) {
            $chamados = array_map(function($chamado) use ($idChamado, $titulo, $descricao) {
                $chamado_dados = explode('#', $chamado);
                if ($chamado_dados[0] == $idChamado) {
                    $chamado_dados[1] = $titulo;
                    $chamado_dados[3] = $descricao;
                }
                return implode('#', $chamado_dados);
            }, $chamados);

            // Atualizar o arquivo
            file_put_contents(FILE_PATH, implode(PHP_EOL, $chamados) . PHP_EOL);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    } else {
        // Logica para criar um novo chamado
        $titulo = $_POST['titulo'] ?? null;
        $descricao = $_POST['descricao'] ?? null;
        $tipo = $_POST['tipo'] ?? 'Outro';
        $status = $_POST['status'] ?? 'Pendente';

        if ($titulo !== null && $descricao !== null) {
            $novoId = gerarNovoId($chamados);
            $chamados[] = "$novoId#$titulo#$tipo#$descricao#$status";

            file_put_contents(FILE_PATH, implode(PHP_EOL, $chamados) . PHP_EOL);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    }
}
?>

<html>
<head>
    <meta charset="utf-8" />
    <title>App Help Desk</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .card-consultar-chamado {
            padding: 30px 0 0 0;
            width: 100%;
            margin: 0 auto;
        }
        .status {
            float: right;
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
        }
        .status-pendente {
            background-color: orange;
            color: black;
            border-radius: 10px;
        }
        .status-aceito {
            background-color: green;
        }
        .modal-footer .btn {
            display: inline-block;
            vertical-align: middle;
        }
        .btn-custom-width {
            width: 150px;
        }
    </style>
</head>

<body>
<nav class="navbar navbar-dark bg-dark">
    <a class="navbar-brand" href="#">
        <img src="logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
        App Help Desk
    </a>
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="logoff.php">SAIR</a>
        </li>
    </ul>
</nav>

<div class="container">    
    <div class="row">
        <div class="card-consultar-chamado">
            <div class="card">
                <div class="card-header">
                    Consulta de chamado
                </div>
                <div class="card-body">
                    <?php foreach ($chamados as $chamado) { ?>
                        <?php
                        $chamado_dados = explode('#', $chamado);
                        if (count($chamado_dados) < 5) {
                            continue;
                        }
                        $statusClass = $chamado_dados[4] == 'Pendente' ? 'status-pendente' : 'status-aceito';
                        ?>
                        <div class="card mb-3 bg-light">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($chamado_dados[1]) ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($chamado_dados[2]) ?>
                                    <span class="status <?= $statusClass ?>"><?= htmlspecialchars($chamado_dados[4]) ?></span>
                                </h6>
                                <p class="card-text"><?= htmlspecialchars($chamado_dados[3]) ?></p>
                                <button class="btn btn-info" data-toggle="modal" data-target="#editModal" 
                                    data-id="<?= htmlspecialchars($chamado_dados[0]) ?>" 
                                    data-titulo="<?= htmlspecialchars($chamado_dados[1]) ?>" 
                                    data-descricao="<?= htmlspecialchars($chamado_dados[3]) ?>">Editar</button>
                                <button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal" 
                                        data-id="<?= htmlspecialchars($chamado_dados[0]) ?>">Deletar</button>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="row mt-5">
                        <div class="col-6">
                            <a class="btn btn-warning btn-custom-width" href="home.php">Voltar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para edição -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Chamado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST">
                    <input type="hidden" id="chamadoId" name="chamadoId">
                    <div class="form-group">
                        <label for="titulo">Título</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required>
                    </div>
                    <div class="form-group">
                        <label for="descricao">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" id="saveChanges">Salvar alterações</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para deletar -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Deletar Chamado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Você tem certeza que deseja deletar este chamado?
                <form id="deleteForm" method="POST">
                    <input type="hidden" name="chamadoId" id="deleteChamadoIdInput">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger" name="deletar">Deletar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<!-- Logica que controla as requisicoes -->
<script>
    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var titulo = button.data('titulo');
        var descricao = button.data('descricao');

        var modal = $(this);
        modal.find('#chamadoId').val(id);
        modal.find('#titulo').val(titulo);
        modal.find('#descricao').val(descricao);
    });

    $('#saveChanges').on('click', function () {
        $('#editForm').submit();
    });

    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var modal = $(this);
        modal.find('#deleteChamadoIdInput').val(id);
    });
</script>

</body>
</html>