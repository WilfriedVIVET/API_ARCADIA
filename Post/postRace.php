<?php

require_once("../getConnect.php");

// Fonction qui ajoute un animal.
function postRace($label) {

    try {
        $pdo = getConnect();
        if ($pdo) {
            
                //Insertion de la race.
                $req="INSERT INTO race (label)VALUES(:label)";
                $stmt=$pdo->prepare($req);
                $stmt->bindParam(':label', $label, PDO::PARAM_STR);  
                $stmt->execute();

                echo json_encode(["message" => "Race ajouté avec succès"]);
            }
   } catch (Exception $e) {
        // Gestion des erreurs
        echo json_encode(["message" => "Problème lors de l'ajout de la race" . $e->getMessage()]);
    } finally {
        // Fermeture de la connexion PDO
        if ($pdo) {
            $pdo = null;
        }
    }
}

$data = json_decode(file_get_contents("php://input"), true);

// Vérification si les données nécessaires sont présentes

if (isset($data['label'])) {
    $label = $data['label'];
   
    postRace($label);

} else {
    // Gestion du cas où des données requises sont manquantes
    echo json_encode(["message" => "Paramètres manquants"]);
}