<?php

/*
 * Manager de theuser
 */

class theuserManager
{
    private $db;

    public function __construct(MyPDO $connect)
    {
        $this->db = $connect;
    }

    // on essaie de se connecter
    public function connecterUser(theuser $user): bool
    {

        $sql = "SELECT idtheuser, thelogin FROM theuser
		WHERE thelogin = :login AND thepwd = :pwd ;";

        $connectUser = $this->db->prepare($sql);
        $connectUser->bindValue("login", $user->getThelogin(), PDO::PARAM_STR);
        $connectUser->bindValue("pwd", $user->getThepwd(), PDO::PARAM_STR);
        $connectUser->execute();

        // si on a récupéré l'utilisateur (connexion réussie)
        if ($connectUser->rowCount()) {
            // on crée la session avec les valeurs venant de la table theuser
            $this->creerSession($connectUser->fetch(PDO::FETCH_ASSOC));

            return true;

        } else {
            return false;
        }
    }

    // on a réussi la connexion
    private function creerSession(array $valeurs)
    {
        $_SESSION = $valeurs;
        $_SESSION["myKey"] = session_id();
    }

    // déconnexion
    public function deconnecterSession()
    {

        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();

        header("Location: ./");

    }

}