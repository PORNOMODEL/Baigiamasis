<?php

class Product {
    private $db;

    function __construct(PDO $db) {
        $this->db = $db;
    }
    
    function create($product) {
        $stmt = $this->db->prepare("INSERT INTO produktai (pavadinimas, tipas, tiekejas, kilmes_salis, baltymai, angliavandeniai, riebalai, kcal) VALUES (:pavadinimas, :tipas, :tiekejas, :kilmes_salis, :baltymai, :angliavandeniai, :riebalai, :kcal)");
        $stmt->execute([
            'pavadinimas' => $product['pavadinimas'],
            'tipas' => $product['tipas'],
            'tiekejas' => $product['tiekejas'],
            'kilmes_salis' => $product['kilmes_salis'],
            'baltymai' => $product['baltymai'],
            'angliavandeniai' => $product['angliavandeniai'],
            'riebalai' => $product['riebalai'],
            'kcal' => $product['kcal'],
        ]);
    }

}
