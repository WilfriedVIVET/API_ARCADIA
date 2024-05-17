<?php
require_once ("./getConnect.php");

//Fonction sendJSON.
function sendJson($data){
    echo json_encode($data,JSON_UNESCAPED_UNICODE);
}

//Récupérations des services du zoo.
function getServices(){
    try{

        $pdo = getConnect();

        if($pdo){
            $req = "SELECT * from services";
            $stmt = $pdo->prepare($req);
            $stmt->execute();
            $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            sendJson($services);   
         }

    }catch (Exception $e){
        sendJson(['error'=> "erreur récupération services" . $e->getMessage()]);

    }finally{
        if($pdo){
            $pdo = null;
        }
    }
}

//Récupération des habitats.
function getHabitats(){
    try{

        $pdo = getConnect();

        if($pdo){
            $req = "SELECT * from habitats";
            $stmt = $pdo->prepare($req);
            $stmt->execute();
            $habitats = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            sendJson($habitats);   
         }

    }catch (Exception $e){
        sendJson(['error'=> "erreur récupération habitats" . $e->getMessage()]);

    }finally{
        if($pdo){
            $pdo = null;
        }
    }
}

//Récupération des animaux.
function getAnimaux(){
    try{

        $pdo = getConnect();

        if($pdo){
            $req = "SELECT * from race";
            $stmt = $pdo->prepare($req);
            $stmt->execute();
            $race = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            sendJson($race);   
         }

    }catch (Exception $e){
        sendJson(['error'=> "erreur récupération race" . $e->getMessage()]);

    }finally{
        if($pdo){
            $pdo = null;
        }
    }
}

//Récupération des infos compléte des habitats.
function getHabitatComplet(){
    try{

        $pdo = getConnect();

        if($pdo){
            $req = "SELECT 
            h.nom, 
            h.description, 
            h.image_path, 
            GROUP_CONCAT(DISTINCT a.prenom) AS liste_animaux
        FROM 
            habitats h
        LEFT JOIN 
            animal_habitat ah ON h.habitat_id = ah.habitat_id
        LEFT JOIN 
            animal a ON ah.animal_id = a.animal_id 
        GROUP BY 
            h.nom, h.description, h.image_path;";
            $stmt = $pdo->prepare($req);
            $stmt->execute();
            $habitatComplet = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            sendJson($habitatComplet);   
         }

    }catch (Exception $e){
        sendJson(['error'=> "erreur récupération habitatComplet" . $e->getMessage()]);

    }finally{
        if($pdo){
            $pdo = null;
        }
    }
}

//Récupération des infos compléte d'un animal.
function getInfoAnimal(){
    try{

        $pdo = getConnect();

        if($pdo){
            $req = "SELECT a.prenom, a.etat, a.image_path, r.label FROM animal a
            LEFT JOIN animal_race ar ON a.animal_id = ar.animal_id
            LEFT JOIN race r ON ar.race_id = r.race_id";
            $stmt = $pdo->prepare($req);
            $stmt->execute();
            $infoAnimal = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            sendJson($infoAnimal);   
         }

    }catch (Exception $e){
        sendJson(['error'=> "erreur récupération info animal" . $e->getMessage()]);

    }finally{
        if($pdo){
            $pdo = null;
        }
    }
}

//Récupération des horaires
function getHoraire(){
    try{

        $pdo = getConnect();

        if($pdo){
            $req = "SELECT * FROM `horaire`";
            $stmt = $pdo->prepare($req);
            $stmt->execute();
            $infoAnimal = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            sendJson($infoAnimal);   
         }

    }catch (Exception $e){
        sendJson(['error'=> "erreur récupération horaire" . $e->getMessage()]);

    }finally{
        if($pdo){
            $pdo = null;
        }
    }
}