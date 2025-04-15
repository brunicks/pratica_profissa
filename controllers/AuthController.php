<?php
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {
    private $usuarioModel;
    
    public function __construct() {
        session_start();
        $this->usuarioModel = new Usuario();
        
        // Check for remember-me cookie
        $this->verificarCookieLogin();
    }
    
    private function verificarCookieLogin() {
        if (!isset($_SESSION['user']) && isset($_COOKIE['remember_token'])) {
            $token = $_COOKIE['remember_token'];
            $usuario = $this->usuarioModel->buscarPorToken($token);
            
            if ($usuario) {
                $_SESSION['user'] = [
                    'id' => $usuario['id'],
                    'nome' => $usuario['nome'],
                    'email' => $usuario['email'],
                    'admin' => $usuario['admin']
                ];
            }
        }
    }
    
    public function login() {
        $erro = null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $senha = $_POST['senha'] ?? '';
            $lembrar = isset($_POST['lembrar']);
            
            if (empty($email) || empty($senha)) {
                $erro = "Preencha todos os campos.";
            } else {
                try {
                    $usuario = $this->usuarioModel->login($email, $senha);
                    
                    if ($usuario) {
                        // Verifica se a conta está ativa
                        if ($usuario['ativo'] == 0) {
                            $erro = "Esta conta foi desativada. Entre em contato com o administrador.";
                            // Log da tentativa de acesso com conta desativada
                            error_log("Tentativa de login em conta desativada: $email em " . date('Y-m-d H:i:s'));
                        } else {
                            // Continua com o processo de login para contas ativas
                            $this->usuarioModel->atualizarUltimoAcesso($usuario['id']);
                            
                            $_SESSION['user'] = [
                                'id' => $usuario['id'],
                                'nome' => $usuario['nome'],
                                'email' => $usuario['email'],
                                'admin' => $usuario['admin']
                            ];
                            
                            // Handle remember me
                            if ($lembrar) {
                                $token = bin2hex(random_bytes(32)); // More secure token
                                $this->usuarioModel->salvarToken($usuario['id'], $token);
                                setcookie('remember_token', $token, time() + 60*60*24*30, '/', '', true, true); // Secure cookies
                            }
                            
                            // Log successful login
                            error_log("Login bem-sucedido para {$usuario['email']} em " . date('Y-m-d H:i:s'));
                            
                            // Redirect based on user role
                            if ($usuario['admin'] == 1) {
                                header('Location: index.php?url=admin/dashboard');
                            } else {
                                header('Location: index.php');
                            }
                            exit;
                        }
                    } else {
                        // Log failed login attempt
                        error_log("Tentativa de login falhou para $email em " . date('Y-m-d H:i:s'));
                        $erro = "Email ou senha inválidos.";
                    }
                } catch (Exception $e) {
                    $erro = "Erro ao fazer login: " . $e->getMessage();
                    error_log("Erro no login: " . $e->getMessage());
                }
            }
        }
        
        include __DIR__ . '/../views/login.php';
    }
    
    public function registro() {
        $erro = null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = $_POST['nome'] ?? '';
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';
            $confirmaSenha = $_POST['confirma_senha'] ?? '';
            $telefone = $_POST['telefone'] ?? '';
            
            if (empty($nome) || empty($email) || empty($senha)) {
                $erro = "Preencha todos os campos obrigatórios.";
            } elseif ($senha !== $confirmaSenha) {
                $erro = "As senhas não conferem.";
            } else {
                try {
                    $cadastrou = $this->usuarioModel->cadastrar($nome, $email, $senha, $telefone);
                    
                    if ($cadastrou) {
                        header('Location: index.php?url=auth/login&cadastro=success');
                        exit;
                    } else {
                        $erro = "Erro ao cadastrar usuário.";
                    }
                } catch (PDOException $e) {
                    if ($e->getCode() == 23000) { // Código para violação de chave única
                        $erro = "Email já cadastrado.";
                    } else {
                        $erro = "Erro ao cadastrar: " . $e->getMessage();
                    }
                }
            }
        }
        
        include __DIR__ . '/../views/registro.php';
    }
    
    public function logout() {
        // Clear the remember me cookie if exists
        if (isset($_COOKIE['remember_token'])) {
            $this->usuarioModel->limparToken($_COOKIE['remember_token']);
            setcookie('remember_token', '', time() - 3600, '/');
        }
        
        session_destroy();
        header('Location: index.php?url=auth/login&logout=success');
        exit;
    }
    
    public function perfil() {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?url=auth/login');
            exit;
        }
        
        $mensagem = null;
        $erro = null;
        $usuario = $this->usuarioModel->buscarPorId($_SESSION['user']['id']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['atualizar_perfil'])) {
                $nome = $_POST['nome'] ?? '';
                $email = $_POST['email'] ?? '';
                $telefone = $_POST['telefone'] ?? '';
                
                if (empty($nome) || empty($email)) {
                    $erro = "Nome e email são obrigatórios.";
                } else {
                    $atualizado = $this->usuarioModel->atualizarPerfil($_SESSION['user']['id'], $nome, $email, $telefone);
                    
                    if ($atualizado) {
                        $_SESSION['user']['nome'] = $nome;
                        $_SESSION['user']['email'] = $email;
                        $mensagem = "Perfil atualizado com sucesso!";
                        $usuario = $this->usuarioModel->buscarPorId($_SESSION['user']['id']);
                    } else {
                        $erro = "Erro ao atualizar perfil.";
                    }
                }
            } elseif (isset($_POST['alterar_senha'])) {
                $senha_atual = $_POST['senha_atual'] ?? '';
                $nova_senha = $_POST['nova_senha'] ?? '';
                $confirma_senha = $_POST['confirma_senha'] ?? '';
                
                if (empty($senha_atual) || empty($nova_senha) || empty($confirma_senha)) {
                    $erro = "Preencha todos os campos de senha.";
                } elseif ($nova_senha !== $confirma_senha) {
                    $erro = "As senhas não conferem.";
                } else {
                    $usuario_verificacao = $this->usuarioModel->login($_SESSION['user']['email'], $senha_atual);
                    
                    if ($usuario_verificacao) {
                        $alterou = $this->usuarioModel->alterarSenha($_SESSION['user']['id'], $nova_senha);
                        
                        if ($alterou) {
                            $mensagem = "Senha alterada com sucesso!";
                        } else {
                            $erro = "Erro ao alterar senha.";
                        }
                    } else {
                        $erro = "Senha atual incorreta.";
                    }
                }
            }
        }
        
        include __DIR__ . '/../views/perfil.php';
    }
    
    public function recuperar() {
        $mensagem = null;
        $erro = null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            
            if (empty($email)) {
                $erro = "Por favor, informe seu email.";
            } else {
                $usuario = $this->usuarioModel->buscarPorEmail($email);
                
                if ($usuario) {
                    // Generate reset token
                    $token = bin2hex(random_bytes(16));
                    $this->usuarioModel->salvarTokenReset($usuario['id'], $token);
                    
                    // In a real app, send email with reset link
                    // For demo, just show success message
                    $mensagem = "Um link para redefinição de senha foi enviado para seu email.";
                } else {
                    $erro = "Email não encontrado em nossa base de dados.";
                }
            }
        }
        
        include __DIR__ . '/../views/recuperar_senha.php';
    }
}
?>
