-- profile --
insert into fulo.profile (ds_profile) values ('administrador'), ('cliente'), ('convidado');

-- status --
insert into fulo.status (ds_status) values
('ativo'),
('inativo'),
('aguardando pagamento'),
('pagamento verificado'),
('pagamento estornado'),
('produto em preparo para envio'),
('produto em produção'),
('produto concluído'),
('pedido aguardando envio'),
('pedido enviado'), -- TEN
('pedido recebido'),
('pedido perdido'),
('pedido cancelado'),
('pedido congelado'),
('aguardando aprovação do pagamento');

-- user (adminisrator) password = 1234--
insert into fulo.person (ds_name, ds_email) values ('adminisrator','admin@admin');
insert into fulo.user (sq_user, sq_profile, ds_password) values (fulo.person_sq_person_seq,1,'$1$nTDxH9JB$yPfFH.xpK8tDtxNGBEvhG/');
insert into fulo.address (sq_person) values (fulo.person_sq_person_seq);
insert into fulo.phone (sq_person, nu_phone) values (fulo.person_sq_person_seq, 'null');

-- operation --
insert into fulo.operation (ds_operation) values
('login'),
('logoff'),
('getUser'),
('getUsers'),
('addUser'),
('upUser'),
('upAccess'),
('inactivateUser'),
('activateUser'),
('upAddress'),
('addAddress'),
('getProfiles'),
('getBasic'),
('getAddressByZip'),
('getProducts'),
('getProduct'),
('addProduct'),
('getProductTypes'),
('getProductDetail'),
('addWishList'),
('getWishList'),
('delWishList'),
('getProductsByFilter'),
('getProductType'),
('upProductType'),
('delProductType'),
('addProductType'),
('activateProduct'),
('inactivateProduct'),
('upProduct'),
('getFareValue'),
('buy'),
('getOrdersByUser'),
('getOrders'),
('getProductsOrder'),
('tracker'),
('paypalResponse'),
('addTracker'),
('freezeOrder'),
('cancelOrder'),
('refundOrder');

-- acl --
insert into fulo.acl (sq_operation, sq_profile) values

    -- login-
 (1,1), (1,2), (1,3),

    -- logoff --
(2,1), (2,2), (2,3),

    -- getUser --
(3,1),

    --getUsers
(4,1),

    --addUser
(5,1), (5,3),

    --upUser
(6,1), (6,2),

    --upAccess
(7,1), (7,2),

    --inactivateUser
(8,1),

    --acctivateUser
(9,1), (9,2),

    --upAddress
(10,1), (10,2),

    --addAddress
(11,1), (11,2),

    --getProfiles
(12,1), (12,2), (12,3),

    --getBasic
(13,1), (13,2), (13,3),

    --getAaddressByZip
(14,1), (14,2),(14,3),

    --getProducts
(15,1),

    --getProduct
(16,1),

    --addProduct
(17,1),

    --getProductTypes
(18,1), (18,2), (18,3),

   --getProductDetail
(19,1), (19,2), (19,3),

   --addWishList
(20,1), (20,2),

   --getWishList
(21,1), (21,2),

   --delWishList
(22,1), (22,2),

   --getProductsByFilter
(23,1), (23,2),(23,3),

--getProductType
(24,1),

--upProductType
(25,1),

--delProductType
(26,1),

--addProductType
(27,1),

--activateProduct
(28,1),

--inactivateProduct
(29,1),

--upProduct
(30,1),

--getFareValue
(31,1), (31,2), (31,3),

--buy
(32,1), (32,2),

--getOrdersByUser
(33,1), (33,2),

--getOrders
(34,1),

--getOrdersProducts
(35,1), (35,2),

--tracker
(36,1), (36,2),

--paypalResponse
(37,3),

-- addTracker
(38,1),

-- freezeOrder
(39,1),

-- cancelOrder
(40,1),

-- refundOrder
(41,1);

-- paying company --
insert into fulo.paying_company (ds_paying_company) values
('paypal');

