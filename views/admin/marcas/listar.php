<?php include __DIR__ . '/../../header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-tags me-2"></i>Gerenciar Marcas</h1>
        <a href="index.php?url=marca/cadastrar" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nova Marca
        </a>
    </div>
    
    <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == 'excluido'): ?>
        <div class="alert alert-success">
            Marca excluída com sucesso!
        </div>
    <?php endif; ?>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <?php if (empty($marcas)): ?>
                <div class="alert alert-info">
                    Nenhuma marca cadastrada.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th width="200">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($marcas as $marca): ?>
                                <tr>
                                    <td><?php echo $marca['id']; ?></td>
                                    <td><?php echo htmlspecialchars($marca['nome']); ?></td>
                                    <td>
                                        <a href="index.php?url=marca/editar&id=<?php echo $marca['id']; ?>" 
                                           class="btn btn-sm btn-warning me-1" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $marca['id']; ?>"
                                                title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        
                                        <!-- Modal de confirmação de exclusão -->
                                        <div class="modal fade" id="deleteModal<?php echo $marca['id']; ?>" tabindex="-1" 
                                             aria-labelledby="deleteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel">Confirmar Exclusão</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Deseja realmente excluir a marca <strong><?php echo htmlspecialchars($marca['nome']); ?></strong>?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <form action="index.php?url=marca/excluir" method="post">
                                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                            <input type="hidden" name="id" value="<?php echo $marca['id']; ?>">
                                                            <button type="submit" class="btn btn-danger">Excluir</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../footer.php'; ?>
