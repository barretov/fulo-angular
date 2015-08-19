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
                    . "* "
                    . "FROM fulo.product "
                    . "JOIN fulo.type "
                    . "ON (type.sq_type = sq_type)"
            );

            $stmt->execute();

            return $stmt->fetchAll();
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

            return $stmt->fetchAll();
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

            $stmt = $this->_conn->prepare("INSERT INTO fulo.product "
                    . "(sq_product_type, ds_product, nu_value, nu_quatity, ds_image, nu_date_time, nu_production) "
                    . "VALUES (?,?,?,?,?,?,?)"
            );

            $stmt->execute([
                $data->sq_product_type,
                $data->ds_product,
                $data->nu_value,
                $data->nu_quantity,
                $data->ds_image,
                date("Y-m-d H:i:s"),
                $data->nu_production
            ]);

            # save log operation.
            $this->saveLog($data->origin_sq_user, $data->sq_product);

            return $stmt->commit();
        } catch (Exception $ex) {

            throw $ex;
        }
    }

}
