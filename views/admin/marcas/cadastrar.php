<?php include __DIR__ . '/../../header.php'; ?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <h1><i class="fas fa-plus-circle me-2"></i>Nova Marca</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?url=admin/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="index.php?url=marca/listar">Marcas</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Nova Marca</li>
                </ol>
            </nav>
        </div>
    </div>

    <?php if ($erro): ?>
        <div class="alert alert-danger">
            <?php echo $erro; ?>
        </div>
    <?php endif; ?>
    
    <?php if ($sucesso): ?>
        <div class="alert alert-success">
            <?php echo $sucesso; ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post" class="needs-validation" novalidate>
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome da Marca <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nome" name="nome" required 
                           maxlength="100" placeholder="Ex: Toyota">
                    <div class="invalid-feedback">
                        Por favor, informe o nome da marca.
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="index.php?url=marca/listar" class="btn btn-secondary me-md-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Cadastrar Marca</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Validação do form do Bootstrap
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
})()
</script>

<?php include __DIR__ . '/../../footer.php'; ?>
