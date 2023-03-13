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

// gauname produktų duomenis iš duomenų bazės
$stmt = $db->prepare("SELECT * FROM produktai");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// nustatome CSV failo pavadinimą
$filename = "maisto_produktai.csv";

// nustatome headerio content-type reikšmę
header('Content-Type: text/csv; charset=utf8');

// nustatome headerio content-disposition reikšmę, kuri leis parsisiųsti failą
header('Content-Disposition: attachment; filename="' . $filename . '";');

// atidarome PHP output buffer, kad galėtume įrašyti duomenis į failą
$output = fopen('php://output', 'w');

// įrašome laukelių pavadinimus kaip pirmą eilutę failo
fputcsv($output, array_keys($products[0]));

// įrašome kiekvieną produktą kaip naują eilutę failo
foreach ($products as $product) {
    fputcsv($output, $product);
}

// uždarome PHP output buffer
fclose($output);
exit();

