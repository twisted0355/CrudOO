<?php

/*
 * Manager des objets de type thestudent
 */

class thestudentManager {

    private $db;

    public function __construct(MyPDO $connect) {
        $this->db = $connect;
    }

    // on sélectionne les étudiants de la section actuelle grâce à son id
    public function selectionnerStudentBySectionId(int $idsection): array {

        if ($idsection === 0)
            return [];

        $sql = "SELECT thestudent.*
	FROM thestudent
    INNER JOIN thesection_has_thestudent
		ON thesection_has_thestudent.thestudent_idthestudent= thestudent.idthestudent
    WHERE  thesection_has_thestudent.thesection_idthesection=?;";

        $recup = $this->db->prepare($sql);
        $recup->bindValue(1, $idsection, PDO::PARAM_INT);
        $recup->execute();

        if ($recup->rowCount() === 0)
            return [];

        return $recup->fetchAll(PDO::FETCH_ASSOC);
    }

    // récupérer tous les stagiaires avec les sections dans lesquelles ils sont, affichez les stagiaires qui n'ont pas de section également
    public function selectionnerAllStudent(): array {

        $sql = "SELECT thestudent.*, GROUP_CONCAT(thesection.thetitle SEPARATOR ' / ') AS thetitle
              FROM thestudent
                    LEFT JOIN thesection_has_thestudent
                        ON thesection_has_thestudent.thestudent_idthestudent= thestudent.idthestudent
                    LEFT JOIN thesection
                        ON thesection_has_thestudent.thesection_idthesection= thesection.idthesection
                GROUP BY thestudent.idthestudent;
        ";

        $recupStudents = $this->db->query($sql);

        if ($recupStudents->rowCount() == 0)
            return [];

        return $recupStudents->fetchAll(PDO::FETCH_ASSOC);
    }

    // VERSION SANS TRANSACTION
    //  on va insérer un nouvel étudiant dans la table thestudent grâce à une instance de type thestudent, et on va insérer dans la table de jointure thesection_has_thestudent le lien entre les 2 tables SI il y a un lien 

    public function insertStudentWithSection(thestudent $datas, array $linkWithSection = []): bool {

        // préparation de la requête d'ajout de thestudent
        $sql = "INSERT INTO thestudent (thename,thesurname) VALUES (?,?);";
        $reqStudent = $this->db->prepare($sql);

        $reqStudent->bindValue(1, $datas->getThename(), PDO::PARAM_STR);
        $reqStudent->bindValue(2, $datas->getThesurname(), PDO::PARAM_STR);

        // on essaie l'insertion de l'étudiant
        try {
            $reqStudent->execute();
        } catch (PDOException $ex) {
            // sinon affichage d'une erreur
            echo $ex->getMessage();
            // et arrêt de la méthode + retour false
            return false;
        }

        // si on est ici, l'insertion a fonctionné
        // si on a pas de section à joindre, on arrête ici
        if (empty($linkWithSection))
            return true;

        // on récupère l'id de l'utilisateur qu'on vient d'insérer
        $idstudent = $this->db->lastInsertId();

        // préparation de la requête pour thesection_has_thestudent

        $sql = "INSERT INTO thesection_has_thestudent (thestudent_idthestudent,thesection_idthesection) VALUES ";

        // boucle sur le tableau $linkWithSection
        foreach ($linkWithSection as $value) {
            $value = (int) $value;
            if (!empty($value))
                $sql .= "($idstudent,$value),";
        }

        // on retire la virgule de fin
        $sql = substr($sql, 0, -1);

        // exécution de l'insertion
        try {
            $this->db->exec($sql);
            return true;
        } catch (PDOException $ex) {
            echo $ex->getMessage();  
            return false;
        }
    }

    // transformez insertStudentWithSection avec des requêtes sql en mode transaction, il ne peut y avoir que un return true et UN return false (voir le modele)
    // VERSION AVEC TRANSACTION

    public function insertStudentWithSectionTransaction(thestudent $datas, array $linkWithSection = []): bool {


        // on utilise un seul try / catch pour la méthode
        try {

            // on va lancer une transaction, ce qui annule l'autocommit (envoi ligne par ligne à sql lors d'un exec, query, execute) qui est la valeur par défaut de MySQL en InnoDB (le moteur sql doit accepter les transacions, donc posséder les propriétés ACID)
            $this->db->beginTransaction();

            // préparation de la requête d'ajout de thestudent
            $sql = "INSERT INTO thestudent (thename,thesurname) VALUES (?,?);";
            $reqStudent = $this->db->prepare($sql);

            $reqStudent->bindValue(1, $datas->getThename(), PDO::PARAM_STR);
            $reqStudent->bindValue(2, $datas->getThesurname(), PDO::PARAM_STR);

            // on insert de l'étudiant
            $reqStudent->execute(); // il y aurait un commit ici sans le mode transaction, pour le moment, ça reste en mémoir en attendant le commit
            
             
            // si on a au moins une section à joindre
            if (!empty($linkWithSection)) {

                // on récupère l'id de l'utilisateur qu'on vient d'insérer
                $idstudent = $this->db->lastInsertId();

                // préparation de la requête pour thesection_has_thestudent

                $sql = "INSERT INTO thesection_has_thestudent (thestudent_idthestudent,thesection_idthesection) VALUES ";

                // boucle sur le tableau $linkWithSection
                foreach ($linkWithSection as $value) {
                    $value = (int) $value;
                    if (!empty($value))
                        $sql .= "($idstudent,$value),";
                }

                // on retire la virgule de fin
                $sql = substr($sql, 0, -1);

                // on exécute la ou les insertion(s)- lors du commit!
                $this->db->exec($sql);
            }

            // on envoie la ou les requête(s) au serveur sql, si il y a une erreur dans le commit (une des requêtes exécutée renvoie une erreur) on va directement au catch (la ligne return true n'est donc pas lue)
            $this->db->commit();
            // si pas de faute lors du commit, la ligne suivante est lue (renvoie true)
            return true;
        
        // erreur lors du commit    
        } catch (PDOException $ex) {
            
            // on efface ce qui a été inséré lors du commit (succès avant l'erreur) - Mysql fait un rollback automatique lorsque un commit est refusé
            $this->db->rollBack();
            // affichage d'une erreur
            echo $ex->getMessage();
            return false;
        }
    }
    
    
    // on sélectionne l'étidiant avec ses éventuelles sections actuelle grâce à son id
    public function selectionnerStudentById(int $idstudent): array {

        if ($idstudent === 0) return [];

        $sql = "SELECT thestudent.*, GROUP_CONCAT(thesection.thetitle SEPARATOR ' / ') AS thetitle
	FROM thestudent
        LEFT JOIN thesection_has_thestudent
            ON thesection_has_thestudent.thestudent_idthestudent= thestudent.idthestudent
        LEFT JOIN thesection
            ON thesection_has_thestudent.thesection_idthesection= thesection.idthesection        
    WHERE  thestudent.idthestudent=?
    GROUP BY thestudent.idthestudent;";

        $recup = $this->db->prepare($sql);
        $recup->bindValue(1, $idstudent, PDO::PARAM_INT);
        $recup->execute();

        if ($recup->rowCount() === 0) return [];

        return $recup->fetch(PDO::FETCH_ASSOC);
    }

}
