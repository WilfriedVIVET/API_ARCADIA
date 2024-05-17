<?php

require_once("./getConnect.php");

//Fonction qui modifie un service.
function updateService($index, $nom,$description) {
    
    try {
        $pdo = getConnect();
        if ($pdo) {
            $req = "UPDATE `services` SET `nom` = :nom , `description` =:description where `service_id` = :index";
            $stmt = $pdo->prepare($req);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':index', $index, PDO::PARAM_INT);
            $stmt->execute();           
            $stmt->closeCursor();
            echo json_encode(["message"=>"Service modifié avec succès"]);
        }            
                    
      
    } catch (Exception $e) {
        // Message d'erreur
       echo json_encode(["message"=>"problème lors de la modification du service"]);
    } finally {
        // Fermeture de la connexion PDO.
        if ($pdo) {
            $pdo = null;
        }
    }
}

$data = json_decode(file_get_contents("php://input"), true);

// Vérification si les données nécessaires sont présentes
if (isset($data['index'], $data['nom'], $data['description'])) {
    
    $index=$data['index'];
    $nom = $data['nom'];
    $description = $data['description'];
   
     
    updateService($index,$nom,$description);
    
} else {
    // Géstion du cas où des données requises sont manquantes
    echo json_encode(["message" => "Paramètres manquants"]);
}