<?php

require_once("../getConnect.php");

// Fonction qui crée un rapport sur l'animal
function postInfoRapport($animal_id, $date, $detail, $etat, $grammage, $nourriture, $nrtconseille, $qtconseille) {
    try {
        $pdo = getConnect();
        if ($pdo) {
            // Insertion de l'état de l'animal.
            $req = "UPDATE animal SET etat = :etat WHERE animal_id = :animal_id";
            $stmt = $pdo->prepare($req);
            $stmt->bindParam(':etat', $etat, PDO::PARAM_STR);
            $stmt->bindParam(':animal_id', $animal_id, PDO::PARAM_INT);
            $stmt->execute();

            // Insertion du rapport de l'animal.
            $req = "INSERT INTO rapport (detail_etat, nourriture, grammage, animal_id, nrtconseille, qtconseille, date_rapport)
                    VALUES (:detail, :nourriture, :grammage, :animal_id, :nrtconseille, :qtconseille, :date_rapport)";
            $stmt = $pdo->prepare($req);
            $stmt->bindParam(":detail", $detail, PDO::PARAM_STR);
            $stmt->bindParam(":nourriture", $nourriture, PDO::PARAM_STR);
            $stmt->bindParam(":grammage", $grammage, PDO::PARAM_STR);
            $stmt->bindParam(":animal_id", $animal_id, PDO::PARAM_INT);
            $stmt->bindParam(":nrtconseille", $nrtconseille, PDO::PARAM_STR);
            $stmt->bindParam(":qtconseille", $qtconseille, PDO::PARAM_STR);
            $stmt->bindParam(":date_rapport", $date, PDO::PARAM_STR);
            $stmt->execute();

            echo json_encode(["message" => "Rapport créé avec succès"]);
        }
    } catch (Exception $e) {
        // Gestion des erreurs
        echo json_encode(["message" => "Problème lors de la création du rapport: " . $e->getMessage()]);
    } finally {
        // Fermeture de la connexion PDO
        if ($pdo) {
            $pdo = null;
        }
    }
}

$data = json_decode(file_get_contents("php://input"), true);

// Vérification si les données nécessaires sont présentes
if (isset($data['animal_id'], $data['date'], $data['detail'], $data['etat'], $data['grammage'], $data['nourriture'], $data['nrtconseille'], $data['qtconseille'])) {
    $animal_id = $data['animal_id'];
    $date = $data['date'];
    $detail = $data['detail'];
    $etat = $data['etat'];
    $grammage = $data['grammage'];
    $nourriture = $data['nourriture'];
    $nrtconseille = $data['nrtconseille'];
    $qtconseille = $data['qtconseille'];

    postInfoRapport($animal_id, $date, $detail, $etat, $grammage, $nourriture, $nrtconseille, $qtconseille);
} else {
    // Gestion du cas où des données requises sont manquantes
    echo json_encode(["message" => "Paramètres manquants"]);
}

