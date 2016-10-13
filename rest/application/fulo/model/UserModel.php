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
 * Model class for users
 * @name UserModel
 * @author Victor Eduardo Barreto
 * @date Apr 8, 2015
 * @version 1.0
 */
class UserModel extends MasterModel {

    /**
     * Method for add user
     * @name addUser
     * @author Victor Eduardo Barreto
     * @param array $data User data
     * @return bool Result of procedure
     * @date Apr 8, 2015
     * @version 1.0
     */
    public function addUser(&$data) {

        try {

            $this->_conn->beginTransaction();

            # Person.
            $stmtPerson = $this->_conn->prepare("INSERT INTO fulo.person (ds_name, ds_email) VALUES (?,?)");

            $stmtPerson->execute([
                $data->ds_name,
                $data->ds_email,
            ]);

            # User.
            $stmtUser = $this->_conn->prepare("INSERT INTO fulo.user (sq_user, sq_profile, ds_password) VALUES (?,?,?)");

            $stmtUser->execute([
                $this->_conn->lastInsertId('fulo.person_sq_person_seq'),
                $data->sq_profile,
                $data->ds_password,
            ]);

            # Address.
            $stmtAddress = $this->_conn->prepare("INSERT INTO fulo.address (sq_person) VALUES (?)");

            $stmtAddress->execute([
                $this->_conn->lastInsertId('fulo.person_sq_person_seq'),
            ]);

            # Phone.
            $stmtPhone = $this->_conn->prepare("INSERT INTO fulo.phone (sq_person, nu_phone) VALUES (?,?)");

            $stmtPhone->execute([
                $this->_conn->lastInsertId('fulo.person_sq_person_seq'),
                null,
            ]);

            # if was sigin of customer set data origin.
            if (empty($data->origin_sq_user)) {

                $data->origin_sq_user = $this->_conn->lastInsertId('fulo.person_sq_person_seq');
            }

            # save log operation.
            $this->saveLog($data->origin_sq_user, $this->_conn->lastInsertId('fulo.person_sq_person_seq'));

            return $this->_conn->commit();
        } catch (Exception $ex) {

            $this->_conn->rollback();

            throw $ex;
        }
    }

    /**
     * Method for update user
     * @name upUser
     * @author Victor Eduardo Barreto
     * @param array $data User data
     * @return bool Result of procedure
     * @date Apr 8, 2015
     * @version 1.0
     */
    public function upUser(&$data) {
        try {

            $this->_conn->beginTransaction();

            # update person.
            $stmtPerson = $this->_conn->prepare("UPDATE fulo.person "
                . "SET ds_name = ?, ds_email = ?, sq_status_news = ? "
                . "WHERE sq_person = ?");

            $stmtPerson->execute([
                $data->ds_name,
                $data->ds_email,
                $data->sq_status_news,
                $data->sq_person,
            ]);

            # set $stmt to update user.
            $stmtUser = $this->_conn->prepare("UPDATE fulo.user SET sq_profile = ? WHERE sq_user = ?");

            $stmtUser->execute([
                $data->sq_profile,
                $data->sq_person,
            ]);

            # set $stmt to update phone.
            $stmtPhone = $this->_conn->prepare("UPDATE fulo.phone SET nu_phone = ? WHERE sq_person = ?");

            $stmtPhone->execute([
                $data->nu_phone,
                $data->sq_person,
            ]);

            # save log operation.
            $this->saveLog($data->origin_sq_user, $data->sq_user);

            return $this->_conn->commit();
        } catch (Exception $ex) {

            $this->_conn->rollback();

            throw $ex;
        }
    }

