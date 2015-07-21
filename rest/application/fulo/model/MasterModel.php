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

namespace fulo\model;

/**
 * Alias to connection
 */
use config\Connection as Connection;

/**
 * Master class for model
 * @name MasterModel
 * @author Victor Eduardo Barreto
 * @date Jul 2, 2015
 * @version 1.0
 */
class MasterModel
{

    /**
     *
     * var object Variable to recive a connection
     */
    protected $_conn;

    /**
     * Method constructor
     * @name __construct
     * @author Victor Eduardo Barreto
     * @date Jul 8, 2015
     * @version 1.0
     */
    public function __construct ()
    {
        $this->_conn = Connection::getConnection();
    }

    /**
     * Method for save log for operations
     * @name saveLog
     * @author Victor Eduardo Barreto
     * @param int $sq_person Identifier of user
     * @param int $sq_operation Identifier of operation
     * @return bool Result of procedure
     * @date Jun 19, 2015
     * @version 1.0
     */
    public function saveLog ($sq_person, $sq_operation)
    {

        try {

            $stmt = $this->_conn->prepare("INSERT INTO fulo.log (sq_operation, sq_person, nu_date_time) VALUES (?,?,?)");

            $stmt->execute(array(
                $sq_operation,
                $sq_person,
                date_default_timezone_get(),
            ));
        } catch (Exception $ex) {

            $this->_conn->rollback();

            throw $ex;
        }
    }

}
