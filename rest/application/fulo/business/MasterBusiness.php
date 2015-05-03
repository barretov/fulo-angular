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
     * @return object or array or string Data without special char
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
                    $data->$key = ereg_replace("[^a-zA-Z0-9_@. ]", "", strtr($value, "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC-"));
                }

                # object.
            } elseif (is_object($data)) {

                foreach ($data as $key => $value) {
                    $data->$key = ereg_replace("[^a-zA-Z0-9_@. ]", "", strtr($value, "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC-"));
                }

                # other types.
            } else {

                $data = ereg_replace("[^a-zA-Z0-9_@. ]", "", strtr($data, "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC-"));
            }
        } catch (Exception $ex) {

            throw new $ex;
        }
    }

    /**
     * Method for remove special char of data
     * @name removeSpecialChar
     * @author Victor Eduardo Barreto
     * @param object or array or string $data Data to remove special char
     * @return object or array or string Data without special char
     * @date Apr 12, 2015
     * @version 1.0
     */
    public function isLoged ()
    {
        try {

            if ($_SESSION['user']) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {

            throw new $ex;
        }
    }

}
