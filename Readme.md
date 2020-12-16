# Gerencianet PIX SDK

[![N|Solid](https://cldup.com/dTxpPi9lDf.thumb.png)](https://nodesource.com/products/nsolid)

[![Build Status](https://travis-ci.org/joemccann/dillinger.svg?branch=develop)](https://travis-ci.org/joemccann/dillinger)

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
$pixCharge = new Gerencianet\Pix\Charge(env('GERENCIA_NET_ENVIROMENT', 'homolog'));
$pixCharge = $pixCharge->setCepDebtor('39900000')
    ->setCityDebtor('Almenara')
    ->setFreeValue(false)
    ->setNameDebtor('Levi Costa')
    ->setCpfCnpjDebtor('12259415636')
    ->setValue(0.01)
    ->setType('estatico')
    ->setDescriptionService('Teste de descrição')
    ->setDimenQrCoode(256)
    ->setUniquePay(true)
    ->setExpiresTimeQrCode(3600)
    ->setKeyPix($authData['accessToken']);
return $pixCharge->create();
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
