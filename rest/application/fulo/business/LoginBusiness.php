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
     * Method for log in the system
     * @name doLogin
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @param array $data Data for user
     * @return bool Result of procedure
     * @date Apr 14, 2015
     * @version 1.0
     */
    public function doLogin (& $data)
    {

        try {

            # remove special char and spaces.
            $this->removeSpecialChar($data);

            # model of user.
            $modelUser = new UserModel();

            # get user data.
            $dataUser = $modelUser->getDataByEmail($data->ds_email);

            # compare user and pass to login in the system.
            if ($dataUser['ds_email'] === $data->ds_email && crypt($data->ds_senha, $dataUser['ds_senha']) === $dataUser['ds_senha']) {

                # add ip user.
                $dataUser['no_ip'] = $_SERVER['REMOTE_ADDR'];

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
     * @param array $data Data for user
     * @return bool Result of procedure
     * @date jun 15, 2015
     * @version 1.0
     */
    public function doLogoff (& $data)
    {

        try {

            # validate origin.
            $this->validateOrigin($data);

            # remove special char and spaces.
            $this->removeSpecialChar($data);

            # model of user.
            $modelUser = new UserModel();

            # TODO write in logoff.

            return true;
        } catch (Exception $ex) {

            throw $ex;
        }
    }

}
