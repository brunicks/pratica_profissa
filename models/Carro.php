<?php
require_once __DIR__ . '/../config/database.php';

class Carro {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
        $this->verificarEstruturaBanco();
    }
    
    private function verificarEstruturaBanco() {
        try {
            // Add better error logging
            error_log('Verificando e corrigindo estrutura das tabelas...');
            
            // Verificar se as colunas têm valores padrão apropriados
            $this->db->exec("ALTER TABLE carros MODIFY km INT NOT NULL DEFAULT 0");
            $this->db->exec("ALTER TABLE carros MODIFY preco DECIMAL(10,2) NOT NULL DEFAULT 0.00");
            $this->db->exec("ALTER TABLE carros MODIFY cor VARCHAR(50) NOT NULL DEFAULT ''");
            $this->db->exec("ALTER TABLE carros MODIFY potencia VARCHAR(50) NOT NULL DEFAULT ''");
            $this->db->exec("ALTER TABLE carros MODIFY descricao TEXT NOT NULL DEFAULT ''");
            
            // Verificar campos opcionais
            $this->db->exec("ALTER TABLE carros ADD COLUMN IF NOT EXISTS cambio VARCHAR(50) DEFAULT 'Manual'"); 
            $this->db->exec("ALTER TABLE carros ADD COLUMN IF NOT EXISTS combustivel VARCHAR(50) DEFAULT 'Gasolina'");
            $this->db->exec("ALTER TABLE carros ADD COLUMN IF NOT EXISTS destaque TINYINT(1) DEFAULT 0");
            $this->db->exec("ALTER TABLE carros ADD COLUMN IF NOT EXISTS placa VARCHAR(10) DEFAULT NULL"); 
            $this->db->exec("ALTER TABLE carros ADD COLUMN IF NOT EXISTS versao VARCHAR(100) DEFAULT NULL");
            $this->db->exec("ALTER TABLE carros ADD COLUMN IF NOT EXISTS portas INT(1) DEFAULT NULL");
            $this->db->exec("ALTER TABLE carros ADD COLUMN IF NOT EXISTS final_placa INT(1) DEFAULT NULL");
            $this->db->exec("ALTER TABLE carros ADD COLUMN IF NOT EXISTS views INT(11) NOT NULL DEFAULT 0");
            // Adicionar coluna status se não existir
            $this->db->exec("ALTER TABLE carros ADD COLUMN IF NOT EXISTS status VARCHAR(20) DEFAULT 'disponivel'");
            
            error_log('Verificação da estrutura concluída');
        } catch (PDOException $e) {
            error_log('Erro ao verificar estrutura do banco: ' . $e->getMessage());
        }
    }

    public function listar() {
        try {
            $query = "SELECT c.*, m.nome as marca 
                     FROM carros c 
                     LEFT JOIN marcas m ON c.marca_id = m.id
                     ORDER BY c.id DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar carros: " . $e->getMessage());
        }
    }

    public function listarDestaque() {
        try {
            $query = "SELECT c.*, m.nome as marca 
                     FROM carros c 
                     LEFT JOIN marcas m ON c.marca_id = m.id
                     WHERE c.destaque = 1
                     ORDER BY c.id DESC
                     LIMIT 6";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar carros em destaque: " . $e->getMessage());
        }
    }

    public function buscar($filtros) {
        try {
            $sql = "SELECT c.*, m.nome as marca 
                   FROM carros c 
                   LEFT JOIN marcas m ON c.marca_id = m.id
                   WHERE 1=1";
            $params = [];
            
            if (!empty($filtros['marca']) && is_numeric($filtros['marca'])) {
                $sql .= " AND c.marca_id = ?";
                $params[] = (int)$filtros['marca'];
            }
            
            if (!empty($filtros['preco_max']) && is_numeric($filtros['preco_max'])) {
                $sql .= " AND c.preco <= ?";
                $params[] = (float)$filtros['preco_max'];
            }
            
            if (!empty($filtros['ano_min']) && is_numeric($filtros['ano_min'])) {
                $sql .= " AND c.ano >= ?";
                $params[] = (int)$filtros['ano_min'];
            }
            
            $sql .= " ORDER BY c.id DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar carros: " . $e->getMessage());
        }
    }

    public function cadastrar($dados) {
        try {
            $this->db->beginTransaction();
            
            // Validação de dados melhorada
            if (empty($dados['modelo'])) {
                throw new Exception("O modelo do carro é obrigatório");
            }
            
            // Validação de ano com mensagem mais específica
            if (!is_numeric($dados['ano']) || $dados['ano'] < 1900 || $dados['ano'] > date('Y') + 1) {
                throw new Exception("Ano inválido. Informe um ano entre 1900 e " . (date('Y') + 1));
            }
            
            // Validação de marca mais robusta
            if (!is_numeric($dados['marca_id'])) {
                throw new Exception("Marca inválida");
            }
            
            // Verificar se marca existe com mensagem mais específica
            $stmt = $this->db->prepare("SELECT id FROM marcas WHERE id = ?");
            $stmt->execute([$dados['marca_id']]);
            if (!$stmt->fetch()) {
                throw new Exception("A marca selecionada não existe no banco de dados");
            }
            
            // Validar dados numéricos com valores mínimos
            $preco = !empty($dados['preco']) ? filter_var($dados['preco'], FILTER_VALIDATE_FLOAT) : 0;
            if ($preco === false || $preco < 0) {
                throw new Exception("O preço deve ser um valor numérico não negativo");
            }
            
            $km = !empty($dados['km']) ? filter_var($dados['km'], FILTER_VALIDATE_INT) : 0;
            if ($km === false || $km < 0) {
                throw new Exception("A quilometragem deve ser um valor numérico não negativo");
            }
            
            // Validação de placa se fornecida
            if (!empty($dados['placa'])) {
                $placaPadrao = '/^[A-Z]{3}[0-9]{4}$|^[A-Z]{3}[0-9]{1}[A-Z]{1}[0-9]{2}$/i';
                if (!preg_match($placaPadrao, $dados['placa'])) {
                    throw new Exception("Formato de placa inválido. Use o formato AAA0000 ou AAA0A00");
                }
                
                // Adicionar final_placa automaticamente
                $dados['final_placa'] = (int)substr(trim($dados['placa']), -1);
            }
            
            // Validação do status
            $statusPermitido = ['disponivel', 'reservado', 'vendido'];
            if (!empty($dados['status']) && !in_array($dados['status'], $statusPermitido)) {
                throw new Exception("Status inválido. Valores permitidos: disponível, reservado ou vendido");
            }
            
            // Validação de portas
            if (!empty($dados['portas']) && (!is_numeric($dados['portas']) || $dados['portas'] < 2 || $dados['portas'] > 5)) {
                throw new Exception("Número de portas inválido. Deve ser entre 2 e 5");
            }
            
            // Preparar demais campos com valores default se necessário
            $cambio = !empty($dados['cambio']) ? $dados['cambio'] : 'Manual';
            $combustivel = !empty($dados['combustivel']) ? $dados['combustivel'] : 'Gasolina';
            $cor = !empty($dados['cor']) ? $dados['cor'] : '';
            $potencia = !empty($dados['potencia']) ? $dados['potencia'] : '';
            $descricao = !empty($dados['descricao']) ? $dados['descricao'] : '';
            $destaque = isset($dados['destaque']) ? 1 : 0;
            $status = !empty($dados['status']) ? $dados['status'] : 'disponivel';
            
            // Campos opcionais adicionais
            $placa = !empty($dados['placa']) ? strtoupper($dados['placa']) : null;
            $versao = !empty($dados['versao']) ? $dados['versao'] : null;
            $portas = !empty($dados['portas']) ? (int)$dados['portas'] : null;
            $final_placa = !empty($dados['final_placa']) ? (int)$dados['final_placa'] : null;

            // Adicionando log para debugging
            error_log('Tentando inserir carro: ' . json_encode($dados));

            // Certifique-se de que o SQL corresponde exatamente aos campos na tabela
            $sql = "INSERT INTO carros (modelo, marca_id, ano, preco, km, cambio, 
                    combustivel, cor, potencia, descricao, destaque, imagem,
                    placa, versao, portas, final_placa, status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    
            $stmt = $this->db->prepare($sql);
            $resultado = $stmt->execute([
                $dados['modelo'],
                (int)$dados['marca_id'],
                (int)$dados['ano'],
                $preco,
                $km,
                $cambio,
                $combustivel,
                $cor,
                $potencia,
                $descricao,
                $destaque,
                $dados['imagem'] ?? null,
                $placa,
                $versao,
                $portas,
                $final_placa,
                $status
            ]);
            
            if (!$resultado) {
                error_log('Erro na query SQL de inserção: ' . print_r($stmt->errorInfo(), true));
                throw new Exception("Erro ao inserir o carro no banco de dados");
            }
            
            $carroId = $this->db->lastInsertId();
            $this->db->commit();
            
            error_log("Carro cadastrado com sucesso! ID: $carroId");
            return $carroId;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('Erro ao cadastrar carro: ' . $e->getMessage() . ' - Trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    public function atualizar($id, $dados) {
        try {
            // Validar ID com mensagem específica
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("ID de carro inválido");
            }
            
            // Verificar se o carro existe com query otimizada
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM carros WHERE id = ?");
            $stmt->execute([(int)$id]);
            if ($stmt->fetchColumn() == 0) {
                throw new Exception("O carro com ID $id não foi encontrado");
            }
            
            // Validação de campos obrigatórios
            if (empty($dados['modelo'])) {
                throw new Exception("O modelo do carro é obrigatório");
            }
            
            // Validação da marca com verificação de existência
            if (!is_numeric($dados['marca_id'])) {
                throw new Exception("Marca inválida");
            }
            
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM marcas WHERE id = ?");
            $stmt->execute([(int)$dados['marca_id']]);
            if ($stmt->fetchColumn() == 0) {
                throw new Exception("A marca selecionada não existe no banco de dados");
            }
            
            // Validações específicas para tipo de dados
            if (!is_numeric($dados['ano']) || $dados['ano'] < 1900 || $dados['ano'] > date('Y') + 1) {
                throw new Exception("Ano inválido. Informe um ano entre 1900 e " . (date('Y') + 1));
            }
            
            if (isset($dados['preco']) && ($dados['preco'] === '' || !is_numeric($dados['preco']) || $dados['preco'] < 0)) {
                throw new Exception("O preço deve ser um valor numérico não negativo");
            }
            
            if (isset($dados['km']) && ($dados['km'] === '' || !is_numeric($dados['km']) || $dados['km'] < 0)) {
                throw new Exception("A quilometragem deve ser um valor numérico não negativo");
            }
            
            // Validação de placa se fornecida
            if (!empty($dados['placa'])) {
                $placaPadrao = '/^[A-Z]{3}[0-9]{4}$|^[A-Z]{3}[0-9]{1}[A-Z]{1}[0-9]{2}$/i';
                if (!preg_match($placaPadrao, $dados['placa'])) {
                    throw new Exception("Formato de placa inválido. Use o formato AAA0000 ou AAA0A00");
                }
                
                // Atualizar o final_placa apenas quando a placa for fornecida
                $dados['final_placa'] = (int)substr(trim($dados['placa']), -1);
            }
            
            // Validação do campo status
            $statusPermitidos = ['disponivel', 'reservado', 'vendido'];
            if (!empty($dados['status']) && !in_array($dados['status'], $statusPermitidos)) {
                throw new Exception("Status inválido. Valores permitidos: disponível, reservado ou vendido");
            }
            
            // Validação do campo portas
            if (!empty($dados['portas']) && (!is_numeric($dados['portas']) || $dados['portas'] < 2 || $dados['portas'] > 5)) {
                throw new Exception("Número de portas inválido. Deve ser entre 2 e 5");
            }
            
            // Validação do campo potencia (opcional)
            if (!empty($dados['potencia'])) {
                // Permite formatos como 1.0, 1.6, 2.0, V6, etc.
                if (strlen($dados['potencia']) > 20) {
                    throw new Exception("O campo potência deve ter no máximo 20 caracteres");
                }
            }
            
            $sql = "UPDATE carros SET 
                    modelo = ?,
                    marca_id = ?,
                    ano = ?,
                    preco = ?,
                    km = ?,
                    cambio = ?,
                    combustivel = ?,
                    cor = ?,
                    potencia = ?,
                    descricao = ?,
                    destaque = ?,
                    placa = ?,
                    versao = ?,
                    portas = ?,
                    final_placa = ?,
                    status = ?
                    WHERE id = ?";
                    
            $stmt = $this->db->prepare($sql);
            $resultado = $stmt->execute([
                $dados['modelo'],
                (int)$dados['marca_id'],
                (int)$dados['ano'],
                (float)$dados['preco'],
                (int)$dados['km'],
                $dados['cambio'] ?? 'Manual',
                $dados['combustivel'] ?? 'Gasolina',
                $dados['cor'] ?? '',
                $dados['potencia'] ?? '',
                $dados['descricao'] ?? '',
                isset($dados['destaque']) ? (int)$dados['destaque'] : 0,
                !empty($dados['placa']) ? strtoupper($dados['placa']) : null,
                !empty($dados['versao']) ? $dados['versao'] : null,
                !empty($dados['portas']) ? (int)$dados['portas'] : null,
                !empty($dados['final_placa']) ? (int)$dados['final_placa'] : null,
                $dados['status'] ?? 'disponivel',
                (int)$id
            ]);
            
            if (!$resultado) {
                throw new Exception("Erro ao atualizar o carro");
            }
            
            return true;
        } catch (Exception $e) {
            error_log('Erro ao atualizar carro ID ' . $id . ': ' . $e->getMessage());
            throw $e;
        }
    }

    public function verificarImagemValida($file) {
        // Verifica se houve erro no upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            switch($file['error']) {
                case UPLOAD_ERR_INI_SIZE:
                    throw new Exception("A imagem excede o tamanho máximo permitido pelo servidor");
                case UPLOAD_ERR_FORM_SIZE:
                    throw new Exception("A imagem excede o tamanho máximo permitido pelo formulário");
                case UPLOAD_ERR_PARTIAL:
                    throw new Exception("O upload da imagem foi interrompido");
                case UPLOAD_ERR_NO_FILE:
                    throw new Exception("Nenhum arquivo foi enviado");
                default:
                    throw new Exception("Erro desconhecido ao fazer upload da imagem");
            }
        }
        
        // Verifica o tamanho do arquivo (máximo 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            throw new Exception("A imagem excede o tamanho máximo permitido de 5MB");
        }
        
        // Verifica o tipo do arquivo
        $permitidos = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $permitidos)) {
            throw new Exception("Tipo de arquivo não permitido. Use apenas JPG, PNG ou GIF");
        }
        
        // Verifica as dimensões da imagem
        $info = getimagesize($file['tmp_name']);
        if (!$info) {
            throw new Exception("Arquivo inválido ou corrompido");
        }
        
        // Verifica as dimensões mínimas (ex: pelo menos 300x200)
        if ($info[0] < 300 || $info[1] < 200) {
            throw new Exception("A imagem deve ter pelo menos 300x200 pixels");
        }
        
        // Verificar a proporção da imagem (ideal entre 4:3 e 16:9)
        $ratio = $info[0] / $info[1];
        if ($ratio < 1.0 || $ratio > 2.0) {
            throw new Exception("A proporção da imagem deve estar entre 1:1 e 2:1 para melhor visualização");
        }
        
        return true;
    }

    public function buscarPorId($id) {
        try {
            // Validar ID
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("ID de carro inválido");
            }
            
            // Buscar informações básicas do carro
            $stmt = $this->db->prepare("SELECT carros.*, marcas.nome AS marca 
                                      FROM carros 
                                      JOIN marcas ON carros.marca_id = marcas.id 
                                      WHERE carros.id = ?");
            $stmt->execute([(int)$id]);
            $carro = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$carro) {
                throw new Exception("Carro não encontrado");
            }
            
            // Garantir valores padrão para colunas potencialmente nulas
            $carro['km'] = $carro['km'] ?? 0;
            $carro['preco'] = $carro['preco'] ?? 0.00;
            $carro['cor'] = $carro['cor'] ?? '';
            $carro['potencia'] = $carro['potencia'] ?? '';
            $carro['descricao'] = $carro['descricao'] ?? '';
            
            // Buscar imagens da galeria
            $stmt = $this->db->prepare("SELECT imagem FROM galeria_carros 
                                       WHERE carro_id = ? 
                                       ORDER BY ordem");
            $stmt->execute([(int)$id]);
            $galeria = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            $carro['galeria'] = $galeria;
            
            // Incrementar contador de visualizações
            $this->incrementarVisualizacao($id);
            
            return $carro;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar carro: " . $e->getMessage());
        }
    }
    
    private function incrementarVisualizacao($id) {
        try {
            $stmt = $this->db->prepare("UPDATE carros SET views = views + 1 WHERE id = ?");
            $stmt->execute([(int)$id]);
            return true;
        } catch (Exception $e) {
            error_log("Erro ao incrementar visualização: " . $e->getMessage());
            return false;
        }
    }

    public function adicionarGaleria($carroId, $imagem, $ordem) {
        try {
            // Verificar se o carro existe
            $stmt = $this->db->prepare("SELECT id FROM carros WHERE id = ?");
            $stmt->execute([(int)$carroId]);
            if (!$stmt->fetch()) {
                throw new Exception("Carro não encontrado");
            }
            
            $sql = "INSERT INTO galeria_carros (carro_id, imagem, ordem) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            
            $resultado = $stmt->execute([(int)$carroId, $imagem, (int)$ordem]);
            if (!$resultado) {
                throw new Exception("Erro ao adicionar imagem à galeria");
            }
            
            return true;
        } catch (Exception $e) {
            throw new Exception("Erro ao adicionar imagem à galeria: " . $e->getMessage());
        }
    }
    
    public function listarGaleria($carroId) {
        try {
            $sql = "SELECT * FROM galeria_carros WHERE carro_id = ? ORDER BY ordem";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([(int)$carroId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar galeria: " . $e->getMessage());
        }
    }

    public function listarMarcas() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM marcas ORDER BY nome");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar marcas: " . $e->getMessage());
        }
    }
    
    public function atualizarImagem($id, $imagem) {
        try {
            // Validar ID
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("ID de carro inválido");
            }
            
            // Primeiro busca a imagem antiga
            $stmt = $this->db->prepare("SELECT imagem FROM carros WHERE id = ?");
            $stmt->execute([(int)$id]);
            $carroAntigo = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$carroAntigo) {
                throw new Exception("Carro não encontrado");
            }
            
            // Remove a imagem antiga se existir
            if ($carroAntigo['imagem'] && file_exists(__DIR__ . '/../public/' . $carroAntigo['imagem'])) {
                unlink(__DIR__ . '/../public/' . $carroAntigo['imagem']);
            }
            
            // Atualiza com a nova imagem
            $stmt = $this->db->prepare("UPDATE carros SET imagem = ? WHERE id = ?");
            $resultado = $stmt->execute([$imagem, (int)$id]);
            
            if (!$resultado) {
                throw new Exception("Erro ao atualizar a imagem do carro");
            }
            
            return true;
        } catch (Exception $e) {
            throw new Exception("Erro ao atualizar imagem: " . $e->getMessage());
        }
    }

    public function excluirImagem($imagem) {
        if (!$imagem) {
            return true;
        }
        
        $caminhoCompleto = __DIR__ . '/../public/' . $imagem;
        if (file_exists($caminhoCompleto)) {
            return unlink($caminhoCompleto);
        }
        return true;
    }
    
    public function excluirGaleriaImagens($carroId) {
        try {
            // Validar ID
            if (!is_numeric($carroId) || $carroId <= 0) {
                throw new Exception("ID de carro inválido");
            }
            
            // Buscar todas as imagens da galeria
            $stmt = $this->db->prepare("SELECT imagem FROM galeria_carros WHERE carro_id = ?");
            $stmt->execute([(int)$carroId]);
            $imagens = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Excluir os arquivos físicos
            foreach ($imagens as $imagem) {
                $this->excluirImagem($imagem);
            }
            
            // Excluir registros do banco
            $stmt = $this->db->prepare("DELETE FROM galeria_carros WHERE carro_id = ?");
            $resultado = $stmt->execute([(int)$carroId]);
            
            if (!$resultado) {
                throw new Exception("Erro ao excluir imagens da galeria");
            }
            
            return true;
        } catch (Exception $e) {
            throw new Exception("Erro ao excluir galeria: " . $e->getMessage());
        }
    }
    
    public function excluirImagemDaGaleria($id) {
        try {
            // Validar ID
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("ID de imagem inválido");
            }
            
            // Buscar informações da imagem
            $stmt = $this->db->prepare("SELECT imagem FROM galeria_carros WHERE id = ?");
            $stmt->execute([(int)$id]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$item) {
                throw new Exception("Imagem não encontrada");
            }
            
            // Excluir arquivo físico
            $this->excluirImagem($item['imagem']);
            
            // Excluir registro do banco
            $stmt = $this->db->prepare("DELETE FROM galeria_carros WHERE id = ?");
            $resultado = $stmt->execute([(int)$id]);
            
            if (!$resultado) {
                throw new Exception("Erro ao excluir imagem da galeria");
            }
            
            return true;
        } catch (Exception $e) {
            throw new Exception("Erro ao excluir imagem: " . $e->getMessage());
        }
    }
    
    public function excluir($id) {
        try {
            $this->db->beginTransaction();
            
            // Validar ID
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("ID de carro inválido");
            }
            
            // Obter informações do carro
            $carro = $this->buscarPorId($id);
            
            // Excluir a imagem principal
            if ($carro['imagem']) {
                $this->excluirImagem($carro['imagem']);
            }
            
            // Excluir imagens da galeria
            $this->excluirGaleriaImagens($id);
            
            // Excluir registro do carro
            $stmt = $this->db->prepare("DELETE FROM carros WHERE id = ?");
            $resultado = $stmt->execute([(int)$id]);
            
            if (!$resultado) {
                throw new Exception("Erro ao excluir o carro");
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function alterarDestaque($id, $destaque) {
        try {
            // Validar ID
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("ID de carro inválido");
            }
            
            $stmt = $this->db->prepare("UPDATE carros SET destaque = ? WHERE id = ?");
            $resultado = $stmt->execute([(int)$destaque, (int)$id]);
            
            if (!$resultado) {
                throw new Exception("Erro ao alterar destaque do carro");
            }
            
            return true;
        } catch (Exception $e) {
            throw new Exception("Erro ao alterar destaque: " . $e->getMessage());
        }
    }
    
    public function reordenarGaleria($carroId, $novaOrdem) {
        try {
            $this->db->beginTransaction();
            
            foreach ($novaOrdem as $id => $ordem) {
                $stmt = $this->db->prepare("UPDATE galeria_carros SET ordem = ? WHERE id = ? AND carro_id = ?");
                $stmt->execute([(int)$ordem, (int)$id, (int)$carroId]);
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw new Exception("Erro ao reordenar galeria: " . $e->getMessage());
        }
    }
}
?>