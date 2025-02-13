# LuLibrary - Rede Social para Leitores

Bem-vindo ao **LuLibrary**, uma plataforma social projetada para conectar leitores apaixonados por livros. Com este projeto, você pode explorar novos títulos, acompanhar seu progresso de leitura, interagir com outros leitores e muito mais!

## 🚀 Funcionalidades Principais

- **Explorar e Gerenciar Livros**
  - Adicionar livros aos favoritos.
  - Marcar livros como lidos ou em andamento.
  - Acompanhar o progresso de leitura por meio de páginas lidas.

- **Interação Social**
  - Seguir outros leitores e acompanhar suas atividades.
  - Curtir e comentar em posts relacionados a livros.
  - Descobrir novas leituras com base nas interações da comunidade.

- **Perfis Personalizados**
  - Exiba seus livros favoritos e progresso de leitura.
  - Interaja com outros leitores por meio do feed social.

## 🛠️ Tecnologias Utilizadas

- **Back-End:** Laravel 10
- **Banco de Dados:** PostgreSQL
- **Testes:** PHPUnit com testes de unidade e de API
- **Autenticação:** Sanctum para tokens de acesso seguro
- **Outras Tecnologias:**
  - Factories e Seeders para geração de dados fictícios.
  - Eloquent ORM para manipulação de dados.

## 📂 Estrutura do Projeto

```
app/
├── Models/        # Modelos Eloquent
├── Http/
│   ├── Controllers/  # Controladores de API
│   ├── Requests/     # Validações de entrada
├── Repositories/  # Camada de abstração para acesso ao banco de dados
├── Services/      # Lógica de negócio

routes/
├── api.php        # Rotas da API

tests/
├── Feature/       # Testes de integração
├── Unit/          # Testes unitários
```

## 📜 Pré-requisitos

- PHP >= 8.1
- Composer
- PostgreSQL
- Node.js e NPM (opcional, se incluir front-end no futuro)

## 🛠️ Como Executar o Projeto

1. **Clone o repositório:**
   ```bash
   git clone https://github.com/seu-usuario/lulibrary.git
   cd lulibrary
   ```

2. **Instale as dependências:**
   ```bash
   composer install
   ```

3. **Configure o ambiente:**
   - Renomeie o arquivo `.env.example` para `.env`.
   - Configure as variáveis de ambiente, como o banco de dados e o Sanctum.

4. **Configure o banco de dados:**
   ```bash
   php artisan migrate --seed
   ```

5. **Gere a chave da aplicação:**
   ```bash
   php artisan key:generate
   ```

6. **Inicie o servidor de desenvolvimento:**
   ```bash
   php artisan serve
   ```

7. **Execute os testes (opcional):**
   ```bash
   php artisan test
   ```

## 🔍 Exemplos de Uso da API

### Autenticação
- **Obter Token de Acesso**
  ```http
  POST /api/login
  {
      "email": "user@example.com",
      "password": "senha123"
  }
  ```

### Gerenciar Livros
- **Adicionar Livro aos Favoritos**
  ```http
  POST /api/books/favorite
  Authorization: Bearer {token}
  {
      "book_id": 1
  }
  ```

- **Atualizar Progresso de Leitura**
  ```http
  PUT /api/books/read
  Authorization: Bearer {token}
  {
      "book_id": 1,
      "pages_read": 150
  }
  ```

### Social
- **Seguir Usuário**
  ```http
  POST /api/follow
  Authorization: Bearer {token}
  {
      "user_id": 2
  }
  ```

## 🤝 Contribuições

Contribuições são sempre bem-vindas! Se você deseja contribuir:

1. Fork o repositório.
2. Crie uma nova branch para sua feature ou correção.
   ```bash
   git checkout -b minha-nova-feature
   ```
3. Faça as alterações necessárias e commit.
   ```bash
   git commit -m "Adiciona nova funcionalidade X"
   ```
4. Envie para o repositório remoto.
   ```bash
   git push origin minha-nova-feature
   ```
5. Abra um Pull Request.

## 📝 Licença

Este projeto está licenciado sob a [MIT License](LICENSE). Sinta-se livre para usá-lo e adaptá-lo como preferir.

---

### 🌟 Mostre seu apoio!
Se você gostou deste projeto, deixe uma estrela ⭐ no repositório! Isso nos motiva a continuar melhorando. Obrigado!