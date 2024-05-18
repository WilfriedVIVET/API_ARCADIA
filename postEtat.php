<?php

require_once("./getConnect.php");

// Fonction qui modifie l'état d'un habitat.
function postEtat($habitat, $etat) {

    try {
        $pdo = getConnect();
        if ($pdo) {
            
                // Insertion de l'etat de l'habitat.
                $req = "UPDATE habitats SET commentaire = :etat WHERE nom = :habitat ";
                $stmt = $pdo->prepare($req);
                $stmt->bindParam(':etat', $etat, PDO::PARAM_STR);
                $stmt->bindParam(':habitat', $habitat, PDO::PARAM_STR);         
                $stmt->execute();
 
                echo json_encode(["message" => "Etat habitat modifié avec succès"]);
            }
   } catch (Exception $e) {
        // Gestion des erreurs
        echo json_encode(["message" => "Problème lors de la modification de l'état de l'habitat" . $e->getMessage()]);
    } finally {
        // Fermeture de la connexion PDO
        if ($pdo) {
            $pdo = null;
        }
    }
}

$data = json_decode(file_get_contents("php://input"), true);

// Vérification si les données nécessaires sont présentes

if (isset($data['habitat'], $data['etat'])) {

    $habitat = $data['habitat'];
    $etat = $data['etat'];
    

    postEtat($habitat,$etat);

} else {
    // Gestion du cas où des données requises sont manquantes
    echo json_encode(["message" => "Paramètres manquants"]);
}