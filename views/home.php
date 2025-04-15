<?php include __DIR__ . '/header.php'; ?>

<!-- Hero Section -->
<section class="hero-section position-relative mb-5">
    <div class="hero-overlay" style="background: rgba(0, 0, 0, 0.7);"></div>
    <div class="container hero-content text-center py-5">
        <div class="bg-dark bg-opacity-50 p-4 rounded">
            <h1 class="display-3 text-white fw-bold mb-3" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">Encontre o Carro dos Seus Sonhos</h1>
            <p class="lead text-white mb-4" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.8);">Seminovos premium com qualidade garantida</p>
        </div>
        
        <!-- Search Form - Fixed to properly route to the controller -->
        <div class="search-container bg-white p-4 rounded shadow mx-auto mt-4" style="max-width: 800px;">
            <form action="/mvc/public/index.php" method="get" class="row g-3">
                <input type="hidden" name="url" value="carros/buscar">
                <div class="col-md-4">
                    <select class="form-select" name="marca">
                        <option value="">Todas as Marcas</option>
                        <?php foreach($marcas ?? [] as $marca): ?>
                            <option value="<?php echo $marca['id']; ?>"><?php echo htmlspecialchars($marca['nome'] ?? ''); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="preco_max">
                        <option value="">Preço Máximo</option>
                        <option value="50000">Até R$ 50.000</option>
                        <option value="100000">Até R$ 100.000</option>
                        <option value="150000">Até R$ 150.000</option>
                        <option value="200000">Até R$ 200.000</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="ano_min">
                        <option value="">Ano Mínimo</option>
                        <?php for($i = date('Y'); $i >= 2010; $i--): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Featured Cars Section -->
<section class="featured-cars py-5">
    <div class="container">
        <h2 class="section-title mb-4">
            <i class="fas fa-star text-warning"></i>
            Veículos em Destaque
        </h2>
        
        <div class="row">
            <?php if(empty($carrosDestaque)): ?>
                <div class="col-12">
                    <div class="alert alert-info">
                        Nenhum veículo em destaque no momento. Veja nosso <a href="index.php?url=carros">catálogo completo</a>.
                    </div>
                </div>
            <?php endif; ?>
            
            <?php foreach ($carrosDestaque ?? [] as $carro): ?>
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
                               <i class="fas fa-info-circle"></i> Ver Detalhes
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="index.php?url=carros" class="btn btn-lg btn-primary">
                <i class="fas fa-car"></i> Ver Catálogo Completo
            </a>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="why-us bg-light py-5">
    <div class="container">
        <h2 class="section-title mb-4 text-center">Por que escolher a Elite Motors?</h2>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-medal fa-3x text-primary"></i>
                    </div>
                    <h3 class="h5">Qualidade Garantida</h3>
                    <p>Todos os veículos passam por rigorosa inspeção de mais de 100 itens antes de entrar para nosso estoque.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-handshake fa-3x text-primary"></i>
                    </div>
                    <h3 class="h5">Negociação Transparente</h3>
                    <p>Sem pegadinhas ou taxas escondidas. Prezamos pela transparência em todas as negociações.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-tools fa-3x text-primary"></i>
                    </div>
                    <h3 class="h5">Garantia Estendida</h3>
                    <p>Oferecemos garantia de motor e câmbio em todos os nossos veículos.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Temporary Admin Debug Section -->
<?php if(isset($_GET['admin_debug'])): ?>
<section class="container my-5 p-4 bg-light rounded">
    <h3 class="text-danger">Admin Credentials Checker</h3>
    <?php
    require_once __DIR__ . '/../config/database.php';
    $db = Database::getConnection();
    
    // Check if reset button was clicked
    if(isset($_GET['reset_admin'])) {
        $senha_hash = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE usuarios SET senha = ? WHERE email = 'admin@exemplo.com' AND admin = 1");
        if($stmt->execute([$senha_hash])) {
            echo '<div class="alert alert-success">Admin password has been reset to "admin123"</div>';
        } else {
            echo '<div class="alert alert-danger">Failed to reset password</div>';
        }
    }
    
    // Display admin info
    $stmt = $db->query("SELECT id, nome, email, senha FROM usuarios WHERE admin = 1");
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($admin) {
        echo '<div class="alert alert-info">';
        echo '<p><strong>Admin Found:</strong><br>';
        echo 'ID: ' . $admin['id'] . '<br>';
        echo 'Name: ' . htmlspecialchars($admin['nome']) . '<br>';
        echo 'Email: ' . htmlspecialchars($admin['email']) . '<br>';
        echo 'Password Hash: ' . htmlspecialchars($admin['senha']) . '</p>';
        
        // Verify if "admin123" would work
        $works = password_verify('admin123', $admin['senha']);
        echo '<p>Password "admin123" ' . ($works ? 'WORKS' : 'DOES NOT WORK') . ' with this hash</p>';
        
        if(!$works) {
            echo '<a href="?admin_debug=1&reset_admin=1" class="btn btn-warning">Reset Admin Password to "admin123"</a>';
        }
        echo '</div>';
    } else {
        echo '<div class="alert alert-warning">No admin user found! The system will create one automatically when you access the Usuario model.</div>';
    }
    ?>
    <p class="mt-3"><a href="index.php" class="btn btn-secondary">Back to Normal Mode</a></p>
</section>
<?php endif; ?>

<!-- Script de dropdown removido daqui e movido para o footer.php -->

<?php include __DIR__ . '/footer.php'; ?>