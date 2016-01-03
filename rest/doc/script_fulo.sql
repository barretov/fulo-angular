/* Database generated with pgModeler (PostgreSQL Database Modeler).
  Project Site: pgmodeler.com.br
  Model Author: --- */


/* Database creation must be done outside an multicommand file.
   These commands were put in this file only for convenience.

-- object: db_joker | type: DATABASE -- 
CREATE DATABASE db_joker
	ENCODING = 'UTF8'
;

*/

-- object: fulo | type: SCHEMA -- 
CREATE SCHEMA fulo;
COMMENT ON SCHEMA fulo IS 'shema of fulo';
-- object: fulo.person | type: TABLE -- 
CREATE TABLE fulo.person(
	sq_person serial NOT NULL,
	ds_name varchar(50) NOT NULL,
	ds_email varchar(50) NOT NULL,
	sq_status_news integer NOT NULL DEFAULT 1 ,
	CONSTRAINT pk_person PRIMARY KEY (sq_person)
)
WITH (OIDS=FALSE);

-- object: ix_person | type: INDEX -- 
CREATE INDEX ix_person ON fulo.person
	USING btree
	(sq_person DESC NULLS LAST)
	WITH (FILLFACTOR = 10)
;

COMMENT ON TABLE fulo.person IS 'data of persons';
COMMENT ON COLUMN fulo.person.sq_person IS 'identifier of person';
COMMENT ON COLUMN fulo.person.ds_name IS 'name of person';
COMMENT ON COLUMN fulo.person.ds_email IS 'email of person';
COMMENT ON COLUMN fulo.person.sq_status_news IS 'status of newsletter';
-- object: fulo.user | type: TABLE -- 
CREATE TABLE fulo.user(
	sq_user integer NOT NULL,
	sq_profile integer NOT NULL,
	sq_status_user integer NOT NULL DEFAULT 1,
	ds_password varchar(40) NOT NULL,
	CONSTRAINT pk_user PRIMARY KEY (sq_user)
)
WITH (OIDS=FALSE);

-- object: ix_user | type: INDEX -- 
CREATE INDEX ix_user ON fulo.user
	USING btree
	(sq_user DESC NULLS LAST)
	WITH (FILLFACTOR = 10)
;

COMMENT ON TABLE fulo.user IS 'data of users';
COMMENT ON COLUMN fulo.user.sq_user IS 'identifier of user';
COMMENT ON COLUMN fulo.user.sq_profile IS 'identifier of profile';
COMMENT ON COLUMN fulo.user.sq_status_user IS 'status of user';
COMMENT ON COLUMN fulo.user.ds_password IS 'password of user';
-- object: fulo.profile | type: TABLE -- 
CREATE TABLE fulo.profile(
	sq_profile serial NOT NULL,
	ds_profile varchar(50) NOT NULL,
	CONSTRAINT pk_profile PRIMARY KEY (sq_profile)
)
WITH (OIDS=FALSE);

-- object: ix_profile | type: INDEX -- 
CREATE INDEX ix_profile ON fulo.profile
	USING btree
	(sq_profile DESC NULLS LAST)
	WITH (FILLFACTOR = 10)
;

COMMENT ON TABLE fulo.profile IS 'data of profiles';
COMMENT ON COLUMN fulo.profile.sq_profile IS 'identifier of profile';
COMMENT ON COLUMN fulo.profile.ds_profile IS 'description of profile';
-- object: fulo.status | type: TABLE -- 
CREATE TABLE fulo.status(
	sq_status serial NOT NULL,
	ds_status varchar(50) NOT NULL,
	CONSTRAINT fk_status PRIMARY KEY (sq_status)
)
WITH (OIDS=FALSE);

-- object: ix_status | type: INDEX -- 
CREATE INDEX ix_status ON fulo.status
	USING btree
	(sq_status DESC NULLS LAST)
	WITH (FILLFACTOR = 10)
;

