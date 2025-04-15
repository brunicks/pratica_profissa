<?php include __DIR__ . '/header.php'; ?>

<div class="car-details-page py-4">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/mvc/public/index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="/mvc/public/index.php?url=carros">Catálogo</a></li>
                <li class="breadcrumb-item active"><?php echo htmlspecialchars($carro['modelo'] ?? ''); ?></li>
            </ol>
        </nav>
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4 position-relative">
                    <div class="card-body p-0">
                        <!-- Adicionar indicador de status -->
                        <div class="status-badge position-absolute top-0 start-0 m-3">
                            <?php
                            $statusClass = 'bg-success';
                            $statusText = 'Disponível';
                            
                            if (isset($carro['status'])) {
                                if ($carro['status'] == 'reservado') {
                                    $statusClass = 'bg-warning';
                                    $statusText = 'Reservado';
                                } elseif ($carro['status'] == 'vendido') {
                                    $statusClass = 'bg-danger';
                                    $statusText = 'Vendido';
                                }
                            }
                            ?>
                            <span class="badge <?php echo $statusClass; ?> fs-6 py-2 px-3 rounded-pill shadow">
                                <i class="fas <?php echo $statusText == 'Disponível' ? 'fa-check-circle' : 'fa-tag'; ?> me-2"></i>
                                <?php echo $statusText; ?>
                            </span>
                        </div>

                        <!-- Melhorar exibição do preço -->
                        <div class="price-badge position-absolute top-0 end-0 m-3">
                            <span class="badge bg-primary fs-5 py-2 px-3 rounded-pill shadow">
                                <i class="fas fa-tag me-2"></i>
                                R$ <?php echo number_format(floatval($carro['preco'] ?? 0), 2, ',', '.'); ?>
                            </span>
                        </div>

                        <!-- Main Image -->
                        <div class="main-image-container">
                            <img id="mainCarImage" src="/mvc/public/<?php echo htmlspecialchars($carro['imagem'] ?? ''); ?>" 
                                class="w-100 rounded-top" style="max-height: 500px; object-fit: cover;"
                                alt="<?php echo htmlspecialchars($carro['modelo'] ?? ''); ?>">
                        </div>
                        
                        <!-- Thumbnails -->
                        <div class="p-3 bg-light">
                            <div class="car-thumbnails-container">
                                <div class="d-flex flex-nowrap overflow-auto py-2">
                                    <div class="thumbnail-item me-2 active" onclick="changeThumbnail(this, '<?php echo htmlspecialchars($carro['imagem'] ?? ''); ?>')">
                                        <img src="/mvc/public/<?php echo htmlspecialchars($carro['imagem'] ?? ''); ?>" 
                                            class="rounded" style="width: 80px; height: 60px; object-fit: cover;"
                                            alt="Principal">
                                    </div>
                                    
                                    <?php foreach ($carro['galeria'] ?? [] as $idx => $foto): ?>
                                        <div class="thumbnail-item me-2" onclick="changeThumbnail(this, '<?php echo htmlspecialchars($foto ?? ''); ?>')">
                                            <img src="/mvc/public/<?php echo htmlspecialchars($foto ?? ''); ?>" 
                                                class="rounded" style="width: 80px; height: 60px; object-fit: cover;"
                                                alt="Foto <?php echo $idx + 1; ?>">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Adicionar contador de visualizações se disponível -->
                        <?php if (isset($carro['views']) && $carro['views'] > 0): ?>
                        <div class="views-info position-absolute bottom-0 end-0 m-3 text-white">
                            <small>
                                <i class="fas fa-eye me-1"></i> <?php echo number_format($carro['views']); ?> visualizações
                            </small>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h2 class="h3 mb-0"><?php echo htmlspecialchars(($carro['marca'] ?? '') . ' ' . ($carro['modelo'] ?? '')); ?></h2>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5 class="text-muted mb-3">Informações Gerais</h5>
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-calendar me-2"></i>Ano</span>
                                        <span class="badge bg-primary rounded-pill"><?php echo htmlspecialchars($carro['ano'] ?? ''); ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-tachometer-alt me-2"></i>Quilometragem</span>
                                        <span class="badge bg-primary rounded-pill"><?php echo number_format(floatval($carro['km'] ?? 0), 0, ',', '.'); ?> km</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-palette me-2"></i>Cor</span>
                                        <span class="badge bg-primary rounded-pill"><?php echo htmlspecialchars($carro['cor'] ?? ''); ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-gas-pump me-2"></i>Combustível</span>
                                        <span class="badge bg-primary rounded-pill"><?php echo htmlspecialchars($carro['combustivel'] ?? ''); ?></span>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-muted mb-3">Especificações Técnicas</h5>
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-cogs me-2"></i>Câmbio</span>
                                        <span class="badge bg-primary rounded-pill"><?php echo htmlspecialchars($carro['cambio'] ?? ''); ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-bolt me-2"></i>Potência</span>
                                        <span class="badge bg-primary rounded-pill"><?php echo htmlspecialchars($carro['potencia'] ?? ''); ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-car me-2"></i>Marca</span>
                                        <span class="badge bg-primary rounded-pill"><?php echo htmlspecialchars($carro['marca'] ?? ''); ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-tag me-2"></i>Código</span>
                                        <span class="badge bg-primary rounded-pill">#<?php echo $carro['id'] ?? ''; ?></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <h5 class="text-muted mb-3">Descrição</h5>
                        <div class="car-description-text p-3 bg-light rounded mb-4">
                            <?php echo nl2br(htmlspecialchars($carro['descricao'] ?? '')); ?>
                        </div>

                        <?php if(isset($isAdmin) && $isAdmin): ?>
                        <div class="admin-actions mt-4 border-top pt-3">
                            <h5 class="text-danger mb-3">Área do Administrador</h5>
                            <div class="d-flex gap-2">
                                <a href="index.php?url=carro/editar&id=<?php echo $carro['id'] ?? ''; ?>" class="btn btn-warning">
                                    <i class="fas fa-edit me-1"></i> Editar
                                </a>
                                
                                <!-- Delete Button Trigger Modal -->
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCarModal">
                                    <i class="fas fa-trash-alt me-1"></i> Excluir
                                </button>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4 sticky-top" style="top: 20px; z-index: 100;">
                    <div class="card-body">
                        <h3 class="text-primary mb-3">
                            R$ <?php echo number_format(floatval($carro['preco'] ?? 0), 2, ',', '.'); ?>
                        </h3>
                        
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary py-3" data-bs-toggle="modal" data-bs-target="#interestModal">
                                <i class="fas fa-thumbs-up me-2"></i> Tenho Interesse
                            </button>
                            
                            <button class="btn btn-outline-primary py-3" data-bs-toggle="modal" data-bs-target="#testDriveModal">
                                <i class="fas fa-car me-2"></i> Agendar Test Drive
                            </button>
                            
                            <a href="https://wa.me/5511999999999?text=Olá, tenho interesse no veículo <?php echo urlencode(($carro['marca'] ?? '') . ' ' . ($carro['modelo'] ?? '')); ?> (Código #<?php echo $carro['id'] ?? ''; ?>)" 
                               class="btn btn-success py-3" target="_blank">
                                <i class="fab fa-whatsapp me-2"></i> Falar pelo WhatsApp
                            </a>
                        </div>
                        
                        <hr>
                        
                        <h5 class="mb-3">Compartilhar</h5>
                        <div class="d-flex gap-2">
                            <a href="#" class="btn btn-outline-primary">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="btn btn-outline-info">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="btn btn-outline-danger">
                                <i class="fab fa-pinterest"></i>
                            </a>
                            <a href="#" class="btn btn-outline-success">
                                <i class="far fa-envelope"></i>
                            </a>
                        </div>
                        
                        <hr>
                        
                        <div class="dealer-info">
                            <h5 class="mb-3">Informações da Concessionária</h5>
                            <p><i class="fas fa-map-marker-alt me-2"></i> Av. Principal, 1000 - Centro</p>
                            <p><i class="fas fa-phone me-2"></i> (11) 9999-9999</p>
                            <p><i class="fas fa-envelope me-2"></i> contato@elitemotors.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Interest Modal -->
