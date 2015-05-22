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
 * Set a better name for data base connection.
 */
use config\Conexao as getConn;

/**
 * Class of model for users
 * @name UserModel
 * @author Victor Eduardo Barreto
 * @date Apr 8, 2015
 * @version 1.0
 */
class UserModel
{

    /**
     * Method for add user
     * @name addUser
     * @author Victor Eduardo Barreto
     * @param array $data Data for user
     * @return bool Result of procedure
     * @date Apr 8, 2015
     * @version 1.0
     */
    public function addUser (& $data)
    {

        try {

            $conn = getConn::getConnect();

            $conn->beginTransaction();

            $stmt = $conn->prepare("INSERT INTO fulo.pessoa (ds_nome, ds_email) VALUES (?,?)");

            $stmt->execute(array(
                $data->ds_nome,
                $data->ds_email,
            ));

            $stmt = $conn->prepare("INSERT INTO fulo.usuario (sq_usuario, sq_perfil, ds_senha) VALUES (?,?,?)");

            $stmt->execute(array(
                $conn->lastInsertId('fulo.pessoa_sq_pessoa_seq'),
                $data->sq_perfil,
                $data->ds_senha,
            ));

            return $conn->commit();
        } catch (Exception $ex) {

            $conn->rollback();

            throw $ex;
        }
    }

    /**
     * Method for update user
     * @name upUser
     * @author Victor Eduardo Barreto
     * @param array $data Data for user
     * @return bool Result of procedure
     * @date Apr 8, 2015
     * @version 1.0
     */
    public function upUser (& $data)
    {
        try {

            $conn = getConn::getConnect();

            $conn->beginTransaction();

            # set $stmt to update pessoa.
            $stmt = $conn->prepare("UPDATE fulo.pessoa SET ds_nome = ?, ds_email = ? WHERE sq_pessoa = ?");

            $stmt->execute(array(
                $data->ds_nome,
                $data->ds_email,
                $data->sq_pessoa,
            ));

            # set $stmt to update usuario.
            $stmt = $conn->prepare("UPDATE fulo.usuario SET sq_perfil = ? WHERE sq_usuario = ?");

            $stmt->execute(array(
                $data->sq_perfil,
                $data->sq_pessoa,
            ));

            return $conn->commit();
        } catch (Exception $ex) {

            $conn->rollback();

            throw $ex;
        }
    }

    /**
     * Method for get users
     * @name getUser
     * @author Victor Eduardo Barreto
     * @return array Data of users
     * @param int Id of loged user
     * @date Apr 8, 2015
     * @version 1.0
     */
    public function getUsers ($sq_pessoa)
    {
        try {

            $conn = getConn::getConnect();

            $stmt = $conn->prepare(
                    "SELECT sq_pessoa, ds_nome, ds_email, sq_perfil FROM fulo.pessoa "
                    . "JOIN fulo.usuario on pessoa.sq_pessoa = usuario.sq_usuario "
                    . "and pessoa.sq_pessoa <> $sq_pessoa ORDER BY pessoa.ds_nome ASC"
            );

            $stmt->execute();

            return $stmt->fetchAll();
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for get user by Identy
     * @name getUserByIdenty
     * @author Victor Eduardo Barreto
     * @param int $sq_pessoa Identifier of user
     * @return array Data of user selected
     * @date Apr 8, 2015
     * @version 1.0
     */
    public function getUserByIdenty (& $sq_pessoa)
    {
        try {

            $conn = getConn::getConnect();

            $stmt = $conn->prepare("SELECT sq_pessoa, ds_nome, ds_email, sq_perfil "
                    . "FROM fulo.pessoa JOIN fulo.usuario on pessoa.sq_pessoa = usuario.sq_usuario "
                    . "WHERE sq_pessoa = ?");

            $stmt->execute(array(
                $sq_pessoa
            ));

            return $stmt->fetch();
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for del user
     * @name delUser
     * @author Victor Eduardo Barreto
     * @param int $sq_pessoa Identifier of user
     * @return bool Result of procedure
     * @date Apr 8, 2015
     * @version 1.0
     */
    public function delUser (& $sq_pessoa)
    {
        try {

            $conn = getConn::getConnect();

            $conn->beginTransaction();

            $stmt = $conn->prepare("DELETE FROM fulo.pessoa WHERE sq_pessoa = ?");

            $stmt->execute(array(
                $sq_pessoa
            ));

            return $conn->commit();
        } catch (Exception $ex) {

            $conn->rollBack();

            throw $ex;
        }
    }

    /**
     * Method for get all data of user by email
     * @name gerDataByEmail
     * @author Victor Eduardo Barreto
     * @package fulo\model
     * @param string $ds_email Email of user
     * @return array Data of user
     * @date Apr 12, 2015
     * @version 1.0
     */
    public function getDataByEmail (& $ds_email)
    {

        try {

            $conn = getConn::getConnect();

            $stmt = $conn->prepare("SELECT * FROM fulo.pessoa JOIN fulo.usuario on pessoa.sq_pessoa = usuario.sq_usuario WHERE ds_email = ?");

            $stmt->execute(array(
                $ds_email
            ));

            return $stmt->fetch();
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for update user data access
     * @name updateDataAccess
     * @author Victor Eduardo Barreto
     * @param array $data Data for user
     * @return bool Result of procedure
     * @date May 19, 2015
     * @version 1.0
     */
    public function updateDataAccess (& $data)
    {

        try {

            $conn = getConn::getConnect();

            $conn->beginTransaction();

            $stmt = $conn->prepare("UPDATE fulo.usuario SET ds_senha = ? WHERE sq_usuario = ?");

            $stmt->execute(array(
                $data->ds_senha,
                $data->sq_usuario,
            ));

            return $conn->commit();
        } catch (Exception $ex) {

            $conn->rollback();

            throw $ex;
        }
    }

}
