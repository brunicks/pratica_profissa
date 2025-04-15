<?php
return [
    '' => ['controller' => 'Carro', 'method' => 'home'],
    'carros' => ['controller' => 'Carro', 'method' => 'index'],
    'carro/novo' => ['controller' => 'Carro', 'method' => 'cadastrar'],
    'carro/editar' => ['controller' => 'Carro', 'method' => 'editar'],
    'carro/atualizar' => ['controller' => 'Carro', 'method' => 'atualizar'],
    'carro/detalhes' => ['controller' => 'Carro', 'method' => 'detalhes'],
    'carro/destaque' => ['controller' => 'Carro', 'method' => 'toggleDestaque'],
    'carro/excluir' => ['controller' => 'Carro', 'method' => 'excluir'],
    
    // Authentication routes
    'auth/login' => ['controller' => 'Auth', 'method' => 'login'],
    'auth/registro' => ['controller' => 'Auth', 'method' => 'registro'],
    'auth/logout' => ['controller' => 'Auth', 'method' => 'logout'],
    'auth/recuperar' => ['controller' => 'Auth', 'method' => 'recuperar'],
    'perfil' => ['controller' => 'Auth', 'method' => 'perfil'],
    
    // Admin routes
    'admin/dashboard' => ['controller' => 'Admin', 'method' => 'dashboard'],
    
    // Car search route
    'carros/buscar' => ['controller' => 'Carro', 'method' => 'buscar'],
];
?>