COMMENT ON TABLE fulo.status IS 'table of status';
COMMENT ON COLUMN fulo.status.sq_status IS 'identifier of status';
COMMENT ON COLUMN fulo.status.ds_status IS 'description of status';
COMMENT ON CONSTRAINT fk_status ON fulo.status IS 'primary key of table';
-- object: fulo.log | type: TABLE -- 
CREATE TABLE fulo.log(
	sq_log serial NOT NULL,
	sq_user integer NOT NULL,
	ds_operation varchar(20) NOT NULL,
	nu_target integer NOT NULL,
	nu_date_time timestamp NOT NULL,
	CONSTRAINT pk_log PRIMARY KEY (sq_log)
)
WITH (OIDS=FALSE);

-- object: ix_log | type: INDEX -- 
CREATE INDEX ix_log ON fulo.log
	USING btree
	(sq_log DESC NULLS LAST)
	WITH (FILLFACTOR = 10)
;

COMMENT ON TABLE fulo.log IS 'table of log';
COMMENT ON COLUMN fulo.log.sq_log IS 'identifier of log';
COMMENT ON COLUMN fulo.log.sq_user IS 'identifier of user';
COMMENT ON COLUMN fulo.log.ds_operation IS 'description of operation';
COMMENT ON COLUMN fulo.log.nu_target IS 'identifier of target operation';
COMMENT ON COLUMN fulo.log.nu_date_time IS 'date and time';
COMMENT ON CONSTRAINT pk_log ON fulo.log IS 'primary key of table';
-- object: fulo.address | type: TABLE -- 
CREATE TABLE fulo.address(
	sq_address serial NOT NULL,
	sq_person integer NOT NULL,
	nu_postcode numeric(8),
	ac_state varchar(2),
	ds_address varchar(100),
	ds_city varchar(100),
	ds_neighborhood varchar(100),
	ds_complement varchar(100),
	CONSTRAINT pk_address PRIMARY KEY (sq_address)
)
WITH (OIDS=FALSE);

-- object: ix_address | type: INDEX -- 
CREATE INDEX ix_address ON fulo.address
	USING btree
	(sq_address DESC NULLS LAST)
	WITH (FILLFACTOR = 10)
;

COMMENT ON TABLE fulo.address IS 'table of address';
COMMENT ON COLUMN fulo.address.sq_address IS 'identifier of address';
COMMENT ON COLUMN fulo.address.sq_person IS 'identifier of person';
COMMENT ON COLUMN fulo.address.nu_postcode IS 'number of postcode';
COMMENT ON COLUMN fulo.address.ac_state IS 'acronym of state';
COMMENT ON COLUMN fulo.address.ds_address IS 'description of address';
COMMENT ON COLUMN fulo.address.ds_city IS 'description of city';
COMMENT ON COLUMN fulo.address.ds_neighborhood IS 'description of neighborhood';
COMMENT ON COLUMN fulo.address.ds_complement IS 'complement of address';
COMMENT ON CONSTRAINT pk_address ON fulo.address IS 'primary key of table';
-- object: fulo.phone | type: TABLE -- 
CREATE TABLE fulo.phone(
	sq_phone serial NOT NULL,
	sq_person integer NOT NULL,
	nu_phone varchar(11),
	CONSTRAINT pk_phone PRIMARY KEY (sq_phone)
)
WITH (OIDS=FALSE);

-- object: ix_phone | type: INDEX -- 
CREATE INDEX ix_phone ON fulo.phone
	USING btree
	(sq_phone DESC NULLS LAST)
	WITH (FILLFACTOR = 10)
;

COMMENT ON TABLE fulo.phone IS 'table of phone';
COMMENT ON COLUMN fulo.phone.sq_phone IS 'identifier of phone';
COMMENT ON COLUMN fulo.phone.sq_person IS 'identifier of user';
COMMENT ON COLUMN fulo.phone.nu_phone IS 'number of phone';
COMMENT ON CONSTRAINT pk_phone ON fulo.phone IS 'primary key of table';
-- object: fulo.product | type: TABLE -- 
CREATE TABLE fulo.product(
	sq_product serial NOT NULL,
	sq_product_type integer NOT NULL,
	sq_status_product integer NOT NULL DEFAULT 1,
	ds_product varchar(100) NOT NULL,
	nu_value numeric(8,2) NOT NULL,
	nu_quantity numeric(4) NOT NULL,
	nu_date_time timestamp NOT NULL,
	nu_production numeric(4) NOT NULL DEFAULT 5,
	nu_height numeric(8,2) NOT NULL,
	nu_length numeric(8,2) NOT NULL,
	nu_width numeric(8,2) NOT NULL,
	nu_weight numeric(8,2) NOT NULL,
	st_foldable integer NOT NULL DEFAULT 1,
	CONSTRAINT pk_product PRIMARY KEY (sq_product)
)
WITH (OIDS=FALSE);

