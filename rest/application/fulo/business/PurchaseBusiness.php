<?php

/*
 * Copyright (C) 2014
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace fulo\business;

use fulo\model\PurchaseModel as PurchaseModel;

/**
 * Class of business for Purchase
 * @name ProductBusiness
 * @author Victor Eduardo Barreto
 * @package fulo\business
 * @date Dec 10, 2015
 * @version 1.0
 */
class PurchaseBusiness extends MasterBusiness
{

    /**
     * variable for instance of product model
     * @var object
     */
    private $_purchaseModel;

    /**
     * Method constructor of class
     * @name __construct
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @return object Model of product
     * @date Alg 18, 2015
     * @version 1.0
     */
    public function __construct ()
    {
        $this->_purchaseModel = new PurchaseModel();
    }

    /**
     * Method for business add item in wish list
     * @name addWishList
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @return bool Result of procedure
     * @date Alg 28, 2015
     * @version 1.0
     */
    public function addWishList ()
    {

        try {

            $data = $this->getRequestData();

            return $this->_purchaseModel->addWishList($data);
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for business get items of wish list
     * @name getWishList
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @return object Data of user wishlist
     * @date Alg 29, 2015
     * @version 1.0
     */
    public function getWishList ()
    {

        try {

            $data = $this->getRequestData();

            $results = $this->_purchaseModel->getWishList($data);

            foreach ($results as $key) {

                $this->makeImageOut($key->im_product_image, 300, 300);
            }

            return $results;
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for business del item of wish list
     * @name delWishList
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @return bool Result of procedure
     * @date Alg 31, 2015
     * @version 1.0
     */
    public function delWishList ()
    {

        try {

            $data = $this->getRequestData();

            return $this->_purchaseModel->delWishList($data);
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for get fare value
     * @name getFareValue
     * @author Victor Eduardo Barreto
     * @param object $data Data
     * @package fulo\business
     * @return object Data of product type
     * @date Oct 10, 2015
     * @version 1.0
     */
    public function getFareValue ($data = null)
    {

        try {

            # verify if data came from inside or outside.
            if (empty($data)) {

                $data = $this->getRequestData();
            }

            # init variables;
            $data->nu_length = 0;
            $data->nu_width = 0;
            $data->nu_height = 0;
            $data->nu_weight = 0;
            $data->nu_packages = 0;

            $products = $this->_purchaseModel->getDataProducts($data);

            # verifica se os produtos são menores que os valores minimos.
            foreach ($products as $key) {

                # verifica se é dobravel?
                if ($key->st_foldable) {

                    # verifica se é maior que o recomendado.
                    # comprimento.
                    while ($key->nu_length > BOX_DELIVERY_BEST_LENGTH) {

                        # dobra o produto.
                        $key->nu_length = $key->nu_length / 2;

                        # soma a altura do produto.
                        $key->nu_height = $key->nu_height * 2;
                    }

                    # largura.
                    while ($key->nu_width > BOX_DELIVERY_BEST_WIDTH) {

                        # dobra o produto.
                        $key->nu_width = $key->nu_width / 2;

                        # soma a altura do produto.
                        $key->nu_height = $key->nu_height * 2;
                    }
                }

                # verifica se é menor que os minimos.
                #comprimento.
                if ($key->nu_length < BOX_DELIVERY_MIN_LENGTH) {

                    $key->nu_length = BOX_DELIVERY_MIN_LENGTH;
                }

                #largura.
                if ($key->nu_width < BOX_DELIVERY_MIN_WIDTH) {

                    $key->nu_width = BOX_DELIVERY_MIN_WIDTH;
                }
            }

            # soma o tamanho de todos os produtos para definir o tamanho do pacote.
            foreach ($products as $key) {

                # verifica o produto com o maior comprimento.
                if ($key->nu_length > $data->nu_length) {

                    $data->nu_length = $key->nu_length;
                }

                # verifica o produto com a maior largura.
                if ($key->nu_width > $data->nu_width) {

                    $data->nu_width = $key->nu_width;
                }

                # percorre o array dos produtos do carrinho para multiplicar peso e altura pela quantidade.
                foreach ($data->product as $key_prod => $value) {

                    if ($key->sq_product == $value->sq_product) {

                        $key->nu_height = $key->nu_height * $value->nu_quantity_buy;
                        $key->nu_weight = $key->nu_weight * $value->nu_quantity_buy;
                    }
                }

                #soma a altura e peso.
                $data->nu_height = $data->nu_height + $key->nu_height;
                $data->nu_weight = $data->nu_weight + $key->nu_weight;
            }

            # verifica os maximos de altura e peso.
            # se o pacote ultrapassar os maximos de altura ou peso, divide o pacote em dois.
            while ($data->nu_height > BOX_DELIVERY_MAX_HEIGHT ||
            $data->nu_weight > BOX_DELIVERY_MAX_WEIGHT ||
            $data->nu_length + $data->nu_width + $data->nu_height > BOX_DELIVERY_MAX_PACKAGE_SIZE) {

                # divide altura e peso.
                $data->nu_height = $data->nu_height / 2;
                $data->nu_weight = $data->nu_weight / 2;

                # flag para multiplicar o valor do frete pela quantidade de caixas.
                $data->nu_packages = $data->nu_packages + 2;
            }

            # verifica os mínimos de altura.
            if ($data->nu_height < BOX_DELIVERY_MIN_HEIGHT) {

                $data->nu_height = BOX_DELIVERY_MIN_HEIGHT;
            }

            # verifica os mínimos de peso.
            if ($data->nu_weight < BOX_DELIVERY_MIN_WEIGHT) {

                $data->nu_weight < BOX_DELIVERY_MIN_WEIGHT;
            }

            $this->requestFareValue($data);

            # multiplica o valor do frete por caixas.
            if (!empty($data->nu_packages)) {

                foreach ($data->fare_value as $key) {

                    $key->Valor = $key->Valor * $data->nu_packages;
                }
            }

            return $data;
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /*
     * Method for request fare value
     * @name requestFareValue
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @return object Data of product
     * @date Oct 20, 2015
     * @version 1.0
     */

    public function requestFareValue (& $data)
    {

        try {

            $wsc['nCdEmpresa'] = '';
            $wsc['sDsSenha'] = '';
            $wsc['sCepOrigem'] = ORIGIN_POSTCODE;
            $wsc['sCepDestino'] = $data->nu_postcode;
            $wsc['nVlPeso'] = $data->nu_weight;
            $wsc['nCdFormato'] = NUMBER_ONE;
            $wsc['nVlComprimento'] = $data->nu_length;
            $wsc['nVlAltura'] = $data->nu_height;
            $wsc['nVlLargura'] = $data->nu_width;
            $wsc['nVlDiametro'] = NUMBER_ZERO;
            $wsc['sCdMaoPropria'] = 'n';
            $wsc['nVlValorDeclarado'] = NUMBER_ZERO;
            $wsc['sCdAvisoRecebimento'] = 'n';
            $wsc['StrRetorno'] = 'xml';
            $wsc['nCdServico'] = '41106, 40010';
            $wsc = http_build_query($wsc);

            $url = 'http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx';

            $curl = curl_init($url . '?' . $wsc);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $result = curl_exec($curl);
            $data->fare_value = simplexml_load_string($result);

            # change the comma to point.
            foreach ($data->fare_value as $key) {

                $key->Valor = preg_replace('/,/', '.', $key->Valor);

                if (!empty($key->Erro)) {

                    $data->fare_value->error = $key->MsgErro;
                }
            }
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Envia uma requisição NVP para uma API PayPal.
     *
     * @param array $requestNvp Define os campos da requisição.
     * @param boolean $sandbox Define se a requisição será feita no sandbox ou no
     *                         ambiente de produção.
     *
     * @return array Campos retornados pela operação da API. O array de retorno poderá
     *               ser vazio, caso a operação não seja bem sucedida. Nesse caso, os
     *               logs de erro deverão ser verificados.
     */
    function sendNvpRequest (array $requestNvp, $sandbox = false)
    {
        //Endpoint da API
        $apiEndpoint = 'https://api-3t.' . ($sandbox ? 'sandbox.' : null);
        $apiEndpoint .= 'paypal.com/nvp';

        //Executando a operação
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $apiEndpoint);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($requestNvp));

        $response = urldecode(curl_exec($curl));

        curl_close($curl);

        //Tratando a resposta
        $responseNvp = array();

        if (preg_match_all('/(?<name>[^\=]+)\=(?<value>[^&]+)&?/', $response, $matches)) {
            foreach ($matches['name'] as $offset => $name) {
                $responseNvp[$name] = $matches['value'][$offset];
            }
        }

        //Verificando se deu tudo certo e, caso algum erro tenha ocorrido,
        //gravamos um log para depuração.
        if (isset($responseNvp['ACK']) && $responseNvp['ACK'] != 'Success') {
            for ($i = 0; isset($responseNvp['L_ERRORCODE' . $i]); ++$i) {
                $message = sprintf("PayPal NVP %s[%d]: %s\n", $responseNvp['L_SEVERITYCODE' . $i], $responseNvp['L_ERRORCODE' . $i], $responseNvp['L_LONGMESSAGE' . $i]);

                error_log($message);
            }
        }

        return $responseNvp;
    }

    /**
     * Method for business of buy
     * @name buy
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @return bool Result of procedure
     * @date Dec 30, 2015
     * @version 1.0
     */
    public function buy ()
    {

        try {

            # instances of models.
            $modelUser = new \fulo\model\UserModel();
            $modelProduct = new \fulo\model\ProductModel();

            # get data.
            $data = $this->getRequestData();

            # get data user.
            $data->user = $modelUser->getDataByIdentity($data->origin_sq_person);

            #get product data.
            $data->products = $modelProduct->getDataProducts($data);

            # adjust data zip code.
            $data->nu_postcode = $data->user->nu_postcode;

            # get fare value.
            $dataIntern = $this->getFareValue($data);

            # start variables;
            $data->nu_total_intern = 0;
            $data->nu_quantity_buy = 0;

            // sum intern data.
            foreach ($data->products as $key) {

                foreach ($dataIntern->product as $keyIn) {

                    if ($key->sq_product == $keyIn->sq_product) {

                        // multiply products.
                        $data->nu_total_intern = $data->nu_total_intern + bcmul($key->nu_value, $keyIn->nu_quantity_buy, NUMBER_TWO);

                        # inject nu_quantity_buy in products for save in order_products.
                        $key->nu_quantity_buy = $keyIn->nu_quantity_buy;
                    }

                    # sum total of products.
                    $data->nu_quantity_buy = $data->nu_quantity_buy + $keyIn->nu_quantity_buy;
                }
            }

            # sum total intern with fare value.
            foreach ($dataIntern->fare_value->cServico as $key) {

                if ($key->Valor == $data->nu_farevalue) {

                    $data->nu_total_intern = bcadd($data->nu_total_intern, floatval($key->Valor), NUMBER_TWO);
                }
            }

            if ($data->nu_total === $data->nu_total_intern) {

                # save order.
//                $this->_purchaseModel->buy($data);
            }

            //Vai usar o Sandbox, ou produção?
            $sandbox = true;

            //Baseado no ambiente, sandbox ou produção, definimos as credenciais
            //e URLs da API.
            if ($sandbox) {
                //credenciais da API para o Sandbox
                $user = 'conta-business_api1.test.com';
                $pswd = '1365001380';
                $signature = 'AiPC9BjkCyDFQXbSkoZcgqH3hpacA-p.YLGfQjc0EobtODs.fMJNajCx';

                //URL da PayPal para redirecionamento, não deve ser modificada
                $paypalURL = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
            } else {
                //credenciais da API para produção
                $user = 'usuario';
                $pswd = 'senha';
                $signature = 'assinatura';

                //URL da PayPal para redirecionamento, não deve ser modificada
                $paypalURL = 'https://www.paypal.com/cgi-bin/webscr';
            }

            # inject data for paypal.
            $data->nvp['USER'] = $user;
            $data->nvp['PWD'] = $pswd;
            $data->nvp['SIGNATURE'] = $signature;
            $data->nvp['VERSION'] = '108.0';
            $data->nvp['METHOD'] = 'SetExpressCheckout';
            $data->nvp['PAYMENTREQUEST_0_PAYMENTACTION'] = 'SALE';
            $data->nvp['PAYMENTREQUEST_0_AMT'] = $data->nu_total_intern;
            $data->nvp['PAYMENTREQUEST_0_INVNUM'] = 'dasdas';
            $data->nvp['PAYMENTREQUEST_0_CURRENCYCODE'] = 'BRL';
            $data->nvp['HDRIMG'] = 'https://www.paypal-brasil.com.br/desenvolvedores/wp-content/uploads/2014/04/hdr.png';
            $data->nvp['LOCALECODE'] = 'pt_BR';
            $data->nvp['RETURNURL'] = 'http://PayPalPartner.com.br/VendeFrete?return=1';
            $data->nvp['CANCELURL'] = 'http://PayPalPartner.com.br/CancelaFrete';
            $data->nvp['BUTTONSOURCE'] = 'BR_EC_EMPRESA';

            # add delivery as product for paypal.
            $data->nvp['L_PAYMENTREQUEST_0_NAME0'] = 'Taxa de entrega';
            $data->nvp['L_PAYMENTREQUEST_0_AMT0'] = $data->nu_farevalue;
            $data->nvp['L_PAYMENTREQUEST_0_QTY0'] = '1';

            # adjust product data for send to paypal.
            $aux = 1;

            foreach ($data->products as $key) {

                $data->nvp['L_PAYMENTREQUEST_0_NAME' . $aux] = $key->ds_product;
//                $data->nvp[L_PAYMENTREQUEST_0_DESC.$aux] = 'Produto';
                $data->nvp['L_PAYMENTREQUEST_0_AMT' . $aux] = $key->nu_value;
                $data->nvp['L_PAYMENTREQUEST_0_QTY' . $aux] = $key->nu_quantity_buy;

                $aux ++;
            }
            
            //Campos da requisição da operação SetExpressCheckout, como ilustrado acima.
//            $requestNvp = array(
//                'USER' => $user,
//                'PWD' => $pswd,
//                'SIGNATURE' => $signature,
//                'VERSION' => '108.0',
//                'METHOD' => 'SetExpressCheckout',
//                'PAYMENTREQUEST_0_PAYMENTACTION' => 'SALE',
//                'PAYMENTREQUEST_0_AMT' => '22.00',
//                'PAYMENTREQUEST_0_CURRENCYCODE' => 'BRL',
//                'PAYMENTREQUEST_0_ITEMAMT' => '22.00',
//                'PAYMENTREQUEST_0_INVNUM' => '123454242424422',
////                produto 0
//                'L_PAYMENTREQUEST_0_NAME0' => 'Item A',
//                'L_PAYMENTREQUEST_0_DESC0' => 'Produto A – 110V',
//                'L_PAYMENTREQUEST_0_AMT0' => '11.00',
//                'L_PAYMENTREQUEST_0_QTY0' => '50',
////                'L_PAYMENTREQUEST_0_ITEMAMT' => '11.00',
////                produto 1
//                'L_PAYMENTREQUEST_0_NAME1' => 'Item B',
//                'L_PAYMENTREQUEST_0_DESC1' => 'Produto B – 220V',
//                'L_PAYMENTREQUEST_0_AMT1' => '11.00',
//                'L_PAYMENTREQUEST_0_QTY1' => '1',
////                outros dados
//                'HDRIMG' => 'https://www.paypal-brasil.com.br/desenvolvedores/wp-content/uploads/2014/04/hdr.png',
//                'LOCALECODE' => 'pt_BR',
//                'RETURNURL' => 'http://PayPalPartner.com.br/VendeFrete?return=1',
//                'CANCELURL' => 'http://PayPalPartner.com.br/CancelaFrete',
//                'BUTTONSOURCE' => 'BR_EC_EMPRESA'
//            );
            //Envia a requisição e obtém a resposta da PayPal
            $responseNvp = $this->sendNvpRequest($data->nvp, $sandbox);

            //Se a operação tiver sido bem sucedida, redirecionamos o cliente para o
            //ambiente de pagamento.
            if (isset($responseNvp['ACK']) && $responseNvp['ACK'] == 'Success') {

                $query = array(
                    'cmd' => '_express-checkout',
                    'token' => $responseNvp['TOKEN']
                );

                $redirectURL = sprintf('%s?%s', $paypalURL, http_build_query($query));

                return $redirectURL;
            } else {
                //Opz, alguma coisa deu errada.
                //Verifique os logs de erro para depuração.
                return ERROR;
            }
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for track order in correios
     * autor: Luis Fernando Meireles
     * @return array Array com tabela html
     * @version 1.0
     * */
    function tracker ()
    {

        $data = $this->getRequestData();
        $url = 'http://websro.correios.com.br/sro_bin/txect01$.Inexistente?P_LINGUA=001&P_TIPO=002&P_COD_LIS=' . $data->nu_tracker;

        $retorno = file_get_contents($url);

        $ini = strpos($retorno, '<table');
        $end = strpos($retorno, '</TABLE>') + 8;
        $len = $end - $ini;
        $table = utf8_encode(substr($retorno, $ini, $len));
        $table = explode('<tr>', str_replace('</TABLE>', '', $table));
        unset($table[0]);
        $table = '<table><tr>' . implode('<tr>', $table) . '</table>';

        return array('table' => $table);
    }

}
