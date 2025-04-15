<?php include __DIR__ . '/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-search me-2"></i>Resultados da Busca</h1>
        <a href="/mvc/public/index.php?url=carros" class="btn btn-outline-primary">
            <i class="fas fa-filter me-2"></i>Nova Busca
        </a>
    </div>

    <!-- Search filters display -->
    <div class="bg-light p-3 rounded mb-4">
        <div class="row">
            <div class="col-md-9">
                <h5 class="mb-3">Filtros aplicados:</h5>
                <ul class="list-inline mb-0">
                    <?php 
                    $temFiltros = false;
                    
                    if(!empty($_GET['marca'])): 
                        $temFiltros = true;
                    ?>
                        <li class="list-inline-item">
                            <span class="badge bg-primary mb-2">
                                <span class="me-1">Marca:</span>
                                <?php 
                                foreach($marcas as $marca) {
                                    if($marca['id'] == $_GET['marca']) echo htmlspecialchars($marca['nome']);
                                }
                                ?>
                                <a href="<?php echo removeQueryParam('marca'); ?>" class="text-white ms-1" title="Remover filtro">
                                    <i class="fas fa-times-circle"></i>
                                </a>
                            </span>
                        </li>
                    <?php endif; ?>
                    
                    <?php if(!empty($_GET['preco_min'])): 
                        $temFiltros = true;
                    ?>
                        <li class="list-inline-item">
                            <span class="badge bg-primary mb-2">
                                <span class="me-1">Preço a partir de:</span>
                                R$ <?php echo number_format($_GET['preco_min'], 2, ',', '.'); ?>
                                <a href="<?php echo removeQueryParam('preco_min'); ?>" class="text-white ms-1" title="Remover filtro">
                                    <i class="fas fa-times-circle"></i>
                                </a>
                            </span>
                        </li>
                    <?php endif; ?>
                    
                    <?php if(!empty($_GET['preco_max'])): 
                        $temFiltros = true;
                    ?>
                        <li class="list-inline-item">
                            <span class="badge bg-primary mb-2">
                                <span class="me-1">Preço até:</span>
                                R$ <?php echo number_format($_GET['preco_max'], 2, ',', '.'); ?>
                                <a href="<?php echo removeQueryParam('preco_max'); ?>" class="text-white ms-1" title="Remover filtro">
                                    <i class="fas fa-times-circle"></i>
                                </a>
                            </span>
                        </li>
                    <?php endif; ?>
                    
                    <?php if(!empty($_GET['ano_min'])): 
                        $temFiltros = true;
                    ?>
                        <li class="list-inline-item">
                            <span class="badge bg-primary mb-2">
                                <span class="me-1">Ano a partir de:</span>
                                <?php echo htmlspecialchars($_GET['ano_min']); ?>
                                <a href="<?php echo removeQueryParam('ano_min'); ?>" class="text-white ms-1" title="Remover filtro">
                                    <i class="fas fa-times-circle"></i>
                                </a>
                            </span>
                        </li>
                    <?php endif; ?>
                    
                    <?php if(!empty($_GET['ano_max'])): 
                        $temFiltros = true;
                    ?>
                        <li class="list-inline-item">
                            <span class="badge bg-primary mb-2">
                                <span class="me-1">Ano até:</span>
                                <?php echo htmlspecialchars($_GET['ano_max']); ?>
                                <a href="<?php echo removeQueryParam('ano_max'); ?>" class="text-white ms-1" title="Remover filtro">
                                    <i class="fas fa-times-circle"></i>
                                </a>
                            </span>
                        </li>
                    <?php endif; ?>
                    
                    <?php if(!empty($_GET['km_max'])): 
                        $temFiltros = true;
                    ?>
                        <li class="list-inline-item">
                            <span class="badge bg-primary mb-2">
                                <span class="me-1">Quilometragem até:</span>
                                <?php echo number_format($_GET['km_max'], 0, ',', '.'); ?> km
                                <a href="<?php echo removeQueryParam('km_max'); ?>" class="text-white ms-1" title="Remover filtro">
                                    <i class="fas fa-times-circle"></i>
                                </a>
                            </span>
                        </li>
                    <?php endif; ?>
                    
                    <?php if(!empty($_GET['cambio'])): 
                        $temFiltros = true;
                    ?>
                        <li class="list-inline-item">
                            <span class="badge bg-primary mb-2">
                                <span class="me-1">Câmbio:</span>
                                <?php echo htmlspecialchars($_GET['cambio']); ?>
                                <a href="<?php echo removeQueryParam('cambio'); ?>" class="text-white ms-1" title="Remover filtro">
                                    <i class="fas fa-times-circle"></i>
                                </a>
                            </span>
                        </li>
                    <?php endif; ?>
                    
                    <?php if(!empty($_GET['combustivel'])): 
                        $temFiltros = true;
                    ?>
                        <li class="list-inline-item">
                            <span class="badge bg-primary mb-2">
                                <span class="me-1">Combustível:</span>
                                <?php echo htmlspecialchars($_GET['combustivel']); ?>
                                <a href="<?php echo removeQueryParam('combustivel'); ?>" class="text-white ms-1" title="Remover filtro">
                                    <i class="fas fa-times-circle"></i>
                                </a>
                            </span>
                        </li>
                    <?php endif; ?>
                    
                    <?php if(!empty($_GET['cor'])): 
                        $temFiltros = true;
                    ?>
                        <li class="list-inline-item">
                            <span class="badge bg-primary mb-2">
                                <span class="me-1">Cor:</span>
                                <?php echo htmlspecialchars($_GET['cor']); ?>
                                <a href="<?php echo removeQueryParam('cor'); ?>" class="text-white ms-1" title="Remover filtro">
                                    <i class="fas fa-times-circle"></i>
                                </a>
                            </span>
                        </li>
                    <?php endif; ?>
                    
                    <?php if(!empty($_GET['portas'])): 
                        $temFiltros = true;
                    ?>
                        <li class="list-inline-item">
                            <span class="badge bg-primary mb-2">
                                <span class="me-1">Portas:</span>
                                <?php echo htmlspecialchars($_GET['portas']); ?>
                                <a href="<?php echo removeQueryParam('portas'); ?>" class="text-white ms-1" title="Remover filtro">
                                    <i class="fas fa-times-circle"></i>
                                </a>
                            </span>
                        </li>
                    <?php endif; ?>
                    
                    <?php if(!$temFiltros): ?>
                        <li class="list-inline-item">
                            <span class="text-muted">Nenhum filtro aplicado</span>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="col-md-3 text-end">
                <?php if($temFiltros): ?>
                <a href="/mvc/public/index.php?url=carro/busca" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-times me-1"></i> Limpar todos os filtros
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Ordenação e contagem de resultados -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <strong><?php echo count($carros); ?> veículo(s)</strong> encontrado(s)
        </div>
        <div>
            <form class="d-flex" id="sortForm">
                <?php
                // Preserva os parâmetros atuais exceto ordenação
                foreach($_GET as $key => $value) {
                    if($key != 'ordenar_por' && $key != 'direcao' && $value) {
                        echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
                    }
                }
                ?>
                <select class="form-select form-select-sm me-2" name="ordenar_por" id="ordenarPor" onchange="this.form.submit()">
                    <option value="c.id" <?php echo (isset($_GET['ordenar_por']) && $_GET['ordenar_por'] == 'c.id') ? 'selected' : ''; ?>>Mais recentes</option>
                    <option value="c.preco" <?php echo (isset($_GET['ordenar_por']) && $_GET['ordenar_por'] == 'c.preco') ? 'selected' : ''; ?>>Preço</option>
                    <option value="c.ano" <?php echo (isset($_GET['ordenar_por']) && $_GET['ordenar_por'] == 'c.ano') ? 'selected' : ''; ?>>Ano</option>
                    <option value="c.km" <?php echo (isset($_GET['ordenar_por']) && $_GET['ordenar_por'] == 'c.km') ? 'selected' : ''; ?>>Quilometragem</option>
                </select>
                <select class="form-select form-select-sm" name="direcao" id="direcao" onchange="this.form.submit()">
                    <option value="ASC" <?php echo (isset($_GET['direcao']) && $_GET['direcao'] == 'ASC') ? 'selected' : ''; ?>>Crescente</option>
                    <option value="DESC" <?php echo (isset($_GET['direcao']) && $_GET['direcao'] == 'DESC' || !isset($_GET['direcao'])) ? 'selected' : ''; ?>>Decrescente</option>
                </select>
            </form>
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
                            <?php if(!empty($carro['km'])): ?>
                            <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                                <?php echo number_format(floatval($carro['km'] ?? 0), 0, ',', '.'); ?> km
                            </span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <span class="d-block text-primary fw-bold"><?php echo htmlspecialchars($carro['marca'] ?? ''); ?></span>
                            <h5 class="card-title"><?php echo htmlspecialchars($carro['modelo'] ?? ''); ?></h5>
                            <div class="car-features mb-3 d-flex gap-2 flex-wrap">
                                <span class="badge bg-secondary"><i class="fas fa-calendar me-1"></i> <?php echo htmlspecialchars($carro['ano'] ?? ''); ?></span>
                                <?php if(!empty($carro['cambio'])): ?>
                                <span class="badge bg-secondary"><i class="fas fa-cog me-1"></i> <?php echo htmlspecialchars($carro['cambio'] ?? ''); ?></span>
                                <?php endif; ?>
                                <?php if(!empty($carro['combustivel'])): ?>
                                <span class="badge bg-secondary"><i class="fas fa-gas-pump me-1"></i> <?php echo htmlspecialchars($carro['combustivel'] ?? ''); ?></span>
                                <?php endif; ?>
                                <?php if(!empty($carro['portas'])): ?>
                                <span class="badge bg-secondary"><i class="fas fa-door-open me-1"></i> <?php echo htmlspecialchars($carro['portas'] ?? ''); ?> portas</span>
                                <?php endif; ?>
                            </div>
                            <div class="car-price fs-5 fw-bold text-primary mb-3">
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

<?php
// Função auxiliar para remover um parâmetro específico da query string
function removeQueryParam($param) {
    $params = $_GET;
    unset($params[$param]);
    $queryString = http_build_query($params);
    return '/mvc/public/index.php?' . $queryString;
}
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});
</script>

<?php include __DIR__ . '/footer.php'; ?>
