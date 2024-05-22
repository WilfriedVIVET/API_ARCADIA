<?php

require_once("./getConnect.php");

// Fonction qui crée un compte utilisateur.
function postAccount($role_id, $email, $prenom, $nom, $password) {

    try {
        $pdo = getConnect();
        if ($pdo) {
            // Vérification si l'utilisateur existe déjà
            $reqCheckUser = "SELECT COUNT(*) FROM utilisateur WHERE username = :email ";
            $stmtCheckUser = $pdo->prepare($reqCheckUser);
            $stmtCheckUser->bindParam(':email', $email, PDO::PARAM_STR);
            $stmtCheckUser->execute();
            $userExist = (bool)$stmtCheckUser->fetchColumn();

            if ($userExist) {
                echo json_encode(['message' => "Ce compte existe déjà"]);
            } else {
                // Insertion des données utilisateur
                $req = "INSERT INTO utilisateur (username, password, nom, prenom,role_id) VALUES (:email, :password, :nom, :prenom,:role_id)";
                $stmt = $pdo->prepare($req);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                // Hashage du mot de passe
                $hashedPassword = password_hash($password , PASSWORD_BCRYPT);
                $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
                $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
                $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
                $stmt->bindParam(':role_id', $role_id, PDO::PARAM_INT);
                $stmt->execute();

                echo json_encode(["message" => "Compte créé avec succès"]);
            }
        }
    } catch (Exception $e) {
        // Gestion des erreurs
        echo json_encode(["message" => "Problème lors de la création du compte" . $e->getMessage()]);
    } finally {
        // Fermeture de la connexion PDO
        if ($pdo) {
            $pdo = null;
        }
    }
}

$data = json_decode(file_get_contents("php://input"), true);

// Vérification si les données nécessaires sont présentes
if (isset($data['role_id'], $data['email'], $data['prenom'], $data['nom'], $data['password'])) {

    $role_id = $data['role_id'];
    $email = $data['email'];
    $prenom = $data['prenom'];
    $nom = $data['nom'];
    $password = $data['password'];

    postAccount($role_id, $email, $prenom, $nom, $password);

} else {
    // Gestion du cas où des données requises sont manquantes
    echo json_encode(["message" => "Paramètres manquants"]);
}