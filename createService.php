<?php

require_once("./getConnect.php");

//Fonction qui supprime un service.
function createService($nom, $description) {
    
    try {
        $pdo = getConnect();
        if ($pdo) {
            $req = "INSERT INTO `services`(nom, description) VALUES (:nom , :description)";
            $stmt = $pdo->prepare($req);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->execute();           
            $stmt->closeCursor();
            echo json_encode(["message"=>"Service ajouté avec succès"]);
        }            
                    
      
    } catch (Exception $e) {
        // Message d'erreur
       echo json_encode(["message"=>"problème lors de l'ajout du service"]);
    } finally {
        // Fermeture de la connexion PDO.
        if ($pdo) {
            $pdo = null;
        }
    }
}

$data = json_decode(file_get_contents("php://input"), true);

// Vérification si les données nécessaires sont présentes
if (isset($data['index'],$data['nom'],$data['description'])) {
    
    $index=$data['index'];
    $nom=$data['nom'];
    $description=$data['description'];

    createService($nom,$description);
    
} else {
    // Géstion du cas où des données requises sont manquantes
    echo json_encode(["message" => "Paramètres manquants"]);
}