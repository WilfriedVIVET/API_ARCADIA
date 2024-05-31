<?php

require_once("./getConnect.php");

// Fonction qui vérifie la présence de l'utilisateur en base de données et récupère son rôle.
function getRole($username, $password) {
    try {
        $pdo = getConnect();
        if ($pdo) {
            // Vérification si l'utilisateur existe dans la BDD et récupération du hash du mot de passe
            $reqCheckUser = "SELECT password FROM utilisateur WHERE username = :username";
            $stmtCheckUser = $pdo->prepare($reqCheckUser);
            $stmtCheckUser->bindParam(':username', $username, PDO::PARAM_STR);
            $stmtCheckUser->execute();
            $hash = $stmtCheckUser->fetchColumn();

            if (!$hash) {
                echo json_encode(['message' => "Ce compte n'existe pas"]);
            } else {
                // Vérification du mot de passe
                if (password_verify($password, $hash)) {
                    // Récupération du rôle
                    $req = "SELECT label FROM role r
                            INNER JOIN utilisateur u ON r.role_id = u.role_id
                            WHERE u.username = :username";
                    $stmt = $pdo->prepare($req);
                    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
                    $stmt->execute();
                    $role = $stmt->fetchColumn();

                    if ($role) {
                        echo json_encode(['role' => $role]);
                    } else {
                        echo json_encode(['message' => "Rôle non trouvé"]);
                    }
                } else {
                    echo json_encode(['message' => "Mot de passe incorrect"]);
                }
            }
        }
    } catch (Exception $e) {
        // Gestion des erreurs
        echo json_encode(["message" => "Problème lors de la récupération du rôle : " . $e->getMessage()]);
    } finally {
        // Fermeture de la connexion PDO
        if ($pdo) {
            $pdo = null;
        }
    }
}

$data = json_decode(file_get_contents("php://input"), true);

// Vérification si les données nécessaires sont présentes
if (isset($data['email'], $data['password'])) {
    $username = htmlspecialchars( $data['email'],ENT_QUOTES,'UTF-8');
    $password = htmlspecialchars( $data['password'], ENT_QUOTES,'UTF-8');
    getRole($username, $password);
} else {
    // Gestion du cas où des données requises sont manquantes
    echo json_encode(["message" => "Paramètres manquants"]);
}
