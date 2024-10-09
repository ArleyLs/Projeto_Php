<?php require_once "validador_acesso.php" ?>

<html>
<head>
    <meta charset="utf-8" />
    <title>App Help Desk</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        .card-abrir-chamado {
            padding: 30px 0 0 0;
            width: 100%;
            margin: 0 auto;
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
        <div class="card-abrir-chamado">
            <div class="card">
                <div class="card-header">
                    Abertura de chamado
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <form method="post" action="registra_chamado.php">
                                <div class="form-group">
                                    <label>Título</label>
                                    <input name="titulo" type="text" class="form-control" placeholder="Título" required>
                                </div>
                                
                                <div class="form-group">
                                    <label>Categoria</label>
                                    <select name="categoria" class="form-control">
                                        <option>Criação Usuário</option>
                                        <option>Impressora</option>
                                        <option>Hardware</option>
                                        <option>Software</option>
                                        <option>Rede</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label>Descrição</label>
                                    <textarea name="descricao" class="form-control" rows="3" required></textarea>
                                </div>

                                <div class="d-flex justify-content-center mt-5">
                                    <a class="btn btn-warning btn-custom-width mr-2" href="home.php">Voltar</a>
                                    <button class="btn btn-info btn-custom-width" type="submit">Abrir</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>
</html>