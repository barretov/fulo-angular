<?php

/*
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
 * Class to keep generic functions for business
 * @name MasterBusiness
 * @author Victor Eduardo Barreto
 * @date Apr 12, 2015
 * @version 1.0
 */
abstract class MasterBusiness
{

    /**
     * Method for remove special char of data
     * @name removeSpecialChar
     * @author Victor Eduardo Barreto
     * @param object or array or string $data Data to remove special char
     * @date Apr 12, 2015
     * @version 1.0
     */
    protected function removeSpecialChar (& $data)
    {

        try {

            # verify the type of variable.
            # array.
            if (is_array($data)) {

                foreach ($data as $key => $value) {
                    $data->$key = preg_replace("/[^a-zA-Z0-9_@.,áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ]/", "", $value);
                }

                # object.
            } elseif (is_object($data)) {

                foreach ($data as $key => $value) {
                    $data->$key = preg_replace("/[^a-zA-Z0-9_@.,áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ]/", "", $value);
                }

                # other types.
            } else {

                $data = preg_replace("/[^a-zA-Z0-9_@.,áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ]/", "", $data);
            }
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for validate the credential and ip of user
     * @name validateOrigin
     * @author Victor Eduardo Barreto
     * @param object $data Data of request
     * @return bool FALSE case the data is fake
     * @date June 15, 2015
     * @version 1.0
     */
    protected function validateOrigin (& $data)
    {

        # verify if arrived is array.
        if (is_array($data) && !empty($data)) {


            $object = new \stdClass();

            # change array to object.
            foreach ($data as $key => $value) {

                $object->$key = $value;
            }

            # save object in $data;
            $data = $object;
        }

        # TODO validade hash access.
        # validate origin ip.
        if (empty($data->origin_no_ip) || $_SERVER['REMOTE_ADDR'] != $data->origin_no_ip) {

            # stop the request.
            echo json_encode('ip_changed');
            \Slim\Slim::getInstance()->stop();
        }
    }

}
