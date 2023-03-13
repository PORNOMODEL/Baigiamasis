<?php
?>
<!DOCTYPE html>
<html>
<head>
    <title>Maisto produktų duomenų bazę su maistingumo vertėmis</title>
</head>
<body>

<form method="GET" action="ieskoti.php">
    <label for="search">Ieškoti pagal pavadinimą:</label>
    <input type="text" id="search" name="search">
    <button type="submit" name="submit">Ieškoti</button>
</form>

<h1>Naujas produktas</h1>
<form method="POST" action="naujas_produktas.php">
    <label for="pavadinimas">Pavadinimas:</label><br>
    <input type="text" id="pavadinimas" name="pavadinimas"><br>

    <label for="tipas">Tipas:</label><br>
    <select id="tipas" name="tipas">
        <option value="maistas">Maistas</option>
        <option value="gerimas">Gėrimas</option>
    </select><br>

    <label for="tiekejas">Tiekėjas:</label><br>
    <input type="text" id="tiekejas" name="tiekejas"><br>

    <label for="kilmės_šalis">Kilmės šalis:</label><br>
    <input type="text" id="kilmes_salis" name="kilmes_salis"><br>

    <label for="baltymai">Baltymų kiekis (g):</label><br>
    <input type="number" id="baltymai" name="baltymai" ><br>

    <label for="angliavandeniai">Angliavandenių kiekis (g):</label><br>
    <input type="number" id="angliavandeniai" name="angliavandeniai"><br>

    <label for="riebalai">Riebalų kiekis (g):</label><br>
    <input type="number" id="riebalai" name="riebalai"><br>

    <label for="kcal">Kcal:</label><br>
    <input type="number" id="kcal" name="kcal"><br><br>

    <input type="submit" value="Pridėti produktą">

</form><br>
<h1>Redaguoti produkta</h1>
<form method="post" action="redaguoti.php">
    <label for="id">ID:</label>
    <input type="text" id="id" name="id" required>

    <label for="pavadinimas">Naujas pavadinimas:</label>
    <input type="text" id="pavadinimas" name="pavadinimas">

    <input type="submit" value="Redaguoti pavadinimą">
</form><br>

<h1>Produktų sąrašas</h1>
<?php
// prisijungimo kintamieji
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

// atvaizduojame lentelę su visais produktų duomenimis
$stmt = $db->query("SELECT * FROM produktai");
echo "<table>";
echo "<tr><th>ID</th><th>Pavadinimas</th><th>Tipas</th><th>Tiekejas</th><th>Kilmės šalis</th><th>Baltymai (g)</th><th>Angliavandeniai (g)</th><th>Riebalai (g)</th><th>Kalorijų kiekis (kcal)</th></tr>";
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr><td>".$row['id']."</td><td>".$row['pavadinimas']."</td><td>".$row['tipas']."</td><td>".$row['tiekejas']."</td><td>".$row['kilmes_salis']."</td><td>".$row['baltymai']."</td><td>".$row['angliavandeniai']."</td><td>".$row['riebalai']."</td><td>".$row['kcal']."</td></tr>";
}
echo "</table>";

?><br>

<h1>Ištrinti produkta</h1>
<form method="POST" action="istrinti.php">
    <label for="id">ID:</label>
    <input type="text" id="id" name="id" required>
    <button type="submit" name="delete">Ištrinti</button>
</form>

<h1>Parsiųsti failą</h1>
<form method="POST" action="csv.php">
    <button type="submit" name="download_csv">Parsisiųsti CSV failą</button>
</form>

</body>
</html>

