<?php

require_once("./getConnect.php");

// Fonction qui récupere les avis utilisateur..
function postAvis($pseudo, $commentaire, $isValid) {

    try {
        $pdo = getConnect();
        if ($pdo) {
            
                // Insertion du contact.
                $req = "INSERT INTO avis(pseudo, commentaire , isValid) VALUES (:pseudo, :commentaire, :isValid)";
                $stmt = $pdo->prepare($req);
                $stmt->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
                $stmt->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);         
                $stmt->bindParam(':isValid', $isValid, PDO::PARAM_BOOL);         
                $stmt->execute();
 
                echo json_encode(["message" => "Avis envoyé avec succès"]);
            }
   } catch (Exception $e) {
        // Gestion des erreurs
        echo json_encode(["message" => "Problème lors de l'ajout de l'avis" . $e->getMessage()]);
    } finally {
        // Fermeture de la connexion PDO
        if ($pdo) {
            $pdo = null;
        }
    }
}

$data = json_decode(file_get_contents("php://input"), true);

// Vérification si les données nécessaires sont présentes

if (isset($data['pseudo'], $data['commentaire'], $data['isValid'])) {

    $pseudo = $data['pseudo'];
    $commentaire = $data['commentaire'];
    $isValid = $data['isValid'];
    
    postAvis($pseudo,$commentaire,$isValid);

} else {
    // Gestion du cas où des données requises sont manquantes
    echo json_encode(["message" => "Paramètres manquants"]);
}