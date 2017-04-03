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
use fulo\model\ProductModel as ProductModel;

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
     * @return Data of profiles
     * @date June 19, 2015
     * @version 1.0
     */
    public function getProfiles ()
    {
    	try {

    		return $this->_domainModel->getProfiles();
    	} catch (Exception $ex) {

    		throw $ex;
    	}
    }

    /**
     * Method for get secret and constants
     * @name getBasic
     * @author Victor Eduardo Barreto
     * @return json Secret and constants
     * @date Jul 8, 2015
     * @version 1.0
     */
    public function getBasic ()
    {

    	try {

            # read constants.
    		$constant = parse_ini_file('./application/config/constants.ini', true);

            # merge constants.
    		$constants = array_merge($constant['frontend'], $constant['both']);

    		// @TODO pegar os tipos de produtos e mandar
    		$productTypes = (new ProductModel())->getProductTypes();

            # return constants and encrypted secret.
    		return $array = [
    		'secret' => crypt(
    			\Slim\Slim::getInstance()->
    			request()->
    			getIp().\Slim\Slim::getInstance()->
    			request()->
    			getUserAgent(),
    			ENCRYPT_SALT),
    		'constants' => $constants,
    		'productTypes' => $productTypes
    		];
    	} catch (Exception $ex) {

    		throw $ex;
    	}
    }

    /**
     * Method for business of get postal data by zip code
     * @name getAddressByZip
     * @author Victor Eduardo Barreto
     * @return Object Data address
     * @date Jul 31, 2015
     * @version 1.0
     */
    public function getAddressByZip ()
    {

    	try {

    		$data = $this->getRequestData();

    		return $this->_domainModel->getAddressByZip($data);
    	} catch (Exception $ex) {

    		throw $ex;
    	}
    }

    /**
     * Method for business for validate acl rules
     * @name validateRuleAcl
     * @author Victor Eduardo Barreto
     * @param string $operation Route accessed
     * @param string $sq_profile
     * @return Object Rules of system
     * @date Alg 11, 2015
     * @version 1.0
     */
    public function validateRuleAcl ()
    {

    	try {

            # get current route and remove the '\'.
    		$operation = substr(\Slim\Slim::getInstance()->request()->getPathInfo(), 1);

            # get data.
    		$data = $this->getRequestData();

            # verify if don't have profile.
    		if (empty($data->origin_sq_profile)) {

                # set guest profile.
    			$data->origin_sq_profile = PROFILE_GUEST;
    		}

    		return $this->_domainModel->validateRuleAcl($operation, $data);
    	} catch (Exception $ex) {

    		throw $ex;
    	}
    }

}