-- object: ix_product | type: INDEX -- 
CREATE INDEX ix_product ON fulo.product
	USING btree
	(sq_product DESC NULLS LAST)
	WITH (FILLFACTOR = 10)
;

COMMENT ON TABLE fulo.product IS 'table of product';
COMMENT ON COLUMN fulo.product.sq_product IS 'identifier of product';
COMMENT ON COLUMN fulo.product.sq_product_type IS 'type of product';
COMMENT ON COLUMN fulo.product.sq_status_product IS 'status of product';
COMMENT ON COLUMN fulo.product.ds_product IS 'description of product';
COMMENT ON COLUMN fulo.product.nu_value IS 'value of product';
COMMENT ON COLUMN fulo.product.nu_quantity IS 'quantity of product';
COMMENT ON COLUMN fulo.product.nu_date_time IS 'date of register';
COMMENT ON COLUMN fulo.product.nu_production IS 'production time for the item';
COMMENT ON COLUMN fulo.product.nu_height IS 'height of product';
COMMENT ON COLUMN fulo.product.nu_length IS 'length of product';
COMMENT ON COLUMN fulo.product.nu_width IS 'width of product';
COMMENT ON COLUMN fulo.product.nu_weight IS 'weight of product';
COMMENT ON COLUMN fulo.product.st_foldable IS 'status of foldable';
COMMENT ON CONSTRAINT pk_product ON fulo.product IS 'primary key of product';
-- object: fulo.product_type | type: TABLE -- 
CREATE TABLE fulo.product_type(
	sq_product_type serial NOT NULL,
	ds_product_type varchar(50) NOT NULL,
	CONSTRAINT pk_product_type PRIMARY KEY (sq_product_type)
)
WITH (OIDS=FALSE);

-- object: ix_product_type | type: INDEX -- 
CREATE INDEX ix_product_type ON fulo.product_type
	USING btree
	(sq_product_type DESC NULLS LAST)
	WITH (FILLFACTOR = 10)
;

COMMENT ON TABLE fulo.product_type IS 'table of types';
COMMENT ON COLUMN fulo.product_type.sq_product_type IS 'identifier of product_type';
COMMENT ON COLUMN fulo.product_type.ds_product_type IS 'description of product_type';
COMMENT ON CONSTRAINT pk_product_type ON fulo.product_type IS 'primary key of table';
-- object: fulo.operation | type: TABLE -- 
CREATE TABLE fulo.operation(
	sq_operation serial NOT NULL,
	ds_operation varchar(20) NOT NULL,
	CONSTRAINT pk_operation PRIMARY KEY (sq_operation),
	CONSTRAINT un_operation UNIQUE (ds_operation)
)
WITH (OIDS=FALSE);

-- object: ix_operation | type: INDEX -- 
CREATE INDEX ix_operation ON fulo.operation
	USING btree
	(sq_operation DESC NULLS LAST)
	WITH (FILLFACTOR = 10)
;

COMMENT ON TABLE fulo.operation IS 'table of operations';
COMMENT ON COLUMN fulo.operation.sq_operation IS 'identifier of operation';
COMMENT ON COLUMN fulo.operation.ds_operation IS 'description of operation';
COMMENT ON CONSTRAINT pk_operation ON fulo.operation IS 'primary key of table';
-- object: fulo.acl | type: TABLE -- 
CREATE TABLE fulo.acl(
	sq_acl serial NOT NULL,
	sq_operation integer NOT NULL,
	sq_profile integer NOT NULL,
	CONSTRAINT pk_acl PRIMARY KEY (sq_acl)
)
WITH (OIDS=FALSE);

