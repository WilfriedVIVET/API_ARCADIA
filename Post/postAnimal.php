<?php

require_once("../getConnect.php");

// Fonction qui ajoute un animal.
function postAnimal($prenom, $race, $habitat,$image_data) {

    try {
        $pdo = getConnect();
        if ($pdo) {
            
                //Insertion de l'image animal
                $req="INSERT INTO image_animal(image_data)VALUES(:image_data)";
                $stmt=$pdo->prepare($req);
                $stmt->bindParam(':image_data', $image_data, PDO::PARAM_LOB);  
                $stmt->execute();

                $lastIndexImage = $pdo->lastInsertId();

                // Insertion de l'animal.
                $req = "INSERT INTO animal (prenom, race_id, habitat_id, image_id)
                VALUES ( :prenom, (SELECT race_id FROM race WHERE label = :race), 
                       (SELECT habitat_id FROM habitats WHERE nom = :habitat), 
                       (SELECT image_id FROM image_animal WHERE image_id = $lastIndexImage))";
                $stmt = $pdo->prepare($req);
                $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);  
                $stmt->bindParam(':race', $race, PDO::PARAM_STR);  
                $stmt->bindParam(':habitat', $habitat, PDO::PARAM_STR);  
                $stmt->execute();

            
                echo json_encode(["message" => "Animal ajouté avec succès"]);
            }
   } catch (Exception $e) {
        // Gestion des erreurs
        echo json_encode(["message" => "Problème lors de l'ajout de l'animal" . $e->getMessage()]);
    } finally {
        // Fermeture de la connexion PDO
        if ($pdo) {
            $pdo = null;
        }
    }
}

$data = json_decode(file_get_contents("php://input"), true);

// Vérification si les données nécessaires sont présentes

if (isset($data['prenom'], $data['race'], $data['habitat'],$data['image_data'])) {

    $prenom = $data['prenom'];
    $race = $data['race'];
    $habitat = $data['habitat'];
    $image_data = $data['image_data'];

    // Stockage de l'image.
    if (strpos($image_data, 'data:image/') === 0) {
        list($type, $data) = explode(';', $image_data);
        list(, $data) = explode(',', $data);
        $image_data = base64_decode($data);
    }
    
    postAnimal($prenom,$race,$habitat,$image_data);

} else {
    // Gestion du cas où des données requises sont manquantes
    echo json_encode(["message" => "Paramètres manquants"]);
}