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

use fulo\model\ProductModel as ProductModel;

/**
 * Class of business for Product
 * @name ProductBusiness
 * @author Victor Eduardo Barreto
 * @package fulo\business
 * @date Alg 18, 2015
 * @version 1.0
 */
class ProductBusiness extends MasterBusiness
{

    /**
     * variable for instance of product model
     * @var object
     */
    private $_productModel;

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
        $this->_productModel = new ProductModel();
    }

    /**
     * Method for get products
     * @name getProducts
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @return object Data products
     * @date Alg 18, 2015
     * @version 1.0
     */
    public function getProducts ()
    {

        try {

            $results = $this->_productModel->getProducts();

            # adjust images.
            foreach ($results as $key) {

                $this->makeImageOut($key->im_product_image, 300, 300);
            }

            return $results;
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for get product
     * @name getProduct
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @return object Data product
     * @date Alg 24, 2015
     * @version 1.0
     */
    public function getProduct ()
    {

        try {

            $data = $this->getRequestData();

            $data = $this->_productModel->getProduct($data);

            $this->makeImageOut($data->im_product_image, 640, 640);

            return $data;
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for get product types
     * @name getProductTypes
     * @author Victor Eduardo Barreto
     * @var $app object Slim instance
     * @return object Data product types
     * @date Alg 19, 2015
     * @version 1.0
     */
    public function getProductTypes ()
    {

        try {

            return $this->_productModel->getProductTypes();
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for business of add product
     * @name addProduct
     * @author Victor Eduardo Barreto
     * @var $app object Slim instance
     * @return bool Result of procedure
     * @date Alg 19, 2015
     * @version 1.0
     */
    public function addProduct ()
    {

        try {

            $data = $this->getRequestData();

            return $this->_productModel->addProduct($data);
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for business of up product
     * @name upProduct
     * @author Victor Eduardo Barreto
     * @var $app object Slim instance
     * @return bool Result of procedure
     * @date Alg 26, 2015
     * @version 1.0
     */
    public function upProduct ()
    {

        try {

            $data = $this->getRequestData();

            # if foldable field does not arrive, he's false;
            if (empty($data->st_foldable)) {

                $data->st_foldable = NUMBER_TWO;
            }

            return $this->_productModel->upProduct($data);
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for business of activate product
     * @name activateProduct
     * @author Victor Eduardo Barreto
     * @var $app object Slim instance
     * @return bool Result of procedure
     * @date Alg 26, 2015
     * @version 1.0
     */
    public function activateProduct ()
    {

        try {

            $data = $this->getRequestData();

            return $this->_productModel->activateProduct($data);
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for business of inactivate product
     * @name inactivateProduct
     * @author Victor Eduardo Barreto
     * @var $app object Slim instance
     * @return bool Result of procedure
     * @date Alg 26, 2015
     * @version 1.0
     */
    public function inactivateProduct ()
    {

        try {

            $data = $this->getRequestData();

            return $this->_productModel->inactivateProduct($data);
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for business of get products by filter
     * @name getProductsByFilter
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @return object Data products
     * @date Alg 27, 2015
     * @version 1.0
     */
    public function getProductsByFilter ()
    {

        try {

            $data = $this->getRequestData();

            # adjust filter in query if products was filtered.
            if (empty($data->sq_product_type)) {

                $data->filter = NULL;
            } else {

                $data->filter = "AND product.sq_product_type = " . $data->sq_product_type;
            }

            $results = $this->_productModel->getProductsByFilter($data);

            # adjust images.
            foreach ($results as $key) {

                $this->makeImageOut($key->im_product_image, 300, 300);
            }

            # verify if exist result and send message.
            if (empty($results)) {

                $results = WITHOUT_RESULT;
            }


            return $results;
        } catch (Exception $ex) {

            throw $ex;
        }
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

            return $this->_productModel->addWishList($data);
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

            $results = $this->_productModel->getWishList($data);

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

            return $this->_productModel->delWishList($data);
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for add product type
     * @name addProductType
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @return object Data of product type
     * @date Out 7, 2015
     * @version 1.0
     */
    public function addProductType ()
    {

        try {

            $data = $this->getRequestData();

            return $this->_productModel->addProductType($data);
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for get product type
     * @name getProductType
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @return object Data of product type
     * @date Out 7, 2015
     * @version 1.0
     */
    public function getProductType ()
    {

        try {

            $data = $this->getRequestData();

            return $this->_productModel->getProductType($data);
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for up product type
     * @name upProductType
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @return object Data of product type
     * @date Out 7, 2015
     * @version 1.0
     */
    public function upProductType ()
    {

        try {

            $data = $this->getRequestData();

            return $this->_productModel->upProductType($data);
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for del product type
     * @name delProductType
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @return object Data of product type
     * @date Out 7, 2015
     * @version 1.0
     */
    public function delProductType ()
    {

        try {

            $data = $this->getRequestData();

            return $this->_productModel->delProductType($data);
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for get fare value
     * @name getFareValue
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @return object Data of product type
     * @date Oct 10, 2015
     * @version 1.0
     */
    public function getFareValue ()
    {

        try {

            $data = $this->getRequestData();

            # init variables;
            $data->nu_length = 0;
            $data->nu_width = 0;
            $data->nu_height = 0;
            $data->nu_weight = 0;
            $data->nu_packages = 0;

            # count the products in cart.
            $data->nu_quantity_order = count($data->product);

            $products = $this->_productModel->getDataProducts($data);

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

                #soma a altura.
                $data->nu_height = $data->nu_height + $key->nu_height;

                #soma o peso.
                $data->nu_weight = $data->nu_weight + $key->nu_weight;
            }

            # verifica os maximos de altura e peso.
            # se o pacote ultrapassar os maximos de altura ou peso, divide o pacote em dois.
            while ($data->nu_height > BOX_DELIVERY_MAX_HEIGHT || $data->nu_weight > BOX_DELIVERY_MAX_WEIGHT) {

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

}
