<?php include __DIR__ . '/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-car me-2"></i>Catálogo de Veículos</h1>
        
        <?php if(isset($isAdmin) && $isAdmin): ?>
        <a href="index.php?url=carro/novo" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Cadastrar Novo Carro
        </a>
        <?php endif; ?>
    </div>

    <?php if(isset($_GET['excluido']) && $_GET['excluido'] == '1'): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i> Veículo excluído com sucesso!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Novo formulário de busca avançada -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-search me-2"></i>Busca Avançada</h5>
                <button class="btn btn-sm btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#filtrosAvancados">
                    <i class="fas fa-sliders-h"></i> Filtros
                </button>
            </div>
        </div>
        <div class="collapse" id="filtrosAvancados">
            <div class="card-body">
                <form action="index.php" method="GET" class="row g-3">
                    <input type="hidden" name="url" value="carro/busca">
                    
                    <div class="col-md-4">
                        <label for="marca" class="form-label">Marca:</label>
                        <select id="marca" name="marca" class="form-select">
                            <option value="">Todas as marcas</option>
                            <?php foreach ($marcas as $marca): ?>
                                <option value="<?php echo $marca['id']; ?>"><?php echo htmlspecialchars($marca['nome']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="cambio" class="form-label">Câmbio:</label>
                        <select id="cambio" name="cambio" class="form-select">
                            <option value="">Todos</option>
                            <option value="Manual">Manual</option>
                            <option value="Automático">Automático</option>
                            <option value="CVT">CVT</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="combustivel" class="form-label">Combustível:</label>
                        <select id="combustivel" name="combustivel" class="form-select">
                            <option value="">Todos</option>
                            <option value="Gasolina">Gasolina</option>
                            <option value="Etanol">Etanol</option>
                            <option value="Flex">Flex</option>
                            <option value="Diesel">Diesel</option>
                            <option value="Elétrico">Elétrico</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="portas" class="form-label">Portas:</label>
                        <select id="portas" name="portas" class="form-select">
                            <option value="">Todas</option>
                            <option value="2">2 portas</option>
                            <option value="3">3 portas</option>
                            <option value="4">4 portas</option>
                            <option value="5">5 portas</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="cor" class="form-label">Cor:</label>
                        <input type="text" id="cor" name="cor" class="form-control" placeholder="Ex: Preto, Branco...">
                    </div>
                    
                    <div class="col-md-3">
                        <label for="km_max" class="form-label">KM máxima:</label>
                        <input type="number" id="km_max" name="km_max" class="form-control" placeholder="Ex: 50000">
                    </div>
                    
                    <div class="col-md-3">
                        <label for="ordenar_por" class="form-label">Ordenar por:</label>
                        <select id="ordenar_por" name="ordenar_por" class="form-select">
                            <option value="c.id">Mais recentes</option>
                            <option value="c.preco">Preço</option>
                            <option value="c.ano">Ano</option>
                            <option value="c.km">Quilometragem</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="direcao" class="form-label">Ordem:</label>
                        <select id="direcao" name="direcao" class="form-select">
                            <option value="ASC">Crescente</option>
                            <option value="DESC">Decrescente</option>
                        </select>
                    </div>
                    
                    <fieldset class="col-md-9">
                        <legend class="col-form-label col-12 fs-6">Faixa de preço:</legend>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" id="preco_min" name="preco_min" class="form-control" placeholder="Mínimo">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" id="preco_max" name="preco_max" class="form-control" placeholder="Máximo">
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    
                    <fieldset class="col-md-9">
                        <legend class="col-form-label col-12 fs-6">Faixa de ano:</legend>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="number" id="ano_min" name="ano_min" class="form-control" placeholder="De">
                            </div>
                            <div class="col-md-6">
                                <input type="number" id="ano_max" name="ano_max" class="form-control" placeholder="Até">
                            </div>
                        </div>
                    </fieldset>
                    
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Buscar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php if(empty($carros)): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> Nenhum carro cadastrado.
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($carros as $carro): ?>
                <div class="col">
                    <div class="card h-100 car-card shadow-sm">
                        <div class="position-relative">
                            <?php if ($carro['imagem']): ?>
                                <img src="/mvc/public/<?php echo htmlspecialchars($carro['imagem']); ?>" 
                                    class="card-img-top" alt="<?php echo htmlspecialchars($carro['modelo']); ?>"
                                    style="height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <div class="no-image bg-light text-center py-5" style="height: 200px;">
                                    <i class="fas fa-car fa-3x text-muted"></i>
                                    <p class="mt-2 text-muted">Sem imagem</p>
                                </div>
                            <?php endif; ?>
                            
                            <?php 
                            $statusClass = 'bg-success';
                            $statusText = 'Disponível';
                            
                            if($carro['status'] == 'reservado') {
                                $statusClass = 'bg-warning';
                                $statusText = 'Reservado';
                            } elseif($carro['status'] == 'vendido') {
                                $statusClass = 'bg-danger';
                                $statusText = 'Vendido';
                            }
                            ?>
                            <span class="position-absolute top-0 end-0 badge <?php echo $statusClass; ?> m-2 px-3 py-2">
                                <?php echo ucfirst(htmlspecialchars($statusText)); ?>
                            </span>
                        </div>
                        
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php echo htmlspecialchars($carro['modelo']); ?>
                                <span class="text-muted">(<?php echo htmlspecialchars($carro['ano']); ?>)</span>
                            </h5>
                            <p class="text-primary mb-2"><?php echo htmlspecialchars($carro['marca']); ?></p>
                            
                            <?php if(!empty($carro['preco'])): ?>
                            <h6 class="fw-bold text-success mb-3">
                                R$ <?php echo number_format($carro['preco'], 2, ',', '.'); ?>
                            </h6>
                            <?php endif; ?>
                            
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <?php if(!empty($carro['km'])): ?>
                                <span class="badge bg-secondary">
                                    <i class="fas fa-tachometer-alt me-1"></i> 
                                    <?php echo number_format($carro['km'], 0, ',', '.'); ?> km
                                </span>
                                <?php endif; ?>
                                
                                <?php if(!empty($carro['cambio'])): ?>
                                <span class="badge bg-secondary">
                                    <i class="fas fa-cogs me-1"></i> 
                                    <?php echo htmlspecialchars($carro['cambio']); ?>
                                </span>
                                <?php endif; ?>
                                
                                <?php if(!empty($carro['combustivel'])): ?>
                                <span class="badge bg-secondary">
                                    <i class="fas fa-gas-pump me-1"></i> 
                                    <?php echo htmlspecialchars($carro['combustivel']); ?>
                                </span>
                                <?php endif; ?>
                                
                                <?php if(!empty($carro['final_placa'])): ?>
                                <span class="badge bg-secondary">
                                    <i class="fas fa-id-card me-1"></i> 
                                    Final <?php echo htmlspecialchars($carro['final_placa']); ?>
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-white border-top-0 pt-0">
                            <div class="d-flex gap-2">
                                <a href="index.php?url=carro/detalhes&id=<?php echo $carro['id']; ?>" 
                                   class="btn btn-outline-primary flex-grow-1">
                                    <i class="fas fa-info-circle me-2"></i>Detalhes
                                </a>
                                
                                <?php if(isset($isAdmin) && $isAdmin): ?>
                                <a href="index.php?url=carro/editar&id=<?php echo $carro['id']; ?>" 
                                   class="btn btn-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <form action="index.php?url=carro/toggleDestaque" method="post" class="d-inline">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                                    <input type="hidden" name="id" value="<?php echo $carro['id']; ?>">
                                    <input type="hidden" name="destaque" value="<?php echo $carro['destaque'] ? '0' : '1'; ?>">
                                    <button type="submit" class="btn <?php echo $carro['destaque'] ? 'btn-warning' : 'btn-secondary'; ?>" 
                                            title="<?php echo $carro['destaque'] ? 'Remover destaque' : 'Destacar'; ?>">
                                        <i class="fas fa-star"></i>
                                    </button>
                                </form>
                                
                                <!-- Substituir o botão do modal por um link direto para a página de confirmação -->
                                <a href="index.php?url=carro/confirmar&id=<?php echo $carro['id']; ?>" 
                                   class="btn btn-danger" title="Excluir">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
// Inicializar o colapso com estado salvo
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltip do Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Configurar comportamento do filtro avançado
    const filtrosEstado = localStorage.getItem('filtrosAvancadosAbertos');
    if (filtrosEstado === 'true') {
        document.getElementById('filtrosAvancados').classList.add('show');
    }
    
    const collapse = document.getElementById('filtrosAvancados');
    if (collapse) {
        collapse.addEventListener('shown.bs.collapse', () => {
            localStorage.setItem('filtrosAvancadosAbertos', 'true');
        });
        collapse.addEventListener('hidden.bs.collapse', () => {
            localStorage.setItem('filtrosAvancadosAbertos', 'false');
        });
    }
});
</script>

<?php include __DIR__ . '/footer.php'; ?>