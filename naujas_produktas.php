<?php
// Prisijungimas prie duomenų bazės
$db_host = 'localhost';         // serveris, kuriame yra duomenų bazė
$db_name = 'maisto_produktai';  // duomenų bazės pavadinimas
$db_user = 'root';              // duomenų bazės vartotojo vardas
$db_pass = '';                  // duomenų bazės vartotojo slaptažodis


$db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);


// Tikriname, ar forma buvo teisingai užpildyta
if (isset($_POST['pavadinimas']) && !empty($_POST['pavadinimas'])
    && isset($_POST['tipas']) && !empty($_POST['tipas'])
    && isset($_POST['tiekejas']) && !empty($_POST['tiekejas'])
    && isset($_POST['kilmes_salis']) && !empty($_POST['kilmes_salis'])
    && isset($_POST['baltymai']) && !empty($_POST['baltymai'])
    && isset($_POST['angliavandeniai']) && !empty($_POST['angliavandeniai'])
    && isset($_POST['riebalai']) && !empty($_POST['riebalai'])
    && isset($_POST['kcal']) && !empty($_POST['kcal'])) {

    if (isset($_POST['submit'])) {
        try {
            // tikriname ar vartotojo įvestis atitinka tam tikras reikalavimus
            if (!is_numeric($_POST['baltymai']) || !is_numeric($_POST['angliavandeniai']) || !is_numeric($_POST['riebalai']) || !is_numeric($_POST['kcal'])) {
                throw new Exception('Netinkama vartotojo įvestis');
            }

        } catch (Exception $e) {
            echo 'Klaida: ' . $e->getMessage();
        }
    }
    // Užklausos SQL sukurimas
    $sql = "INSERT INTO produktai (pavadinimas, tipas, tiekejas, kilmes_salis, baltymai, angliavandeniai, riebalai, kcal)
            VALUES (:pavadinimas, :tipas, :tiekejas, :kilmes_salis, :baltymai, :angliavandeniai, :riebalai, :kcal)";

    // Užklausos vykdymas
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':pavadinimas', $_POST['pavadinimas']);
    $stmt->bindParam(':tipas', $_POST['tipas']);
    $stmt->bindParam(':tiekejas', $_POST['tiekejas']);
    $stmt->bindParam(':kilmes_salis', $_POST['kilmes_salis']);
    $stmt->bindParam(':baltymai', $_POST['baltymai']);
    $stmt->bindParam(':angliavandeniai', $_POST['angliavandeniai']);
    $stmt->bindParam(':riebalai', $_POST['riebalai']);
    $stmt->bindParam(':kcal', $_POST['kcal']);
    $stmt->execute();

    echo "Produktas sėkmingai pridėtas į duomenų bazę!";
} else {
    echo "Klaida: nevisi laukai užpildyti arba neteisingi duomenys.";
}


