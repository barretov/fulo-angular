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

namespace fulo\middleware;

use fulo\controller\MasterController as MasterController;

/**
 * Middleware to validate secret
 * @name secret
 * @author Victor Eduardo Barreto
 * @date Aug 13, 2015
 * @version 1.0
 */
class Secret extends \Slim\Middleware
{

    public function call ()
    {

        try {

            if ($this->app->request()->getPathInfo() === "/getBasic") {

                $this->next->call();
            } else {

                $business = MasterController::getDomainBusiness();

                # get request data.
                $data = $business->getRequestData();

                # validate secret.
                if (!empty($data->secret) && crypt($this->app->request()->getIp() . $this->app->request()->getUserAgent(), ENCRYPT_SALT) === $data->secret) {

                    $this->next->call();
                } else {

                    echo json_encode(ACCESS_DENIED);
                }
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}
