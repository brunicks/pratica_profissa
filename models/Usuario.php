<?php
require_once __DIR__ . '/../config/database.php';

class Usuario {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
        $this->criarTabelaSeNaoExistir();
    }
    
    public function criarTabelaSeNaoExistir() {
        $sql = "CREATE TABLE IF NOT EXISTS usuarios (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            senha VARCHAR(255) NOT NULL,
            telefone VARCHAR(20),
            admin TINYINT(1) DEFAULT 0,
            remember_token VARCHAR(255),
            reset_token VARCHAR(255),
            reset_token_expira DATETIME,
            data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        $this->db->exec($sql);
        
        // Fix table structure if it's incomplete
        try {
            $this->db->exec("ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS ativo TINYINT(1) DEFAULT 1");
            $this->db->exec("ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS ultimo_acesso DATETIME DEFAULT NULL");
            
            // Check if we need to create default admin
            $stmt = $this->db->query("SELECT COUNT(*) FROM usuarios WHERE admin = 1");
            if ($stmt->fetchColumn() == 0) {
                // Create default admin with more secure password
                $senha_hash = password_hash('Admin@' . date('Y'), PASSWORD_DEFAULT);
                $this->db->exec("INSERT INTO usuarios (nome, email, senha, admin, ativo) 
                            VALUES ('Administrador', 'admin@exemplo.com', '$senha_hash', 1, 1)");
                
                // Log admin creation
                error_log('Default admin user created at ' . date('Y-m-d H:i:s'));
            }
        } catch (PDOException $e) {
            error_log('Error checking/fixing usuarios table: ' . $e->getMessage());
        }
    }
    
    public function cadastrar($nome, $email, $senha, $telefone = null) {
        // Validate inputs
        if (empty($nome) || strlen($nome) > 255) {
            throw new Exception("Nome inválido");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255) {
            throw new Exception("Email inválido");
        }
        if (strlen($senha) < 6) {
            throw new Exception("Senha deve ter pelo menos 6 caracteres");
        }
        
        // Check if email already exists
        $stmt = $this->db->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            throw new Exception("Este email já está em uso");
        }
        
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO usuarios (nome, email, senha, telefone, ultimo_acesso) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nome, $email, $senha_hash, $telefone]);
    }
    
    public function login($email, $senha) {
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            return $usuario;
        }
        
        return false;
    }
    
    public function buscarPorId($id) {
        $sql = "SELECT id, nome, email, telefone, admin FROM usuarios WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function buscarPorEmail($email) {
        $sql = "SELECT id, nome, email FROM usuarios WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function buscarPorToken($token) {
        $sql = "SELECT id, nome, email, admin FROM usuarios WHERE remember_token = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function salvarToken($userId, $token) {
        $sql = "UPDATE usuarios SET remember_token = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$token, $userId]);
    }
    
    public function limparToken($token) {
        $sql = "UPDATE usuarios SET remember_token = NULL WHERE remember_token = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$token]);
    }
    
    public function salvarTokenReset($userId, $token) {
        // Token expires in 1 hour
        $expira = date('Y-m-d H:i:s', time() + 3600);
        $sql = "UPDATE usuarios SET reset_token = ?, reset_token_expira = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$token, $expira, $userId]);
    }
    
    public function atualizarPerfil($id, $nome, $email, $telefone) {
        $sql = "UPDATE usuarios SET nome = ?, email = ?, telefone = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nome, $email, $telefone, $id]);
    }
    
    public function alterarSenha($id, $senha) {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET senha = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$senha_hash, $id]);
    }
    
    public function atualizarUltimoAcesso($userId) {
        $sql = "UPDATE usuarios SET ultimo_acesso = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$userId]);
    }
}
?>
