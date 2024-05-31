<?php

require_once("../getConnect.php");

//Fonction qui supprime un service.
function deleteService($index) {
    
    try {
        $pdo = getConnect();
        if ($pdo) {
            $req = "DELETE FROM `services` where `service_id` = :index";
            $stmt = $pdo->prepare($req);
            $stmt->bindParam(':index', $index, PDO::PARAM_INT);
            $stmt->execute();           
            $stmt->closeCursor();
            echo json_encode(["message"=>"Service supprimé avec succès"]);
        }            
                    
      
    } catch (Exception $e) {
        // Message d'erreur
       echo json_encode(["message"=>"problème lors de la suppression du service" + $e]);
    } finally {
        // Fermeture de la connexion PDO.
        if ($pdo) {
            $pdo = null;
        }
    }
}

$data = json_decode(file_get_contents("php://input"), true);

// Vérification si les données nécessaires sont présentes
if (isset($data['index'])) {
    
    $index=$data['index'];

    deleteService($index);
    
} else {
    // Géstion du cas où des données requises sont manquantes
    echo json_encode(["message" => "Paramètres manquants"]);
}