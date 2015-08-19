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

use fulo\model\UserModel as UserModel;

/**
 * Class of business for users
 * @name UserBusiness
 * @author Victor Eduardo Barreto
 * @package fulo\business
 * @date Apr 8, 2015
 * @version 1.0
 */
class UserBusiness extends MasterBusiness
{

    /**
     * variable for instance of user model
     * @var object
     */
    private $_userModel;

    /**
     * Method constructor of class
     * @name __construct
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @return object Model of user
     * @date May 3, 2015
     * @version 1.0
     */
    public function __construct ()
    {
        $this->_userModel = new UserModel();
    }

    /**
     * Method for business of add user
     * @name addUser
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @return bool Result of procedure
     * @date Apr 8, 2015
     * @version 1.0
     */
    public function addUser ()
    {

        try {

            $data = $this->getRequestData();

            # verify it's administrator or customer.
            if (empty($data->origin)) {

                # set user perfil sq_profile as customer.
                $data->sq_profile = PROFILE_CUSTOMER;
            }

            # verify if e-mail already exists.
            if (!$this->verifyEmailExists($data)) {

                return EMAIL_ALREADY;
            }

            #cript password.
            $data->ds_password = crypt($data->ds_password);

            # send to the model of user for add and return for controller.
            return $this->_userModel->addUser($data);
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for business of up user
     * @name upUser
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @return bool Result of procedure
     * @date Apr 8, 2015
     * @version 1.0
     */
    public function upUser ()
    {

        try {

            $data = $this->getRequestData();

            # verify if e-mail already exists.
            if (!$this->verifyEmailExists($data)) {

                return EMAIL_ALREADY;
            }

            # verify if profile is customer and adjust data.
            if ($data->origin_sq_profile === PROFILE_CUSTOMER) {

                $data->sq_person = $data->origin_sq_person;
                $data->sq_user = $data->origin_sq_user;
                $data->sq_profile = $data->origin_sq_profile;
            }

            # send to the model of user for update and return for controller.
            return $this->_userModel->upUser($data);
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for business of get users data
     * @name getUsers
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @return array Data of users
     * @date Apr 8, 2015
     * @version 1.0
     */
    public function getUsers ()
    {

        try {

            $data = $this->getRequestData();

            return $this->_userModel->getUsers($data->origin_sq_user);
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for business of get user data
     * @name getUser
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @return array Data of user selected
     * @date Apr 8, 2015
     * @version 1.0
     */
    public function getUser ()
    {
        try {

            $data = $this->getRequestData();

            $return = $this->_userModel->getDataByIdentity($data->sq_person);

            # remove password.
            unset($return->ds_password);

            return $return;
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for verify if email already existis
     * @name verifyEmailExists
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @return bool Result of procedure
     * @date Apr 12, 2015
     * @version 1.0
     */
    public function verifyEmailExists ()
    {

        try {

            $data = $this->getRequestData();

            # get user data.
            $userData = $this->_userModel->getDataByEmail($data->ds_email);

            if ($userData) {

                # if has email get current email in the base if has user loged.
                if (!empty($data->sq_person)) {

                    $currentEmail = $this->_userModel->getDataByIdentity($data->sq_person);

                    # if don't change email, can do the update.
                    if ($data->ds_email === $currentEmail->ds_email) {

                        return $data;
                    }
                }

                # Exist email, but user isn't loged.
                return false;
            }

            # Don't exist email.
            return $data;
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for business of update data access user
     * @name upAccess
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @return bool Result of procedure
     * @date May 19, 2015
     * @version 1.0
     */
    public function upAccess ()
    {

        try {

            $data = $this->getRequestData();

            #cript password.
            $data->ds_password = crypt($data->ds_password);

            # send to the model of user for update and return for controller.
            return $this->_userModel->upAccess($data);
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for business of inactivate user
     * @name inactivateUser
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @return bool Result of procedure
     * @date Jul 23, 2015
     * @version 1.0
     */
    public function inactivateUser ()
    {
        try {

            $data = $this->getRequestData();

            return $this->_userModel->inactivateUser($data);
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for business of activate user
     * @name activateUser
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @return bool Result of procedure
     * @date Jul 23, 2015
     * @version 1.0
     */
    public function activateUser ()
    {
        try {

            $data = $this->getRequestData();

            return $this->_userModel->activateUser($data);
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for business of update address
     * @name upAddress
     * @author Victor Eduardo Barreto
     * @return bool Result of procedure
     * @date Jul 30, 2015
     * @version 1.0
     */
    public function upAddress ()
    {
        try {

            $data = $this->getRequestData();

            # verify if profile is customer and ajust data.
            if ($data->origin_sq_profile === PROFILE_CUSTOMER) {

                $data->sq_address = $data->origin_sq_address;
            }

            return $this->_userModel->upAddress($data);
        } catch (Exception $ex) {

            throw $ex;
        }
    }

}
