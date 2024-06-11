<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, UPDATE,INSERT");
header("Access-Control-Allow-Credentials: true");



// Fonction de connexion à la base de données
function getConnect(){

    
    if(getenv('JAWSDB_URL') !== false){
        $dbparts = parse_url(getenv('JAWSDB_URL'));
        //En ligne
        $hostname = $dbparts['host'];
        $username = $dbparts['user'];
        $password = $dbparts['pass'];
        $database = ltrim($dbparts['path'],'/');
        $dsn = "mysql:host=$hostname;dbname=$database";
    }else{
        //En local
        $username = 'root';
        $password = 'root';
        $database = 'arcadia';
        $hostname= 'localhost';
        $dsn = "mysql:host=$hostname;dbname=$database;port=3306";
    }
    

    try {
        $pdo = new PDO($dsn,$username,$password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        // l'erreur de connexion.
        throw new Exception("Erreur de connexion à la base de données: " . $e->getMessage());
    } 
}