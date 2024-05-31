<?php

require_once("../getConnect.php");

// Fonction qui valide des avis.
function postValidAvis($newAvis) {
    try {
        $pdo = getConnect();
        if ($pdo) {
            foreach ($newAvis as $avis) {
                $avis_id = $avis['avis_id'];
                $isValid = $avis['isValid'];

                // Mise à jour des avis validés.
                $req = "UPDATE avis SET isValid = :isValid WHERE avis_id = :avis_id";
                $stmt = $pdo->prepare($req);
                $stmt->bindParam(':isValid', $isValid, PDO::PARAM_BOOL);
                $stmt->bindParam(':avis_id', $avis_id, PDO::PARAM_INT);
                $stmt->execute();
            }
            echo json_encode(["message" => "Avis validé(s) avec succès"]);
        }
    } catch (Exception $e) {
        // Gestion des erreurs
        echo json_encode(["message" => "Problème lors de la validation des avis: " . $e->getMessage()]);
    } finally {
        // Fermeture de la connexion PDO
        if ($pdo) {
            $pdo = null;
        }
    }
}

$data = json_decode(file_get_contents("php://input"), true);

// Vérification si les données nécessaires sont présentes
if (isset($data) && is_array($data)) {
    foreach ($data as $avis) {
        if (!isset($avis['avis_id'], $avis['isValid'])) {
            echo json_encode(["message" => "Paramètres manquants"]);
            exit;
        }
    }
    postValidAvis($data);
} else {
    // Gestion du cas où des données requises sont manquantes
    echo json_encode(["message" => "Paramètres manquants"]);
}
