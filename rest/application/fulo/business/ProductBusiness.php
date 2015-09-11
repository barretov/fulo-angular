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

                $this->makeImageOut($key->im_product_image, 250, 250);
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

//            $data->im_product_image = Image::make($data->im_product_image)->resize(640, 510)->encode('data-url');
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

            $results = $this->_productModel->getProductsByFilter($data);

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

                $this->makeImageOut($key->im_product_image, 100, 100);
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

            $results = $this->_productModel->delWishList($data);

            return $results;
        } catch (Exception $ex) {

            throw $ex;
        }
    }

}
