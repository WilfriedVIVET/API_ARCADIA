<?php

require_once("./getConnect.php");

// Fonction qui crée un compte utilisateur.
function postInfoAnimal($id_animal, $etat, $detail,$nourritue,$grammage,$date) {

    try {
        $pdo = getConnect();
        if ($pdo) {
            
                // Insertion de l'etat de l'animal.
                $req = "UPDATE animal SET etat = :etat where animal_id = :animal_id";
                $stmt = $pdo->prepare($req);
                $stmt->bindParam(':etat', $etat, PDO::PARAM_STR);
                $stmt->bindParam(':animal_id', $animal_id, PDO::PARAM_INT);         
                $stmt->execute();

                
                // Insertion du rapport de l'animal.
                $stmt = $pdo->prepare("INSERT INTO rapport (detail_etat,nourriture,grammage,date_rapport) VALUES
                (:detail, :nourriture, :grammage, :date_rapport)");
                $stmt->bindParam(":detail", $detail, PDO::PARAM_STR);
                $stmt->bindParam(":nourriture", $nourriture, PDO::PARAM_STR);
                $stmt->bindParam(":grammage", $grammage, PDO::PARAM_STR);
                $stmt->bindParam(":date_rapport", $date_rapport, PDO::PARAM_STR);
                $stmt->execute();

                //Récupération de l'ID du nouveau rapport.
                $newRapport = $pdo->lastInsertId();

                //Insertion dans la table animal_rapport de la liaison animal/rapport
                $stmt = $pdo->prepare("INSERT INTO animal_rapport (rapport_id,animal_id) VALUES
                ($newRapport, :id_animal)");
                $stmt->bindParam(":id_animal", $animal_id, PDO::PARAM_INT);
                $stmt->execute();

                echo json_encode(["message" => "Rapport créé avec succès"]);
            }
   } catch (Exception $e) {
        // Gestion des erreurs
        echo json_encode(["message" => "Problème lors de la création du rapport" . $e->getMessage()]);
    } finally {
        // Fermeture de la connexion PDO
        if ($pdo) {
            $pdo = null;
        }
    }
}

$data = json_decode(file_get_contents("php://input"), true);

// Vérification si les données nécessaires sont présentes

if (isset($data['id_animal'], $data['etat'], $data['detail'], $data['nourriture'], $data['grammage'], $data['date'])) {

    $id_animal = $data['id_animal'];
    $etat = $data['etat'];
    $detail = $data['detail'];
    $nourriture = $data['nourriture'];
    $grammage = $data['grammage'];
    $date = $data['date'];

    postInfoAnimal($id_animal,$etat, $detail,$nourriture,$grammage,$date);

} else {
    // Gestion du cas où des données requises sont manquantes
    echo json_encode(["message" => "Paramètres manquants"]);
}