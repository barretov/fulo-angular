<?php

/**
 * @license <http://www.gnu.org/licenses/>
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
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 */

namespace fulo\model;

use PDO;

/**
 * Model class for Purchase.
 *
 * @name ProductModel
 *
 * @author Victor Eduardo Barreto
 * @date Dec 10, 2015
 *
 * @version 1.0
 */
class PurchaseModel extends MasterModel
{
    /**
     * Method for add item in wish list.
     *
     * @name addWishList
     *
     * @author Victor Eduardo Barreto
     *
     * @param object $data Data of product and user
     *
     * @return bool Result of procedure
     * @date Alg 28, 2015
     *
     * @version 1.0
     */
    public function addWishList($data)
    {
    	try {
    		$stmtSelect = $this->_conn->prepare('SELECT sq_user '
    			.'FROM fulo.wishlist '
    			.'WHERE sq_user = ? '
    			.'AND sq_product = ? '
    		);

    		$stmtSelect->execute([
    			$data->origin_sq_user,
    			$data->sq_product,
    			]);

    		if (!$stmtSelect->fetch()) {
    			$this->_conn->beginTransaction();

    			$stmtAdd = $this->_conn->prepare('INSERT INTO fulo.wishlist '
    				.'(sq_user, sq_product) '
    				.'VALUES (?,?)'
    			);

    			$stmtAdd->execute([
    				$data->origin_sq_user,
    				$data->sq_product,
    				]);

                // save log operation.
    			$this->saveLog($data->origin_sq_user, $data->sq_product);

    			$return = $this->_conn->commit();
    		} else {
    			$return = WISHLIST_ALREADY;
    		}

    		return $return;
    	} catch (Exception $ex) {
    		throw $ex;
    	}
    }

    /**
     * Method for get item of wish list.
     *
     * @name getWishList
     *
     * @author Victor Eduardo Barreto
     *
     * @param object $data Data of product and user
     *
     * @return object Data of user wishlist
     * @date Alg 29, 2015
     *
     * @version 1.0
     */
    public function getWishList($data)
    {
    	try {
    		$stmt = $this->_conn->prepare('SELECT * '
    			.'FROM fulo.wishlist '
    			.'JOIN fulo.product '
    			.'ON (product.sq_product = wishlist.sq_product) '
    			.'JOIN fulo.product_image '
    			.'ON (product.sq_product = product_image.sq_product) '
    			.'WHERE sq_user = ? '
    		);

    		$stmt->execute([
    			$data->origin_sq_user,
    			]);

    		return $stmt->fetchAll(PDO::FETCH_OBJ);
    	} catch (Exception $ex) {
    		throw $ex;
    	}
    }

    /**
     * Method for del item of wish list.
     *
     * @name delWishList
     *
     * @author Victor Eduardo Barreto
     *
     * @param object $data Data of product and user
     *
     * @return bool Result of procedure
     * @date Alg 29, 2015
     *
     * @version 1.0
     */
    public function delWishList($data)
    {
    	try {
    		$this->_conn->beginTransaction();

    		$stmt = $this->_conn->prepare('DELETE '
    			.'FROM fulo.wishlist '
    			.'WHERE sq_user = ? '
    			.'AND sq_product = ?'
    		);

    		$stmt->execute([
    			$data->origin_sq_user,
    			$data->sq_product,
    			]);

            // save log operation.
    		$this->saveLog($data->origin_sq_user, $data->sq_product);

    		return $this->_conn->commit();
    	} catch (Exception $ex) {
    		throw $ex;
    	}
    }

    /**
     * Method for get data products.
     *
     * @name getDataProducts
     *
     * @author Victor Eduardo Barreto
     *
     * @param array $data->sq_product Identifier of products
     *
     * @return array Data products
     * @date Oct 20, 2015
     *
     * @version 1.0
     */
    public function getDataProducts(&$data)
    {
    	try {
    		$query = 'SELECT * FROM fulo.product';

    		foreach ($data->product as $key => $value) {
    			if (!$key) {
    				$query = $query.' WHERE sq_product = '.$value->sq_product;
    			} else {
    				$query = $query.' OR sq_product = '.$value->sq_product;
    			}
    		}

    		$stmt = $this->_conn->prepare($query);

    		$stmt->execute();

    		return $stmt->fetchAll(PDO::FETCH_OBJ);
    	} catch (Exception $ex) {
    		throw $ex;
    	}
    }

