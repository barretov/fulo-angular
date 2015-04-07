<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace fulo\model;

use config\Conexao as getConn;

/**
 * Descrição da classe
 * @name usuario_model
 * @author Victor Eduardo Barreto
 * @date Apr 3, 2015
 * @version 1.0
 */
class UserModel
{

    public function addUser ($data)
    {

        try {

            # get connection.
            $conn = getConn::getConnect();

            # init transaction.
            $conn->beginTransaction();

            # define the query to add a user.
            $pessoa = "INSERT INTO pessoa (ds_nome,ds_email) VALUES (?,?)";

            # prepare.
            $stmt = $conn->prepare($pessoa);

            # exec.
            $stmt->execute(array(
                $data->ds_nome,
                $data->ds_email,
            ));

            # get the sequence of pessoa to insert in the user.
            $sq_usuario = $conn->lastInsertId('pessoa_sq_pessoa_seq');

            # prepare to inser usuario.
            $usuario = "INSERT INTO usuario (sq_usuario, sq_perfil) VALUES (?,?)";

            # prepare.
            $stmt = $conn->prepare($usuario);

            # exec.
            $stmt->execute(array(
                $sq_usuario,
                $data->sq_perfil,
            ));

            return $conn->commit();
        } catch (Exception $ex) {

            $conn->rollback();
            return false;
        }
    }

    public function upUser ($data)
    {
        try {

            # get connection.
            $conn = getConn::getConnect();

            # init transaction.
            $conn->beginTransaction();

            $pessoa = "UPDATE pessoa SET ds_nome = ?,ds_email = ? WHERE sq_pessoa = ?";
            $usuario = "UPDATE usuario SET sq_perfil = ? WHERE sq_usuario = ?";

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
            return false;
        }
    }

    public function getUsers ()
    {
        try {

            # get connection.
            $conn = getConn::getConnect();

            $sql = "SELECT * FROM pessoa JOIN usuario on pessoa.sq_pessoa = usuario.sq_usuario";

            $stmt = $conn->prepare($sql);

            $stmt->execute();

            return $stmt->fetchAll();
        } catch (Exception $ex) {
            return false;
        }
    }

    public function getUser ($sq_pessoa)
    {
        try {

            # get connection.
            $conn = getConn::getConnect();

            $sql = "SELECT * FROM pessoa JOIN usuario on pessoa.sq_pessoa = usuario.sq_usuario WHERE sq_pessoa = ?";

            $stmt = $conn->prepare($sql);

            $stmt->execute(array(
                $sq_pessoa
            ));

            return $stmt->fetch();
        } catch (Exception $ex) {
            return false;
        }
    }

    public function delUser ($sq_pessoa)
    {
        try {

            # get connection.
            $conn = getConn::getConnect();

            # init transaction.
            $conn->beginTransaction();

            # set query to del a user.
            $sql = "DELETE FROM pessoa WHERE sq_pessoa = ?";

            $stmt = $conn->prepare($sql);

            $stmt->execute(array(
                $sq_pessoa
            ));

            return $conn->commit();
        } catch (Exception $ex) {

            $conn->rollBack();

            return false;
        }
    }

}
