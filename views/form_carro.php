<?php include __DIR__ . '/header.php'; ?>

<div class="container py-4">
    <div class="form-page">
        <h2>Cadastrar Novo Carro</h2>
        
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
        
        <form action="index.php?url=carro/cadastrar" method="post" enctype="multipart/form-data" class="car-form needs-validation" novalidate>
            <!-- CSRF Protection -->
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="modelo">Modelo: <span class="text-danger">*</span></label>
                    <input type="text" id="modelo" name="modelo" class="form-control" required
                           minlength="2" maxlength="100" 
                           data-bs-toggle="tooltip" title="Digite o modelo do veículo">
                    <div class="invalid-feedback">O modelo do veículo é obrigatório e deve ter pelo menos 2 caracteres.</div>
                </div>

                <div class="form-group">
                    <label for="marca_id">Marca: <span class="text-danger">*</span></label>
                    <select id="marca_id" name="marca_id" class="form-select" required>
                        <option value="">Selecione uma marca</option>
                        <?php foreach ($marcas as $marca): ?>
                            <option value="<?php echo $marca['id']; ?>">
                                <?php echo htmlspecialchars($marca['nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">Por favor, selecione a marca do veículo.</div>
                </div>
                
                <div class="form-group">
                    <label for="ano">Ano: <span class="text-danger">*</span></label>
                    <input type="number" id="ano" name="ano" class="form-control" required 
                        min="1900" max="<?php echo date('Y') + 1; ?>"
                        value="<?php echo date('Y'); ?>">
                    <div class="invalid-feedback">Informe um ano válido entre 1900 e <?php echo date('Y') + 1; ?>.</div>
                </div>

                <div class="form-group">
                    <label for="preco">Preço: <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input type="number" id="preco" name="preco" step="0.01" class="form-control" required min="0"
                               data-bs-toggle="tooltip" title="Digite o valor do veículo">
                    </div>
                    <div class="invalid-feedback">Informe um preço válido (valor positivo).</div>
                </div>
                
                <div class="form-group">
                    <label for="placa">Placa:</label>
                    <input type="text" id="placa" name="placa" class="form-control"
                           pattern="[A-Za-z]{3}[0-9]{4}|[A-Za-z]{3}[0-9]{1}[A-Za-z]{1}[0-9]{2}"
                           placeholder="ABC1234 ou ABC1D23"
                           data-bs-toggle="tooltip" title="Formato: ABC1234 ou ABC1D23">
                    <div class="invalid-feedback">Formato de placa inválido. Use o formato ABC1234 ou ABC1D23.</div>
                </div>
                
                <div class="form-group">
                    <label for="versao">Versão:</label>
                    <input type="text" id="versao" name="versao" class="form-control"
                           placeholder="Ex: LX 1.6 AT">
                </div>
                
                <div class="form-group">
                    <label for="portas">Quantidade de Portas:</label>
                    <select id="portas" name="portas" class="form-select">
                        <option value="">Selecione</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="km">Quilometragem:</label>
                    <input type="number" id="km" name="km" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="cambio">Câmbio:</label>
                    <select id="cambio" name="cambio" class="form-select" required>
                        <option value="Manual">Manual</option>
                        <option value="Automático">Automático</option>
                        <option value="CVT">CVT</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="combustivel">Combustível:</label>
                    <select id="combustivel" name="combustivel" class="form-select" required>
                        <option value="Gasolina">Gasolina</option>
                        <option value="Etanol">Etanol</option>
                        <option value="Flex">Flex</option>
                        <option value="Diesel">Diesel</option>
                        <option value="Elétrico">Elétrico</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="cor">Cor:</label>
                    <input type="text" id="cor" name="cor" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="potencia">Potência:</label>
                    <input type="text" id="potencia" name="potencia" class="form-control" placeholder="Ex: 1.6 16V">
                </div>
            </div>

            <div class="form-group full-width">
                <label for="descricao">Descrição: <span class="text-danger">*</span></label>
                <textarea id="descricao" name="descricao" rows="5" class="form-control" required></textarea>
                <div class="invalid-feedback">Por favor, adicione uma descrição para o veículo.</div>
            </div>

            <div class="form-group full-width">
                <label for="imagens">Fotos do Carro: <span class="text-danger">*</span></label>
                <input type="file" id="imagens" name="imagens[]" accept="image/jpeg,image/png,image/gif" 
                    multiple required class="form-control">
                <small class="form-text text-muted">Formatos permitidos: JPG, PNG, GIF. Tamanho máximo: 5MB por imagem.</small>
                <div class="invalid-feedback">Adicione pelo menos uma foto do veículo.</div>
                <div id="preview-container" class="image-preview mt-3"></div>
            </div>

            <div class="form-check form-group">
                <input type="checkbox" id="destaque" name="destaque" value="1" class="form-check-input">
                <label class="form-check-label" for="destaque">
                    Exibir em destaque na página inicial
                </label>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Cadastrar Carro
                </button>
                <a href="index.php?url=carros" class="btn btn-secondary ms-2">
                    <i class="fas fa-times me-2"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Form validation script
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    
    // Validar placa em tempo real
    document.getElementById('placa').addEventListener('input', function(e) {
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
    
    // Preview de imagens
    document.getElementById('imagens').addEventListener('change', function(e) {
        const previewContainer = document.getElementById('preview-container');
        previewContainer.innerHTML = '';
        
        if (this.files.length > 0) {
            const errors = [];
            
            Array.from(this.files).forEach((file, index) => {
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
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'preview-item';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-thumbnail';
                    img.style.maxHeight = '150px';
                    img.style.marginRight = '10px';
                    img.style.marginBottom = '10px';
                    
                    const caption = document.createElement('small');
                    caption.className = 'text-muted d-block';
                    caption.textContent = index === 0 ? 'Imagem Principal' : `Galeria #${index}`;
                    
                    previewDiv.appendChild(img);
                    previewDiv.appendChild(caption);
                    previewContainer.appendChild(previewDiv);
                };
                reader.readAsDataURL(file);
            });
            
            if (errors.length > 0) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'alert alert-danger';
                errorDiv.innerHTML = '<strong>Erros encontrados:</strong><ul>' + 
                    errors.map(err => `<li>${err}</li>`).join('') + '</ul>';
                previewContainer.appendChild(errorDiv);
            }
        }
    });
    
    // Bootstrap form validation
    const form = document.querySelector('.needs-validation');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });
});
</script>

<?php include __DIR__ . '/footer.php'; ?>