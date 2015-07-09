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

namespace config;

use PDO as PDO;

/**
 * Class for data base connection
 * @name Connection
 * @author Victor Eduardo Barreto
 * @package config
 * @date Jul 8, 2015
 * @version 1.0
 */
class Connection
{

    /**
     *
     * @var object Variable to recive PDO connection
     */
    protected static $_PDO;

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
     * Method for get PDO connection
     * @name Connection
     * @author Victor Eduardo Barreto
     * @return object Data base connection
     * @date Jul 8, 2015
     * @version 1.0
     */
    public static function getConnection ()
    {

        # Informações sobre o sistema:
        $sistema_titulo = "Fulo Patchwork";
        $sistema_email = "victor.eduardo.barreto@gmail.com";

        try {

            if (!isset(self::$_PDO)) {

                self::$_PDO = new PDO("pgsql:dbname=" . BASE . "; host=" . HOST, USER, PASS);
                self::$_PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }

            return self::$_PDO;
        } catch (PDOException $e) {

            # Envia um e-mail para o e-mail oficial do sistema, em caso de erro de conexão.
            mail($sistema_email, "PDOException em $sistema_titulo", $e->getMessage());

            # Então não carrega nada mais da página.
            die("Connection Error: " . $e->getMessage());
        }
    }

}
