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
    public function getProfiles ($data)
    {

        # validate origin.
        $this->validateOrigin($data);

        return $this->_domainModel->getProfiles();
    }

}
