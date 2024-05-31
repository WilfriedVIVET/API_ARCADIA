<?php

require_once("../getConnect.php");

// Fonction qui modifie l'état d'un habitat.
function postContact($titre, $description, $email) {

    try {
        $pdo = getConnect();
        if ($pdo) {
            
                // Insertion du contact.
                $req = "INSERT INTO contact(titre, description , email) VALUES (:titre, :description, :email)";
                $stmt = $pdo->prepare($req);
                $stmt->bindParam(':titre', $titre, PDO::PARAM_STR);
                $stmt->bindParam(':description', $description, PDO::PARAM_STR);         
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);         
                $stmt->execute();
 
                echo json_encode(["message" => "Contact ajouté avec succès"]);
            }
   } catch (Exception $e) {
        // Gestion des erreurs
        echo json_encode(["message" => "Problème lors de l'ajout du contact" . $e->getMessage()]);
    } finally {
        // Fermeture de la connexion PDO
        if ($pdo) {
            $pdo = null;
        }
    }
}

$data = json_decode(file_get_contents("php://input"), true);

// Vérification si les données nécessaires sont présentes

if (isset($data['titre'], $data['description'], $data['email'])) {

    $titre = htmlspecialchars($data['titre'],ENT_QUOTES,'UTF-8');
    $description = htmlspecialchars($data['description'],ENT_QUOTES,'UTF-8');
    $email = htmlspecialchars($data['email'],ENT_QUOTES,'UTF-8');
    
    postContact($titre,$description,$email);

} else {
    // Gestion du cas où des données requises sont manquantes
    echo json_encode(["message" => "Paramètres manquants"]);
}