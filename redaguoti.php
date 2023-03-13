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

// tikriname, ar yra užklausos duomenys
if(isset($_POST['id']) && !empty($_POST['id'])) {
    $id = $_POST['id'];

    // renkamės produktą iš duomenų bazės pagal ID
    $stmt = $db->prepare("SELECT * FROM produktai WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // atnaujiname produktą su nauju pavadinimu
    if(isset($_POST['pavadinimas'])) {
        $product['pavadinimas'] = $_POST['pavadinimas'];
    }

    // atnaujiname produkto pavadinimą duomenų bazėje
    $stmt = $db->prepare("UPDATE produktai SET pavadinimas = :pavadinimas WHERE id = :id");
    $stmt->execute([
        'id' => $id,
        'pavadinimas' => $product['pavadinimas'],
    ]);

    $stmt->execute();

    echo "Produktas sėkmingai pakeistas duomenų bazeje!";
} else {
    echo "Nepateikti produktų duomenys.";
}



