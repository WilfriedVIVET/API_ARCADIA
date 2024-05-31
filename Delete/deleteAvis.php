<?php

require_once("../getConnect.php");

// Fonction qui supprime un habitat.
function deleteAvis($avis_id) {
   
    try {
        $pdo = getConnect();
        if ($pdo) {
            
             // suppression de l'habitat.
            $req = "DELETE FROM avis WHERE avis_id = :avis_id";
            $stmt = $pdo->prepare($req);
            $stmt->bindParam(':avis_id', $avis_id, PDO::PARAM_INT);
          
            $stmt->execute();

                echo json_encode(["message" => "avis supprimer avec succès"]);
            }
   } catch (Exception $e) {
        // Gestion des erreurs
        echo json_encode(["message" => "Problème lors de la suppression de l'avis" . $e->getMessage()]);
    } finally {
        // Fermeture de la connexion PDO
        if ($pdo) {
            $pdo = null;
        }
    }

}

$data = json_decode(file_get_contents("php://input"), true);

// Vérification si les données nécessaires sont présentes

if (isset($data['avis_id'])) {

    $avis_id = $data['avis_id'];
    
   
    deleteAvis($avis_id );

} else {
    // Gestion du cas où des données requises sont manquantes
    echo json_encode(["message" => "Paramètres manquants"]);
}