<div class="modal fade" id="interestModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Tenho Interesse</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" name="carro_id" value="<?php echo $carro['id'] ?? ''; ?>">
                    <input type="hidden" name="carro_modelo" value="<?php echo htmlspecialchars($carro['modelo'] ?? ''); ?>">
                    
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="tel" class="form-control" id="telefone" name="telefone" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="mensagem" class="form-label">Mensagem</label>
                        <textarea class="form-control" id="mensagem" name="mensagem" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary">Enviar</button>
            </div>
        </div>
    </div>
</div>

<!-- Test Drive Modal -->
<div class="modal fade" id="testDriveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Agendar Test Drive</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" name="carro_id" value="<?php echo $carro['id'] ?? ''; ?>">
                    <input type="hidden" name="carro_modelo" value="<?php echo htmlspecialchars($carro['modelo'] ?? ''); ?>">
                    
                    <div class="mb-3">
                        <label for="nome_td" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" id="nome_td" name="nome" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email_td" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email_td" name="email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="telefone_td" class="form-label">Telefone</label>
                        <input type="tel" class="form-control" id="telefone_td" name="telefone" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="data" class="form-label">Data Preferencial</label>
                        <input type="date" class="form-control" id="data" name="data" required 
                               min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="horario" class="form-label">Horário Preferencial</label>
                        <select class="form-control" id="horario" name="horario" required>
                            <option value="">Selecione...</option>
                            <option value="09:00">09:00</option>
                            <option value="10:00">10:00</option>
                            <option value="11:00">11:00</option>
                            <option value="14:00">14:00</option>
                            <option value="15:00">15:00</option>
                            <option value="16:00">16:00</option>
                            <option value="17:00">17:00</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary">Agendar</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Car Modal -->
