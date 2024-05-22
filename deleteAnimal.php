<?php

require_once("./getConnect.php");

//Fonction qui supprime un animal.
function deleteAnimal($index) {
    
    try {
        $pdo = getConnect();
        if ($pdo) {
            $req = "DELETE FROM animal where animal_id = :index";
            $stmt = $pdo->prepare($req);
            $stmt->bindParam(':index', $index, PDO::PARAM_INT);
            $stmt->execute();           
            $stmt->closeCursor();
            echo json_encode(["message"=>"Animal supprimé avec succès"]);
        }            
        
                    
      
    } catch (Exception $e) {
        // Message d'erreur
       echo json_encode(["message"=>"problème lors de la suppression de l'animal" + $e]);
    } finally {
        // Fermeture de la connexion PDO.
        if ($pdo) {
            $pdo = null;
        }
    }
}

$data = json_decode(file_get_contents("php://input"), true);

// Vérification si les données nécessaires sont présentes
if (isset($data['animal_id'])) {
    
    $index=$data['animal_id'];

    deleteAnimal($index);
    
} else {
    // Géstion du cas où des données requises sont manquantes
    echo json_encode(["message" => "Paramètres manquants"]);
}