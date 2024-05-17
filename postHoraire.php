<?php

require_once("./getConnect.php");

//Fonction qui modifie les horaire.
function postHoraire($newHoraire) {
    
    try {
        $pdo = getConnect();
        if ($pdo) {

            foreach($newHoraire as $horaire){
                $moment = $horaire['moment'];
                $heure = $horaire['heure'];
                $index = $horaire['index'];
                
                $req = "UPDATE `horaire` SET `%s` = :heure where `horaire_id` = :index";
                $stmt = $pdo->prepare(sprintf($req, $moment));
                $stmt->bindParam(':heure', $heure, PDO::PARAM_STR);
                $stmt->bindParam(':index', $index, PDO::PARAM_INT);
                $stmt->execute();           
                $stmt->closeCursor();
                
            }
                echo json_encode(["message"=>"horaire modifié avec succès"]);
        }            
                    
      
    } catch (Exception $e) {
        // Message d'erreur
       echo json_encode(["message"=>"problème lors de la modification des horaires"]);
    } finally {
        // Fermeture de la connexion PDO.
        if ($pdo) {
            $pdo = null;
        }
    }
}

$data = json_decode(file_get_contents("php://input"), true);

// Vérification si les données nécessaires sont présentes
if (!empty($data)) {
    
    foreach ($data as $horaire) {
        if (!isset($horaire['index'], $horaire['moment'], $horaire['heure'])) {
             echo json_encode(["message" => "Paramètres manquants"]);       
        }
    }
        
    postHoraire($data);
       
} else {
    // Géstion du cas où des données requises sont manquantes
    echo json_encode(["message" => "Paramètres manquants"]);
    
}