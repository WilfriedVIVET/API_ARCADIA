<?php

require_once("./getConnect.php");

// Fonction qui supprime un habitat.
function deleteHabitat($habitat_id) {
   
    try {
        $pdo = getConnect();
        if ($pdo) {
            
             // suppression de l'habitat.
            $req = "DELETE FROM habitats WHERE habitat_id = :habitat_id";
            $stmt = $pdo->prepare($req);
            $stmt->bindParam(':habitat_id', $habitat_id, PDO::PARAM_INT);
          
            $stmt->execute();

           

                echo json_encode(["message" => "habitat supprimer avec succès"]);
            }
   } catch (Exception $e) {
        // Gestion des erreurs
        echo json_encode(["message" => "Problème lors de la suppression de l'habitat" . $e->getMessage()]);
    } finally {
        // Fermeture de la connexion PDO
        if ($pdo) {
            $pdo = null;
        }
    }

}

$data = json_decode(file_get_contents("php://input"), true);

// Vérification si les données nécessaires sont présentes

if (isset($data['habitat_id'],)) {

    $habitat_id = $data['habitat_id'];
   
    deleteHabitat($habitat_id);

} else {
    // Gestion du cas où des données requises sont manquantes
    echo json_encode(["message" => "Paramètres manquants"]);
}