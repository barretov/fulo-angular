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

            # count the products in cart.
            $data->nu_quantity_order = count($data->sq_product);

            $products = $this->_productModel->getDataProducts($data);

            # se eu tenho somente um produto.
            if ($data->nu_quantity_order = 1) {

                # verifica se o produto é menor que os valores minimos.
                foreach ($products as $key) {

                    if ($key->nu_length < BOX_DELIVERY_MIN_LENGTH) {

                        $data->length = BOX_DELIVERY_MIN_LENGTH;
                    } else {

                        $data->length = $key->nu_length;
                    }

                    if ($key->nu_width < BOX_DELIVERY_MIN_WIDTH) {

                        $data->width = BOX_DELIVERY_MIN_WIDTH;
                    } else {

                        $data->width = $key->nu_width;
                    }

                    if ($key->nu_weight < BOX_DELIVERY_MIN_WEIGHT) {

                        $data->weight = BOX_DELIVERY_MIN_WEIGHT;
                    } else {

                        $data->weight = $key->nu_weight;
                    }

                    if ($key->nu_height < BOX_DELIVERY_MIN_HEIGHT) {

                        $data->height = BOX_DELIVERY_MIN_HEIGHT;
                    } else {

                        $data->height = $key->nu_height;
                    }
                }
            }

            $this->requestFareValue($data);

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
            $wsc['nVlPeso'] = $data->weight;
            $wsc['nCdFormato'] = NUMBER_ONE;
            $wsc['nVlComprimento'] = $data->length;
            $wsc['nVlAltura'] = $data->height;
            $wsc['nVlLargura'] = $data->width;
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

//            foreach ($result->cServico as $row) {
//
//                //Os dados de cada serviço estará aqui
//                if ($row->Erro == 0) {
//                    echo $row->Codigo . '<br>';
//                    echo $row->Valor . '<br>';
//                    echo $row->PrazoEntrega . '<br>';
//                    echo $row->ValorMaoPropria . '<br>';
//                    echo $row->ValorAvisoRecebimento . '<br>';
//                    echo $row->ValorValorDeclarado . '<br>';
//                    echo $row->EntregaDomiciliar . '<br>';
//                    echo $row->EntregaSabado;
//                } else {
//                    echo $row->MsgErro;
//                }
//            }
        } catch (Exception $ex) {

            throw $ex;
        }
    }

}
