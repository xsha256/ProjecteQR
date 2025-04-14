<?php
$errors = [];
error_reporting(error_level: E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = trim($_POST["nom"] ?? '');
    $cognoms = trim($_POST["cognoms"] ?? '');
    $correu = trim($_POST["correu"] ?? '');
    $contrasenya = $_POST["contrasenya"] ?? '';
    $confirmacio_contrasenya = $_POST["confirmacio_contrasenya"] ?? '';

    if (empty($nom)) $errors[] = "El campo 'Nom' es obligatori.";
    if (empty($cognoms)) $errors[] = "El campo 'Cognoms' es obligatori.";
    if (empty($correu)) $errors[] = "El campo 'Correu electrònic' es obligatori.";
    if (empty($contrasenya)) $errors[] = "El campo 'Contrasenya' es obligatori.";

    if ($contrasenya !== $confirmacio_contrasenya) {
        $errors[] = "Las contraseñas no coinciden.";
    }

    if (empty($errors)) {

        $conexionDB = mysqli_connect("azureqrra.database.windows.net",  "usuari", "Nador.!993", "azureqrra/contacto");
        if (!$conexionDB) {
            die("Connection failed: "  . mysqli_connect_error());
        }

        //! op 1
        // $sql = "INSERT INTO usuari (nom, cognoms, email, contrasenya) VALUES ('" . $nom . "', '" . $cognoms . "', '" . $correu . "', '" . $contrasenya . "')";
        // $inserta = mysqli_query($conexionDB, $sql);

        // if (!$inserta) {
        //      echo "Error: " . $sql . "<br>";
        // } 

        // $adresaIP = '127.0.0.1';
        // $missatgeBenvinguda = function ($nom, $cognoms, $contrasenya, $adresaIP) {
        //     echo "<p style='color:green'>Benvingut/da, $nom $cognoms $contrasenya! (IP: $adresaIP connexió: " . date("d-m-Y H:i:s pm") . ").</p>";
        // };

        // $missatgeBenvinguda($nom, $cognoms, $contrasenya, $adresaIP);


        //! op 2
        $sql = "INSERT INTO usuari (nom, cognoms, email, contrasenya) VALUES (?, ?, ?, ?)";


        $stmt = $conexionDB->prepare($sql);

        if ($stmt) {
            $contrasenya_hashed = password_hash($contrasenya, PASSWORD_DEFAULT);

            $stmt->bind_param("ssss", $nom, $cognoms, $correu, $contrasenya_hashed);

            if ($stmt->execute()) {
                $adresaIP = '127.0.0.1';
                $missatgeBenvinguda = function ($nom, $cognoms, $contrasenya, $adresaIP) {
                    echo "<p style='color:green'>Benvingut/da, $nom $cognoms $contrasenya! (IP: $adresaIP connexió: " . date("d-m-Y H:i:s pm") . ").</p>";
                };
                $missatgeBenvinguda($nom, $cognoms, $contrasenya_hashed, $adresaIP);
            } else {
                echo "<p style='color:red;'>Error al insertar: " . $stmt->error . "</p>";
            }

            $stmt->close();
        }
    } else {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
}
