<?php

abstract class Conexao
{

    public static function getConnect ()
    {

        # Informações sobre o sistema:
        $sistema_titulo = "Fulo Patchwork";
        $sistema_email = "victor.eduardo.barreto@gmail.com";

        try {

            $PDO = new PDO("pgsql:dbname=" . DB_NAME . ";host=" . DB_HOST, DB_USER, DB_PASSWORD);
            $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $PDO;

            # trata erros.
        } catch (PDOException $e) {

            # Envia um e-mail para o e-mail oficial do sistema, em caso de erro de conexão.
            mail($sistema_email, "PDOException em $sistema_titulo", $e->getMessage());

            # Então não carrega nada mais da página.
            die("Connection Error: " . $e->getMessage());
        }
    }

}
