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

use fulo\model\DomainModel as DomainModel;

/**
 * Class of business for domain
 * @name DomainBusiness
 * @author Victor Eduardo Barreto
 * @package fulo\business
 * @date Jun 19, 2015
 * @version 1.0
 */
class DomainBusiness extends MasterBusiness
{

    /**
     * variable for instance of domain model
     * @var object
     */
    private $_domainModel;

    /**
     * Method constructor of class
     * @name __construct
     * @author Victor Eduardo Barreto
     * @package fulo\business
     * @return object Model of domain
     * @date Jun 19, 2015
     * @version 1.0
     */
    public function __construct ()
    {
        $this->_domainModel = new DomainModel();
    }

    /**
     * Method for get domain profiles
     * @name getProfiles
     * @author Victor Eduardo Barreto
     * @patam Object $data Data of origin
     * @return Data of profiles
     * @date June 19, 2015
     * @version 1.0
     */
    public function getProfiles (& $data)
    {
        try {

            $this->validateSecret($data);

            return $this->_domainModel->getProfiles();
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for get secret
     * @name getSecret
     * @author Victor Eduardo Barreto
     * @return json Data of users
     * @date Jul 8, 2015
     * @version 1.0
     */
    public function getSecret ()
    {

        try {

            return crypt($_SERVER['REMOTE_ADDR'] . $_SERVER['SERVER_ADDR'], ENCRYPT_SALT);
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for get constants
     * @name getConstants
     * @author Victor Eduardo Barreto
     * @patam Object $data Data of origin
     * @return array Constants
     * @date Jul 8, 2015
     * @version 1.0
     */
    public function getConstants (& $data)
    {

        try {

            $this->validateSecret($data);

            # load constants file
            $constant = parse_ini_file('./application/config/constants.ini', true);

            # merge constants for frontend.
            return $constants = array_merge($constant['frontend'], $constant['both']);
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for business of get postal data by zip code
     * @name getAddressByZip
     * @author Victor Eduardo Barreto
     * @param String $data User data
     * @return Object Data address
     * @date Jul 31, 2015
     * @version 1.0
     */
    public function getAddressByZip (& $data)
    {

        try {

            $this->validateSecret($data);

            $this->removeSpecialChar($data);

            return $this->_domainModel->getAddressByZip($data);
        } catch (Exception $ex) {

            throw $ex;
        }
    }

}
