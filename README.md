# Elite Motors - Sistema de Concessionária de Automóveis

Elite Motors é uma aplicação PHP abrangente baseada em MVC projetada para gerenciar um site de concessionária de automóveis. O sistema permite o gerenciamento eficiente do inventário de veículos, contas de usuários e fornece uma interface limpa para navegação e busca de carros.

![Elite Motors Screenshot](path/to/screenshot.png)

## Recursos

- 🚗 Sistema completo de gerenciamento de veículos
- 👤 Autenticação e autorização de usuários
- 🔍 Opções avançadas de busca e filtragem
- 📊 Painel administrativo com estatísticas
- 📱 Design responsivo para todos os dispositivos
- 🔄 Atualizações de status em tempo real para veículos
- 🖼️ Funcionalidade de upload de múltiplas imagens
- 🏷️ Sistema de gerenciamento de marcas

## Instalação

### Passos

1. **Clone o repositório**

```bash
git clone https://github.com/seuusuario/elite-motors.git
```

2. **Coloque no diretório do servidor web**

Se estiver usando XAMPP, coloque o repositório clonado na pasta `htdocs`:

```bash
# Para XAMPP
mv elite-motors C:/xampp/htdocs/mvc
```

3. **Configure o banco de dados**

- Abra o phpMyAdmin (geralmente em http://localhost/phpmyadmin)
- Crie um novo banco de dados chamado `carros_db`
- Importe o arquivo SQL de `carros_db(7).sql`

```bash
# Alternativamente, você pode usar a linha de comando:
mysql -u root -p carros_db < carros_db\(7\).sql
```

4. **Configuração**

- Se necessário, atualize as configurações de conexão com o banco de dados em `config/database.php`

5. **Acesse a aplicação**

Abra seu navegador e acesse:

```
http://localhost/mvc/public/
```

## Acesso Administrativo

Use estas credenciais para acessar o painel de administração:

- **URL**: http://localhost/mvc/public/index.php?url=auth/login
- **Email**: admin@exemplo.com
- **Senha**: admin123

## Conta de Usuário Regular

Para testar os recursos de usuário regular, você pode usar:

- **Email**: brunicks02@gmail.com
- **Senha**: 123

Ou crie uma nova conta através da página de registro.

## Estrutura do Projeto

```
mvc/
├── config/            # Arquivos de configuração
├── controllers/       # Classes controladoras
├── models/            # Classes de modelos
├── public/            # Arquivos acessíveis publicamente
│   ├── index.php      # Ponto de entrada
│   ├── style.css      # Folha de estilo principal
│   └── uploads/       # Arquivos enviados
├── views/             # Templates de visualização
│   ├── admin/         # Visualizações específicas do admin
│   ├── header.php     # Cabeçalho comum
│   └── footer.php     # Rodapé comum
├── Dispatcher.php     # Direciona requisições para controladores
└── README.md          # Documentação do projeto
```

## Recursos em Detalhe

### Gerenciamento de Carros

- Adicionar, editar e excluir anúncios de carros
- Upload de múltiplas imagens para cada carro
- Definir status (disponível, reservado, vendido)
- Destaque de veículos em destaque na página inicial

### Gerenciamento de Usuários

- Registro e autenticação
- Funções de administrador e usuário regular
- Gerenciamento de perfil de usuário
- Controle de status da conta

### Busca e Filtro

- Busca por marca, modelo, ano e preço
- Opções avançadas de filtragem
- Classificar resultados por diferentes critérios

## Tecnologias Utilizadas

- PHP (Arquitetura MVC)
- MySQL
- Bootstrap 5
- JavaScript/jQuery
- Font Awesome
- DataTables
