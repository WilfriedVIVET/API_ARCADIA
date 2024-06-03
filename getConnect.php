<?php
header("Access-Control-Allow-Origin: https://arcadia-vivet-729d06aa27ef.herokuapp.com");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Credentials: true");

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

// Gérer les requêtes OPTIONS (pré-vol CORS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Fonction de connexion à la base de données
function getConnect(){
    $dsn = "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'] . ";port=" . $_ENV['DB_PORT'];
    $username = $_ENV['DB_USER'];
    $password = $_ENV['DB_PASS'];

    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        // l'erreur de connexion.
        throw new Exception("Erreur de connexion à la base de données: " . $e->getMessage());
    } catch (Exception $e) {
        throw new Exception("Erreur de connexion à la base de données: " . $e->getMessage());
    }
}


