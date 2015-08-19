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

            return $this->_productModel->getProducts();
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

            var_dump($data);

            $dir = fopen("/media/victor/data/projects/fulo-angular/rest/application/images/product/teste.bin", 'wb');
            fwrite($dir, $data->ds_image);
            fclose($dir);

            $data->ds_image = $dir . "teste.bin";

            return $this->_productModel->addProduct($data);
        } catch (Exception $ex) {

            throw $ex;
        }
    }

}
