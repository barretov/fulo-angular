<?php

namespace fulo;

$app->get("/teste", function () {

    $db = getConnect();

    $sql = "SELECT sq_pessoa,ds_nome,ds_email FROM pessoa";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    formatJson($stmt->fetchAll());
});
