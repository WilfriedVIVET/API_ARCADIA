<?php

require_once("../getConnect.php");

//Fonction qui supprime un animal.
function deleteAnimal($index) {
    
    try {
        $pdo = getConnect();
        if ($pdo) {
            //Récupération du chemin de l'image de l'animal.
            $reqPath="SELECT image_path FROM animal where animal_id = :index";
            $stmtPath=$pdo->prepare($reqPath);
            $stmtPath->bindParam(':index',$index, PDO::PARAM_INT);
            $stmtPath->execute();
            $result = $stmtPath->fetch(PDO::FETCH_ASSOC);

            if($result){
                $image_path= $result['image_path'];
                
                //suppression du rapport de l'animal à supprimer.
                $reqRapport="DELETE from rapport where animal_id = :index";
                $stmtRapport = $pdo->prepare($reqRapport);
                $stmtRapport->bindParam(':index', $index, PDO::PARAM_INT);
                $stmtRapport->execute();           
                
                //suppression de l animal.
                $req = "DELETE FROM animal where animal_id = :index";
                $stmt = $pdo->prepare($req);
                $stmt->bindParam(':index', $index, PDO::PARAM_INT);
                $stmt->execute();           
                $stmt->closeCursor();

                //Suppression de l'image dans le dossier uploads.
                $absolute_path = __DIR__ . '/../' . $image_path;
                if(file_exists($absolute_path)){
                    unlink($absolute_path);
                }
                echo json_encode(["message"=>"Animal supprimé avec succès"]);
            }
        }  
        
    } catch (Exception $e) {
        // Message d'erreur
       echo json_encode(["message"=>"problème lors de la suppression de l'animal" + $e]);
    } finally {
        // Fermeture de la connexion PDO.
        if ($pdo) {
            $pdo = null;
        }
    }
}

$data = json_decode(file_get_contents("php://input"), true);

// Vérification si les données nécessaires sont présentes
if (isset($data['animal_id'])) {
    
    $index=$data['animal_id'];

    deleteAnimal($index);
    
} else {
    // Géstion du cas où des données requises sont manquantes
    echo json_encode(["message" => "Paramètres manquants"]);
}