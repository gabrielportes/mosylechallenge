### BACKEND PHP - PROJETO API REST
Voc� foi convidado para desenvolver a API de um projeto. Para isso, foi escrito uma
pequena documenta��o das funcionalidades necess�rias no projeto. Os desenvolvedores frontend usar�o a sua API para criar um aplicativo pessoal para monitorar quantas vezes o usu�rio bebeu �gua.

**Observa��es de implementa��o:**
- O projeto deve ser desenvolvido em PHP e com banco de dados relacional
- N�o deve ser utilizado nenhum framework (Laravel, Slim framework, Doctrine, etc.)
- Todas as entradas e sa�das devem ser no formato JSON
- Se poss�vel, a API deve seguir o padr�o REST
- � desej�vel que o c�digo use o m�todo Programa��o Orientada a Objetos
- Projetos plagiados ser�o desconsiderados

**Endpoints desej�veis:**

| Opera��o  |  | Entrada  | Sa�da  | Header  |
| ------------ | ------------ | ------------ | ------------ | ------------ |
| **/users/**<br>(criar um novo usu�rio) | <span style="color:green">**POST**</span>  | email \*<br>name \*<br>password \*  |   |   |
| **/login**<br>(autenticar com um usu�rio)  |  <span style="color:green">**POST**</span> | email \*<br>password \*  | **token**<br>iduser<br>email<br>name<br>drink_counter |   |
|**/users/:iduser**<br>(obter um usu�rio) | <span style="color:blue">**GET**</span>  |  | iduser<br>name<br>email<br>drink_counter | token \* |
| **/users/**<br>(obter a lista de usu�rios)  | <span style="color:blue">**GET**</span>  |   | (array de usu�rios)  | token \*  |
|**/users/:iduser**<br>(editar o seu pr�prio usu�rio)  | <span style="color:orange">**PUT**</span> | email<br>name<br>password  |   | token \*  |
|**/users/:iduser**<br>(apagar o seu pr�prio usu�rio)  |   <span style="color:red">**DELETE**</span> |   |   | token \*  |
| **/users/:iduser/drink**<br>(incrementar o contador de quantas vezes bebeu �gua)  | <span style="color:green">**POST**</span> | drink_ml (int)  |  iduser<br>email<br>name<br>drink_counter | token \*  |
| **/users/:iduser/history**<br>(lista o hist�rico de registros de um usu�rio)  | <span style="color:blue">**GET**</span> |  | (array com todos registro que o usu�rio bebeu �gua) | token \*  |
| **/users/ranking**<br>(lista o ranking do usu�rio que mais bebeu �gua hoje)  | <span style="color:blue">**GET**</span> |  | (array ranking de usu�rios que mais beberam �gua) | token \*  |

\* Campos obrigat�rios


**Tratamentos desej�veis:**
- Na cria��o de um usu�rio, retornar um erro se o usu�rio j� existe
- No login, alertar que o usu�rio n�o existe ou que a senha est� inv�lida
- Na edi��o e na remo��o do usu�rio, limitar-se apenas ao usu�rio autenticado

**Tratamentos opcionais:**
- Pagina��o na lista de usu�rios
- Criar um servi�o que liste o hist�rico de registros de um usu�rio (retornando a data e a quantidade em mL de cada registro)
- Criar um servi�o que liste o ranking do usu�rio que mais bebeu �gua hoje (considerando os ml e n�o a quantidade de vezes), retornando o nome e a quantidade em mililitros (mL)