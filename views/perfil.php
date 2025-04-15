<?php include __DIR__ . '/header.php'; ?>

<div class="row">
    <div class="col-lg-3">
        <div class="card mb-4">
            <div class="card-body text-center">
                <div class="avatar mb-3">
                    <i class="fas fa-user-circle fa-5x text-primary"></i>
                </div>
                <h5 class="mb-1"><?php echo htmlspecialchars($usuario['nome']); ?></h5>
                <p class="text-muted mb-3">
                    <?php echo $usuario['admin'] ? 'Administrador' : 'Cliente'; ?>
                </p>
                <p>
                    <i class="fas fa-envelope me-2"></i> <?php echo htmlspecialchars($usuario['email']); ?>
                </p>
                <?php if($usuario['telefone']): ?>
                <p>
                    <i class="fas fa-phone me-2"></i> <?php echo htmlspecialchars($usuario['telefone']); ?>
                </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-9">
        <?php if(isset($mensagem)): ?>
            <div class="alert alert-success">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($erro)): ?>
            <div class="alert alert-danger">
                <?php echo $erro; ?>
            </div>
        <?php endif; ?>
        
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Editar Perfil</h5>
            </div>
            <div class="card-body">
                <form method="post" action="index.php?url=perfil">
                    <div class="row mb-3">
                        <label for="nome" class="col-md-3 col-form-label">Nome Completo</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="nome" name="nome" 
                                value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="email" class="col-md-3 col-form-label">Email</label>
                        <div class="col-md-9">
                            <input type="email" class="form-control" id="email" name="email" 
                                value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="telefone" class="col-md-3 col-form-label">Telefone</label>
                        <div class="col-md-9">
                            <input type="tel" class="form-control" id="telefone" name="telefone" 
                                value="<?php echo htmlspecialchars($usuario['telefone'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="text-end">
                        <button type="submit" name="atualizar_perfil" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-lock me-2"></i>Alterar Senha</h5>
            </div>
            <div class="card-body">
                <form method="post" action="index.php?url=perfil">
                    <div class="row mb-3">
                        <label for="senha_atual" class="col-md-3 col-form-label">Senha Atual</label>
                        <div class="col-md-9">
                            <input type="password" class="form-control" id="senha_atual" name="senha_atual" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="nova_senha" class="col-md-3 col-form-label">Nova Senha</label>
                        <div class="col-md-9">
                            <input type="password" class="form-control" id="nova_senha" name="nova_senha" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="confirma_senha" class="col-md-3 col-form-label">Confirmar Nova Senha</label>
                        <div class="col-md-9">
                            <input type="password" class="form-control" id="confirma_senha" name="confirma_senha" required>
                        </div>
                    </div>
                    
                    <div class="text-end">
                        <button type="submit" name="alterar_senha" class="btn btn-primary">
                            <i class="fas fa-key me-2"></i>Alterar Senha
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
