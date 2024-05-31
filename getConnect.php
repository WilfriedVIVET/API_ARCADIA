<?php

function loadEnv($path)
{
    if (!file_exists($path)) {
        throw new Exception("Le fichier .env n'existe pas.");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        if (!array_key_exists($name, $_ENV)) {
            $_ENV[$name] = $value;
        }
    }
}

// Détection de l'environnement
$isLocalhost = in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']);
$envFile = $isLocalhost ? '.env.developpement' : '.env.production';

// Chargement des variables d'environnement depuis le fichier .env
loadEnv(__DIR__ . '/'. $envFile);


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Credentials: true");


// Fonction de connexion à la base de données
function getConnect(){
   $dsn = "mysql://" . $_ENV['DB_USER'].":" .$_ENV['DB_PASS']."@".$_ENV['DB_HOST'].":".$_ENV['DB_PORT']."/".$_ENV['DB_NAME'];
   $username = $_ENV['DB_USER'];
   $password = $_ENV['DB_PASS'];

    try {
        $pdo = new PDO($dsn,$username,$password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
     } catch (PDOException $e) {
        // l'erreur de connexion.
        throw new Exception("Erreur de connexion à la base de données" .$e->getMessage());
       
     }catch(Exception $e){
       throw new Exception("Erreur de connexion à la base de données" .$e->getMessage());
      
     }
    }