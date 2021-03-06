# Gerencianet PIX SDK

[![Latest Stable Version](https://poser.pugx.org/levicosta201/gerencianet-pix-sdk/v)](//packagist.org/packages/levicosta201/gerencianet-pix-sdk) [![Total Downloads](https://poser.pugx.org/levicosta201/gerencianet-pix-sdk/downloads)](//packagist.org/packages/levicosta201/gerencianet-pix-sdk) [![Latest Unstable Version](https://poser.pugx.org/levicosta201/gerencianet-pix-sdk/v/unstable)](//packagist.org/packages/levicosta201/gerencianet-pix-sdk) [![License](https://poser.pugx.org/levicosta201/gerencianet-pix-sdk/license)](//packagist.org/packages/levicosta201/gerencianet-pix-sdk)

Essa API foi desenvolvida para facilitar a integração com a API do PIX da [Gerencianet][gerencianet-pix-link]. Você pode isntalar em qualquer projeto via composer.

# New Features!

  - Autenticação
  - Geração de cobrança

> A medida que forem desenvolvidas novas features iremos adicionando aqui neste documento, a API será atualizada constantemente até que seja realizado a comunicação com todas as partes da API.

### Requisítos Mínimos

Para utilizar este SDK você precisará de no mínimo:

* [PHP] - php: >=7.3
* [CURL] - Latest Version
* [COMPOSER] - Latest Version

### Instalação

A instalação deste SDK é ralmente muito simples e você poderá fazer isso apenas com o comando abaixo.

```sh
$ composer require levicosta201/gerencianet-pix-sdk
```

### Utilização

Utilizar este SDK também foi facilitado. Sabemos que o SDK da GN é simples de utilizar, porém foi feita uma organização para melhor compreesão e facilidade da emissão do PIX. Veja o exemplo abaixo:

### Autenticação:
Antes de efetuar qualquer transação na API PIX da GN é preciso que você receba um token de autenticação, este token pode ser obtido por meio de ticket da própria GN.

```php
//Aqui são aceitos dois parâmetros em forma de string, homolog ou production
$connect = new Gerencianet\Pix\Connect('production');
//Aqui você você precisa informar apenas o caminho do seu certificado .pem fornecido pela GN
$connect = $connect->setCertFile('PATH_TO_CERT_FILE')
    ->setClientId(env('GERENCIA_NET_CLIENT_ID'))
    ->setClientSecret(env('GERENCIA_NET_CLIENT_SECRET'));
return $connect->proccess();
```
A solicitação acima irá retornar um array com os seguintes dados:
```php
[▼
  "data" => array:2 [▼
    "accessToken" => "AQUI_IRA_VIR_SEU_TOKEN_DE_ACESSO ▶"
    "tokenType" => "Bearer"
  ]
]
```

### Cobrança:
Após a autenticação você já possui o necessário para realizar uma cobrança, que poderá ser realizada da seguinte maneira:
```php
$pixCharge = new Gerencianet\Pix\Charge('production');
$pixCharge = $pixCharge->setCepDebtor('12300999')
    ->setCityDebtor('CIDADE_DE_QUEM_PAGA')
    ->setFreeValue(false)
    ->setNameDebtor('Nome de quem paga')
    ->setCpfCnpjDebtor('CPF_DEQUEM_PAGA')
    ->setValue(10.0)
    ->setType('estatico')
    ->setDescriptionService('Teste de descrição')
    ->setDimenQrCoode(256)
    ->setUniquePay(true)
    ->setExpiresTimeQrCode(3600)
    ->setAccessToken($authData['accessToken'])
    ->setTokenType('TIPO_TOKEN_RETORNO_API')
    ->setKeyPix('SUA_CHAVE_PIX_GN');
return $pixCharge->create();
```
Parâmetros

| Função | Tipo | Descrição |
| ----- | ---- | --------- |
| setNameDebtor() | String | Deverá ser informado o nome de quem está REALIZANDO O PAGAMENTO do Pix |
| setCpfCnpjDebtor() | String | Deverá ser informado o CPF/CNPJ de quem está REALIZANDO O PAGAMENTO do Pix. Sem pontos e sem traços |
| setCepDebtor() | String | Deverá ser informado o CEP de quem está REALIZANDO O PAGAMENTO do Pix |
| setCityDebtor() | String | Deverá ser informado a Cidade de quem está REALIZANDO O PAGAMENTO do Pix |
| setFreeValue() | Bool | Deverá ser informado se o valor do pagamento é livre ou não | 
| setValue() | Float | Deverá ser informado o valor da cobrança do Pix | 
| setType() | Bool | Deverá ser informado se o QR Code será dinâmico ou estático | 
| setDescriptionService() | String | Deverá ser informado a descrição da cobrança | 
| setDimenQrCoode() | Integer | Deverá ser informado o tamanho da imagem do QR Code, o tamanho máximo é 256px | 
| setUniquePay() | Bool | Deverá ser informado se o QR Code poderá ser reutilizado |
| setExpiresTimeQrCode() | Integer | Deverá ser informado o tempo de expiração do QR Code em Milisegundos |
| setKeyPix() | String | Deverá informar a sua chave PIX cadastrada no GN sem pontos e sem traços | 
| setTokenType() | String | Deverá informar o tipo de token que a API retorna | 
| create() | Class | Executa a cobrança e retorna o array com os dados da cobrança | 

Caso tenha sucesso na execução acima você deverá receber o seguinte retorno em forma de array
```php
array:4 [▼
  "success" => "true"
  "barCode" => "CODIGO_DE_BARRAS"
  "qrCodeBase64" => "iVBORw0KGgoAAAANSUhEUgAAAQAAAAEAAQMAAABmvDolAAAABlB"
  "pixData" => array:7 [▼
    "calendario" => array:1 [▼
      "expiracao" => "3600"
    ]
    "devedor" => array:2 [▼
      "cpf" => "CPF_PAGADOR"
      "nome" => "NOME_PAGADOR"
    ]
    "valor" => array:1 [▼
      "original" => 0.01
    ]
    "chave" => "28656307000164"
    "solicitacaoPagador" => "Teste de descrição"
    "txid" => "5m6KHjHtDP2a 5Yva30gWjMrA"
  ]
]
```

### Listagem de Pix:
Após realizar a sua cobrança, você poderá listar e visualizar as cobranças recebidas, para isso faça da seguinte forma:

```php
$authData = PixService::auth();
$issuedPix = new Issued();
$issuedPix = $issuedPix->setInitDate("2020-12-09" . "T00:00:00Z")
    ->setEndDate("2021-12-12" . "T00:00:00Z")
    ->setCertFile(self::getCertFile())
    ->setTokenType($authData['tokenType'])
    ->setAccessToken($authData['accessToken']);

return $issuedPix->list();
```

Parâmetros

| Função | Tipo | Descrição |
| ----- | ---- | --------- |
| setInitDate() | DateTime | Deverá ser informado para saber qual o início do período da busca | 
| setEndDate() | DateTime | Deverá ser informado para saber qual o fim do período da busca |
| setFilterCpf() | String | Deverá ser informado para filtrar PIX por um CPF específico |
| setFilterCnpj() | String | Deverá ser informado para filtrar PIX por um CNPJ específico |
| setPaginate() | Integer | Deverá ser informado para filtrar a paginação da Lista do PIX |
| setItensPerPage() | Integer | Deverá ser informado para o retorno de quantos PIX serão exibidos por página |

Caso tenha sucesso na requisição você receberá o seguinte array:

```php
array:2 [▼
  "parametros" => array:3 [▼
    "inicio" => "2020-12-09T00:00:00Z"
    "fim" => "2021-12-12T00:00:00Z"
    "paginacao" => array:4 [▼
      "paginaAtual" => 0
      "itensPorPagina" => 100
      "quantidadeDePaginas" => 1
      "quantidadeTotalDeItens" => 1
    ]
  ]
  "pix" => array:1 [▼
    0 => array:4 [▼
      "endToEndId" => "ABCDEFGR020121414PVXK1"
      "valor" => "0.01"
      "horario" => "2020-12-14T14:24:19.000Z"
      "infoPagador" => "API"
    ]
  ]
]
```
### Todos

 - Lis PIX
 - List PIX Cob
 - Pix Webhook
 - Cancel PIX

License
----

MIT

**Free Software, Hell Yeah!**

[//]: # (These are reference links used in the body of this note and get stripped out when the markdown processor does its job. There is no need to format nicely because it shouldn't be seen. Thanks SO - http://stackoverflow.com/questions/4823468/store-comments-in-markdown-syntax)

   [gerencianet-pix-link]: <https://gerencianet.com.br/pix/>
   [dill]: <https://github.com/joemccann/dillinger>
   [git-repo-url]: <https://github.com/joemccann/dillinger.git>
   [john gruber]: <http://daringfireball.net>
   [df1]: <http://daringfireball.net/projects/markdown/>
   [markdown-it]: <https://github.com/markdown-it/markdown-it>
   [Ace Editor]: <http://ace.ajax.org>
   [node.js]: <http://nodejs.org>
   [Twitter Bootstrap]: <http://twitter.github.com/bootstrap/>
   [jQuery]: <http://jquery.com>
   [@tjholowaychuk]: <http://twitter.com/tjholowaychuk>
   [express]: <http://expressjs.com>
   [AngularJS]: <http://angularjs.org>
   [Gulp]: <http://gulpjs.com>

   [PlDb]: <https://github.com/joemccann/dillinger/tree/master/plugins/dropbox/README.md>
   [PlGh]: <https://github.com/joemccann/dillinger/tree/master/plugins/github/README.md>
   [PlGd]: <https://github.com/joemccann/dillinger/tree/master/plugins/googledrive/README.md>
   [PlOd]: <https://github.com/joemccann/dillinger/tree/master/plugins/onedrive/README.md>
   [PlMe]: <https://github.com/joemccann/dillinger/tree/master/plugins/medium/README.md>
   [PlGa]: <https://github.com/RahulHP/dillinger/blob/master/plugins/googleanalytics/README.md>
