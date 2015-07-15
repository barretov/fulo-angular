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
    public function addUser (& $data) {

        try {

            $this->_conn->beginTransaction();

            $stmt = $this->_conn->prepare("INSERT INTO fulo.pessoa (ds_nome, ds_email) VALUES (?,?)");

            $stmt->execute([
                $data->ds_nome,
                $data->ds_email,
            ]);

            $stmt = $this->_conn->prepare("INSERT INTO fulo.usuario (sq_usuario, sq_perfil, ds_senha) VALUES (?,?,?)");

            $stmt->execute([
                $this->_conn->lastInsertId('fulo.pessoa_sq_pessoa_seq'),
                $data->sq_perfil,
                $data->ds_senha,
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
    public function upUser (& $data) {
        try {

            $this->_conn->beginTransaction();

            # set $stmt to update pessoa.
            $stmt = $this->_conn->prepare("UPDATE fulo.pessoa SET ds_nome = ?, ds_email = ? WHERE sq_pessoa = ?");

            $stmt->execute([
                $data->ds_nome,
                $data->ds_email,
                $data->sq_pessoa,
            ]);

            # set $stmt to update usuario.
            $stmt = $this->_conn->prepare("UPDATE fulo.usuario SET sq_perfil = ? WHERE sq_usuario = ?");

            $stmt->execute([
                $data->sq_perfil,
                $data->sq_pessoa,
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
     * @param int $sq_pessoa Id of loged user
     * @date Apr 8, 2015
     * @version 1.0
     */
    public function getUsers ($sq_pessoa) {
        try {

            $stmt = $this->_conn->prepare(
                "SELECT sq_pessoa, ds_nome, ds_email, sq_perfil FROM fulo.pessoa "
                . "JOIN fulo.usuario on pessoa.sq_pessoa = usuario.sq_usuario "
                . "and pessoa.sq_pessoa <> ? ORDER BY pessoa.ds_nome ASC"
            );

            $stmt->execute([
                $sq_pessoa
            ]);

            return $stmt->fetchAll();
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for get user by Identy
     * @name getUserByIdenty
     * @author Victor Eduardo Barreto
     * @param int $sq_pessoa User identifier
     * @return array Data of user selected
     * @date Apr 8, 2015
     * @version 1.0
     */
    public function getUserByIdenty (& $sq_pessoa) {
        try {

            $stmt = $this->_conn->prepare("SELECT sq_pessoa, ds_nome, ds_email, sq_perfil "
                . "FROM fulo.pessoa JOIN fulo.usuario on pessoa.sq_pessoa = usuario.sq_usuario "
                . "WHERE sq_pessoa = ?");

            $stmt->execute([
                $sq_pessoa
            ]);

            return $stmt->fetchObject();
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for del user
     * @name delUser
     * @author Victor Eduardo Barreto
     * @param int $sq_pessoa User identifier
     * @return bool Result of procedure
     * @date Apr 8, 2015
     * @version 1.0
     */
    public function delUser (& $sq_pessoa) {
        try {

            $this->_conn->beginTransaction();

            $stmt = $this->_conn->prepare("DELETE FROM fulo.pessoa WHERE sq_pessoa = ?");

            $stmt->execute([
                $sq_pessoa
            ]);

            return $this->_conn->commit();
        } catch (Exception $ex) {

            $this->_conn->rollBack();

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
    public function getDataByEmail (& $ds_email) {

        try {

            $stmt = $this->_conn->prepare("SELECT * FROM fulo.pessoa JOIN fulo.usuario on pessoa.sq_pessoa = usuario.sq_usuario WHERE ds_email = ?");

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
    public function updateDataAccess (& $data) {

        try {

            $this->_conn->beginTransaction();

            $stmt = $this->_conn->prepare("UPDATE fulo.usuario SET ds_senha = ? WHERE sq_usuario = ?");

            $stmt->execute([
                $data->ds_senha,
                $data->origin_sq_pessoa,
            ]);

            return $this->_conn->commit();
        } catch (Exception $ex) {

            $this->_conn->rollback();

            throw $ex;
        }
    }

    /**
     * Method for update Customer
     * @name upCustomer
     * @author Victor Eduardo Barreto
     * @param array $data Customer data
     * @return bool Result of procedure
     * @date Jul 14, 2015
     * @version 1.0
     */
    public function upCustomer (& $data) {
        try {

            $this->_conn->beginTransaction();

            # set $stmt to update pessoa.
            $stmt = $this->_conn->prepare("UPDATE fulo.pessoa SET ds_nome = ?, ds_email = ? WHERE sq_pessoa = ?");

            $stmt->execute([
                $data->ds_nome,
                $data->ds_email,
                $data->origin_sq_pessoa,
            ]);

            # set $stmt to update usuario.
            $stmt = $this->_conn->prepare("UPDATE fulo.usuario SET sq_perfil = ? WHERE sq_usuario = ?");

            $stmt->execute([
                $data->sq_perfil,
                $data->origin_sq_pessoa,
            ]);

            return $this->_conn->commit();
        } catch (Exception $ex) {

            $this->_conn->rollback();

            throw $ex;
        }
    }

}