-- object: ix_acl | type: INDEX -- 
CREATE INDEX ix_acl ON fulo.acl
	USING btree
	(sq_acl DESC NULLS LAST)
	WITH (FILLFACTOR = 10)
;

COMMENT ON TABLE fulo.acl IS 'table of access rules';
COMMENT ON COLUMN fulo.acl.sq_acl IS 'identifier of rule';
COMMENT ON COLUMN fulo.acl.sq_operation IS 'identifier of operation';
COMMENT ON COLUMN fulo.acl.sq_profile IS 'identifier of profile';
COMMENT ON CONSTRAINT pk_acl ON fulo.acl IS 'primary key of table';
-- object: fulo.wishlist | type: TABLE -- 
CREATE TABLE fulo.wishlist(
	sq_wishlist serial NOT NULL,
	sq_user integer NOT NULL,
	sq_product integer NOT NULL,
	CONSTRAINT pk_wishlist PRIMARY KEY (sq_wishlist)
)
WITH (OIDS=FALSE);

-- object: ix_wishlist | type: INDEX -- 
CREATE INDEX ix_wishlist ON fulo.wishlist
	USING btree
	(sq_wishlist DESC NULLS LAST)
	WITH (FILLFACTOR = 10)
;

COMMENT ON TABLE fulo.wishlist IS 'table of wishlist';
COMMENT ON COLUMN fulo.wishlist.sq_wishlist IS 'identifier of wishlist';
COMMENT ON COLUMN fulo.wishlist.sq_user IS 'identifier of user';
COMMENT ON COLUMN fulo.wishlist.sq_product IS 'identifier of product';
COMMENT ON CONSTRAINT pk_wishlist ON fulo.wishlist IS 'primary key of table';
-- object: fulo.product_image | type: TABLE -- 
CREATE TABLE fulo.product_image(
	sq_product_image serial NOT NULL,
	sq_product integer NOT NULL,
	im_product_image text NOT NULL,
	CONSTRAINT pk_image PRIMARY KEY (sq_product_image)
)
WITH (OIDS=FALSE);

-- object: ix_product_image | type: INDEX -- 
CREATE INDEX ix_product_image ON fulo.product_image
	USING btree
	(sq_product_image DESC NULLS LAST)
	WITH (FILLFACTOR = 10)
;

COMMENT ON TABLE fulo.product_image IS 'table of product image';
COMMENT ON COLUMN fulo.product_image.sq_product_image IS 'identifier of image';
COMMENT ON COLUMN fulo.product_image.sq_product IS 'identifier of product';
COMMENT ON COLUMN fulo.product_image.im_product_image IS 'image in base64';
COMMENT ON CONSTRAINT pk_image ON fulo.product_image IS 'primary key of table';
-- object: fulo.order | type: TABLE -- 
CREATE TABLE fulo.order(
	sq_order serial NOT NULL,
	sq_user integer NOT NULL,
	ds_address varchar(250) NOT NULL,
	nu_phone numeric(11),
	nu_quantity integer NOT NULL,
	nu_total numeric(8,2) NOT NULL,
	st_status integer NOT NULL DEFAULT 3,
	nu_tracker varchar(15),
	nu_date_time timestamp NOT NULL,
	CONSTRAINT pk_order PRIMARY KEY (sq_order)
)
WITH (OIDS=FALSE);

-- object: ix_order | type: INDEX -- 
CREATE INDEX ix_order ON fulo.order
	USING btree
	(sq_order DESC NULLS LAST)
	WITH (FILLFACTOR = 10)
;

