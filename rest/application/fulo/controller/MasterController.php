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

/**
 * Format object to json
 * @name formatJson
 * @author Victor Eduardo Barreto
 * @date May 3, 2015
 * @version 1.0
 */
function formatJson ($obj) {
    echo json_encode($obj);
}

/**
 * Method for verify if user is loged in the system
 * @name isLoged
 * @author Victor Eduardo Barreto
 * @return Bool Result of the procedure
 * @date Apr 12, 2015
 * @version 1.0
 */
function isLoged () {

    try {

        return empty($_SESSION['user']) ? TRUE : FALSE;
    } catch (Exception $ex) {

        throw $ex;
    }
}
