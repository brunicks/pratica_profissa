<?php include __DIR__ . '/../header.php'; ?>

<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-user-edit me-2"></i> Editar Usuário</h1>
        <div>
            <a href="index.php?url=users" class="btn btn-outline-secondary me-2">
                <i class="fas fa-users me-2"></i> Voltar para Lista
            </a>
            <a href="index.php?url=admin/dashboard" class="btn btn-outline-primary">
                <i class="fas fa-tachometer-alt me-2"></i> Painel
            </a>
        </div>
    </div>
    
    <?php if(isset($erro)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> <?php echo $erro; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if(isset($sucesso)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?php echo $sucesso; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_GET['senha_resetada'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> Senha do usuário resetada com sucesso!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i> Dados do Usuário</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="index.php?url=users/editar&id=<?php echo $usuario['id']; ?>">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        
                        <div class="row mb-3">
                            <label for="nome" class="col-sm-3 col-form-label">Nome Completo</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="nome" name="nome" 
                                       value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="email" class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="telefone" class="col-sm-3 col-form-label">Telefone</label>
                            <div class="col-sm-9">
                                <input type="tel" class="form-control" id="telefone" name="telefone" 
                                       value="<?php echo htmlspecialchars($usuario['telefone'] ?? ''); ?>">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Último Acesso</label>
                            <div class="col-sm-9">
                                <p class="form-control-plaintext">
                                    <?php echo isset($usuario['ultimo_acesso']) && $usuario['ultimo_acesso'] 
                                        ? date('d/m/Y H:i:s', strtotime($usuario['ultimo_acesso'])) 
                                        : 'Nunca acessou'; ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Data de Cadastro</label>
                            <div class="col-sm-9">
                                <p class="form-control-plaintext">
                                    <?php echo isset($usuario['data_cadastro']) && $usuario['data_cadastro'] 
                                        ? date('d/m/Y H:i:s', strtotime($usuario['data_cadastro'])) 
                                        : date('d/m/Y H:i:s'); ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-9 offset-sm-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           id="admin" name="admin" value="1"
                                           <?php echo $usuario['admin'] ? 'checked' : ''; ?>
                                           <?php echo $usuario['id'] == $_SESSION['user']['id'] ? 'disabled' : ''; ?>>
                                    <label class="form-check-label" for="admin">Administrador</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-9 offset-sm-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           id="ativo" name="ativo" value="1"
                                           <?php echo isset($usuario['ativo']) && $usuario['ativo'] ? 'checked' : ''; ?>
                                           <?php echo $usuario['id'] == $_SESSION['user']['id'] ? 'disabled' : ''; ?>>
                                    <label class="form-check-label" for="ativo">Conta Ativa</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Card para resetar senha -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="fas fa-key me-2"></i> Resetar Senha</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Use esta opção para definir uma nova senha para o usuário.</p>
                    
                    <form method="post" action="index.php?url=users/resetarSenha" id="resetPasswordForm">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                        
                        <div class="mb-3">
                            <label for="nova_senha" class="form-label">Nova Senha</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="nova_senha" name="nova_senha" 
                                       autocomplete="new-password" required minlength="6">
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="form-text">A senha deve ter pelo menos 6 caracteres.</div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning" id="resetPasswordBtn">
                                <i class="fas fa-key me-2"></i>Resetar Senha
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Card com botões de ação -->
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-cogs me-2"></i> Ações</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="index.php?url=users" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Voltar para Lista
                        </a>
                        
                        <?php if($usuario['id'] != $_SESSION['user']['id']): ?>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal">
                            <i class="fas fa-trash me-2"></i>Excluir Usuário
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmação para excluir -->
<?php if($usuario['id'] != $_SESSION['user']['id']): ?>
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirmar Exclusão</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Você tem certeza que deseja excluir o usuário <strong><?php echo htmlspecialchars($usuario['nome']); ?></strong>?</p>
                <p class="text-danger"><strong>Esta ação não pode ser desfeita!</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="index.php?url=users/excluir" method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                    <button type="submit" class="btn btn-danger">Confirmar Exclusão</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const passwordInput = this.previousElementSibling;
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle icon
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    });
    
    // Reset password confirmation
    document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
        const password = document.getElementById('nova_senha').value;
        if (password.length < 6) {
            e.preventDefault();
            alert('A senha deve ter pelo menos 6 caracteres.');
        } else if (!confirm('Tem certeza que deseja resetar a senha deste usuário?')) {
            e.preventDefault();
        }
    });
});
</script>

<?php include __DIR__ . '/../footer.php'; ?>