COMMENT ON TABLE fulo.order IS 'table of orders';
COMMENT ON COLUMN fulo.order.sq_order IS 'identifier of order';
COMMENT ON COLUMN fulo.order.sq_user IS 'idenfier of user';
COMMENT ON COLUMN fulo.order.ds_address IS 'address of user';
COMMENT ON COLUMN fulo.order.nu_phone IS 'phone of user';
COMMENT ON COLUMN fulo.order.nu_quantity IS 'quantity of product';
COMMENT ON COLUMN fulo.order.nu_total IS 'total value order';
COMMENT ON COLUMN fulo.order.st_status IS 'status of order';
COMMENT ON COLUMN fulo.order.nu_tracker IS 'track number of order';
COMMENT ON COLUMN fulo.order.nu_date_time IS 'date and time of sale';
COMMENT ON CONSTRAINT pk_order ON fulo.order IS 'primary key of table';
-- object: fulo.order_products | type: TABLE -- 
CREATE TABLE fulo.order_products(
	sq_order_products serial NOT NULL,
	sq_order integer NOT NULL,
	sq_product integer NOT NULL,
	ds_product varchar(100) NOT NULL,
	nu_value numeric(8,2) NOT NULL,
	nu_quantity numeric(4) NOT NULL,
	nu_production numeric(4) NOT NULL,
	CONSTRAINT pk_order_products PRIMARY KEY (sq_order_products)
)
WITH (OIDS=FALSE);

-- object: ix_order_products | type: INDEX -- 
CREATE INDEX ix_order_products ON fulo.order_products
	USING btree
	(sq_order_products DESC NULLS LAST)
	WITH (FILLFACTOR = 10)
;

COMMENT ON TABLE fulo.order_products IS 'products of order';
COMMENT ON COLUMN fulo.order_products.sq_order_products IS 'identifier of order_products';
COMMENT ON COLUMN fulo.order_products.sq_order IS 'identifier of order';
COMMENT ON COLUMN fulo.order_products.sq_product IS 'identifier of product';
COMMENT ON COLUMN fulo.order_products.ds_product IS 'description of product';
COMMENT ON COLUMN fulo.order_products.nu_value IS 'value of product';
COMMENT ON COLUMN fulo.order_products.nu_quantity IS 'quantity of product';
COMMENT ON COLUMN fulo.order_products.nu_production IS 'time of production ';
COMMENT ON CONSTRAINT pk_order_products ON fulo.order_products IS 'primary key of products';
-- object: fk_order_products_product | type: CONSTRAINT -- 
ALTER TABLE fulo.order_products ADD CONSTRAINT fk_order_products_product FOREIGN KEY (sq_product)
REFERENCES fulo.product (sq_product) MATCH FULL
ON DELETE CASCADE ON UPDATE CASCADE NOT DEFERRABLE;

-- object: fk_order_products_order | type: CONSTRAINT -- 
ALTER TABLE fulo.order_products ADD CONSTRAINT fk_order_products_order FOREIGN KEY (sq_order)
REFERENCES fulo.order (sq_order) MATCH FULL
ON DELETE CASCADE ON UPDATE CASCADE NOT DEFERRABLE;

-- object: fk_order_user | type: CONSTRAINT -- 
ALTER TABLE fulo.order ADD CONSTRAINT fk_order_user FOREIGN KEY (sq_user)
REFERENCES fulo.user (sq_user) MATCH FULL
ON DELETE CASCADE ON UPDATE CASCADE NOT DEFERRABLE;

-- object: fk_image_product | type: CONSTRAINT -- 
ALTER TABLE fulo.product_image ADD CONSTRAINT fk_image_product FOREIGN KEY (sq_product)
REFERENCES fulo.product (sq_product) MATCH FULL
ON DELETE CASCADE ON UPDATE CASCADE NOT DEFERRABLE;

-- object: fk_wishlist_product | type: CONSTRAINT -- 
ALTER TABLE fulo.wishlist ADD CONSTRAINT fk_wishlist_product FOREIGN KEY (sq_product)
REFERENCES fulo.product (sq_product) MATCH FULL
ON DELETE CASCADE ON UPDATE CASCADE NOT DEFERRABLE;

-- object: fk_wishlist_user | type: CONSTRAINT -- 
ALTER TABLE fulo.wishlist ADD CONSTRAINT fk_wishlist_user FOREIGN KEY (sq_user)
REFERENCES fulo.user (sq_user) MATCH FULL
ON DELETE CASCADE ON UPDATE CASCADE NOT DEFERRABLE;

-- object: fk_acl_profile | type: CONSTRAINT -- 
ALTER TABLE fulo.acl ADD CONSTRAINT fk_acl_profile FOREIGN KEY (sq_profile)
REFERENCES fulo.profile (sq_profile) MATCH FULL
ON DELETE CASCADE ON UPDATE CASCADE NOT DEFERRABLE;

