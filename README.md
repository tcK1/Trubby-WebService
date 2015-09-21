# Trubby-WebService
Software de WebService para a plataforma Trubby.

### Build e execução do projeto

- Requisitos:
  - Maven;
  - Ant;
  - Eclipse;
  - Tomcat 7.x;
  - MySQL 5.x.

1. Fazer o download do projeto.
2. Dentro do diretório raiz do projeto (onde estão os arquivos pom.xml e build.xml) executar os seguintes comandos:
```
  - mvn eclipse:clean
  - mvn eclipse:eclipse
```
3. Importar o projeto no Eclipse (File -> Import -> Existing Maven Projects). No campo "Root Directory" escolha o diretório raiz do projeto.
4. Entre as linhas 82 e 85 do arquivo "pom.xml" (diretório raiz do projeto), modifique os valores nas tags "url", "username" e "password" conforme as configurações do Tomcat:
```
<url>http://localhost:8080/manager/text</url>
<path>/${project.build.finalName}</path>
<username>username</username>
<password>password</password>
```
4. Abra a janela do Ant dentro do Eclipse (Windows -> Show View -> Other... -> Ant) e arraste o arquivo "build.xml" para essa janela (o arquivo está dentro do diretório raiz do projeto no Eclipse).
5. Feito isso, uma lista de comando irá aparecer. Se o Tomcat já estiver configurado, execute o comando "start_tomcat" (duplo clique).
6. Após iniciar o Tomcat, faça o deploy do projeto executando da mesma maneira o comando "deploy".

### Configurando o Tomcat 7.x

1. Faça o download da versão 7 do Tomcat (http://tomcat.apache.org/download-70.cgi) e extraia o conteúdo do .zip.
2. Crie as variáveis de ambiente "CATALINA\_HOME" e "CATALINA\_BASE". O valor deve ser o caminho para o diretório do Tomcat.
3. Dentro do diretório raiz do Tomcat, abra o arquivo tomcat-users.xml dentro do diretório "conf" (/conf/tomcat-users.xml). O arquivo deve ficar da seguinte forma:
```  
  <?xml version='1.0' encoding='utf-8'?>
    <role rolename="manager-gui"/>
    <role rolename="manager-script"/>
    <user username="username" password="password" roles="manager-gui,manager-script"/>
  </tomcat-users>
```
4. Para iniciar o Tomcat, dentro do diretório "bin" digite: catalina.bat run (Windows) ou ./catalina.sh run (Linux).
5. Para parar o Tomcat, dentro do diretório "bin" digite: catalina.bat stop (Windows) ou ./catalina.sh stop (Linux).

### Serviços disponíveis (WebService)

1. **Consulta de estoque por usuário:**
  - **URL:** http://localhost:8080/trubby/estoque/{idUsuario}
  - **Exemplo:** http://localhost:8080/trubby/estoque/0
  - **Tipo de requisição HTTP:** GET
  - **O que faz?:** Retorna a lista de todos os itens no estoque do usuário {idUsuario}.
  - **Recebe:** ID do usuário (deve ser um número).
  - **Retorna:** Lista de itens do estoque (em JSON).
  - **Foi testado?:** Sim.
  - **Obs.:** O serviço não vai funcionar caso exista algum registro na tabela de estoque em que o tipo da quantidade seja NULL.

2. **Exclusão de usuário:**
  - **URL:** http://localhost:8080/trubby/usuarios/{idUsuario}
  - **Exemplo:** http://localhost:8080/trubby/usuarios/14
  - **Tipo de requisição HTTP:** DELETE
  - **O que faz?:** Exclui o usuário de ID {idUsuario}.
  - **Recebe:** ID do usuário (deve ser um número).
  - **Retorna:** N/A.
  - **Exemplo:** http://localhost:8080/trubby/usuarios/14
  - **Foi testado?:** Sim.
  - **Obs.:** N/A.
