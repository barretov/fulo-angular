<?php

function getConnect ()
{
    return Conexao::getConnect();
}

$app->get("/clientes", function () {

    $db = getConnect();

    $sql = "SELECT sq_pessoa,ds_nome,ds_email FROM pessoa";
    $stmt = $db->prepare($sql);
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

        $db = getConnect();

        try {

            # inicia uma transação.
            $db->beginTransaction();

            # define a query de inserção de pessoa.
            $pessoa = "INSERT INTO pessoa (ds_nome,ds_email) VALUES (?,?)";

            # prepara.
            $stmt = $db->prepare($pessoa);

            # executa.
            $stmt->execute(array(
                $data->ds_nome,
                $data->ds_email,
                    )
            );

            $sq_usuario = $db->lastInsertId('pessoa_sq_pessoa_seq');

            # prepara inserção de usuário.
            $usuario = "INSERT INTO usuario (sq_usuario, sq_perfil) VALUES (?,?)";

            # prepara.
            $stmt = $db->prepare($usuario);

            # executa.
            $stmt->execute(array(
                $sq_usuario,
                $data->sq_perfil,
                    )
            );

            $db->commit();
        } catch (Exception $ex) {

            $db->rollback();
        }
    }

    formatJson($data);
});

$app->delete("/clientes/:id", function ($id) {

    $sql = "DELETE FROM pessoa WHERE sq_pessoa=?";
    $stmt = DB::prepare($sql);
    $stmt->execute(array($id));
    formatJson(true);
});

$app->get("/teste", function () {

//    $sql = "SELECT sq_pessoa FROM pessoa";
//    $stmt = DB::prepare($sql);
//    $stmt->execute();
//    $a = $stmt->fetchAll();
//    $banco = new PDO('pgsql:host=localhost;dbname=fulo', 'postgres', 'postgres');
//    $connection = new PDO("pgsql:dbname="fulo";host="localhost, root, root);


    $db = getConnect();

    $pessoa = "INSERT INTO pessoa (ds_nome,ds_email) VALUES (?,?)";

    # prepara.
    $stmt = DB::prepare($pessoa);

    # executa.
    $stmt->execute(array(
        '4',
        '3@3',
            )
    );

    $a = DB::lastInsertId('pessoa_sq_pessoa_seq');

    var_dump($a);
});



