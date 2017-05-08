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

namespace fulo\controller;

/**
 * alias for business class
 */
use \fulo\business\DomainBusiness as DomainBusiness;
use \fulo\business\LoginBusiness as LoginBusiness;
use \fulo\business\ProductBusiness as ProductBusiness;
use \fulo\business\UserBusiness as UserBusiness;
use \fulo\business\PurchaseBusiness as PurchaseBusiness;

/**
 * Master class for controllers
 * @name MasterController
 * @author Victor Eduardo Barreto
 * @package fulo\controller
 * @date Jul 8, 2015
 * @version 1.0
 */
class MasterController
{

    /**
     *
     * @var object $_userBusiness Variable to recive instance of DomainBusiness
     * @var object $_domainBusiness Variable to recive instance of LoginBusiness
     * @var object $_loginBusiness Variable to recive instance of ProductBusiness
     * @var object $_loginBusiness Variable to recive instance of UserBusiness
     * @var object $_loginBusiness Variable to recive instance of PurchaseBusiness
     */
    protected static $_domainBusiness;
    protected static $_loginBusiness;
    protected static $_productBusiness;
    public static $_userBusiness;
    public static $_purchaseBusiness;

    /**
     * Method constructor
     * @name __construct
     * @author Victor Eduardo Barreto
     * @date Jul 8, 2015
     * @version 1.0
     */
    private function __construct ()
    {
        # prevent instance.
    }

    /**
     * Method for get instance of domain business
     * @name getDomainBusiness
     * @author Victor Eduardo Barreto
     * @return object Domain business
     * @date Jul 8, 2015
     * @version 1.0
     */
    public static function getDomainBusiness ()
    {

        try {

            if (!isset(self::$_domainBusiness)) {

                self::$_domainBusiness = new DomainBusiness();
            }

            return self::$_domainBusiness;
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for get instance of login business
     * @name getLoginBusiness
     * @author Victor Eduardo Barreto
     * @return object Login business
     * @date Jul 8, 2015
     * @version 1.0
     */
    public static function getLoginBusiness ()
    {

        try {

            if (!isset(self::$_loginBusiness)) {

                self::$_loginBusiness = new LoginBusiness();
            }

            return self::$_loginBusiness;
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for get instance of user business
     * @name getUserBusiness
     * @author Victor Eduardo Barreto
     * @return object User business
     * @date Jul 8, 2015
     * @version 1.0
     */
    public static function getUserBusiness ()
    {

        try {

            if (!isset(self::$_userBusiness)) {

                self::$_userBusiness = new UserBusiness();
            }

            return self::$_userBusiness;
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for get instance of product business
     * @name getProductBusiness
     * @author Victor Eduardo Barreto
     * @return object Product business
     * @date Alg 18, 2015
     * @version 1.0
     */
    public static function getProductBusiness ()
    {

        try {

            if (!isset(self::$_productBusiness)) {

                self::$_productBusiness = new ProductBusiness();
            }

            return self::$_productBusiness;
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for get instance of purchase business
     * @name getCarttBusiness
     * @author Victor Eduardo Barreto
     * @return object Cart business
     * @date Dec 10, 2015
     * @version 1.0
     */
    public static function getPurchaseBusiness ()
    {

        try {

            if (!isset(self::$_purchaseBusiness)) {
                self::$_purchaseBusiness = new PurchaseBusiness();
            }

            return self::$_purchaseBusiness;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}
