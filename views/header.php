<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elite Motors - Seminovos Premium</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/mvc/public/style.css">
    <style>
        /* Fix for sticky header overlap - removing the JS-based padding adjustment */
        body {
            padding-top: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .main-content {
            flex: 1;
        }
        footer {
            margin-top: auto;
        }
        
        /* For special pages with hero sections - adjust their margin */
        .hero-section {
            margin-top: -24px; /* Remove the white space between navbar and hero */
        }
        
        /* Fix text contrast issues */
        .navbar-dark .navbar-nav .nav-link {
            color: rgba(255,255,255,0.85);
            transition: color 0.2s;
        }
        .navbar-dark .navbar-nav .nav-link:hover,
        .navbar-dark .navbar-nav .nav-link:focus {
            color: #ffffff;
        }
        .dropdown-menu {
            background-color: #f8f9fa;
        }
        .dropdown-item {
            color: #343a40;
        }
        .dropdown-item:hover, 
        .dropdown-item:focus {
            background-color: #e9ecef;
            color: #212529;
        }
        /* Fix active state visibility */
        .navbar-dark .navbar-nav .active > .nav-link,
        .navbar-dark .navbar-nav .nav-link.active {
            color: #ffffff;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/mvc/public/index.php">
                <i class="fas fa-car-side"></i> Elite Motors
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/mvc/public/index.php"><i class="fas fa-home"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/mvc/public/index.php?url=carros"><i class="fas fa-car"></i> Catálogo</a>
                    </li>
                    <?php if(isset($_SESSION['user']) && $_SESSION['user']['admin'] == 1): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-cogs"></i> Administração
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/mvc/public/index.php?url=carro/novo"><i class="fas fa-plus-circle me-2"></i>Adicionar Carro</a></li>
                            <li><a class="dropdown-item" href="/mvc/public/index.php?url=carros"><i class="fas fa-car me-2"></i>Gerenciar Carros</a></li>
                            <li><a class="dropdown-item" href="/mvc/public/index.php?url=users"><i class="fas fa-users me-2"></i>Gerenciar Usuários</a></li>
                            <li><a class="dropdown-item" href="/mvc/public/index.php?url=marca/listar"><i class="fas fa-tags me-2"></i>Gerenciar Marcas</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/mvc/public/index.php?url=admin/dashboard"><i class="fas fa-tachometer-alt me-2"></i>Painel Admin</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <?php if(isset($_SESSION['user'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user"></i> Olá, <?php echo $_SESSION['user']['nome']; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="/mvc/public/index.php?url=perfil">Meu Perfil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/mvc/public/index.php?url=auth/logout">Sair</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/mvc/public/index.php?url=auth/login">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/mvc/public/index.php?url=auth/registro">
                                <i class="fas fa-user-plus"></i> Cadastrar
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</body>
</html>