<?php

/**
 * @license <http://www.gnu.org/licenses/>
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
 * Model class for Purchase
 * @name ProductModel
 * @author Victor Eduardo Barreto
 * @date Dec 10, 2015
 * @version 1.0
 */
class PurchaseModel extends MasterModel
{

    /**
     * Method for add item in wish list
     * @name addWishList
     * @author Victor Eduardo Barreto
     * @param Object $data Data of product and user
     * @return bool Result of procedure
     * @date Alg 28, 2015
     * @version 1.0
     */
    public function addWishList ($data)
    {

        try {

            $stmtSelect = $this->_conn->prepare("SELECT sq_user "
                    . "FROM fulo.wishlist "
                    . "WHERE sq_user = ? "
                    . "AND sq_product = ? "
            );

            $stmtSelect->execute([
                $data->origin_sq_user,
                $data->sq_product
            ]);

            if (!$stmtSelect->fetch()) {

                $this->_conn->beginTransaction();

                $stmtAdd = $this->_conn->prepare("INSERT INTO fulo.wishlist "
                        . "(sq_user, sq_product) "
                        . "VALUES (?,?)"
                );

                $stmtAdd->execute([
                    $data->origin_sq_user,
                    $data->sq_product
                ]);

                # save log operation.
                $this->saveLog($data->origin_sq_user, $data->sq_product);

                $return = $this->_conn->commit();
            } else {

                $return = WISHLIST_ALREADY;
            }

            return $return;
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for get item of wish list
     * @name getWishList
     * @author Victor Eduardo Barreto
     * @param Object $data Data of product and user
     * @return object Data of user wishlist
     * @date Alg 29, 2015
     * @version 1.0
     */
    public function getWishList ($data)
    {

        try {

            $stmt = $this->_conn->prepare("SELECT * "
                    . "FROM fulo.wishlist "
                    . "JOIN fulo.product "
                    . "ON (product.sq_product = wishlist.sq_product) "
                    . "JOIN fulo.product_image "
                    . "ON (product.sq_product = product_image.sq_product) "
                    . "WHERE sq_user = ? "
            );

            $stmt->execute([
                $data->origin_sq_user,
            ]);

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for del item of wish list
     * @name delWishList
     * @author Victor Eduardo Barreto
     * @param Object $data Data of product and user
     * @return bool Result of procedure
     * @date Alg 29, 2015
     * @version 1.0
     */
    public function delWishList ($data)
    {

        try {

            $this->_conn->beginTransaction();

            $stmt = $this->_conn->prepare("DELETE "
                    . "FROM fulo.wishlist "
                    . "WHERE sq_user = ? "
                    . "AND sq_product = ?"
            );

            $stmt->execute([
                $data->origin_sq_user,
                $data->sq_product
            ]);

            # save log operation.
            $this->saveLog($data->origin_sq_user, $data->sq_product);

            return $this->_conn->commit();
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for get data products
     * @name getDataProducts
     * @author Victor Eduardo Barreto
     * @param Array $data->sq_product Identifier of products
     * @return Array Data products
     * @date Oct 20, 2015
     * @version 1.0
     */
    public function getDataProducts (& $data)
    {

        try {

            $query = "SELECT * FROM fulo.product";

            foreach ($data->product as $key => $value) {

                if (!$key) {

                    $query = $query . " WHERE sq_product = " . $value->sq_product;
                } else {

                    $query = $query . " OR sq_product = " . $value->sq_product;
                }
            }

            $stmt = $this->_conn->prepare($query);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for buy products
     * @name buy
     * @author Victor Eduardo Barreto
     * @param Object $data All data of products and user
     * @return bool Result of transaction
     * @date Dec 31, 2015
     * @version 1.0
     */
    public function buy (& $data)
    {

        try {

            // @TODO save order data. and products order
            $this->_conn->beginTransaction();

            $stmt = $this->_conn->prepare("INSERT "
                    . "INTO fulo.order "
                    . "(sq_user, nu_quantity, nu_total, st_status, nu_date_time) "
                    . "VALUES (?,?,?,?,?)"
            );

            $stmt->execute([
                $data->origin_sq_user,
                $data->nu_quantity,
                $data->nu_total,
                $data->st_status,
                date("Y-m-d H:i:s")
            ]);

            return $this->_conn->commit();
        } catch (Exception $ex) {

            throw $ex;
        }
    }

}
