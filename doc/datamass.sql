-- profile --
insert into fulo.profile (ds_profile) values ('adminisrator'), ('customer'), ('guest');

-- status --
insert into fulo.status (ds_status) values ('active'), ('inactive'), ('wait'), ('paid'), ('sent'), ('recivede'), ('returned'), ('canceled');

-- user (adminisrator) password = 1234--
insert into fulo.person (ds_name, ds_email) values ('adminisrator','admin@admin');
insert into fulo.user (sq_user, sq_profile, ds_password) values (fulo.person_sq_person_seq,1,'$1$nTDxH9JB$yPfFH.xpK8tDtxNGBEvhG/');
insert into fulo.address (sq_person) values (fulo.person_sq_person_seq);
insert into fulo.phone (sq_person, nu_phone) values (fulo.person_sq_person_seq, 'null');

-- operation --
insert into fulo.operation (ds_operation) values ('login'), ('logoff'), ('getUser'), ('getUsers'), ('addUser'), ('upUser'), ('upAccess'), ('inactivateuser'), ('activateuser'), ('upAddress'), ('addAddress'), ('getProfiles'), ('getBasic'), ('getAddressByZip'), ('getProducts'), ('getProduct'), ('addProduct');

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
(14,1), (14,2),

    --getProducts
(15,1),

    --getProduct
(16,1),

    --addProduct
(17,1);

