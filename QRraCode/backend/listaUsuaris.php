<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        rel="stylesheet" />
</head>

<body style="text-align: center">
    <h3>
        <a href="./formulari.html">Back</a>
    </h3>
    <?php
    $serverName = "tcp:azureqrra.database.windows.net,1433";
    $connectionOptions = array(
        "Database" => "contacto",
        "Uid" => "usuari",
        "PWD" => "Nador.!993",
        "LoginTimeout" => 30,
        "Encrypt" => 1,
        "TrustServerCertificate" => 0
    );


    $conn = sqlsrv_connect($serverName, $connectionOptions);
    ?>
    <div class="container mt-5">
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Nom</th>
                    <th>Cognoms</th>
                    <th>Email</th>
                    <th>Contraseña</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!$conn) {
                    die("Connection failed: " . print_r(sqlsrv_errors(), true));
                } else {
                    $sql = "SELECT nom, cognoms, email, contrasenya FROM usuari";
                    $stmt = sqlsrv_query($conn, $sql);

                    if ($stmt === false) {
                        die("Query failed: " . print_r(sqlsrv_errors(), true));
                    }

                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                        echo "
                    <tr>
                        <td>{$row['nom']}</td>
                        <td>{$row['cognoms']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['contrasenya']}</td>
                    </tr>
                    ";
                    }

                    sqlsrv_free_stmt($stmt);
                    sqlsrv_close($conn);
                }
                ?>
            </tbody>
        </table>
    </div>





</body>

</html>