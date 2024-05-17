<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Credentials: true");


//fonction connexion à la base de données.
function getConnect(){
   
    $dsn = "mysql:host=localhost;dbname=arcadia;port=3308";
    $username = "root";
    $password = "";

   
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