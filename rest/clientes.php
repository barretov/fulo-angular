<?php

$app->get("/clientes", function () {

    $sql = "SELECT sq_pessoa,ds_nome,ds_email FROM pessoa";
    $stmt = DB::prepare($sql);
    $stmt->execute();
    formatJson($stmt->fetchAll());
});

$app->get("/clientes/:id", function ($id) {

    $sql = "SELECT sq_pessoa,ds_nome,ds_email FROM pessoa WHERE sq_pessoa='$id'";
    $stmt = DB::prepare($sql);
    $stmt->execute();
    formatJson($stmt->fetch());
});

$app->post("/clientes/:id", function ($id) {

    $data = json_decode(\Slim\Slim::getInstance()->request()->getBody());

    if ($data->isUpdate) {
        $sql = "UPDATE pessoa SET ds_nome=?,ds_email=? WHERE sq_pessoa=?";
        $stmt = DB::prepare($sql);
        $stmt->execute(array(
            $data->ds_nome,
            $data->ds_email,
            $data->sq_pessoa,
                )
        );
    } else {
        $sql = "INSERT INTO pessoa (ds_nome,ds_email) VALUES (?,?)";
        $stmt = DB::prepare($sql);
        $stmt->execute(array(
            $data->ds_nome,
            $data->ds_email
                )
        );
    }

    formatJson($data);
});

$app->delete("/clientes/:id", function ($id) {

    $sql = "DELETE FROM pessoa WHERE sq_pessoa=?";
    $stmt = DB::prepare($sql);
    $stmt->execute(array($id));
    formatJson(true);
});



