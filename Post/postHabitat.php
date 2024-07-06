<?php

require_once("../getConnect.php");

// Fonction qui ajoute un habitat.
function postHabitat( $nom, $descriptionHabitat, $image_path) {
    try {
        $pdo = getConnect();
        if ($pdo) {

            //Vérification si l'habitat existe.
            $reqcheckHabitat = "SELECT COUNT(*) FROM habitats WHERE nom = :nom ";
            $stmtcheckHabitat = $pdo->prepare($reqcheckHabitat);
            $stmtcheckHabitat->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmtcheckHabitat->execute();
            $habitatExist = (bool)$stmtcheckHabitat->fetchColumn();

            if (!$habitatExist) {
                //Création d'un nouvel habitat.
                $req = "INSERT INTO habitats (nom,descriptionHabitat,image_path)VALUES(:nom, :descriptionHabitat,:image_path)";
                $stmt = $pdo->prepare($req);
                $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
                $stmt->bindParam(':descriptionHabitat', $descriptionHabitat, PDO::PARAM_STR);
                $stmt->bindParam(':image_path', $image_path, PDO::PARAM_STR);
                $stmt->execute();
                echo json_encode(["message" => "Habitat créé avec succès"]);
               
            } else {
                echo json_encode(["message" => "Habitat déjà existant !"]);
            }
    }
        
    } catch (Exception $e) {
        // Gestion des erreurs
        echo json_encode(["message" => "Problème lors de l'ajout du nouvel habitat: " . $e->getMessage()]);
    } finally {
        // Fermeture de la connexion PDO
        if ($pdo) {
            $pdo = null;
        }
    }
}

$data = json_decode(file_get_contents("php://input"), true);

// Vérification si les données nécessaires sont présentes
if (isset( $data['nom'], $data['descriptionHabitat'], $data['image_data'])) {
    $nom = $data['nom'];
    $descriptionHabitat = $data['descriptionHabitat'];
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
            $relative_path = 'uploads/' . $image_name;
            $absolute_path = __DIR__ . '/../' . $relative_path;

            // Enregistrer l'image dans le dossier
            if (file_put_contents($absolute_path, $image_data)) { 
                postHabitat( $nom, $descriptionHabitat, $relative_path);
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