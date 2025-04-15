<?php require_once 'views/admin/header.php'; ?>

<div class="container mt-4">
    <h1><?= isset($marca) ? 'Editar Marca' : 'Nova Marca' ?></h1>
    
    <div class="card">
        <div class="card-body">
            <form action="index.php?controller=marca&action=salvar" method="post">
                <?php if (isset($marca)): ?>
                    <input type="hidden" name="id" value="<?= $marca['id'] ?>">
                <?php endif; ?>
                
                <div class="form-group mb-3">
                    <label for="nome">Nome da Marca</label>
                    <input type="text" class="form-control" id="nome" name="nome" 
                           value="<?= isset($marca) ? htmlspecialchars($marca['nome']) : '' ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="index.php?controller=marca&action=index" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'views/admin/footer.php'; ?>
