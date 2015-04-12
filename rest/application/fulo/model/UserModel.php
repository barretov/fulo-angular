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
    public function addUser ($data)
    {

        try {

            $conn = getConn::getConnect();

            $conn->beginTransaction();

            $pessoa = "INSERT INTO fulo.pessoa (ds_nome,ds_email) VALUES (?,?)";

            $stmt = $conn->prepare($pessoa);

            $stmt->execute(array(
                $data->ds_nome,
                $data->ds_email,
            ));

            # get the sequence of pessoa to insert in the user.
            $sq_usuario = $conn->lastInsertId('fulo.pessoa_sq_pessoa_seq');

            $usuario = "INSERT INTO fulo.usuario (sq_usuario, sq_perfil) VALUES (?,?)";

            $stmt = $conn->prepare($usuario);

            $stmt->execute(array(
                $sq_usuario,
                $data->sq_perfil,
            ));

            return $conn->commit();
        } catch (Exception $ex) {

            $conn->rollback();

            throw new $ex;
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
    public function upUser ($data)
    {
        try {

            $conn = getConn::getConnect();

            $conn->beginTransaction();

            # set queryes for update in tables of person and user.
            $pessoa = "UPDATE fulo.pessoa SET ds_nome = ?,ds_email = ? WHERE sq_pessoa = ?";
            $usuario = "UPDATE fulo.usuario SET sq_perfil = ? WHERE sq_usuario = ?";

            $stmtPessoa = $conn->prepare($pessoa);
            $stmtUsuario = $conn->prepare($usuario);

            $stmtPessoa->execute(array(
                $data->ds_nome,
                $data->ds_email,
                $data->sq_pessoa,
            ));

            $stmtUsuario->execute(array(
                $data->sq_perfil,
                $data->sq_pessoa,
            ));

            return $conn->commit();
        } catch (Exception $ex) {

            $conn->rollback();

            throw new $ex;
        }
    }

    /**
     * Method for get users
     * @name getUser
     * @author Victor Eduardo Barreto
     * @return array Data of users
     * @date Apr 8, 2015
     * @version 1.0
     */
    public function getUsers ()
    {
        try {

            $conn = getConn::getConnect();

            $sql = "SELECT * FROM fulo.pessoa JOIN fulo.usuario on pessoa.sq_pessoa = usuario.sq_usuario";

            $stmt = $conn->prepare($sql);

            $stmt->execute();

            return $stmt->fetchAll();
        } catch (Exception $ex) {

            throw new $ex;
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
    public function getUserByIdenty ($sq_pessoa)
    {
        try {

            $conn = getConn::getConnect();

            $sql = "SELECT * FROM fulo.pessoa JOIN fulo.usuario on pessoa.sq_pessoa = usuario.sq_usuario WHERE sq_pessoa = ?";

            $stmt = $conn->prepare($sql);

            $stmt->execute(array(
                $sq_pessoa
            ));

            return $stmt->fetch();
        } catch (Exception $ex) {

            throw new $ex;
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
    public function delUser ($sq_pessoa)
    {
        try {

            $conn = getConn::getConnect();

            $conn->beginTransaction();

            $sql = "DELETE FROM fulo.pessoa WHERE sq_pessoa = ?";

            $stmt = $conn->prepare($sql);

            $stmt->execute(array(
                $sq_pessoa
            ));

            return $conn->commit();
        } catch (Exception $ex) {

            $conn->rollBack();

            throw new $ex;
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
    public function gerDataByEmail ($ds_email)
    {

        try {

            $conn = getConn::getConnect();

            $sql = "SELECT * FROM fulo.pessoa JOIN fulo.usuario on pessoa.sq_pessoa = usuario.sq_usuario WHERE ds_email = ?";

            $stmt = $conn->prepare($sql);

            $stmt->execute(array(
                $ds_email
            ));

            return $stmt->fetch();
        } catch (Exception $ex) {

            throw new $ex;
        }
    }

}
