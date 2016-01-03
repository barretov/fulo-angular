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
                    . "product.sq_product, product.sq_product_type, sq_status_product, ds_product, nu_value, nu_quantity, nu_production, st_foldable, im_product_image "
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
                    . "product.sq_product, product.sq_product_type, sq_status_product, ds_product, nu_value, nu_quantity, st_foldable, "
                    . "nu_production, product_type.ds_product_type, nu_height, nu_length, nu_width, nu_weight, im_product_image "
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

            $stmt = $this->_conn->prepare("SELECT * FROM fulo.product_type ORDER BY ds_product_type ASC");

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
                    . "(sq_product_type, ds_product, nu_value, nu_quantity, nu_date_time, nu_production, "
                    . "nu_height, nu_length, nu_width, nu_weight, st_foldable) "
                    . "VALUES (?,?,?,?,?,?,?,?,?,?,?)"
            );

            $stmtProduct->execute([
                $data->sq_product_type,
                $data->ds_product,
                $data->nu_value,
                $data->nu_quantity,
                date("Y-m-d H:i:s"),
                $data->nu_production,
                $data->nu_height,
                $data->nu_length,
                $data->nu_width,
                $data->nu_weight,
                $data->st_foldable
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

    /**
     * Model for up product
     * @name upProduct
     * @author Victor Eduardo Barreto
     * @param object $data Data of product
     * @var $app object Slim instance
     * @return bool Result of procedure
     * @date Alg 26, 2015
     * @version 1.0
     */
    public function upProduct (& $data)
    {

        try {

            $this->_conn->beginTransaction();

            $stmtProduct = $this->_conn->prepare("UPDATE fulo.product "
                    . "SET sq_product_type = ?, ds_product = ?, nu_value = ?, nu_quantity = ?, nu_production = ?, "
                    . "nu_length = ?, nu_width = ?, nu_weight = ?, nu_height = ?, st_foldable = ? "
                    . "WHERE sq_product = ?"
            );

            $stmtProduct->execute([
                $data->sq_product_type,
                $data->ds_product,
                $data->nu_value,
                $data->nu_quantity,
                $data->nu_production,
                $data->nu_length,
                $data->nu_width,
                $data->nu_weight,
                $data->nu_height,
                $data->st_foldable,
                $data->sq_product
            ]);

            if (!empty($data->im_image)) {

                $stmtImage = $this->_conn->prepare("UPDATE fulo.product_image "
                        . "SET im_product_image = ? "
                        . "WHERE sq_product = ?"
                );

                $stmtImage->execute([
                    $data->im_image,
                    $data->sq_product
                ]);
            }

            # save log operation.
            $this->saveLog($data->origin_sq_user, $data->sq_product);

            return $this->_conn->commit();
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Model for activate product
     * @name activateProduct
     * @author Victor Eduardo Barreto
     * @param object $data Data of product
     * @var $app object Slim instance
     * @return bool Result of procedure
     * @date Alg 26, 2015
     * @version 1.0
     */
    public function activateProduct (& $data)
    {

        try {

            $this->_conn->beginTransaction();

            $stmt = $this->_conn->prepare("UPDATE fulo.product "
                    . "SET sq_status_product = ? "
                    . "WHERE sq_product = ?"
            );

            $stmt->execute([
                STATUS_ACTIVE,
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
     * Model for inactivate product
     * @name inactivateProduct
     * @author Victor Eduardo Barreto
     * @param object $data Data of product
     * @var $app object Slim instance
     * @return bool Result of procedure
     * @date Alg 26, 2015
     * @version 1.0
     */
    public function inactivateProduct (& $data)
    {

        try {

            $this->_conn->beginTransaction();

            $stmt = $this->_conn->prepare("UPDATE fulo.product "
                    . "SET sq_status_product = ? "
                    . "WHERE sq_product = ?"
            );

            $stmt->execute([
                STATUS_INACTIVE,
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
     * Method for get products by filter
     * @name getProductsByFilter
     * @author Victor Eduardo Barreto
     * @param Object $data Data of filters and user
     * @return Object Data products
     * @date Alg 18, 2015
     * @version 1.0
     */
    public function getProductsByFilter ($data)
    {

        try {

            $stmt = $this->_conn->prepare("SELECT "
                    . "product.sq_product, product.sq_product_type, sq_status_product, ds_product, nu_value, nu_quantity, "
                    . "nu_production, im_product_image "
                    . "FROM fulo.product "
                    . "JOIN fulo.product_type ON (product_type.sq_product_type = product.sq_product_type) "
                    . "JOIN fulo.product_image ON (product_image.sq_product = product.sq_product) "
                    . "WHERE sq_status_product <> ? " . $data->filter
            );

            $stmt->execute([
                STATUS_INACTIVE
            ]);

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for add item in wish list
     * @name addWishList
     * @author Victor Eduardo Barreto
     * @param Object $data Data of product and user
     * @return bool Result of procedure
     * @date Alg 28, 2015
     * @version 1.0
     */
//    public function addWishList ($data)
//    {
//
//        try {
//
//            $stmtSelect = $this->_conn->prepare("SELECT sq_user "
//                    . "FROM fulo.wishlist "
//                    . "WHERE sq_user = ? "
//                    . "AND sq_product = ? "
//            );
//
//            $stmtSelect->execute([
//                $data->origin_sq_user,
//                $data->sq_product
//            ]);
//
//            if (!$stmtSelect->fetch()) {
//
//                $this->_conn->beginTransaction();
//
//                $stmtAdd = $this->_conn->prepare("INSERT INTO fulo.wishlist "
//                        . "(sq_user, sq_product) "
//                        . "VALUES (?,?)"
//                );
//
//                $stmtAdd->execute([
//                    $data->origin_sq_user,
//                    $data->sq_product
//                ]);
//
//                # save log operation.
//                $this->saveLog($data->origin_sq_user, $data->sq_product);
//
//                $return = $this->_conn->commit();
//            } else {
//
//                $return = WISHLIST_ALREADY;
//            }
//
//            return $return;
//        } catch (Exception $ex) {
//
//            throw $ex;
//        }
//    }

    /**
     * Method for get item of wish list
     * @name getWishList
     * @author Victor Eduardo Barreto
     * @param Object $data Data of product and user
     * @return object Data of user wishlist
     * @date Alg 29, 2015
     * @version 1.0
     */
//    public function getWishList ($data)
//    {
//
//        try {
//
//            $stmt = $this->_conn->prepare("SELECT * "
//                    . "FROM fulo.wishlist "
//                    . "JOIN fulo.product "
//                    . "ON (product.sq_product = wishlist.sq_product) "
//                    . "JOIN fulo.product_image "
//                    . "ON (product.sq_product = product_image.sq_product) "
//                    . "WHERE sq_user = ? "
//            );
//
//            $stmt->execute([
//                $data->origin_sq_user,
//            ]);
//
//            return $stmt->fetchAll(PDO::FETCH_OBJ);
//        } catch (Exception $ex) {
//
//            throw $ex;
//        }
//    }

    /**
     * Method for del item of wish list
     * @name delWishList
     * @author Victor Eduardo Barreto
     * @param Object $data Data of product and user
     * @return bool Result of procedure
     * @date Alg 29, 2015
     * @version 1.0
     */
//    public function delWishList ($data)
//    {
//
//        try {
//
//            $this->_conn->beginTransaction();
//
//            $stmt = $this->_conn->prepare("DELETE "
//                    . "FROM fulo.wishlist "
//                    . "WHERE sq_user = ? "
//                    . "AND sq_product = ?"
//            );
//
//            $stmt->execute([
//                $data->origin_sq_user,
//                $data->sq_product
//            ]);
//
//            # save log operation.
//            $this->saveLog($data->origin_sq_user, $data->sq_product);
//
//            return $this->_conn->commit();
//        } catch (Exception $ex) {
//
//            throw $ex;
//        }
//    }

    /**
     * Model for add product type
     * @name addProductType
     * @author Victor Eduardo Barreto
     * @param object $data Data of product
     * @var $app object Slim instance
     * @return bool Result of procedure
     * @date Out 7, 2015
     * @version 1.0
     */
    public function addProductType (& $data)
    {

        try {

            $this->_conn->beginTransaction();

            $stmtProduct = $this->_conn->prepare("INSERT INTO fulo.product_type "
                    . "(ds_product_type) "
                    . "VALUES (?) "
            );

            $stmtProduct->execute([
                $data->ds_product_type
            ]);

            # save log operation.
            $this->saveLog($data->origin_sq_user, $this->_conn->lastInsertId('fulo.product_type_sq_product_type_seq'));

            return $this->_conn->commit();
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for get product type
     * @name getProductType
     * @author Victor Eduardo Barreto
     * @param Object $data Data of product and user
     * @return bool Result of procedure
     * @date Out 7, 2015
     * @version 1.0
     */
    public function getProductType (& $data)
    {

        try {

            $stmt = $this->_conn->prepare("SELECT * "
                    . "FROM fulo.product_type "
                    . "WHERE sq_product_type = ? "
            );

            $stmt->execute([
                $data->sq_product_type,
            ]);

            return $stmt->fetchObject();
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Model for up product type
     * @name upProductType
     * @author Victor Eduardo Barreto
     * @param object $data Data of product
     * @var $app object Slim instance
     * @return bool Result of procedure
     * @date Out 7, 2015
     * @version 1.0
     */
    public function upProductType (& $data)
    {

        try {

            $this->_conn->beginTransaction();

            $stmtProduct = $this->_conn->prepare("UPDATE fulo.product_type "
                    . "SET ds_product_type = ? "
                    . "WHERE sq_product_type = ?"
            );

            $stmtProduct->execute([
                $data->ds_product_type,
                $data->sq_product_type
            ]);

            # save log operation.
            $this->saveLog($data->origin_sq_user, $data->sq_product_type);

            return $this->_conn->commit();
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Model for del product type
     * @name delProductType
     * @author Victor Eduardo Barreto
     * @param object $data Data of product
     * @var $app object Slim instance
     * @return bool Result of procedure
     * @date Out 7, 2015
     * @version 1.0
     */
    public function delProductType (& $data)
    {

        try {

            $stmtSelect = $this->_conn->prepare("SELECT sq_product "
                    . "FROM fulo.product "
                    . "WHERE sq_product_type = ? "
            );

            $stmtSelect->execute([
                $data->sq_product_type
            ]);

            if (!$stmtSelect->fetch()) {

                $this->_conn->beginTransaction();

                $stmtProduct = $this->_conn->prepare("DELETE FROM fulo.product_type "
                        . "WHERE sq_product_type = ?"
                );

                $stmtProduct->execute([
                    $data->sq_product_type
                ]);

                # save log operation.
                $this->saveLog($data->origin_sq_user, $data->sq_product_type);

                $return = $this->_conn->commit();
            } else {

                $return = PRODUCT_TYPE_BUSY;
            }

            return $return;
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

}
