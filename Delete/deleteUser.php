<?php

require_once("../getConnect.php");

// Fonction qui supprime un compte utilisateur.
function deleteUser($username) {

    try {
        $pdo = getConnect();
        if ($pdo) {
           
                // suppression d'un utilisateur utilisateur
                $req = "DELETE FROM utilisateur WHERE username = :username";
                $stmt = $pdo->prepare($req);
                $stmt->bindParam(':username', $username, PDO::PARAM_STR);
                
                $stmt->execute();

                echo json_encode(["message" => "Compte supprimer avec succès"]);
            
        }
    } catch (Exception $e) {
        // Gestion des erreurs
        echo json_encode(["message" => "Problème lors de la suppression du compte" . $e->getMessage()]);
    } finally {
        // Fermeture de la connexion PDO
        if ($pdo) {
            $pdo = null;
        }
    }
}

$data = json_decode(file_get_contents("php://input"), true);

// Vérification si les données nécessaires sont présentes
if (isset($data['username'])) {

    $username = $data['username'];
    
    deleteUser($username);

} else {
    // Gestion du cas où des données requises sont manquantes
    echo json_encode(["message" => "Paramètres manquants"]);
}