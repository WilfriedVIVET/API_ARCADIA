<?php

require_once("../getConnect.php");

// Fonction qui supprime un habitat.
function deleteHabitat($habitat_id) {
    try {
        $pdo = getConnect();
        if ($pdo) {
            // Récupérer le chemin de l'image à supprimer
            $req_select_image = "SELECT image_path FROM habitats WHERE habitat_id = :habitat_id";
            $stmt_select_image = $pdo->prepare($req_select_image);
            $stmt_select_image->bindParam(':habitat_id', $habitat_id, PDO::PARAM_INT);
            $stmt_select_image->execute();
            $image_path = $stmt_select_image->fetchColumn();

            // Suppression de l'habitat
            $req_delete_habitat = "DELETE FROM habitats WHERE habitat_id = :habitat_id";
            $stmt_delete_habitat = $pdo->prepare($req_delete_habitat);
            $stmt_delete_habitat->bindParam(':habitat_id', $habitat_id, PDO::PARAM_INT);
            $stmt_delete_habitat->execute();

            // Suppression de l'image du dossier uploads
            if (!empty($image_path) && file_exists("../uploads/" . $image_path)) {
                unlink("../uploads/" . $image_path);
                echo json_encode(["message" => $image_path]);
            }
            echo json_encode(["message" => "Habitat supprimé avec succès"]);

        }
    } catch (Exception $e) {
        // Gestion des erreurs
        echo json_encode(["message" => "Problème lors de la suppression de l'habitat: " . $e->getMessage()]);
    } finally {
        // Fermeture de la connexion PDO
        if ($pdo) {
            $pdo = null;
        }
    }
}

$data = json_decode(file_get_contents("php://input"), true);

// Vérification si les données nécessaires sont présentes
if (isset($data['habitat_id'])) {
    $habitat_id = $data['habitat_id'];
    deleteHabitat($habitat_id);
} else {
    // Gestion du cas où des données requises sont manquantes
    echo json_encode(["message" => "Paramètre 'habitat_id' manquant"]);
}

