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

namespace fulo\model;

use PDO;

/**
 * Model class for product
 * @name ProductModel
 * @author Victor Eduardo Barreto
 * @date Alg 18, 2015
 * @version 1.0
 */
class ProductModel extends MasterModel
{

    /**
     * Method for get products
     * @name getProducts
     * @author Victor Eduardo Barreto
     * @return Object Data products
     * @date Alg 18, 2015
     * @version 1.0
     */
    public function getProducts ()
    {

        try {

            $stmt = $this->_conn->prepare("SELECT "
                    . "product.sq_product, product.sq_product_type, sq_status_product, ds_product, nu_value, nu_quantity, "
                    . "im_product_image "
                    . "FROM fulo.product "
                    . "JOIN fulo.product_type "
                    . "ON (product_type.sq_product_type = product.sq_product_type) "
                    . "JOIN fulo.product_image "
                    . "ON (product_image.sq_product = product.sq_product)"
            );

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for get product
     * @name getProduct
     * @author Victor Eduardo Barreto
     * @param int $data Data user and product
     * @return Object Data product
     * @date Alg 24, 2015
     * @version 1.0
     */
    public function getProduct (& $data)
    {

        try {

            $stmt = $this->_conn->prepare("SELECT "
                    . "product.sq_product, product.sq_product_type, sq_status_product, ds_product, nu_value, nu_quantity, nu_production, product_type.ds_product_type, "
                    . "im_product_image "
                    . "FROM fulo.product "
                    . "JOIN fulo.product_type "
                    . "ON (product_type.sq_product_type = product.sq_product_type) "
                    . "JOIN fulo.product_image "
                    . "ON (product_image.sq_product = product.sq_product)"
                    . "WHERE product.sq_product = ?"
            );

            $stmt->execute([
                $data->sq_product
            ]);

            return $stmt->fetchObject();
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

            $stmt = $this->_conn->prepare("SELECT * FROM fulo.product_type");

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Model for add product
     * @name addProduct
     * @author Victor Eduardo Barreto
     * @param object $data Data of product
     * @var $app object Slim instance
     * @return bool Result of procedure
     * @date Alg 19, 2015
     * @version 1.0
     */
    public function addProduct (& $data)
    {

        try {

            $this->_conn->beginTransaction();

            $stmtProduct = $this->_conn->prepare("INSERT INTO fulo.product "
                    . "(sq_product_type, ds_product, nu_value, nu_quantity, nu_date_time, nu_production) "
                    . "VALUES (?,?,?,?,?,?)"
            );

            $stmtProduct->execute([
                $data->sq_product_type,
                $data->ds_product,
                $data->nu_value,
                $data->nu_quantity,
                date("Y-m-d H:i:s"),
                $data->nu_production
            ]);

            $stmtImage = $this->_conn->prepare("INSERT INTO fulo.product_image "
                    . "(im_product_image, sq_product) "
                    . "VALUES (?,?)"
            );

            $stmtImage->execute([
                $data->im_image,
                $this->_conn->lastInsertId('fulo.product_sq_product_seq')
            ]);

            # save log operation.
            $this->saveLog($data->origin_sq_user, $this->_conn->lastInsertId('fulo.product_sq_product_seq'));

            return $this->_conn->commit();
        } catch (Exception $ex) {

            throw $ex;
        }
    }

}
