<?php

namespace Gerencianet\Pix;

class Helper
{
    public static function getTaxId(string $type): string
    {
        $quantity = ($type === "dinamico") ? 35 : 25;

        for (
            $token = '', $i = 0, $z = strlen($a = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz 0123456789') - 1;
            $i != $quantity;
            $x = rand(0, $z), $token .= $a{
        $x}, $i++
        );

        return $token;
    }

    public static function mountBody($keyPix, $cpf, $nome, $value, $serviceDefine, $expiresTime)
    {
        return [
            "calendario" => [
                "expiracao" => $expiresTime // [opcional] Tempo de vida da cobrança, especificado em segundos a partir da data de criação. Caso não definido o padrão será de 86400 segundos ( 24 horas)
            ],
            "devedor" => [
                "cpf" => $cpf, // [opcional] CPF do usuário pagador.string /^\d{11}$/
                "nome" => $nome // [opcional] Nome do usuário pagador. Máximo: 25 caracteres.
            ],
            "valor" => [
                "original" => $value // [obrigatório] Valor original da cobrança.string \d{1,10}.\d{2} Obs: Para QR Code dinâmico, valor mínimo é de 0.01. Para QR Code poderá ser 0.00 (Ficará aberto para o pagador definir o valor)
            ],
            "chave" => $keyPix, // [obrigatório] Determina a chave Pix registrada no DICT que será utilizada para a cobrança.
            "solicitacaoPagador" => $serviceDefine, // [opcional] determina um texto a ser apresentado ao pagador para que ele possa digitar uma informação correlata, em formato livre, a ser enviada ao recebedor.
            "infoAdicionais" => [ // [opcional] Cada respectiva informação adicional contida na lista (nome e valor) deve ser apresentada ao pagador. Campo presente somente no QR Code dinâmico
                [
                    "nome" => "Campo 1", // Nome do campo string (Nome) ≤ 50 characters
                    "valor" => "Informação Adicional1 do PSP-Recebedor" // Dados do campo string (Valor) ≤ 200 characters
                ],
                [
                    "nome" => "Campo 2",
                    "valor" => "Informação Adicional2 do PSP-Recebedor"
                ]
            ]
        ];
    }

    public static function createBarCode($dadosPix, $tipo, $pagoUmaVez, $nomeRecebedor, $cidade, $cep, $valorLivre, $tamanhoQrCode)
    {
        // Rotina montará a variável que correspondente ao payload no padrão EMV-QRCPS-MPM
        $payload_format_indicator = '01';
        $point_of_initiation_method = '12';
        $merchant_account_information = '00' . self::completeInput('BR.GOV.BCB.PIX');
        $merchant_category_code = '0000';
        $transaction_currency = '986';
        $country_code = 'BR';

        $payloadBrCode = "00" . self::completeInput($payload_format_indicator); // [obrigatório] Payload Format Indicator, valor fixo: 01

        if ($pagoUmaVez) { // Se o QR Code for para pagamento único (só puder ser utilizado uma vez), a variável $pagoUmaVez deverá ser true
            $payloadBrCode .= "01" . self::completeInput($point_of_initiation_method); // [opcional] Point of Initiation Method Se o valor 12 estiver presente, significa que o BR Code só pode ser utilizado uma vez.
        }

        if ($tipo === "dinamico") {
            $location = str_replace("https://", "", $dadosPix["loc"]["location"]); // [obrigatório] URL payload do PSP do recebedor que contém as informações da cobrança
            $merchant_account_information .= '25' . self::completeInput($location);
        } else { // Caso seja estático
            $merchant_account_information .= '01' . self::completeInput($dadosPix["chave"]); //Chave do destinatário do pix, pode ser EVP, e-mail, CPF ou CNPJ.
        }
        $payloadBrCode .= '26' .  self::completeInput($merchant_account_information); // [obrigatório] Indica arranjo específico; “00” (GUI) e valor fixo: br.gov.bcb.pix

        $payloadBrCode .= '52' . self::completeInput($merchant_category_code); // [obrigatório] Merchant Category Code “0000” ou MCC ISO18245

        $payloadBrCode .= '53' . self::completeInput($transaction_currency); // [obrigatório] Moeda, “986” = BRL: real brasileiro - ISO4217

        $payloadBrCode .= '54';  // [opcional] Valor da transação. Utilizar o . como separador decimal.
        $payloadBrCode .= ($valorLivre === true) ? self::completeInput('0.00') : self::completeInput($dadosPix["valor"]["original"]) ;

        $payloadBrCode .= '58' . self::completeInput($country_code); // [obrigatório] “BR” – Código de país ISO3166-1 alpha 2

        $payloadBrCode .= '59';
        $payloadBrCode .= self::completeInput($nomeRecebedor); // [obrigatório] Nome do beneficiário/recebedor. Máximo: 25 caracteres.

        $payloadBrCode .= '60' . self::completeInput($cidade); // [obrigatório] Nome cidade onde é efetuada a transação. Máximo 15 caracteres.

        $payloadBrCode .= '61' . self::completeInput($cep); // [opcional] CEP da cidade onde é efetuada a transação.

        $txID = ($tipo === "dinamico") ? '***' : $dadosPix["txid"]; // [opcional] Identificador da transação.
        $aditional_data_field_template = '05' . self::completeInput($txID);
        $payloadBrCode .= '62' . self::completeInput($aditional_data_field_template);


        $payloadBrCode .= "6304"; // Adiciona o campo do CRC no fim da linha do pix.

        $payloadBrCode .= self::calculaChecksum($payloadBrCode); // Calcula o checksum CRC16 e acrescenta ao final.

        $imageString = self::qrCodeGenerator($payloadBrCode, $tamanhoQrCode);

        return [
            'success' => 'true',
            'barCode' => $payloadBrCode,
            'qrCodeBase64' => $imageString,

        ];
    }

    private static function completeInput($value)
    {
        /*
         * Esta função retorna a string preenchendo com 0 na esquerda, com tamanho o especificado, concatenando com o valor do campo
         */
        return str_pad(strlen($value), 2, '0', STR_PAD_LEFT) . $value;
    }
}
