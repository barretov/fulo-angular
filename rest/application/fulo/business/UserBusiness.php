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

namespace fulo\business;

/**
 * Class of business for users
 * @name UserBusiness
 * @author Victor Eduardo Barreto
 * @package fulo\business
 * @date Apr 8, 2015
 * @version 1.0
 */
class UserBusiness
{

    /**
     * Method to retuen a instance model of user
     * @name userModel
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @return object Model of user
     * @date Apr 8, 2015
     * @version 1.0
     */
    private function userModel ()
    {
        try {

            return new \fulo\model\UserModel();
        } catch (Exception $ex) {

            throw new $ex;
        }
    }

    /**
     * Method for business to add user
     * @name addUser
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @param array $data Data for user
     * @return bool Result of procedure
     * @date Apr 8, 2015
     * @version 1.0
     */
    public function addUser ($data)
    {

        try {

            # verify if e-mail already exists.
            if ($this->verifyEmailExists($data->ds_email)) {

                return "email-already";
            }

            # send to the model of user for add and return for controller.
            return $this->userModel()->addUser($data);
        } catch (Exception $ex) {

            throw new $ex;
        }
    }

    /**
     * Method for business to up user
     * @name upUser
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @param array $data Data for user
     * @return bool Result of procedure
     * @date Apr 8, 2015
     * @version 1.0
     */
    public function upUser ($data)
    {

        try {

            # verify if e-mail already exists.
            if ($this->verifyEmailExists($data->ds_email)) {

                # get current email in the base.
                $currentEmail = $this->userModel()->getUserByIdenty($data->sq_pessoa);

                # if don't change email, do the update.
                if ($data->ds_email != $currentEmail['ds_email']) {

                    return "email-already";
                }
            }

            # send to the model of user for update and return for controller.
            return $this->userModel()->upUser($data);
        } catch (Exception $ex) {

            throw new $ex;
        }
    }

    /**
     * Method for business to get users data
     * @name getUser
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @return array Data of users
     * @date Apr 8, 2015
     * @version 1.0
     */
    public function getUsers ()
    {

        try {

            return $this->userModel()->getUsers();
        } catch (Exception $ex) {

            throw new $ex;
        }
    }

    /**
     * Method for business to get user data
     * @name getUser
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @param int $sq_pessoa Identifier of user
     * @return array Data of user selected
     * @date Apr 8, 2015
     * @version 1.0
     */
    public function getUser ($sq_pessoa)
    {
        try {

            return $this->userModel()->getUserByIdenty($sq_pessoa);
        } catch (Exception $ex) {

            throw new $ex;
        }
    }

    /**
     * Method for business to del user
     * @name delUser
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @param int $sq_pessoa Identifier of user
     * @return bool Result of procedure
     * @date Apr 8, 2015
     * @version 1.0
     */
    public function delUser ($sq_pessoa)
    {
        try {

            return $this->userModel()->delUser($sq_pessoa);
        } catch (Exception $ex) {

            throw new $ex;
        }
    }

    /**
     * Method for verify if email already existis
     * @name verifyEmailExists
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @param string $ds_email Email of user
     * @return bool Result of procedure
     * @date Apr 12, 2015
     * @version 1.0
     */
    public function verifyEmailExists ($ds_email)
    {

        try {

            # get user data.
            $UserData = $this->userModel()->gerDataByEmail($ds_email);

            # compare the email.
            if ($UserData['ds_email'] === $ds_email) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {

            throw new $ex;
        }
    }

}
