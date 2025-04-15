<?php include __DIR__ . '/../header.php'; ?>

<!-- Generate CSRF token for AJAX requests -->
<?php $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>

<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-users me-2"></i> Gerenciamento de Usuários</h1>
        <div>
            <a href="index.php?url=admin/dashboard" class="btn btn-outline-secondary me-2">
                <i class="fas fa-tachometer-alt me-2"></i> Painel
            </a>
            <a href="index.php" class="btn btn-outline-primary">
                <i class="fas fa-home me-2"></i> Voltar para o Site
            </a>
        </div>
    </div>
    
    <?php if(isset($_GET['excluido'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> Usuário excluído com sucesso!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-user-cog me-2"></i> Lista de Usuários</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="tabela-usuarios">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Admin</th>
                            <th>Status</th>
                            <th>Data de Cadastro</th>
                            <th>Último Acesso</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td><?php echo $u['id']; ?></td>
                            <td><?php echo htmlspecialchars($u['nome']); ?></td>
                            <td><?php echo htmlspecialchars($u['email']); ?></td>
                            <td><?php echo htmlspecialchars($u['telefone'] ?? 'N/A'); ?></td>
                            <td class="text-center">
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input class="form-check-input toggle-admin" type="checkbox" role="switch" 
                                        data-id="<?php echo $u['id']; ?>" 
                                        <?php echo $u['admin'] ? 'checked' : ''; ?>
                                        <?php echo $u['id'] == $_SESSION['user']['id'] ? 'disabled' : ''; ?>>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input class="form-check-input toggle-status" type="checkbox" role="switch" 
                                        data-id="<?php echo $u['id']; ?>" 
                                        <?php echo $u['ativo'] ? 'checked' : ''; ?>
                                        <?php echo $u['id'] == $_SESSION['user']['id'] ? 'disabled' : ''; ?>>
                                </div>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($u['data_cadastro'])); ?></td>
                            <td><?php echo $u['ultimo_acesso'] ? date('d/m/Y H:i', strtotime($u['ultimo_acesso'])) : 'Nunca'; ?></td>
                            <td>
                                <a href="index.php?url=users/editar&id=<?php echo $u['id']; ?>" 
                                   class="btn btn-sm btn-primary me-1" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <?php if($u['id'] != $_SESSION['user']['id']): ?>
                                <button type="button" class="btn btn-sm btn-danger delete-user" 
                                        data-id="<?php echo $u['id']; ?>"
                                        data-nome="<?php echo htmlspecialchars($u['nome']); ?>"
                                        title="Excluir">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmação para excluir -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirmar Exclusão</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Você tem certeza que deseja excluir o usuário <strong id="userName"></strong>?</p>
                <p class="text-danger"><strong>Esta ação não pode ser desfeita!</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="index.php?url=users/excluir" method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="id" id="deleteUserId">
                    <button type="submit" class="btn btn-danger">Confirmar Exclusão</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // DataTables initialization
    $('#tabela-usuarios').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.1/i18n/pt-BR.json"
        },
        "order": [[0, "desc"]]
    });
    
    // Toggle Active Status
    document.querySelectorAll('.toggle-status').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const userId = this.dataset.id;
            const isActive = this.checked ? 1 : 0;
            const toggleElement = this;
            
            // Show processing indicator
            toggleElement.disabled = true;
            
            fetch('index.php?url=users/alterarStatus', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${userId}&ativo=${isActive}&csrf_token=<?php echo $_SESSION['csrf_token']; ?>`
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na resposta do servidor: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Show success notification
                    const row = toggleElement.closest('tr');
                    row.classList.add('table-success');
                    setTimeout(() => {
                        row.classList.remove('table-success');
                    }, 2000);
                } else {
                    // Show error and revert
                    alert(data.message || 'Erro ao alterar status do usuário');
                    toggleElement.checked = !toggleElement.checked;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erro ao processar solicitação: ' + error.message);
                toggleElement.checked = !toggleElement.checked;
            })
            .finally(() => {
                toggleElement.disabled = false;
            });
        });
    });
    
    // Apply the same improved error handling to admin toggles
    document.querySelectorAll('.toggle-admin').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const userId = this.dataset.id;
            const isAdmin = this.checked ? 1 : 0;
            const toggleElement = this;
            
            toggleElement.disabled = true;
            
            fetch('index.php?url=users/alterarAdmin', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${userId}&admin=${isAdmin}&csrf_token=<?php echo $_SESSION['csrf_token']; ?>`
            })
            .then(response => {
                if (!response.ok) throw new Error('Erro na resposta do servidor: ' + response.status);
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const row = toggleElement.closest('tr');
                    row.classList.add('table-info');
                    setTimeout(() => { row.classList.remove('table-info'); }, 2000);
                } else {
                    alert(data.message || 'Erro ao alterar status de administrador');
                    toggleElement.checked = !toggleElement.checked;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erro ao processar solicitação: ' + error.message);
                toggleElement.checked = !toggleElement.checked;
            })
            .finally(() => {
                toggleElement.disabled = false;
            });
        });
    });
    
    // Delete User Modal with improved confirmation
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
    document.querySelectorAll('.delete-user').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.dataset.id;
            const userName = this.dataset.nome;
            
            document.getElementById('deleteUserId').value = userId;
            document.getElementById('userName').textContent = userName;
            
            deleteModal.show();
        });
    });
    
    // Add form submission validation for delete
    document.querySelector('#deleteUserModal form').addEventListener('submit', function(e) {
        const userId = document.getElementById('deleteUserId').value;
        if (!userId || userId === '') {
            e.preventDefault();
            alert('ID de usuário inválido.');
            return false;
        }
        return true;
    });
});
</script>

<?php include __DIR__ . '/../footer.php'; ?>
