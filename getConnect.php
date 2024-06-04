<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: *");


if(getenv('JAWSDB_URL') !== false){
    $dbparts = parse_url(getenv('JAWSDB_URL'));
    //En ligne
    $hostname = $dbparts['host'];
    $username = $dbparts['user'];
    $password = $dbparts['pass'];
    $database = ltrim($dbparts['path'],'/');
}else{
    //En local
    $username = 'root';
    $password = '';
    $database = 'arcadia';
    $hostname= 'localhost';
}

// Fonction de connexion à la base de données
function getConnect($hostname,$username,$password,$database){
   
    try {
        $pdo = new PDO("mysql:host=$hostname;dbname=$database",$username,$password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        // l'erreur de connexion.
        throw new Exception("Erreur de connexion à la base de données: " . $e->getMessage());
    } 
}