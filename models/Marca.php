<?php
class Marca {
    private $db;
    
    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;dbname=carros_db', 'root', '');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    public function listar() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM marcas ORDER BY nome");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar marcas: " . $e->getMessage());
        }
    }
    
    public function buscarPorId($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM marcas WHERE id = ?");
            $stmt->execute([(int)$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar marca: " . $e->getMessage());
        }
    }
    
    public function cadastrar($nome) {
        try {
            // Validação
            if (empty($nome)) {
                throw new Exception("O nome da marca é obrigatório");
            }
            
            // Verificar se já existe
            $stmt = $this->db->prepare("SELECT id FROM marcas WHERE nome = ?");
            $stmt->execute([$nome]);
            if ($stmt->fetch()) {
                throw new Exception("Esta marca já está cadastrada");
            }
            
            $stmt = $this->db->prepare("INSERT INTO marcas (nome) VALUES (?)");
            $stmt->execute([$nome]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Erro ao cadastrar marca: " . $e->getMessage());
        }
    }
    
    public function atualizar($id, $nome) {
        try {
            // Validação
            if (empty($nome)) {
                throw new Exception("O nome da marca é obrigatório");
            }
            
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("ID inválido");
            }
            
            // Verificar se já existe outra marca com este nome
            $stmt = $this->db->prepare("SELECT id FROM marcas WHERE nome = ? AND id != ?");
            $stmt->execute([$nome, (int)$id]);
            if ($stmt->fetch()) {
                throw new Exception("Já existe outra marca com este nome");
            }
            
            $stmt = $this->db->prepare("UPDATE marcas SET nome = ? WHERE id = ?");
            return $stmt->execute([$nome, (int)$id]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar marca: " . $e->getMessage());
        }
    }
    
    public function excluir($id) {
        try {
            // Verificar se existem carros usando esta marca
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM carros WHERE marca_id = ?");
            $stmt->execute([(int)$id]);
            if ($stmt->fetchColumn() > 0) {
                throw new Exception("Não é possível excluir esta marca pois existem carros cadastrados com ela");
            }
            
            $stmt = $this->db->prepare("DELETE FROM marcas WHERE id = ?");
            return $stmt->execute([(int)$id]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao excluir marca: " . $e->getMessage());
        }
    }
}
?>
