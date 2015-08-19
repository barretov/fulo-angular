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
 * Class of business for login
 * @name LoginBusiness
 * @author Victor Eduardo Barreto
 * @package fulo\business
 * @date Apr 14, 2015
 * @version 1.0
 */
class LoginBusiness extends MasterBusiness
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
     * Method for log in the system
     * @name doLogin
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @return bool Result of procedure
     * @date Apr 14, 2015
     * @version 1.0
     */
    public function doLogin ()
    {

        try {

            $data = $this->getRequestData();

            # get user data.
            $dataUser = $this->_userModel->getDataByEmail($data->ds_email);

            # compare user and pass to login in the system.
            if (!empty($dataUser) && $dataUser->ds_email === $data->ds_email && crypt($data->ds_password, $dataUser->ds_password) === $dataUser->ds_password) {

                # make secret.
                $origin = [
                    'origin_sq_person' => $dataUser->sq_person,
                    'origin_sq_user' => $dataUser->sq_user,
                    'origin_sq_address' => $dataUser->sq_address,
                    'origin_sq_profile' => $dataUser->sq_profile,
                ];

                # encript origin data.
                $dataUser->origin = $this->encrypt(json_encode($origin));

                unset($dataUser->ds_password);

                # save log operation.
                $this->_userModel->saveLog($dataUser->sq_user, $dataUser->sq_user);

                return $dataUser;
            }

            return false;
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for logoff in the system
     * @name doLogoff
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @return bool Result of procedure
     * @date jun 15, 2015
     * @version 1.0
     */
    public function doLogoff ()
    {

        try {

            $data = $this->getRequestData();

            # save log operation.
            return $this->_userModel->saveLog($data->sq_user, $data->sq_user);
        } catch (Exception $ex) {

            throw $ex;
        }
    }

}
