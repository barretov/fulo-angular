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
 * Mater class for business
 * @name MasterBusiness
 * @author Victor Eduardo Barreto
 * @date Apr 12, 2015
 * @version 1.0
 */
class MasterBusiness
{

    /**
     * Method for remove special char of data
     * @name removeSpecialChar
     * @author Victor Eduardo Barreto
     * @param object or array or string $data Data to remove special char
     * @date Apr 12, 2015
     * @version 1.0
     */
    public function removeSpecialChar (& $data)
    {

        try {

            foreach ($data as $key => $value) {

                if ($key != "im_image") {

                    $data->$key = \preg_replace("/[^a-zA-Z0-9_@.,áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ]/", "", $value);
                }
            }

            # set email to lower case.
            (!empty($data->ds_email) ? $data->ds_email = strtolower($data->ds_email) : '');
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for encrypt data
     * @name encrypt
     * @author Victor Eduardo Barreto
     * @param string $data Data for encryption
     * @return string Encrypted data
     * @date Jul 08, 2015
     * @version 1.0
     */
    public function encrypt ($data)
    {

        try {

            return trim(
                    base64_encode(
                            mcrypt_encrypt(
                                    MCRYPT_RIJNDAEL_256, ENCRYPT_SALT, $data, MCRYPT_MODE_ECB, mcrypt_create_iv(
                                            mcrypt_get_iv_size(
                                                    MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB
                                            ), MCRYPT_RAND
                                    )
                            )
                    )
            );
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for deencrypt data
     * @name decrypt
     * @author Victor Eduardo Barreto
     * @param string $data Data for decryption
     * @return string decrypted data
     * @date Jul 08, 2015
     * @version 1.0
     */
    public function decrypt ($data)
    {

        try {

            return trim(
                    mcrypt_decrypt(
                            MCRYPT_RIJNDAEL_256, ENCRYPT_SALT, base64_decode($data), MCRYPT_MODE_ECB, mcrypt_create_iv(
                                    mcrypt_get_iv_size(
                                            MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB
                                    ), MCRYPT_RAND
                            )
                    )
            );
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for transform array in object
     * @name arrayToObject
     * @author Victor Eduardo Barreto
     * @param array $data Data of request
     * @return object Data of request
     * @date Alg 12, 2015
     * @version 1.0
     */
    public function arrayToObject (& $data)
    {

        try {

            $object = new \stdClass();

            if (!empty($data)) {

                foreach ($data as $key => $value) {

                    $object->$key = $value;
                }
            }

            $data = $object;

            return $data;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Method for request data
     * @name arrayToObject
     * @author Victor Eduardo Barreto
     * @return object Data of request
     * @date Alg 13, 2015
     * @version 1.0
     */
    public function getRequestData ()
    {

        try {

            # get request method and get data of request.
            switch (\Slim\Slim::getInstance()->request()->getMethod()) {

                case "GET":

                    $data = \Slim\Slim::getInstance()->request->params();
                    $this->arrayToObject($data);
                    break;

                case "POST":

                    $data = json_decode(\Slim\Slim::getInstance()->request()->getBody());
                    break;
            }

            if (!empty($data->origin) && $data->origin != "undefined") {

                # decrypt data secret.
                $origin = $this->decrypt($data->origin);

                # remove origin hash.
                unset($data->origin);

                # add decrypted secret in data.
                foreach (json_decode($origin) as $key => $value) {

                    $data->$key = $value;
                }
            }

            $this->removeSpecialChar($data);

            return $data;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}
