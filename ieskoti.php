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

// tikriname, ar buvo paspaustas mygtukas 'Ieškoti'
if (isset($_GET['submit'])) {
    $search = $_GET['search'];
    $stmt = $db->prepare("SELECT * FROM produktai WHERE pavadinimas LIKE :search");
    $stmt->execute(['search' => "%$search%"]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($products) > 0) {
        // atvaizduojame produktus, kurie atitinka paieškos kriterijus
        echo "<table>";
        echo "<tr><th>ID</th><th>Pavadinimas</th><th>Tipas</th><th>Tiekėjas</th><th>Kilmės šalis</th><th>Baltymai (g)</th><th>Angliavandeniai (g)</th><th>Riebalai (g)</th><th>Kalorijų kiekis (kcal)</th></tr>";
        foreach($products as $row) {
            echo "<tr><td>".$row['id']."</td><td>".$row['pavadinimas']."</td><td>".$row['tipas']."</td><td>".$row['tiekejas']."</td><td>".$row['kilmes_salis']."</td><td>".$row['baltymai']."</td><td>".$row['angliavandeniai']."</td><td>".$row['riebalai']."</td><td>".$row['kcal']."</td></tr>";
        }
        echo "</table>";

    } else {
        echo "Nerasta produktų, atitinkančių paieškos kriterijus.";
    }
}

