<?php require_once 'views/admin/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gerenciar Marcas</h1>
        <a href="index.php?controller=marca&action=novo" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nova Marca
        </a>
    </div>
    
    <?php if (isset($_GET['mensagem'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_GET['mensagem']) ?>
        </div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($marcas as $marca): ?>
                        <tr>
                            <td><?= $marca['id'] ?></td>
                            <td><?= htmlspecialchars($marca['nome']) ?></td>
                            <td>
                                <a href="index.php?controller=marca&action=editar&id=<?= $marca['id'] ?>" 
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="index.php?controller=marca&action=excluir&id=<?= $marca['id'] ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Tem certeza que deseja excluir esta marca?')">
                                    <i class="fas fa-trash"></i> Excluir
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    
                    <?php if (empty($marcas)): ?>
                        <tr>
                            <td colspan="3" class="text-center">Nenhuma marca cadastrada</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'views/admin/footer.php'; ?>
