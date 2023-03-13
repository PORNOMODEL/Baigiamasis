<?php
// Prisijungimas prie duomenų bazės
$db_host = 'localhost';         // serveris, kuriame yra duomenų bazė
$db_name = 'maisto_produktai';  // duomenų bazės pavadinimas
$db_user = 'root';              // duomenų bazės vartotojo vardas
$db_pass = '';                  // duomenų bazės vartotojo slaptažodis

// prisijungimo kodas
try {
    $db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Prisijungti nepavyko: " . $e->getMessage();
}

// tikriname, ar buvo paspaustas mygtukas 'Ištrinti'
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $stmt = $db->prepare("DELETE FROM produktai WHERE id = :id");
    $stmt->execute(['id' => $id]);
    echo "Produktas ištrintas sėkmingai.";
}