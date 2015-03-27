    <?php

$app->get("/employee", function () {

    $sql = "SELECT EmployeeID,FirstName,LastName,HomePhone FROM Employees";
    $stmt = DB::prepare($sql);
    $stmt->execute();
    formatJson($stmt->fetchAll());
});

$app->get("/employee/:id", function ($id) {

    //DATE_FORMAT( `date` , '%d/%c/%Y %H:%i:%s' ) AS `date`
    $sql = "SELECT EmployeeID,FirstName,LastName,HomePhone,DATE_FORMAT(BirthDate,'%d/%c/%Y') as BirthDate FROM Employees WHERE EmployeeID=?";
    $stmt = DB::prepare($sql);
    $stmt->execute(array($id));
    formatJson($stmt->fetch());
});

$app->post("/employee/", function () {

    $data = json_decode(\Slim\Slim::getInstance()->request()->getBody());

    if ($data->EmployeeID != 0) {
        $sql = "UPDATE Employees SET FirstName=?,LastName=?,HomePhone=?,BirthDate=? WHERE EmployeeID=?";
        $stmt = DB::prepare($sql);
        $stmt->execute(array(
            $data->FirstName,
            $data->LastName,
            $data->HomePhone,
            DB::dateToMySql($data->BirthDate),
            $data->EmployeeID
                )
        );
    } else {
        $sql = "INSERT INTO Employees (FirstName,LastName,HomePhone,BirthDate)  VALUES (?,?,?,?)";
        $stmt = DB::prepare($sql);
        $stmt->execute(array(
            $data->FirstName,
            $data->LastName,
            $data->HomePhone,
            DB::dateToMySql($data->BirthDate)
                )
        );
        $data->EmployeeID = DB::lastInsertId();
    }

    formatJson($data);
});

$app->delete("/employee/:id", function ($id) {
    $sql = "DELETE FROM Customers WHERE CustomerID=?";
    $stmt = DB::prepare($sql);
    $stmt->execute(array($id));
    formatJson(true);
});





