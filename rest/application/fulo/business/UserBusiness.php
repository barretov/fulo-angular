<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace fulo\business;

/**
 * Descrição da classe
 * @name usuario_business
 * @author Victor Eduardo Barreto
 * @date Apr 3, 2015
 * @version 1.0
 */
class UserBusiness
{

    private function userModel ()
    {
        return new \fulo\model\UserModel();
    }

    public function addUser ($data)
    {

        # aplica regras de negócio.
        try {

            # verify if is update.
            if ($data->isUpdate) {

                return $this->userModel()->upUser($data);
            } else {

                return $this->userModel()->addUser($data);
            }

            # launch exception.
        } catch (Exception $ex) {
            throw new Exception($ex, null, null);
        }
    }

    public function getUsers ()
    {

        # aplica regras de negócio.
        try {
            return $this->userModel()->getUsers();
        } catch (Exception $ex) {
            throw new Exception($ex, null, null);
        }
    }

    public function getUser ($sq_pessoa)
    {
        try {

            return $this->userModel()->getUser($sq_pessoa);
        } catch (Exception $ex) {
            throw new Exception($ex, null, null);
        }
    }

    public function delUser ($sq_pessoa)
    {
        try {

            return $this->userModel()->delUser($sq_pessoa);
        } catch (Exception $ex) {
            throw new Exception($ex, null, null);
        }
    }

}
