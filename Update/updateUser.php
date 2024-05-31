<?php

require_once("../getConnect.php");

// Fonction qui modifie un utilisateur.
function updateUser($username, $prenom, $nom) {

    try {
        $pdo = getConnect();
        if ($pdo) {
            
                // Insertion de l'utilisateur.
                $req = "UPDATE utilisateur SET nom = :nom, prenom=:prenom WHERE username = :username ";
                $stmt = $pdo->prepare($req);
                $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
                $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);         
                $stmt->bindParam(':username', $username, PDO::PARAM_STR);         
                $stmt->execute();

                
                echo json_encode(["message" => "Utilisateur modifié avec succès"]);
            }
   } catch (Exception $e) {
        // Gestion des erreurs
        echo json_encode(["message" => "Problème lors de la modification de l'utilisateur" . $e->getMessage()]);
    } finally {
        // Fermeture de la connexion PDO
        if ($pdo) {
            $pdo = null;
        }
    }
}

$data = json_decode(file_get_contents("php://input"), true);

// Vérification si les données nécessaires sont présentes

if (isset($data['username'], $data['prenom'], $data['nom'])) {

    $username = $data['username'];
    $prenom = $data['prenom'];
    $nom = $data['nom'];
        
    updateUser($username,$prenom,$nom);

} else {
    // Gestion du cas où des données requises sont manquantes
    echo json_encode(["message" => "Paramètres manquants"]);
}