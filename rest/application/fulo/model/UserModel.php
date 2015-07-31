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
 * Model class for users
 * @name UserModel
 * @author Victor Eduardo Barreto
 * @date Apr 8, 2015
 * @version 1.0
 */
class UserModel extends MasterModel
{

    /**
     * Method for add user
     * @name addUser
     * @author Victor Eduardo Barreto
     * @param array $data User data
     * @return bool Result of procedure
     * @date Apr 8, 2015
     * @version 1.0
     */
    public function addUser (& $data)
    {

        try {

            $this->_conn->beginTransaction();

            $stmtPerson = $this->_conn->prepare("INSERT INTO fulo.person (ds_name, ds_email) VALUES (?,?)");

            $stmtPerson->execute([
                $data->ds_name,
                $data->ds_email,
            ]);

            $stmtUser = $this->_conn->prepare("INSERT INTO fulo.user (sq_user, sq_profile, ds_password) VALUES (?,?,?)");

            $stmtUser->execute([
                $this->_conn->lastInsertId('fulo.person_sq_person_seq'),
                $data->sq_profile,
                $data->ds_password,
            ]);

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
    public function upUser (& $data)
    {
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
                $data->sq_person
            ]);

            # set $stmt to update user.
            $stmtUser = $this->_conn->prepare("UPDATE fulo.user SET sq_profile = ? WHERE sq_user = ?");

            $stmtUser->execute([
                $data->sq_profile,
                $data->sq_person,
            ]);

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
    public function getUsers ($sq_user)
    {
        try {

            $stmt = $this->_conn->prepare("SELECT "
                    . "person.sq_person, ds_name, ds_email, sq_status_news, sq_user, sq_profile, sq_status_user, "
                    . "sq_address, nu_postcode, ac_state, ds_address, ds_complement, ds_city "
                    . "FROM fulo.person "
                    . "JOIN fulo.user ON (person.sq_person = sq_user) "
                    . "LEFT JOIN fulo.address ON (person.sq_person = address.sq_person)"
                    . "WHERE sq_user <> ? ORDER BY ds_name ASC"
            );

            $stmt->execute([
                $sq_user
            ]);

            return $stmt->fetchAll();
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
    public function getDataByIdentity (& $sq_person)
    {
        try {

            $stmt = $this->_conn->prepare("SELECT "
                    . "person.sq_person, ds_name, ds_email, sq_status_news, sq_user, sq_profile, sq_status_user, "
                    . "ds_password, sq_address, nu_postcode, ac_state, ds_address, ds_complement, ds_city "
                    . "FROM fulo.person "
                    . "JOIN fulo.user ON (person.sq_person = sq_user) "
                    . "LEFT JOIN fulo.address ON (person.sq_person = address.sq_person)"
                    . "WHERE person.sq_person = ?");

            $stmt->execute([
                $sq_person
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
    public function getDataByEmail (& $ds_email)
    {

        try {

            $stmt = $this->_conn->prepare("SELECT "
                    . "person.sq_person, ds_name, ds_email, sq_status_news, sq_user, sq_profile, sq_status_user, "
                    . "ds_password, sq_address, nu_postcode, ac_state, ds_address, ds_complement, ds_city "
                    . "FROM fulo.person "
                    . "JOIN fulo.user ON (person.sq_person = sq_user) "
                    . "LEFT JOIN fulo.address ON (person.sq_person = address.sq_person)"
                    . "WHERE ds_email = ?");

            $stmt->execute([
                $ds_email
            ]);

            return $stmt->fetchObject();
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for update user data access
     * @name updateDataAccess
     * @author Victor Eduardo Barreto
     * @param array $data User data
     * @return bool Result of procedure
     * @date May 19, 2015
     * @version 1.0
     */
    public function updateDataAccess (& $data)
    {

        try {

            $this->_conn->beginTransaction();

            $stmt = $this->_conn->prepare("UPDATE fulo.user SET ds_password = ? WHERE sq_user = ?");

            $stmt->execute([
                $data->ds_password,
                $data->origin_sq_person,
            ]);

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
     * @param int $sq_user User identifier
     * @return bool Result of procedure
     * @date Jul 23, 2015
     * @version 1.0
     */
    public function inactivateUser (& $sq_user)
    {
        try {

            $this->_conn->beginTransaction();

            $stmt = $this->_conn->prepare("UPDATE fulo.user SET sq_status_user = ? WHERE sq_user = ?");

            $stmt->execute([
                STATUS_INACTIVE,
                $sq_user
            ]);

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
     * @param int $sq_user User identifier
     * @return bool Result of procedure
     * @date Jul 23, 2015
     * @version 1.0
     */
    public function activateUser (& $sq_user)
    {
        try {

            $this->_conn->beginTransaction();

            $stmt = $this->_conn->prepare("UPDATE fulo.user SET sq_status_user = ? WHERE sq_user = ?");

            $stmt->execute([
                STATUS_ACTIVE,
                $sq_user
            ]);

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
    public function upAddress (& $data)
    {
        try {

            $this->_conn->beginTransaction();

            $stmt = $this->_conn->prepare("UPDATE fulo.address "
                    . "SET nu_postcode = ? , ac_state = ?, ds_address = ?, ds_complement = ?, ds_city = ? "
                    . "WHERE sq_address = ?");

            $stmt->execute([
                $data->nu_postcode,
                $data->ac_state,
                $data->ds_address,
                $data->ds_complement,
                $data->ds_city,
                $data->sq_address
            ]);

            return $this->_conn->commit();
        } catch (Exception $ex) {

            $this->_conn->rollBack();

            throw $ex;
        }
    }

    /**
     * Method for add address
     * @name addAddress
     * @author Victor Eduardo Barreto
     * @param Object $data Data user
     * @return bool Result of procedure
     * @date Jul 30, 2015
     * @version 1.0
     */
    public function addAddress (& $data)
    {
        try {

            $this->_conn->beginTransaction();

            $stmt = $this->_conn->prepare("INSERT INTO fulo.address "
                    . "(sq_person, nu_postcode, ac_state, ds_address, ds_complement, ds_city) "
                    . "VALUES (?,?,?,?,?,?)");

            $stmt->execute([
                $data->sq_person,
                $data->nu_postcode,
                $data->ac_state,
                $data->ds_address,
                $data->ds_complement,
                $data->ds_city
            ]);

            return $this->_conn->commit();
        } catch (Exception $ex) {

            $this->_conn->rollBack();

            throw $ex;
        }
    }

}
