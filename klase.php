<?php

// Prisijungimas prie duomenų bazės
$db_host = 'localhost';         // serveris, kuriame yra duomenų bazė
$db_name = 'maisto_produktai';// duomenų bazės pavadinimas
$db_user = 'root'; // duomenų bazės vartotojo vardas
$db_pass = ''; // duomenų bazės vartotojo slaptažodis


$db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);

// Sukuriame produktų klasę
class Product {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getProducts() {
        $stmt = $this->db->prepare("SELECT * FROM produktai");
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $products;
    }
}

// Sukuriame duomenų bazės klasę
class Database {
    private $host;
    private $name;
    private $user;
    private $pass;
    private $conn;

    public function __construct($host, $name, $user, $pass) {
        $this->host = $host;
        $this->name = $name;
        $this->user = $user;
        $this->pass = $pass;
    }

    public function connect() {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->name;charset=utf8", $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Prisijungti nepavyko: " . $e->getMessage();
        }
        return $this->conn;
    }
}

// Sukuriame funkciją, kuri sukurs reikiamus objektus
function createObjects($host, $name, $user, $pass) {
    $db = new Database($host, $name, $user, $pass);
    $conn = $db->connect();
    $product = new Product($conn);
    return $product;
}

// Kviečiame funkciją, kuri sukurs reikiamus objektus
$product = createObjects('localhost', 'maisto_produktai', 'root', '');

// Gauname produktų masyvą ir jį atvaizduojame
$products = $product->getProducts();
foreach ($products as $product) {
    echo $product['id'] . " " . $product['pavadinimas'] . " " . $product['kcal'] . "<br>";
}

class Products {
    private $conn;

    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO maisto_produktai (pavadinimas, tipas, tiekejo_pavadinimas, kilmes_salis, baltymu_kiekis, angliavandenių_kiekis, riebalu_kiekis, kcal) VALUES (:pavadinimas, :tipas, :tiekejo_pavadinimas, :kilmes_salis, :baltymu_kiekis, :angliavandenių_kiekis, :riebalu_kiekis, :kcal)");
        $stmt->bindParam(':pavadinimas', $data['pavadinimas']);
        $stmt->bindParam(':tipas', $data['tipas']);
        $stmt->bindParam(':tiekiejas', $data['tiekiejas']);
        $stmt->bindParam(':kilmes_salis', $data['kilmes_salis']);
        $stmt->bindParam(':baltymai', $data['baltymai']);
        $stmt->bindParam(':angliavandeniai', $data['angliavandeniai']);
        $stmt->bindParam(':reibalai', $data['reibalai']);
        $stmt->bindParam(':kcal', $data['kcal']);
        $stmt->execute();
        return $this->conn->lastInsertId();
    }

    public function get($id) {
        $stmt = $this->conn->prepare("SELECT * FROM maisto_produktai WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE maisto_produktai SET pavadinimas = :pavadinimas, tipas = :tipas, tiekejo_pavadinimas = :tiekejo_pavadinimas, kilmes_salis = :kilmes_salis, baltymu_kiekis = :baltymu_kiekis, angliavandenių_kiekis = :angliavandenių_kiekis, riebalu_kiekis = :riebalu_kiekis, kcal = :kcal WHERE id = :id");
        $stmt->bindParam(':pavadinimas', $data['pavadinimas']);
        $stmt->bindParam(':tipas', $data['tipas']);
        $stmt->bindParam(':tiekiejas', $data['tiekiejas']);
        $stmt->bindParam(':kilmes_salis', $data['kilmes_salis']);
        $stmt->bindParam(':baltymai', $data['baltymai']);
        $stmt->bindParam(':angliavandeniai', $data['angliavandeniai']);
        $stmt->bindParam(':reibalai', $data['reibalai']);
        $stmt->bindParam(':kcal', $data['kcal']);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM maisto_produktai WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function search($field, $value) {
        $stmt = $this->conn->prepare("SELECT * FROM maisto_produktai WHERE $field LIKE :value");
        $stmt->bindValue(':value', "%$value%");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $stmt = $this->conn->query("SELECT * FROM maisto_produktai");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

class ObjectFactory {
    public static function createPDO($host, $dbname, $username, $password) {
        return new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    }

    public static function createProduct() {
        $pdo = self::createPDO('localhost', 'maisto_produktai', 'root', '');
        return new Product($pdo);
    }

    public static function createProductController() {
        $product = self::createProduct();
        return new ProductController($product);
    }
}

class ProductController {
    private $product;

    public function __construct(Product $product) {
        $this->product = $product;
    }

    public function createProduct($data) {
        return $this->product->create($data);
    }

    public function getProduct($id) {
        return $this->product->get($id);
    }

    public function updateProduct($id, $data) {
        $this->product->update($id, $data);
    }

    public function deleteProduct($id) {
        $this->product->delete($id);
    }

    public function searchProduct($field, $value) {
        return $this->product->search($field, $value);
    }

    public function getAllProducts() {
        return $this->product->getAll();
    }
}

$productController = ObjectFactory::createProductController();

// Sukuriam nauja produktu irasymo masyva
$productData = array(
    'pavadinimas' => 'Naujas produktas',
    'tipas' => 'Maistas',
    'tiekiejas' => 'Tiekejas UAB',
    'kilmes_salis' => 'Lietuva',
    'baltymai' => 10,
    'angliavandeniai' => 20,
    'riebalai' => 5,
    'kcal' => 150
);

$newProductId = $productController->createProduct($productData);

$newProduct = $productController->getProduct($newProductId);

print_r($newProduct);