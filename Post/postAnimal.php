<?php

require_once("../getConnect.php");

// Fonction qui ajoute un animal.
function postAnimal($prenom, $race, $habitat,$image_path) {

    try {
        $pdo = getConnect();
        if ($pdo) {
            
                //Vérification que le nom de l'animal n'existe pas déjà.
                $reqAnimal="SELECT COUNT(*) from animal where prenom = :prenom";
                $stmtCheck = $pdo->prepare($reqAnimal);
                $stmtCheck->bindParam(':prenom', $prenom, PDO::PARAM_STR);
                $stmtCheck->execute();
                $result = $stmtCheck->fetch(PDO::FETCH_ASSOC);

                if($result){
                     echo json_encode(['message'=> "Animal déjà existant!"]);   
                }else{       
                    // Insertion de l'animal.
                    $req = "INSERT INTO animal (prenom, race_id, habitat_id, image_path)
                    VALUES ( :prenom, (SELECT race_id FROM race WHERE label = :race), 
                       (SELECT habitat_id FROM habitats WHERE nom = :habitat),
                       :image_path)"; 
                    $stmt = $pdo->prepare($req);
                    $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);  
                    $stmt->bindParam(':race', $race, PDO::PARAM_STR);  
                    $stmt->bindParam(':habitat', $habitat, PDO::PARAM_STR);  
                    $stmt->bindParam(':image_path', $image_path, PDO::PARAM_STR);  
                    $stmt->execute();

                     echo json_encode(["message" => "Animal ajouté avec succès"]);
            }
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

        // Extraction de l'extension à partir du type MIME
        $mime_type = explode('/', $type)[1];
        $allowed_extensions = ['png', 'jpeg', 'jpg'];
        
        if (in_array($mime_type, $allowed_extensions)) {
            // Définition du nom de l'image
            $image_name = uniqid() . '.' . $mime_type;
            $relative_path = 'uploads/' .$image_name;
            $absolute_path = __DIR__ . '/../' . $relative_path;

            // Enregistrer l'image dans le dossier
            if (file_put_contents($absolute_path, $image_data)) {   
                postAnimal($prenom, $race, $habitat, $relative_path);
            } else {
                echo json_encode(["message" => "Échec de l'enregistrement de l'image"]);
            }
        } else {
            echo json_encode(["message" => "Format d'image non autorisé"]);
        }
    } else {
        echo json_encode(["message" => "Format d'image incorrect"]);
    }

} else {
    // Gestion du cas où des données requises sont manquantes
    echo json_encode(["message" => "Paramètres manquants"]);
}