-- object: fk_acl_operation | type: CONSTRAINT -- 
ALTER TABLE fulo.acl ADD CONSTRAINT fk_acl_operation FOREIGN KEY (sq_operation)
REFERENCES fulo.operation (sq_operation) MATCH FULL
ON DELETE CASCADE ON UPDATE CASCADE NOT DEFERRABLE;

-- object: fk_produtct_status_foldable | type: CONSTRAINT -- 
ALTER TABLE fulo.product ADD CONSTRAINT fk_produtct_status_foldable FOREIGN KEY (st_foldable)
REFERENCES fulo.status (sq_status) MATCH FULL
ON DELETE CASCADE ON UPDATE CASCADE NOT DEFERRABLE;

-- object: fk_product_status | type: CONSTRAINT -- 
ALTER TABLE fulo.product ADD CONSTRAINT fk_product_status FOREIGN KEY (sq_status_product)
REFERENCES fulo.status (sq_status) MATCH FULL
ON DELETE CASCADE ON UPDATE CASCADE NOT DEFERRABLE;

-- object: fk_product_type | type: CONSTRAINT -- 
ALTER TABLE fulo.product ADD CONSTRAINT fk_product_type FOREIGN KEY (sq_product_type)
REFERENCES fulo.product_type (sq_product_type) MATCH FULL
ON DELETE CASCADE ON UPDATE CASCADE NOT DEFERRABLE;

-- object: fk_phone_person | type: CONSTRAINT -- 
ALTER TABLE fulo.phone ADD CONSTRAINT fk_phone_person FOREIGN KEY (sq_person)
REFERENCES fulo.person (sq_person) MATCH FULL
ON DELETE CASCADE ON UPDATE CASCADE NOT DEFERRABLE;

-- object: fk_address_person | type: CONSTRAINT -- 
ALTER TABLE fulo.address ADD CONSTRAINT fk_address_person FOREIGN KEY (sq_person)
REFERENCES fulo.person (sq_person) MATCH FULL
ON DELETE CASCADE ON UPDATE CASCADE NOT DEFERRABLE;

-- object: fk_log_operation | type: CONSTRAINT -- 
ALTER TABLE fulo.log ADD CONSTRAINT fk_log_operation FOREIGN KEY (ds_operation)
REFERENCES fulo.operation (ds_operation) MATCH FULL
ON DELETE CASCADE ON UPDATE CASCADE NOT DEFERRABLE;

-- object: fk_log_user | type: CONSTRAINT -- 
ALTER TABLE fulo.log ADD CONSTRAINT fk_log_user FOREIGN KEY (sq_user)
REFERENCES fulo.user (sq_user) MATCH FULL
ON DELETE CASCADE ON UPDATE CASCADE NOT DEFERRABLE;

-- object: fk_user_status | type: CONSTRAINT -- 
ALTER TABLE fulo.user ADD CONSTRAINT fk_user_status FOREIGN KEY (sq_status_user)
REFERENCES fulo.status (sq_status) MATCH FULL
ON DELETE CASCADE ON UPDATE CASCADE NOT DEFERRABLE;

-- object: fk_user_profile | type: CONSTRAINT -- 
ALTER TABLE fulo.user ADD CONSTRAINT fk_user_profile FOREIGN KEY (sq_profile)
REFERENCES fulo.profile (sq_profile) MATCH FULL
ON DELETE CASCADE ON UPDATE CASCADE NOT DEFERRABLE;

-- object: fk_user_person | type: CONSTRAINT -- 
ALTER TABLE fulo.user ADD CONSTRAINT fk_user_person FOREIGN KEY (sq_user)
REFERENCES fulo.person (sq_person) MATCH FULL
ON DELETE CASCADE ON UPDATE CASCADE NOT DEFERRABLE;

-- object: fk_person_status_news | type: CONSTRAINT -- 
ALTER TABLE fulo.person ADD CONSTRAINT fk_person_status_news FOREIGN KEY (sq_status_news)
REFERENCES fulo.status (sq_status) MATCH FULL
ON DELETE CASCADE ON UPDATE CASCADE NOT DEFERRABLE;


