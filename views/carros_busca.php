<?php include __DIR__ . '/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-search me-2"></i>Resultados da Busca</h1>
        <a href="/mvc/public/index.php" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Voltar
        </a>
    </div>

    <!-- Search filters display -->
    <div class="bg-light p-3 rounded mb-4">
        <div class="row">
            <div class="col-md-8">
                <h5 class="mb-3">Filtros aplicados:</h5>
                <ul class="list-inline mb-0">
                    <?php if(!empty($_GET['marca'])): ?>
                        <li class="list-inline-item badge bg-primary mb-2">
                            Marca: <?php 
                            foreach($marcas as $marca) {
                                if($marca['id'] == $_GET['marca']) echo htmlspecialchars($marca['nome']);
                            }
                            ?>
                        </li>
                    <?php endif; ?>
                    
                    <?php if(!empty($_GET['preco_max'])): ?>
                        <li class="list-inline-item badge bg-primary mb-2">
                            Preço máximo: R$ <?php echo number_format($_GET['preco_max'], 2, ',', '.'); ?>
                        </li>
                    <?php endif; ?>
                    
                    <?php if(!empty($_GET['ano_min'])): ?>
                        <li class="list-inline-item badge bg-primary mb-2">
                            Ano a partir de: <?php echo htmlspecialchars($_GET['ano_min']); ?>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="col-md-4 text-end">
                <a href="/mvc/public/index.php?url=carros" class="btn btn-sm btn-outline-secondary">
                    Limpar filtros
                </a>
            </div>
        </div>
    </div>

    <!-- Results -->
    <?php if(empty($carros)): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>Nenhum carro encontrado com os filtros selecionados.
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach($carros as $carro): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card car-card h-100">
                        <div class="card-img-container position-relative">
                            <img src="/mvc/public/<?php echo htmlspecialchars($carro['imagem'] ?? ''); ?>" 
                                class="card-img-top" alt="<?php echo htmlspecialchars($carro['modelo'] ?? ''); ?>">
                            <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                                <?php echo number_format(floatval($carro['km'] ?? 0), 0, ',', '.'); ?> km
                            </span>
                        </div>
                        <div class="card-body">
                            <span class="d-block text-primary fw-bold"><?php echo htmlspecialchars($carro['marca'] ?? ''); ?></span>
                            <h5 class="card-title"><?php echo htmlspecialchars($carro['modelo'] ?? ''); ?></h5>
                            <div class="car-features mb-3 d-flex gap-2">
                                <span class="badge bg-secondary"><i class="fas fa-calendar"></i> <?php echo htmlspecialchars($carro['ano'] ?? ''); ?></span>
                                <span class="badge bg-secondary"><i class="fas fa-gas-pump"></i> <?php echo htmlspecialchars($carro['combustivel'] ?? ''); ?></span>
                                <span class="badge bg-secondary"><i class="fas fa-cog"></i> <?php echo htmlspecialchars($carro['cambio'] ?? ''); ?></span>
                            </div>
                            <div class="car-price fs-4 fw-bold text-primary mb-3">
                                R$ <?php echo number_format(floatval($carro['preco'] ?? 0), 2, ',', '.'); ?>
                            </div>
                            <a href="index.php?url=carro/detalhes&id=<?php echo $carro['id'] ?? ''; ?>" 
                               class="btn btn-outline-primary w-100">
                               <i class="fas fa-info-circle me-2"></i>Ver Detalhes
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/footer.php'; ?>
