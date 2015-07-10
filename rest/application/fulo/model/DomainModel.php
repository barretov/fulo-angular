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
 * Model class for domain
 * @name DomainModel
 * @author Victor Eduardo Barreto
 * @date Jun 19, 2015
 * @version 1.0
 */
class DomainModel extends MasterModel
{

    /**
     * Method for get domain profiles
     * @name getProfiles
     * @author Victor Eduardo Barreto
     * @return Object Data profiles
     * @date Jun 19, 2015
     * @version 1.0
     */
    public function getProfiles ()
    {

        try {

            $this->_conn->beginTransaction();

            $stmt = $this->_conn->prepare("SELECT * FROM dominio.perfil");

            $stmt->execute();

            return $stmt->fetchAll();
        } catch (Exception $ex) {

            $this->_conn->rollback();

            throw $ex;
        }
    }

}
