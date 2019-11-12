### BACKEND PHP - PROJETO API REST
Você foi convidado para desenvolver a API de um projeto. Para isso, foi escrito uma
pequena documentação das funcionalidades necessárias no projeto. Os desenvolvedores frontend usarão a sua API para criar um aplicativo pessoal para monitorar quantas vezes o usuário bebeu água.

**Observações de implementação:**
- O projeto deve ser desenvolvido em PHP e com banco de dados relacional
- Não deve ser utilizado nenhum framework (Laravel, Slim framework, Doctrine, etc.)
- Todas as entradas e saídas devem ser no formato JSON
- Se possível, a API deve seguir o padrão REST
- É desejável que o código use o método Programação Orientada a Objetos
- Projetos plagiados serão desconsiderados

**Endpoints desejáveis:**

| Operação  |  | Entrada  | Saída  | Header  |
| ------------ | ------------ | ------------ | ------------ | ------------ |
| **/users/**<br>(criar um novo usuário) | <span style="color:green">**POST**</span>  | email \*<br>name \*<br>password \*  |   |   |
| **/login**<br>(autenticar com um usuário)  |  <span style="color:green">**POST**</span> | email \*<br>password \*  | **token**<br>iduser<br>email<br>name<br>drink_counter |   |
|**/users/:iduser**<br>(obter um usuário) | <span style="color:blue">**GET**</span>  |  | iduser<br>name<br>email<br>drink_counter | token \* |
| **/users/**<br>(obter a lista de usuários)  | <span style="color:blue">**GET**</span>  |   | (array de usuários)  | token \*  |
|**/users/:iduser**<br>(editar o seu próprio usuário)  | <span style="color:orange">**PUT**</span> | email<br>name<br>password  |   | token \*  |
|**/users/:iduser**<br>(apagar o seu próprio usuário)  |   <span style="color:red">**DELETE**</span> |   |   | token \*  |
| **/users/:iduser/drink**<br>(incrementar o contador de quantas vezes bebeu água)  | <span style="color:green">**POST**</span> | drink_ml (int)  |  iduser<br>email<br>name<br>drink_counter | token \*  |
| **/users/:iduser/history**<br>(lista o histórico de registros de um usuário)  | <span style="color:blue">**GET**</span> |  | (array com todos registro que o usuário bebeu água) | token \*  |
| **/users/ranking**<br>(lista o ranking do usuário que mais bebeu água hoje)  | <span style="color:blue">**GET**</span> |  | (array ranking de usuários que mais beberam água) | token \*  |

\* Campos obrigatórios


**Tratamentos desejáveis:**
- Na criação de um usuário, retornar um erro se o usuário já existe
- No login, alertar que o usuário não existe ou que a senha está inválida
- Na edição e na remoção do usuário, limitar-se apenas ao usuário autenticado

**Tratamentos opcionais:**
- Paginação na lista de usuários
- Criar um serviço que liste o histórico de registros de um usuário (retornando a data e a quantidade em mL de cada registro)
- Criar um serviço que liste o ranking do usuário que mais bebeu água hoje (considerando os ml e não a quantidade de vezes), retornando o nome e a quantidade em mililitros (mL)