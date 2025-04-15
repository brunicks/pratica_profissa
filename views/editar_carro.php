<?php include __DIR__ . '/header.php'; ?>

<div class="container my-4">
    <h2>Editar Carro: <?php echo htmlspecialchars($carro['modelo']); ?></h2>

    <?php if(isset($erro)): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i> <?php echo $erro; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if(isset($sucesso)): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i> <?php echo $sucesso; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Imagem Principal</div>
                <div class="card-body text-center">
                    <?php if ($carro['imagem']): ?>
                        <img src="/mvc/public/<?php echo htmlspecialchars($carro['imagem']); ?>" 
                            alt="<?php echo htmlspecialchars($carro['modelo']); ?>"
                            class="img-fluid mb-3" style="max-height: 200px;">
                    <?php endif; ?>
                    
                    <form action="index.php?url=carro/atualizar" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input type="hidden" name="id" value="<?php echo $carro['id']; ?>">
                        
                        <div class="mb-3">
                            <label for="imagem" class="form-label">Nova Foto Principal:</label>
                            <input type="file" id="imagem" name="imagem" accept="image/jpeg,image/png,image/gif" class="form-control">
                            <small class="text-muted">Formatos permitidos: JPG, PNG, GIF. Máximo: 5MB.</small>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload me-2"></i>Atualizar Foto
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dados do Veículo</div>
                <div class="card-body">
                    <form action="index.php?url=carro/salvarEdicao" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input type="hidden" name="id" value="<?php echo $carro['id']; ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="modelo" class="form-label">Modelo: <span class="text-danger">*</span></label>
                                <input type="text" id="modelo" name="modelo" class="form-control" 
                                    value="<?php echo htmlspecialchars($carro['modelo']); ?>" required>
                                <div class="invalid-feedback">O modelo é obrigatório.</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="placa" class="form-label">Placa:</label>
                                <input type="text" id="placa" name="placa" class="form-control"
                                       value="<?php echo htmlspecialchars($carro['placa'] ?? ''); ?>"
                                       pattern="[A-Za-z]{3}[0-9]{4}|[A-Za-z]{3}[0-9]{1}[A-Za-z]{1}[0-9]{2}"
                                       placeholder="ABC1234 ou ABC1D23">
                                <div class="invalid-feedback">Formato de placa inválido.</div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="versao" class="form-label">Versão:</label>
                                <input type="text" id="versao" name="versao" class="form-control"
                                       value="<?php echo htmlspecialchars($carro['versao'] ?? ''); ?>"
                                       placeholder="Ex: LX 1.6 AT">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="portas" class="form-label">Portas:</label>
                                <select id="portas" name="portas" class="form-select">
                                    <option value="">Selecione</option>
                                    <?php for($i = 2; $i <= 5; $i++): ?>
                                        <option value="<?php echo $i; ?>" 
                                            <?php echo (isset($carro['portas']) && $carro['portas'] == $i) ? 'selected' : ''; ?>>
                                            <?php echo $i; ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status:</label>
                                <select id="status" name="status" class="form-select">
                                    <option value="disponivel" <?php echo ($carro['status'] == 'disponivel') ? 'selected' : ''; ?>>Disponível</option>
                                    <option value="reservado" <?php echo ($carro['status'] == 'reservado') ? 'selected' : ''; ?>>Reservado</option>
                                    <option value="vendido" <?php echo ($carro['status'] == 'vendido') ? 'selected' : ''; ?>>Vendido</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="marca_id" class="form-label">Marca: <span class="text-danger">*</span></label>
                                <select id="marca_id" name="marca_id" class="form-select" required>
                                    <?php foreach ($marcas as $marca): ?>
                                        <option value="<?php echo $marca['id']; ?>" 
                                            <?php echo ($marca['id'] == $carro['marca_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($marca['nome']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="ano" class="form-label">Ano:</label>
                                <input type="number" id="ano" name="ano" class="form-control" 
                                    value="<?php echo htmlspecialchars($carro['ano']); ?>" required>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="preco" class="form-label">Preço:</label>
                                <input type="number" id="preco" name="preco" step="0.01" class="form-control" 
                                    value="<?php echo htmlspecialchars($carro['preco']); ?>" required>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="km" class="form-label">Quilometragem:</label>
                                <input type="number" id="km" name="km" class="form-control" 
                                    value="<?php echo htmlspecialchars($carro['km']); ?>" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="cambio" class="form-label">Câmbio:</label>
                                <select id="cambio" name="cambio" class="form-select" required>
                                    <option value="Manual" <?php echo ($carro['cambio'] == 'Manual') ? 'selected' : ''; ?>>Manual</option>
                                    <option value="Automático" <?php echo ($carro['cambio'] == 'Automático') ? 'selected' : ''; ?>>Automático</option>
                                    <option value="CVT" <?php echo ($carro['cambio'] == 'CVT') ? 'selected' : ''; ?>>CVT</option>
                                </select>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="combustivel" class="form-label">Combustível:</label>
                                <select id="combustivel" name="combustivel" class="form-select" required>
                                    <option value="Gasolina" <?php echo ($carro['combustivel'] == 'Gasolina') ? 'selected' : ''; ?>>Gasolina</option>
                                    <option value="Etanol" <?php echo ($carro['combustivel'] == 'Etanol') ? 'selected' : ''; ?>>Etanol</option>
                                    <option value="Flex" <?php echo ($carro['combustivel'] == 'Flex') ? 'selected' : ''; ?>>Flex</option>
                                    <option value="Diesel" <?php echo ($carro['combustivel'] == 'Diesel') ? 'selected' : ''; ?>>Diesel</option>
                                    <option value="Elétrico" <?php echo ($carro['combustivel'] == 'Elétrico') ? 'selected' : ''; ?>>Elétrico</option>
                                </select>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="cor" class="form-label">Cor:</label>
                                <input type="text" id="cor" name="cor" class="form-control" 
                                    value="<?php echo htmlspecialchars($carro['cor']); ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="potencia" class="form-label">Potência:</label>
                            <input type="text" id="potencia" name="potencia" class="form-control" 
                                value="<?php echo htmlspecialchars($carro['potencia']); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição:</label>
                            <textarea id="descricao" name="descricao" class="form-control" rows="5" required><?php echo htmlspecialchars($carro['descricao']); ?></textarea>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="destaque" name="destaque" value="1" 
                                <?php echo ($carro['destaque'] == 1) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="destaque">
                                Exibir em destaque na página inicial
                            </label>
                        </div>
                        
                        <div class="mb-3">
                            <label for="novas_imagens" class="form-label">Adicionar mais fotos:</label>
                            <input type="file" name="novas_imagens[]" id="novas_imagens" class="form-control" accept="image/jpeg,image/png,image/gif" multiple>
                            <small class="text-muted">Selecione múltiplas imagens para adicionar à galeria.</small>
                            <div id="new-images-preview" class="mt-2 d-flex flex-wrap gap-2"></div>
                        </div>
                        
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Salvar Alterações
                        </button>
                        <a href="index.php?url=carro/detalhes&id=<?php echo $carro['id']; ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-images me-2"></i>Gerenciar Galeria</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <?php if(empty($carro['galeria'])): ?>
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>Este veículo não possui imagens adicionais na galeria.
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach($carro['galeria'] as $index => $imagem): ?>
                        <div class="col-md-3 mb-4">
                            <div class="card">
                                <img src="/mvc/public/<?php echo htmlspecialchars($imagem); ?>" 
                                    class="card-img-top" alt="Imagem <?php echo ($index + 1); ?>">
                                <div class="card-body">
                                    <form action="index.php?url=carro/excluirGaleriaImagem" method="post" 
                                          onsubmit="return confirm('Tem certeza que deseja excluir esta imagem?');">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                        <input type="hidden" name="id" value="<?php echo ($index + 1); ?>">
                                        <input type="hidden" name="carro_id" value="<?php echo $carro['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger w-100">
                                            <i class="fas fa-trash-alt me-2"></i>Remover
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar validação
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
    
    // Preview de novas imagens
    document.getElementById('novas_imagens').addEventListener('change', function() {
        const previewContainer = document.getElementById('new-images-preview');
        previewContainer.innerHTML = '';
        
        if (this.files.length > 0) {
            const errors = [];
            
            Array.from(this.files).forEach(file => {
                // Verificar tamanho
                if (file.size > 5 * 1024 * 1024) {
                    errors.push(`A imagem "${file.name}" excede o tamanho máximo de 5MB`);
                    return;
                }
                
                // Verificar tipo
                if (!['image/jpeg', 'image/png', 'image/gif'].includes(file.type)) {
                    errors.push(`A imagem "${file.name}" não é um formato permitido (JPG, PNG ou GIF)`);
                    return;
                }
                
                // Criar preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    const card = document.createElement('div');
                    card.className = 'card me-2 mb-2';
                    card.style.width = '150px';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'card-img-top';
                    img.style.height = '100px';
                    img.style.objectFit = 'cover';
                    
                    const cardBody = document.createElement('div');
                    cardBody.className = 'card-body p-1 text-center';
                    
                    const caption = document.createElement('small');
                    caption.className = 'text-muted';
                    caption.textContent = 'Nova Imagem';
                    
                    cardBody.appendChild(caption);
                    card.appendChild(img);
                    card.appendChild(cardBody);
                    previewContainer.appendChild(card);
                };
                reader.readAsDataURL(file);
            });
            
            if (errors.length > 0) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'alert alert-danger mt-2';
                errorDiv.innerHTML = '<strong>Erros encontrados:</strong><ul>' + 
                    errors.map(err => `<li>${err}</li>`).join('') + '</ul>';
                previewContainer.appendChild(errorDiv);
            }
        }
    });
    
    // Validar placa em tempo real
    const placaInput = document.getElementById('placa');
    if (placaInput) {
        placaInput.addEventListener('input', function(e) {
            const placaValue = e.target.value.toUpperCase();
            e.target.value = placaValue;
            
            const placaPadrao = /^[A-Z]{3}[0-9]{4}$|^[A-Z]{3}[0-9]{1}[A-Z]{1}[0-9]{2}$/;
            const isValid = placaPadrao.test(placaValue) || placaValue === '';
            
            if (isValid) {
                e.target.classList.remove('is-invalid');
                e.target.classList.add('is-valid');
            } else {
                e.target.classList.remove('is-valid');
                e.target.classList.add('is-invalid');
            }
        });
    }
    
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<?php include __DIR__ . '/footer.php'; ?>