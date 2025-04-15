<?php include __DIR__ . '/header.php'; ?>

<div class="page-header">
    <h2>Lista de Carros</h2>
    <a href="index.php?url=carro/novo" class="btn">Cadastrar Novo Carro</a>
</div>

<ul class="car-list">
    <?php if(empty($carros)): ?>
        <li class="car-item">
            <div class="alert alert-info">Nenhum carro cadastrado.</div>
        </li>
    <?php endif; ?>
    
    <?php foreach ($carros as $carro): ?>
        <li class="car-item">
            <div class="car-image">
                <?php if ($carro['imagem']): ?>
                    <img src="/mvc/public/<?php echo htmlspecialchars($carro['imagem']); ?>" 
                         alt="<?php echo htmlspecialchars($carro['modelo']); ?>">
                <?php else: ?>
                    <div class="no-image">Sem imagem</div>
                <?php endif; ?>
                
                <?php 
                $statusClass = 'bg-success';
                if($carro['status'] == 'reservado') $statusClass = 'bg-warning';
                if($carro['status'] == 'vendido') $statusClass = 'bg-danger';
                ?>
                <span class="car-status badge <?php echo $statusClass; ?>">
                    <?php echo ucfirst(htmlspecialchars($carro['status'] ?? 'disponível')); ?>
                </span>
            </div>
            <div class="car-info">
                <strong><?php echo htmlspecialchars($carro['modelo']); ?></strong>
                <span>(<?php echo htmlspecialchars($carro['ano']); ?>)</span>
                <span>- <?php echo htmlspecialchars($carro['marca']); ?></span>
                
                <div class="car-details">
                    <?php if(!empty($carro['preco'])): ?>
                    <div class="car-price">
                        R$ <?php echo number_format($carro['preco'], 2, ',', '.'); ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="car-specs">
                        <?php if(!empty($carro['km'])): ?>
                        <span><i class="fas fa-tachometer-alt"></i> <?php echo number_format($carro['km'], 0, ',', '.'); ?> km</span>
                        <?php endif; ?>
                        <?php if(!empty($carro['cambio'])): ?>
                        <span><i class="fas fa-cogs"></i> <?php echo htmlspecialchars($carro['cambio']); ?></span>
                        <?php endif; ?>
                        <?php if(!empty($carro['placa'])): ?>
                        <span><i class="fas fa-id-card"></i> Placa final <?php echo htmlspecialchars($carro['final_placa']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="car-actions">
                    <a href="index.php?url=carro/editar&id=<?php echo $carro['id']; ?>" 
                       class="btn btn-small">Editar</a>    
                
                    <a href="index.php?url=carro/detalhes&id=<?php echo $carro['id']; ?>" 
                       class="btn btn-small btn-info">Detalhes</a>
                    
                    <form action="index.php?url=carro/destaque" method="post" style="display: inline;">
                        <input type="hidden" name="id" value="<?php echo $carro['id']; ?>">
                        <input type="hidden" name="destaque" value="<?php echo $carro['destaque'] ? '0' : '1'; ?>">
                        <button type="submit" class="btn btn-small <?php echo $carro['destaque'] ? 'btn-warning' : 'btn-secondary'; ?>">
                            <?php echo $carro['destaque'] ? 'Remover Destaque' : 'Destacar'; ?>
                        </button>
                    </form>
                    
                    <form action="index.php?url=carro/excluir" method="post" style="display: inline;"
                          onsubmit="return confirm('Tem certeza que deseja excluir este carro?');">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                        <input type="hidden" name="id" value="<?php echo $carro['id']; ?>">
                        <button type="submit" class="btn btn-small btn-danger">Excluir</button>
                    </form>
                </div>
            </div>
        </li>
    <?php endforeach; ?>
</ul>

<style>
.car-status {
    position: absolute;
    top: 10px;
    right: 10px;
    color: white;
    padding: 3px 8px;
    border-radius: 3px;
}
.car-details {
    margin-top: 8px;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
}
.car-price {
    font-weight: bold;
    font-size: 1.2em;
    color: #4CAF50;
}
.car-specs {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 5px;
}
.car-specs span {
    font-size: 0.9em;
    color: #666;
}
.car-specs i {
    margin-right: 5px;
}
.btn-danger {
    background-color: #dc3545;
    color: white;
}
.btn-info {
    background-color: #17a2b8;
    color: white;
}
</style>

<?php include __DIR__ . '/footer.php'; ?>