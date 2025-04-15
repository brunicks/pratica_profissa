<?php include __DIR__ . '/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Confirmar Exclusão</h4>
                </div>
                <div class="card-body">
                    <h5 class="card-title mb-4">Você tem certeza que deseja excluir este veículo?</h5>
                    
                    <div class="alert alert-warning">
                        <div class="d-flex align-items-center mb-3">
                            <?php if(!empty($carro['imagem'])): ?>
                                <img src="/mvc/public/<?php echo htmlspecialchars($carro['imagem']); ?>" 
                                     alt="<?php echo htmlspecialchars($carro['modelo']); ?>"
                                     class="rounded me-3" style="max-width: 80px; max-height: 60px; object-fit: cover;">
                            <?php endif; ?>
                            <div>
                                <h5 class="mb-0"><?php echo htmlspecialchars($carro['modelo']); ?> (<?php echo htmlspecialchars($carro['ano']); ?>)</h5>
                                <p class="mb-0 text-muted"><?php echo htmlspecialchars($carro['marca']); ?></p>
                            </div>
                        </div>
                        <p class="mb-0 text-danger"><strong>Atenção:</strong> Esta ação não pode ser desfeita!</p>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-4">
                        <a href="index.php?url=carros" class="btn btn-secondary me-2">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        <form action="index.php?url=carro/excluir" method="post">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                            <input type="hidden" name="id" value="<?php echo $carro['id']; ?>">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash-alt me-2"></i>Sim, Excluir
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <a href="index.php?url=carros" class="text-decoration-none">
                    <i class="fas fa-arrow-left me-2"></i>Voltar para o catálogo
                </a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
