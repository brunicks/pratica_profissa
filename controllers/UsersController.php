<?php
require_once __DIR__ . '/../models/Usuario.php';

class UsersController {
    private $usuarioModel;
    
    public function __construct() {
        session_start();
        $this->verificarAdmin();
        $this->usuarioModel = new Usuario();
    }
    
    private function verificarAdmin() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['admin'] != 1) {
            header("Location: index.php?url=auth/login");
            exit;
        }
    }
    
    public function index() {
        try {
            $usuarios = $this->usuarioModel->listarTodos();
            include __DIR__ . '/../views/admin/users.php';
        } catch (Exception $e) {
            $erro = "Erro ao listar usuários: " . $e->getMessage();
            include __DIR__ . '/../views/erro.php';
        }
    }
    
    public function editar() {
        $erro = null;
        $sucesso = null;
        
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        
        if (!$id) {
            header("Location: index.php?url=users");
            exit;
        }
        
        try {
            $usuario = $this->usuarioModel->buscarPorId($id);
            
            if (!$usuario) {
                $erro = "Usuário não encontrado";
                include __DIR__ . '/../views/erro.php';
                exit;
            }
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // CSRF validation
                if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                    throw new Exception("Erro de validação do formulário. Por favor, tente novamente.");
                }
                
                $nome = $_POST['nome'] ?? '';
                $email = $_POST['email'] ?? '';
                $telefone = $_POST['telefone'] ?? '';
                $admin = isset($_POST['admin']) ? 1 : 0;
                $ativo = isset($_POST['ativo']) ? 1 : 0;
                
                if (empty($nome) || empty($email)) {
                    $erro = "Nome e email são obrigatórios.";
                } else {
                    $this->usuarioModel->atualizarPerfil($id, $nome, $email, $telefone);
                    $this->usuarioModel->alterarAdmin($id, $admin);
                    $this->usuarioModel->alterarStatus($id, $ativo);
                    
                    $sucesso = "Dados do usuário atualizados com sucesso!";
                    $usuario = $this->usuarioModel->buscarPorId($id);
                }
            }
        } catch (Exception $e) {
            $erro = "Erro: " . $e->getMessage();
        }
        
        // Generate CSRF token
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        
        include __DIR__ . '/../views/admin/edit_user.php';
    }
    
    public function resetarSenha() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF validation
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $erro = "Erro de validação do formulário. Por favor, tente novamente.";
                include __DIR__ . '/../views/erro.php';
                exit;
            }
            
            $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
            $novaSenha = $_POST['nova_senha'] ?? '';
            
            if (!$id || empty($novaSenha)) {
                $erro = "ID do usuário e nova senha são obrigatórios.";
                include __DIR__ . '/../views/erro.php';
                exit;
            }
            
            try {
                $this->usuarioModel->resetarSenha($id, $novaSenha);
                header("Location: index.php?url=users/editar&id=$id&senha_resetada=1");
                exit;
            } catch (Exception $e) {
                $erro = "Erro ao resetar senha: " . $e->getMessage();
                include __DIR__ . '/../views/erro.php';
                exit;
            }
        }
        
        header("Location: index.php?url=users");
        exit;
    }
    
    public function alterarStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF validation
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                echo json_encode(['success' => false, 'message' => 'Erro de validação do token CSRF']);
                exit;
            }
            
            $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
            $ativo = isset($_POST['ativo']) ? (int)$_POST['ativo'] : 0;
            
            try {
                $result = $this->usuarioModel->alterarStatus($id, $ativo);
                echo json_encode(['success' => $result]);
                exit;
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                exit;
            }
        }
        
        echo json_encode(['success' => false, 'message' => 'Método não permitido']);
        exit;
    }
    
    public function alterarAdmin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF validation
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                echo json_encode(['success' => false, 'message' => 'Erro de validação do token CSRF']);
                exit;
            }
            
            $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
            $admin = isset($_POST['admin']) ? (int)$_POST['admin'] : 0;
            
            try {
                $result = $this->usuarioModel->alterarAdmin($id, $admin);
                echo json_encode(['success' => $result]);
                exit;
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                exit;
            }
        }
        
        echo json_encode(['success' => false, 'message' => 'Método não permitido']);
        exit;
    }
    
    public function excluir() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF validation
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $erro = "Erro de validação do formulário. Por favor, tente novamente.";
                include __DIR__ . '/../views/erro.php';
                exit;
            }
            
            $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
            
            if (!$id) {
                $erro = "ID do usuário é obrigatório.";
                include __DIR__ . '/../views/erro.php';
                exit;
            }
            
            // Não permitir excluir a si mesmo
            if ($id == $_SESSION['user']['id']) {
                $erro = "Você não pode excluir sua própria conta.";
                include __DIR__ . '/../views/erro.php';
                exit;
            }
            
            try {
                $this->usuarioModel->excluirUsuario($id);
                header("Location: index.php?url=users?excluido=1");
                exit;
            } catch (Exception $e) {
                $erro = "Erro ao excluir usuário: " . $e->getMessage();
                include __DIR__ . '/../views/erro.php';
                exit;
            }
        }
        
        header("Location: index.php?url=users");
        exit;
    }
}
?>
