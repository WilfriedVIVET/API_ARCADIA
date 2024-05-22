<?php

require_once("./getConnect.php");

// Fonction qui modifie ou ajoute un habitat.
function postHabitat($habitat_id, $nom, $description, $image_data) {
    try {
        $pdo = getConnect();
        if ($pdo) {

            
            //Vérification si l'habitat existe.
            $reqcheckHabitat = "SELECT COUNT(*) FROM habitats WHERE nom = :nom ";
            $stmtcheckHabitat = $pdo->prepare($reqcheckHabitat);
            $stmtcheckHabitat->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmtcheckHabitat->execute();
            $habitatExist = (bool)$stmtcheckHabitat->fetchColumn();

            if ($habitatExist) {
                // Insertion des modification de l' habitat.
                $req = "UPDATE habitats SET nom = :nom, description = :description WHERE habitat_id = :habitat_id";
                $stmt = $pdo->prepare($req);
                $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
                $stmt->bindParam(':description', $description, PDO::PARAM_STR);
                $stmt->bindParam(':habitat_id', $habitat_id, PDO::PARAM_INT);
                $stmt->execute();
                
                //Insertion de l'image de l'habitat
                $req = "UPDATE image_habitat SET image_data = :image_data WHERE image_id = :habitat_id";
                $stmt = $pdo->prepare($req);
                $stmt->bindParam(':image_data', $image_data, PDO::PARAM_LOB);
                $stmt->bindParam(':habitat_id', $habitat_id, PDO::PARAM_INT);
                $stmt->execute();

                echo json_encode(["message" => "Habitat modifié avec succès"]);
            } else {

                // Insertion de l'image de l'habitat.
                $req = "INSERT INTO image_habitat (image_data) VALUES (:image_data)";
                $stmt = $pdo->prepare($req);
                $stmt->bindParam(':image_data', $image_data, PDO::PARAM_LOB);
                $stmt->execute();


                 //Récupération de lindex image_id de la table image_habitat.
                 $lastIndex = $pdo->lastInsertId();

                //Création d'un nouvel habitat.
                $req = "INSERT INTO habitats (nom,description,image_id)VALUES(:nom, :description,:image_id)";
                $stmt = $pdo->prepare($req);
                $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
                $stmt->bindParam(':description', $description, PDO::PARAM_STR);
                $stmt->bindParam(':image_id', $lastIndex, PDO::PARAM_INT);
                $stmt->execute();
        

            echo json_encode(["message" => "Habitat créé avec succès"]);
            }
    }
        
    } catch (Exception $e) {
        // Gestion des erreurs
        echo json_encode(["message" => "Problème lors de la modification de l'habitat: " . $e->getMessage()]);
    } finally {
        // Fermeture de la connexion PDO
        if ($pdo) {
            $pdo = null;
        }
    }
}

$data = json_decode(file_get_contents("php://input"), true);

// Vérification si les données nécessaires sont présentes
if (isset($data['habitat_id'], $data['nom'], $data['descriptionHabitat'], $data['image_data'])) {
    $habitat_id = $data['habitat_id'];
    $nom = $data['nom'];
    $description = $data['descriptionHabitat'];
    $image_data = $data['image_data'];
    
    // Stockage de l'image.
    if (strpos($image_data, 'data:image/') === 0) {
        list($type, $data) = explode(';', $image_data);
        list(, $data) = explode(',', $data);
        $image_data = base64_decode($data);
    }

    postHabitat($habitat_id, $nom, $description, $image_data);

} else {
    // Gestion du cas où des données requises sont manquantes
    echo json_encode(["message" => "Paramètres manquants"]);
}