    /**
     * Method for buy products.
     *
     * @name buy
     *
     * @author Victor Eduardo Barreto
     *
     * @param object $data All data of products and user
     *
     * @return bool Result of transaction
     * @date Dec 31, 2015
     *
     * @version 1.0
     */
    public function buy(&$data)
    {
    	try {

    		$this->_conn->beginTransaction();

            // save order.
    		$stmtOrder = $this->_conn->prepare(
    			'INSERT INTO fulo.order '
    			.'(sq_user, ds_address, nu_phone, nu_quantity, nu_total, sq_status, nu_date_time, nu_farevalue, sq_paying_company, ds_payment_info) '
    			.'VALUES (?,?,?,?,?,?,?,?,?,?)'
    		);

    		$stmtOrder->execute([
    			$data->origin_sq_user,
    			$data->user->ds_address
    			.', '.$data->user->ds_complement
    			.'- '.$data->user->ds_city.'- '.$data->user->nu_postcode,
    			$data->user->nu_phone,
    			$data->nu_quantity_buy,
    			$data->nu_total,
    			NUMBER_THRE,
    			date('Y-m-d H:i:s'),
    			$data->nu_farevalue,
    			PAYPAL,
    			$data->nvp['TOKEN']
    			]);

            // save products of order.
    		$stmtOrderProducts = $this->_conn->prepare(
    			'INSERT INTO fulo.order_products '
    			.'(sq_order, sq_product, ds_product, nu_value, nu_quantity, nu_production, nu_quantity_stock) '
    			.'VALUES (?,?,?,?,?,?,?)'
    		);

            // verify quantity of product in stock now.
    		$stmtStock = $this->_conn->prepare(
    			'SELECT nu_quantity FROM fulo.product WHERE sq_product = ?'
    		);

            // remove of stock the product buyed
    		$stmtRemoveStock = $this->_conn->prepare(
    			'UPDATE fulo.product SET nu_quantity = ? WHERE sq_product = ?'
    		);

            // add order products.
    		foreach ($data->products as $key) {

                // verify quantity of product has in stock now.
    			$stmtStock->execute([
    				$key->sq_product,
    				]);

    			$nu_quantity = $stmtStock->fetchObject();

                // inset products in order products
    			$stmtOrderProducts->execute(
    				[
    				$this->_conn->lastInsertId('fulo.order_sq_order_seq'),
    				$key->sq_product,
    				$key->ds_product,
    				$key->nu_value,
    				$key->nu_quantity_buy,
    				$key->nu_production,
    				$nu_quantity->nu_quantity,
    				]
    			);

                // remove from stock.
    			$stock = bcsub($nu_quantity->nu_quantity, $key->nu_quantity_buy);
    			$stock < 0 ? $stock = 0 : '';

    			$stmtRemoveStock->execute([
    				$stock,
    				$key->sq_product,
    				]
    			);
    		}

            // inject sq_order in $data for use in paypal.
    		$data->sq_order = $this->_conn->lastInsertId('fulo.order_sq_order_seq');

            // save log operation.
    		$this->saveLog($data->origin_sq_user, $this->_conn->lastInsertId('fulo.order_sq_order_seq'));

    		return $this->_conn->commit();
    	} catch (Exception $ex) {
    		throw $ex;
    	}
    }

    public function addTracker(&$data)
    {
    	try {
    		$this->_conn->beginTransaction();

    		$stmt = $this->_conn->prepare('UPDATE  fulo.order SET '
    			.'nu_tracker = ?, sq_status =  ? '
    			.' WHERE sq_order =  ? '
    		);

    		$stmt->execute([
    			$data->nu_tracker,
    			NUMBER_TEN,
    			$data->sq_order,
    			]
    		);

    		$this->saveLog($data->origin_sq_user, $data->sq_order);
    		$return = $this->_conn->commit();

    		return $return;
    	} catch (Exception $ex) {
    		$this->_conn->rollbrack();
    		throw $ex;
    	}
    }

    public function updateStatusOrder(&$data, $status)
    {

    	try {

    		$this->_conn->beginTransaction();

    		$stmt = $this->_conn->prepare('UPDATE fulo.order SET '
    			.'sq_status = ?, ds_payment_info = ? '
    			.' WHERE ds_payment_info =  ? '
    		);

    		$stmt->execute([
    			$status,
    			$data->response['PAYMENTINFO_0_TRANSACTIONID'],
    			$data->response['TOKEN']
    			]
    		);

    		$return = $this->_conn->commit();
    	} catch (Exception $ex) {
    		$this->_conn->rollbrack();
    		throw $ex;
    	}
    }

    /**
     * Method for freeze order
     * @author Victor Eduardo Barreto
     * @package [subpackage]
     * @filesource
     * @throw Mensagem de erro
     * @param object &$data Data of order
     * @return bool Result of operation
     */
    public function freezeOrder(&$data)
    {

    	try {
    		$this->_conn->beginTransaction();

    		$stmt = $this->_conn->prepare('UPDATE fulo.order SET '
    			.'sq_status = ? WHERE sq_order =  ? '
    		);

    		$stmt->execute([
    			NUMBER_FOURTEEN,
    			$data->sq_order
    			]
    		);

    		$this->saveLog($data->origin_sq_user, $data->sq_order);
    		$return = $this->_conn->commit();
    	} catch (Exception $ex) {
    		$this->_conn->rollbrack();
    		throw $ex;
    	}
    }

    public function cancelOrder(&$data) {
    	try {
    		$this->_conn->beginTransaction();

    		// @verificar se tem estoque para retornar
    		// select
    		// retornar o estoque se tiver
    		// update

    		$stmt = $this->_conn->prepare('UPDATE fulo.order SET '
    			.'sq_status = ? WHERE sq_order =  ? '
    		);

    		$stmt->execute([
    			NUMBER_THRETEEN,
    			$data->sq_order
    			]
    		);

    		$this->saveLog($data->origin_sq_user, $data->sq_order);
    		$return = $this->_conn->commit();
    	} catch (Exception $exc) {
    		throw $exc->getMessage();
    	}
    }

    public function refundOrder(&$data) {
    	try {
    		$this->_conn->beginTransaction();

    		$stmt = $this->_conn->prepare('UPDATE fulo.order SET '
    			.'sq_status = ? WHERE sq_order =  ? '
    		);

    		$stmt->execute([
    			NUMBER_FIVE,
    			$data->sq_order
    			]
    		);

    		$this->saveLog($data->origin_sq_user, $data->sq_order);
    		$return = $this->_conn->commit();
    	} catch (Exception $exc) {
    		throw $exc->getMessage();
    	}
    }
}
