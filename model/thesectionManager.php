<?php

/*
 * Manageur de l'instance de type "thesection", il peut servir à la création de différentes formes de CRUD, mais également aux actions et interactions entre instances (par exemple afficher les étudiants d'une section)
 */

class thesectionManager
{
    private $db; // connexion MyPDO (PDO étendue)

    public function __construct(MyPDO $connect ) // passage de la connexion
    {
        $this->db = $connect;
    }

    // actions (méthodes) généralement publiques car exécutées depuis un contrôleur, dont le nom est généralement un verbe, applicable aux instances de thesection

    /*
     *
     *
     * Méthodes pour la partie publique du site
     *
     *
     *
     */

    // création du menu qui nous renvoie un tableau
    public function creerMenu(): array {
        $sql = "SELECT idthesection,thetitle FROM thesection ORDER BY thetitle ASC ;";
        $recup = $this->db->query($sql);

        if($recup->rowCount()===0){
            return [];
        }
            return $recup->fetchAll(PDO::FETCH_ASSOC);


    }

    // création de l'affichage de toutes les sections sur l'accueil publique du site
    public function selectionnerSectionIndexPublic(): array {
        $sql = "SELECT * FROM thesection ORDER BY thetitle ASC ;";
        $recup = $this->db->query($sql);

        if($recup->rowCount()===0){
            return [];
        }
            return $recup->fetchAll(PDO::FETCH_ASSOC);

    }

    // récupération d'une section d'après son id (détail des sections)
    public function selectionnerSectionParId(int $idsection): array {
        // si la variable vaut 0 (id ne peux valoir 0 ou la conversion a donné 0)
        if(empty($idsection)){
            return [];
        }
        $sql = "SELECT * FROM thesection WHERE idthesection = ? ;";
        $recup = $this->db->prepare($sql);
        $recup->bindValue(1,$idsection,PDO::PARAM_INT);
        $recup->execute();

        if($recup->rowCount()===0){
            return [];
        }
        return $recup->fetch(PDO::FETCH_ASSOC);

    }


    /*
     *
     *
     * Méthodes pour l'admin du site
     *
     *
     */

    // création de l'affichage de toutes les sections avec ses utilisateurs sur l'accueil de l'administration du site

    public function selectionnerSectionIndexAdmin(): array {
        $sql = "SELECT a.idthesection, a.thetitle, LEFT(a.thedesc,120) AS thedesc,
	GROUP_CONCAT(c.thename SEPARATOR '|||') AS thename, 
    GROUP_CONCAT(c.thesurname SEPARATOR '|||') AS thesurname
	FROM thesection a
		LEFT JOIN thesection_has_thestudent b
			ON a.idthesection = b.thesection_idthesection
		LEFT JOIN thestudent c
			ON b.thestudent_idthestudent = c.idthestudent
    GROUP BY a.idthesection        
    ;";
        $recup = $this->db->query($sql);

        if($recup->rowCount()===0){
            return [];
        }
        return $recup->fetchAll(PDO::FETCH_ASSOC);

    }

    // Requête pour créer une section à partir d'une instance de type thesection

    public function createSectionAdmin(thesection $datas) {


        // vérification que les champs soient valides (pas vides)

        if(empty($datas->getThetitle())||empty($datas->getThedesc())){
            return false;
        }

        $sql = "INSERT INTO thesection (thetitle,thedesc) VALUES (?,?);";

        $insert = $this->db->prepare($sql);

        $insert->bindValue(1,$datas->getThetitle(),PDO::PARAM_STR);
        $insert->bindValue(2,$datas->getThedesc(),PDO::PARAM_STR);


        // gestion des erreurs avec try catch
        try {
            $insert->execute();
            return true;

        }catch(PDOException $e){
            echo $e->getCode();
            return false;

        }

    }

    // Requête pour mettre à jour une section en vérifant si la variable get idsection correspond bien à la variable post idsection (usurpation d'identité)

    public function updateSection(thesection $datas, int $get){

        // vérification que les champs soient valides (pas vides)
        if(empty($datas->getThetitle())||empty($datas->getThedesc())||empty($datas->getIdthesection())){
            return false;
        }

        // vérification contre le contournement des droits
        if($datas->getIdthesection()!=$get){
            return false;
        }

        $sql = "UPDATE thesection SET thetitle=?, thedesc=? WHERE idthesection=?";

        $update = $this->db->prepare($sql);

        $update->bindValue(1,$datas->getThetitle(),PDO::PARAM_STR);
        $update->bindValue(2,$datas->getThedesc(),PDO::PARAM_STR);
        $update->bindValue(3,$datas->getIdthesection(),PDO::PARAM_INT);

        try{
            $update->execute();
            return true;
        }catch (PDOException $e){
            echo $e->getCode();
            return false;
        }

    }

    // pour supprimer une section

    public function deleteSection(int $idsection){

        $sql = "DELETE FROM thesection WHERE idthesection=?";

        $delete = $this->db->prepare($sql);
        $delete->bindValue(1,$idsection, PDO::PARAM_INT);

        try{
            $delete->execute();
            return true;
        }catch(PDOException $e){
            echo $e->getCode();
            return false;
        }

    }


}
