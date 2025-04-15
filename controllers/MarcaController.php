<?php
require_once __DIR__ . '/../models/Marca.php';

class MarcaController {
    private $marcaModel;
    
    public function __construct() {
        $this->marcaModel = new Marca();
        // Verificar se o usuário está logado e é admin
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['admin'] != 1) {
            header('Location: index.php?url=auth/login');
            exit;
        }
        
        // Generate CSRF token if it doesn't exist
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }
    
    private function verificarCsrf() {
        if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
            $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die("Erro de validação do token CSRF.");
        }
    }
    
    public function listar() {
        try {
            $marcas = $this->marcaModel->listar();
            include __DIR__ . '/../views/admin/marcas/listar.php';
        } catch (Exception $e) {
            $erro = $e->getMessage();
            include __DIR__ . '/../views/erro.php';
        }
    }
    
    public function cadastrar() {
        $erro = null;
        $sucesso = null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->verificarCsrf();
                $nome = $_POST['nome'] ?? '';
                
                if (empty($nome)) {
                    throw new Exception("O nome da marca é obrigatório");
                }
                
                $this->marcaModel->cadastrar($nome);
                header("Location: index.php?url=marca/listar&sucesso=cadastrado");
                exit;
            } catch (Exception $e) {
                $erro = $e->getMessage();
            }
        }
        
        include __DIR__ . '/../views/admin/marcas/cadastrar.php';
    }
    
    public function editar() {
        $erro = null;
        $sucesso = null;
        $marca = null;
        
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$id) {
            header("Location: index.php?url=marca/listar");
            exit;
        }
        
        try {
            $marca = $this->marcaModel->buscarPorId($id);
            if (!$marca) {
                throw new Exception("Marca não encontrada");
            }
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->verificarCsrf();
                $nome = $_POST['nome'] ?? '';
                
                if (empty($nome)) {
                    throw new Exception("O nome da marca é obrigatório");
                }
                
                $this->marcaModel->atualizar($id, $nome);
                header("Location: index.php?url=marca/listar&sucesso=atualizado");
                exit;
            }
        } catch (Exception $e) {
            $erro = $e->getMessage();
        }
        
        include __DIR__ . '/../views/admin/marcas/editar.php';
    }
    
    public function excluir() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->verificarCsrf();
                $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
                
                if (!$id) {
                    throw new Exception("ID inválido");
                }
                
                $this->marcaModel->excluir($id);
                header("Location: index.php?url=marca/listar&sucesso=excluido");
                exit;
            } catch (Exception $e) {
                $erro = $e->getMessage();
                include __DIR__ . '/../views/erro.php';
                exit;
            }
        }
        
        header("Location: index.php?url=marca/listar");
        exit;
    }
}
?>
