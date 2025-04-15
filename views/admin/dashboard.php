<?php include __DIR__ . '/../header.php'; ?>

<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-tachometer-alt me-2"></i> Painel Administrativo</h1>
        <a href="/mvc/public/index.php" class="btn btn-outline-primary">
            <i class="fas fa-home me-2"></i> Voltar para o Site
        </a>
    </div>
    
    <div class="row g-4">
        <div class="col-md-6 col-lg-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-car fa-fw me-2"></i> Gerenciar Carros
                    </h5>
                    <p class="card-text">Adicione, edite ou remova veículos do catálogo.</p>
                    <a href="/mvc/public/index.php?url=carros" class="btn btn-light">
                        <i class="fas fa-arrow-right"></i> Acessar
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-plus-circle fa-fw me-2"></i> Novo Carro
                    </h5>
                    <p class="card-text">Adicione um novo veículo ao seu catálogo.</p>
                    <a href="/mvc/public/index.php?url=carro/novo" class="btn btn-light">
                        <i class="fas fa-arrow-right"></i> Adicionar
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Novo card para gerenciar usuários -->
        <div class="col-md-6 col-lg-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-users fa-fw me-2"></i> Usuários
                    </h5>
                    <p class="card-text">Gerencie contas de usuários e administradores.</p>
                    <a href="/mvc/public/index.php?url=users" class="btn btn-light">
                        <i class="fas fa-arrow-right"></i> Gerenciar
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-tags fa-fw me-2"></i> Gerenciar Marcas
                    </h5>
                    <p class="card-text">Cadastre, edite ou remova marcas de veículos.</p>
                    <a href="/mvc/public/index.php?url=marca/listar" class="btn btn-light">
                        <i class="fas fa-arrow-right"></i> Acessar
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-user-cog fa-fw me-2"></i> Meu Perfil
                    </h5>
                    <p class="card-text">Gerencie suas informações pessoais.</p>
                    <a href="/mvc/public/index.php?url=perfil" class="btn btn-light">
                        <i class="fas fa-arrow-right"></i> Editar
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-sign-out-alt fa-fw me-2"></i> Sair
                    </h5>
                    <p class="card-text">Encerrar sua sessão de administrador.</p>
                    <a href="/mvc/public/index.php?url=auth/logout" class="btn btn-light">
                        <i class="fas fa-arrow-right"></i> Desconectar
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Admin Debug Link -->
    <div class="mt-5">
        <a href="/mvc/public/index.php?admin_debug=1" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-bug me-1"></i> Ferramenta de Diagnóstico
        </a>
    </div>
</div>

<?php include __DIR__ . '/../footer.php'; ?>
