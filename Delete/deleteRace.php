<?php

require_once("../getConnect.php");

// Fonction qui ajoute un animal.
function deleteRace($idRace) {

    try {
        $pdo = getConnect();
        if ($pdo) {
            
                //Insertion de l'image animal
                $req="DELETE FROM race WHERE race_id = :idRace";
                $stmt=$pdo->prepare($req);
                $stmt->bindParam(':idRace', $idRace, PDO::PARAM_INT);  
                $stmt->execute();

               
                echo json_encode(["message" => "Race supprimer avec succès"]);
            }
   } catch (Exception $e) {
        // Gestion des erreurs
        echo json_encode(["message" => "Problème lors de la suppression de la race" . $e->getMessage()]);
    } finally {
        // Fermeture de la connexion PDO
        if ($pdo) {
            $pdo = null;
        }
    }
}

$data = json_decode(file_get_contents("php://input"), true);

// Vérification si les données nécessaires sont présentes

if (isset($data['idRace'])) {

    $idRace = $data['idRace'];
   
    
    deleteRace($idRace);

} else {
    // Gestion du cas où des données requises sont manquantes
    echo json_encode(["message" => "Paramètres manquants"]);
}