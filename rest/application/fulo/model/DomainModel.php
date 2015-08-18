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

            $stmt = $this->_conn->prepare("SELECT * FROM fulo.profile");

            $stmt->execute();

            return $stmt->fetchAll();
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for get postal data by zip code
     * @name getAddressByZip
     * @author Victor Eduardo Barreto
     * @param String $data Data user
     * @return Object Data address
     * @date Jul 31, 2015
     * @version 1.0
     */
    public function getAddressByZip ($data)
    {

        try {

            $stmt = $this->_conn->prepare("SELECT "
                    . "log_logradouro.log_no as logradouro, log_logradouro.log_tipo_logradouro, "
                    . "log_bairro.bai_no as bairro, log_localidade.loc_no as cidade, log_localidade.ufe_sg as uf, "
                    . "log_logradouro.cep "
                    . "FROM cep.log_logradouro,cep.log_localidade, cep.log_bairro "
                    . "WHERE log_logradouro.loc_nu_sequencial = log_localidade.loc_nu_sequencial "
                    . "AND log_logradouro.bai_nu_sequencial_ini = log_bairro.bai_nu_sequencial "
                    . "AND log_logradouro.cep = ?");

            $stmt->execute([
                $data->nu_postcode
            ]);

            return $stmt->fetch();
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    /**
     * Method for validate acl rules
     * @name validateRuleAcl
     * @author Victor Eduardo Barreto
     * @param string $operation Route accessed
     * @param object $data User data
     * @return Object Rules of system
     * @date Alg 11, 2015
     * @version 1.0
     */
    public function validateRuleAcl (& $operation, & $data)
    {

        try {

            $stmt = $this->_conn->prepare("SELECT sq_acl "
                    . "FROM fulo.acl "
                    . "JOIN fulo.operation "
                    . "ON (acl.sq_operation = operation.sq_operation) "
                    . "WHERE operation.ds_operation = ? AND acl.sq_profile = ?");

            $stmt->execute([
                $operation,
                $data->origin_sq_profile
            ]);

            return $stmt->fetchAll();
        } catch (Exception $ex) {

            throw $ex;
        }
    }

}
