# API da Pastelaria

API simples de uma pastelaria fictícia construída em MySQL, Laravel e NGINX para teste de conhecimento.

# Utilização

O projeto roda em docker, utilizando o docker-compose. Por trás de tudo alguns scripts configuram o Laravel para que o projeto já saia rodando.

## Atenção ao clonar o projeto

A imagem do servidor foi preparada em outro repositório para facilitar a entrega do projeto.

**Após o clone do repositório, atualize os submódulos:**

`git submodule update --init --recursive submodules/docker;`

Feito isso, execute deixe o docker trabalhar:

`docker-compose up --force-recreate;`

## Configurações Manuais

O projeto dispara e-mail ao receber um pedido, para isso, você deve adicionar as credenciais SMTP no arquivo .env. Recomendo você usar o MailTrap para efeito de testes.

## Resolução de problemas

Caso algo dê errado e você precise seguir os passos novamente, exclua os volumes preparados para evitar um comportamento indesejado:

`docker volume prune --filter label=pastelaria.project.name=PASTELARIA -f;`

E remova o arquivo **CONTAINER_ALREADY_RAN_ONCE** de dentro da pasta ***www**.


# Estruturas implementadas

Boa parte dos recursos do Laravel foram utilizados.

O projeto possui uma API RESTFUL que é montada através dos Resources Controllers do Laravel.

A camada de controller despacha a responsabilidade da lógica do negócio para a camada de Services, com classes divididas por entidades que possuem funções específicas.

As entidades da API utilizam UUID como forma de não mostrar o ID primário das tabelas, para isso, utilizei os Observers que atuam antes da criação das models e definem o valor do UUID daquele cadastro que está sendo criado.

Além disso, para a validação e parseamento do upload de imagens foi construída a Rule 'EncodeImage'. As imagens dos pasteis são recebidas em base64 e convertidas para arquivos no disco.

As respostas das requisições são montadas através de API Resources, que formatam um padrão de resposta em JSON e oferecem controle sobre os atributos entregues.

Foi utilizada o disparo de e-mail instantâneo, sem fila, após o recebimento de uma Order. Para isso, as Mailables foram utilizadas.

Somente a model Client possui função de atualização.


## Testes

O projeto não possui teste automatizado, abaixo segue o link para uma coleção do Postman onde você poderá utilzar para consumir a API.

https://www.getpostman.com/collections/bb35c81398308b7bda8a