<?php if(isset($isAdmin) && $isAdmin): ?>
<div class="modal fade" id="deleteCarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Excluir Veículo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Você tem certeza que deseja excluir o veículo <strong><?php echo htmlspecialchars(($carro['marca'] ?? '') . ' ' . ($carro['modelo'] ?? '')); ?></strong>?</p>
                <p class="text-danger"><strong>Esta ação não pode ser desfeita!</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="index.php?url=carro/excluir" method="post">
                    <input type="hidden" name="id" value="<?php echo $carro['id'] ?? ''; ?>">
                    <button type="submit" class="btn btn-danger">Confirmar Exclusão</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
// Função para trocar imagem principal ao clicar em miniaturas
function changeThumbnail(thumbnailElement, imageUrl) {
    // Remover classe 'active' de todas as miniaturas
    document.querySelectorAll('.thumbnail-item').forEach(el => {
        el.classList.remove('active');
    });
    
    // Adicionar classe 'active' à miniatura clicada
    thumbnailElement.classList.add('active');
    
    // Atualizar a imagem principal
    const mainImage = document.getElementById('mainCarImage');
    
    // Aplicar efeito de transição
    mainImage.style.opacity = '0.5';
    
    // Atualizar a imagem e restaurar opacidade após carregar
    setTimeout(() => {
        mainImage.src = '/mvc/public/' + imageUrl;
        mainImage.onload = function() {
            mainImage.style.opacity = '1';
        };
    }, 300);
}

// Inicializar tooltips e popovers
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    popoverTriggerList.forEach(function (popoverTriggerEl) {
        new bootstrap.Popover(popoverTriggerEl);
    });
});
</script>

<?php include __DIR__ . '/footer.php'; ?>