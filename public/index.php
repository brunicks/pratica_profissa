<?php
// For development, enable error display to debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../Dispatcher.php';

try {
    $dispatcher = new Dispatcher();
    $dispatcher->dispatch($_GET['url'] ?? '');
} catch (Exception $e) {
    // Log error
    error_log("Fatal error: " . $e->getMessage());
    // Show friendly message
    echo "<h1>Ocorreu um erro</h1>";
    echo "<p>Desculpe, ocorreu um problema ao processar sua solicitação.</p>";
    // In development, you might want to see the error
    if (getenv('ENVIRONMENT') !== 'production') {
        echo "<p>Erro: " . $e->getMessage() . "</p>";
    }
}
?>
