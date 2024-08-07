<?php
require_once("./getConnect.php");

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, UPDATE");

// Fonction pour envoyer des données au format JSON.
function sendJson($data){
    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// Récupération de tous les utilisateurs.
function getUtilisateur(){
    try{
        $pdo = getConnect();
        if($pdo){
            $req = "SELECT u.username, u.nom, u.prenom, r.label FROM utilisateur u
                    INNER JOIN role r ON u.role_id = r.role_id";
            $stmt = $pdo->prepare($req);
            $stmt->execute();
            $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            sendJson($utilisateurs);   
        }
    }catch (Exception $e){
        sendJson(['error'=> "Erreur récupération utilisateur: " . $e->getMessage()]);
    }finally{
        if($pdo){
            $pdo = null;
        }
    }
}

// Récupération des services du zoo.
function getServices(){
    try{
        $pdo = getConnect();
        if($pdo){
            $req = "SELECT * FROM services";
            $stmt = $pdo->prepare($req);
            $stmt->execute();
            $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            sendJson($services);   
        }
    }catch (Exception $e){
        sendJson(['error'=> "Erreur récupération services: " . $e->getMessage()]);
    }finally{
        if($pdo){
            $pdo = null;
        }
    }
}

// Récupération des habitats.
function getHabitats() {
    try {
        $pdo = getConnect();
        if ($pdo) {
            $req = "SELECT * FROM habitats ";
            $stmt = $pdo->prepare($req);
            $stmt->execute();
            $habitats = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            sendJson($habitats);   
        }
    } catch (Exception $e) {
        sendJson(['error' => "Erreur récupération habitats: " . $e->getMessage()]);
    } finally {
        if ($pdo) {
            $pdo = null;
        }
    }
}

function getHabitatComplet() {
    try {
        $pdo = getConnect();
        if ($pdo) {
            $req = " SELECT 
            h.habitat_id,
            h.nom, 
            h.descriptionHabitat, 
            h.image_path,
            GROUP_CONCAT(DISTINCT animal.prenom ORDER BY animal.prenom SEPARATOR ', ') AS liste_animaux
        FROM 
            habitats h
        LEFT JOIN 
            animal ON h.habitat_id = animal.habitat_id 
        GROUP BY 
            h.habitat_id, h.nom, h.descriptionHabitat";                  
            $stmt = $pdo->prepare($req);
            $stmt->execute();
            $habitatComplet = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

       
            sendJson($habitatComplet);   
        }
    } catch (Exception $e) {
        sendJson(['error' => "Erreur récupération habitatComplet: " . $e->getMessage()]);
    } finally {
        if ($pdo) {
            $pdo = null;
        }
    }
}

// Fonction pour récupérer les animaux.
function getAnimaux() {
    try {
        $pdo = getConnect();
        if ($pdo) {
            $req = "SELECT a.animal_id,a.prenom,a.etat,a.image_path, h.nom,r.label FROM animal a 
            INNER JOIN race r ON a.race_id= r.race_id
            INNER JOIN habitats h ON  a.habitat_id = h.habitat_id";
            $stmt = $pdo->prepare($req);
            $stmt->execute();
            $animaux = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
 
            sendJson($animaux);   
        }
    } catch (Exception $e) {
        sendJson(['error' => "Erreur récupération animaux: " . $e->getMessage()]);
    } finally {
        if (isset($pdo)) {
            $pdo = null;
        }
    }
}

// Récupération des races.
function getRaces(){
    try{
        $pdo = getConnect();
        if($pdo){
            $req = "SELECT race_id, label FROM race";
            $stmt = $pdo->prepare($req);
            $stmt->execute();
            $races = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            sendJson($races);   
        }
    }catch (Exception $e){
        sendJson(['error'=> "Erreur récupération race: " . $e->getMessage()]);
    }finally{
        if($pdo){
            $pdo = null;
        }
    }
}

// Récupération des informations complètes d'un animal.
function getInfoAnimal(){
    try{
        $pdo = getConnect();
        if($pdo){
            $req = "SELECT 
            a.animal_id, 
            a.prenom, 
            a.etat, 
            r.label, 
            ra.detail_etat, 
            ra.date_rapport, 
            ra.nrtconseille,
            ra.qtconseille
        FROM 
            animal a
        LEFT JOIN 
            race r ON a.race_id = r.race_id
        LEFT JOIN 
            (
                SELECT 
                    animal_id, 
                    MAX(rapport_id) AS max_rapport_id
                FROM 
                    rapport ra
                GROUP BY 
                    animal_id
            ) max_rapport ON a.animal_id = max_rapport.animal_id
        LEFT JOIN 
            rapport ra ON a.animal_id = ra.animal_id AND ra.rapport_id = max_rapport.max_rapport_id
        ORDER BY 
            a.prenom ASC ";
            $stmt = $pdo->prepare($req);
            $stmt->execute();
            $infoAnimal = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            sendJson($infoAnimal);   
        }
    }catch (Exception $e){
        sendJson(['error'=> "Erreur récupération info animal: " . $e->getMessage()]);
    }finally{
        if($pdo){
            $pdo = null;
        }
    }
}

// Récupération des horaires.
function getHoraire(){
    try{
        $pdo = getConnect();
        if($pdo){
            $req = "SELECT * FROM horaire";
            $stmt = $pdo->prepare($req);
            $stmt->execute();
            $horaires = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            sendJson($horaires);   
        }
    }catch (Exception $e){
        sendJson(['error'=> "Erreur récupération horaire: " . $e->getMessage()]);
    }finally{
        if($pdo){
            $pdo = null;
        }
    }
}

// Récupération des avis.
function getAvis(){
    try{
        $pdo = getConnect();
        if($pdo){
            $req = "SELECT * FROM avis";
            $stmt = $pdo->prepare($req);
            $stmt->execute();
            $races = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            sendJson($races);   
        }
    }catch (Exception $e){
        sendJson(['error'=> "Erreur récupération avis: " . $e->getMessage()]);
    }finally{
        if($pdo){
            $pdo = null;
        }
    }
}

// Récupération des avis validés du zoo.
function getAvisIsValid(){
    try{
        $pdo = getConnect();
        if($pdo){
            $req = "SELECT * FROM avis WHERE isValid = 1";
            $stmt = $pdo->prepare($req);
            $stmt->execute();
            $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            sendJson($services);   
        }
    }catch (Exception $e){
        sendJson(['error'=> "Erreur récupération avis valide: " . $e->getMessage()]);
    }finally{
        if($pdo){
            $pdo = null;
        }
    }
}

// Récupération des rapport.
function getRapport(){
    try{
        $pdo = getConnect();
        if($pdo){
            $req = "SELECT r.rapport_id,ra.label, a.etat, r.detail_etat, r.date_rapport, r.nrtconseille, r.qtconseille, a.prenom  FROM rapport r
            INNER JOIN animal a on r.animal_id = a.animal_id
            INNER JOIN race ra on a.race_id = ra.race_id
            WHERE r.date_rapport IS NOT NULL ";
            $stmt = $pdo->prepare($req);
            $stmt->execute();
            $races = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            sendJson($races);   
        }
    }catch (Exception $e){
        sendJson(['error'=> "Erreur récupération rapport: " . $e->getMessage()]);
    }finally{
        if($pdo){
            $pdo = null;
        }
    }
}