    /**
     * Method for get users
     * @name getUsers
     * @author Victor Eduardo Barreto
     * @return array Data of users
     * @param int $sq_user Id of loged user
     * @date Apr 8, 2015
     * @version 1.0
     */
    public function getUsers($sq_user) {
        try {

            $stmt = $this->_conn->prepare("SELECT "
                . "person.sq_person, ds_name, ds_email, sq_status_news, sq_user, sq_profile, sq_status_user, "
                . "sq_address, nu_postcode, ac_state, ds_address, ds_complement, ds_city, nu_phone "
                . "FROM fulo.person "
                . "JOIN fulo.user ON (person.sq_person = sq_user) "
                . "LEFT JOIN fulo.address ON (person.sq_person = address.sq_person)"
                . "JOIN fulo.phone ON (person.sq_person = phone.sq_person)"
                . "WHERE sq_user <> ? ORDER BY ds_name ASC"
            );

            $stmt->execute([
                $sq_user,
            ]);

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for get user data by Identity
     * @name getDataByIdentity
     * @author Victor Eduardo Barreto
     * @param int $sq_person User identifier
     * @return array Data of user selected
     * @date Apr 8, 2015
     * @version 1.0
     */
    public function getDataByIdentity(&$sq_person) {
        try {

            $stmt = $this->_conn->prepare("SELECT "
                . "person.sq_person, ds_name, ds_email, sq_status_news, sq_user, sq_profile, sq_status_user, "
                . "ds_password, sq_address, nu_postcode, ac_state, ds_address, ds_complement, ds_city, "
                . "ds_neighborhood, nu_phone "
                . "FROM fulo.person "
                . "JOIN fulo.user ON (person.sq_person = sq_user) "
                . "LEFT JOIN fulo.address ON (person.sq_person = address.sq_person)"
                . "JOIN fulo.phone ON (person.sq_person = phone.sq_person)"
                . "WHERE person.sq_person = ?");

            $stmt->execute([
                $sq_person,
            ]);

            return $stmt->fetchObject();
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for get all data of user by email
     * @name gerDataByEmail
     * @author Victor Eduardo Barreto
     * @package fulo\model
     * @param string $ds_email Email of user
     * @return array User data
     * @date Apr 12, 2015
     * @version 1.0
     */
    public function getDataByEmail(&$ds_email) {

        try {

            $stmt = $this->_conn->prepare("SELECT "
                . "person.sq_person, ds_name, ds_email, sq_status_news, sq_user, sq_profile, sq_status_user, "
                . "ds_password, sq_address, nu_postcode, ac_state, ds_address, ds_complement, ds_city, "
                . "ds_neighborhood, nu_phone "
                . "FROM fulo.person "
                . "JOIN fulo.user ON (person.sq_person = sq_user) "
                . "LEFT JOIN fulo.address ON (person.sq_person = address.sq_person) "
                . "JOIN fulo.phone ON (person.sq_person = phone.sq_person) "
                . "WHERE ds_email = ?");

            $stmt->execute([
                $ds_email,
            ]);

            return $stmt->fetchObject();
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for update user data access
     * @name upAccess
     * @author Victor Eduardo Barreto
     * @param array $data User data
     * @return bool Result of procedure
     * @date May 19, 2015
     * @version 1.0
     */
    public function upAccess(&$data) {

        try {

            $this->_conn->beginTransaction();

            $stmt = $this->_conn->prepare("UPDATE fulo.user SET ds_password = ? WHERE sq_user = ?");

            $stmt->execute([
                $data->ds_password,
                $data->origin_sq_person,
            ]);

            # save log operation.
            $this->saveLog($data->origin_sq_user, $data->sq_user);

            return $this->_conn->commit();
        } catch (Exception $ex) {

            $this->_conn->rollback();

            throw $ex;
        }
    }

    /**
     * Method for inactivate user
     * @name inactivateUser
     * @author Victor Eduardo Barreto
     * @param object $data User data
     * @return bool Result of procedure
     * @date Jul 23, 2015
     * @version 1.0
     */
    public function inactivateUser(&$data) {
        try {

            $this->_conn->beginTransaction();

            $stmt = $this->_conn->prepare("UPDATE fulo.user SET sq_status_user = ? WHERE sq_user = ?");

            $stmt->execute([
                STATUS_INACTIVE,
                $data->sq_user,
            ]);

            # save log operation.
            $this->saveLog($data->origin_sq_user, $data->sq_user);

            return $this->_conn->commit();
        } catch (Exception $ex) {

            $this->_conn->rollBack();

            throw $ex;
        }
    }

    /**
     * Method for activate user
     * @name activateUser
     * @author Victor Eduardo Barreto
     * @param object $data User data
     * @return bool Result of procedure
     * @date Jul 23, 2015
     * @version 1.0
     */
    public function activateUser(&$data) {
        try {

            $this->_conn->beginTransaction();

            $stmt = $this->_conn->prepare("UPDATE fulo.user SET sq_status_user = ? WHERE sq_user = ?");

            $stmt->execute([
                STATUS_ACTIVE,
                $data->sq_user,
            ]);

            # save log operation.
            $this->saveLog($data->origin_sq_user, $data->sq_user);

            return $this->_conn->commit();
        } catch (Exception $ex) {

            $this->_conn->rollBack();

            throw $ex;
        }
    }

    /**
     * Method for update address
     * @name upAddress
     * @author Victor Eduardo Barreto
     * @param Object $data Data user
     * @return bool Result of procedure
     * @date Jul 29, 2015
     * @version 1.0
     */
    public function upAddress(&$data) {

        try {

            $this->_conn->beginTransaction();

            $stmt = $this->_conn->prepare("UPDATE fulo.address "
                . "SET nu_postcode = ? , ac_state = ?, ds_address = ?, ds_complement = ?, ds_city = ?, "
                . "ds_neighborhood = ? "
                . "WHERE sq_address = ?");

            $stmt->execute([
                $data->nu_postcode,
                $data->ac_state,
                $data->ds_address,
                $data->ds_complement,
                $data->ds_city,
                $data->ds_neighborhood,
                $data->sq_address,
            ]);

            # save log operation.
            $this->saveLog($data->origin_sq_user, $data->sq_user);

            return $this->_conn->commit();
        } catch (Exception $ex) {

            $this->_conn->rollBack();

            throw $ex;
        }
    }

    /**
     * Method for get total number of products in wishlist
     * @name getNuWishList
     * @author Victor Eduardo Barreto
     * @package fulo\model
     * @param object $data Data user
     * @return int Number of products in wishlist
     * @date Alg 29, 2015
     * @version 1.0
     */
    public function getNuWishList(&$data) {

        try {

            $stmt = $this->_conn->prepare("SELECT "
                . "COUNT(sq_user) as nu_wishlist "
                . "FROM fulo.wishlist "
                . "WHERE sq_user = ?");

            $stmt->execute([
                $data->sq_user,
            ]);

            return $stmt->fetchObject();
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for get orders of user
     * @name getOrdersByUser
     * @author Victor Eduardo Barreto
     * @package fulo\model
     * @param object $data Data user
     * @return Object Orders of user
     * @date Dec 31, 2015
     * @version 1.0
     */
    public function getOrdersByUser(&$data) {

        try {

            $stmt = $this->_conn->prepare("SELECT "
                . "sq_order, nu_quantity, nu_total, nu_tracker, nu_date_time, ds_status, sq_status, ds_address, nu_phone, "
                . "nu_farevalue, ds_name "
                . "FROM fulo.order "
                . "JOIN fulo.status ON (fulo.order.st_status = status.sq_status) "
                . "JOIN fulo.person ON (fulo.order.sq_user = person.sq_person) "
                . "WHERE sq_user = ?"
                . "ORDER BY sq_order ASC"
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
     * Method for get orders
     * @name getOrders
     * @author Victor Eduardo Barreto
     * @package fulo\model
     * @param object $data Data user
     * @return Object Orders of user
     * @date Dec 31, 2015
     * @version 1.0
     */
    public function getOrders() {

        try {

            $stmt = $this->_conn->prepare("SELECT "
                . "sq_order, nu_quantity, nu_total, nu_tracker, nu_date_time, ds_status, nu_farevalue, st_status,"
                . "ds_address "
                . "FROM fulo.order "
                . "JOIN fulo.status ON (fulo.order.st_status = status.sq_status) "
                . "Order By sq_order ASC"
            );

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for get products of order
     * @name getProductsOrder
     * @author Victor Eduardo Barreto
     * @package fulo\model
     * @param object $data Data user
     * @return Object Products of orders
     * @date Jan 1, 2016
     * @version 1.0
     */
    public function getProductsOrder(&$data) {

        try {

            $stmt = $this->_conn->prepare("SELECT "
                . "ds_product, nu_value, nu_quantity "
                . "FROM fulo.order_products "
                . "WHERE sq_order = ?");

            $stmt->execute([
                $data->sq_order,
            ]);

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $ex) {

            throw $ex;
        }
    }

}
