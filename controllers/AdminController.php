<?php
class AdminController {
    
    public function __construct() {
        session_start();
        $this->verificarAdmin();
    }
    
    private function verificarAdmin() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['admin'] != 1) {
            header("Location: index.php?url=auth/login");
            exit;
        }
    }
    
    public function dashboard() {
        // Include any models you need for the dashboard
        // For example: require_once __DIR__ . '/../models/Carro.php';
        
        // You could load stats or summary data here
        $pageTitle = "Painel Administrativo";
        include __DIR__ . '/../views/admin/dashboard.php';
    }
}
?>
