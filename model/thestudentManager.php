<?php

/*
 * Manager des objets de type thestudent
 */

class thestudentManager
{
    private $db;

    public function __construct(MyPDO $connect)
    {
        $this->db = $connect;
    }

    // on sélectionne les étudiants de la section actuelle grâce à son id
    public function selectionnerStudentBySectionId(int $idsection): array {

        if($idsection===0) return [];

        $sql="SELECT thestudent.*
	FROM thestudent
    INNER JOIN thesection_has_thestudent
		ON thesection_has_thestudent.thestudent_idthestudent= thestudent.idthestudent
    WHERE  thesection_has_thestudent.thesection_idthesection=?;";

        $recup = $this->db->prepare($sql);
        $recup->bindValue(1,$idsection,PDO::PARAM_INT);
        $recup->execute();

        if($recup->rowCount() ===0) return [];

        return $recup->fetchAll(PDO::FETCH_ASSOC);

    }

    // récupérer tous les stagiaires avec les sections dans lesquelles ils sont, affichez les stagiaires qui n'ont pas de section également
    public function selectionnerAllStudent():array {

        $sql="SELECT thestudent.*, GROUP_CONCAT(thesection.thetitle SEPARATOR ' / ') AS thetitle
              FROM thestudent
                    LEFT JOIN thesection_has_thestudent
                        ON thesection_has_thestudent.thestudent_idthestudent= thestudent.idthestudent
                    LEFT JOIN thesection
                        ON thesection_has_thestudent.thesection_idthesection= thesection.idthesection
                GROUP BY thestudent.idthestudent;
        ";
        
        $recupStudents = $this->db->query($sql); 
        
        if($recupStudents->rowCount()==0) return [];
        
        return $recupStudents->fetchAll(PDO::FETCH_ASSOC);


    }
    
    // on va insérer un nouvel étudiant dans la table thestudent grâce à une instance de type thestudent, et on va insérer dans la table de jointure thesection_has_thestudent le lien entre les 2 tables SI il y a un lien
    
    public function insertStudentWithSection(thestudent $datas, array $linkWithSection = []): bool{
        
        // préparation de la requête d'ajout de thestudent
        $sql = "INSERT INTO thestudent (thename,thesurname) VALUES (?,?);";
        $reqStudent = $this->db->prepare($sql);
        
        $reqStudent->bindValue(1, $datas->getThename(),PDO::PARAM_STR);
        $reqStudent->bindValue(2, $datas->getThesurname(),PDO::PARAM_STR);
        
        // on essaie l'insertion de l'étudiant
        try{
            $reqStudent->execute();
        } catch (PDOException $ex) {
            // sinon affichage d'une erreur
            echo $ex->getMessage();
            // et arrêt de la méthode + retour false
            return false;
        }
        
        // si on est ici, l'insertion a fonctionné
        
        // si on a pas de section à joindre, on arrête ici
        if(empty($linkWithSection)) return true;
        
        // on récupère l'id de l'utilisateur qu'on vient d'insérer
        $idstudent = $this->db->lastInsertId(); 
        
        // préparation de la requête pour thesection_has_thestudent
        
        $sql = "INSERT INTO thesection_has_thestudent (thestudent_idthestudent,thesection_idthesection) VALUES ";
        
        // boucle sur le tableau $linkWithSection
        foreach($linkWithSection as $value){
            $value = (int) $value;
            if(!empty($value)) $sql .= "($idstudent,$value)," ;
        }
        
        // on retire la virgule de fin
        $sql = substr($sql, 0,-1);
        
        // exécution de l'insertion
        try{
            $this->db->exec($sql);
            return true;
            
        } catch (PDOException $ex) {
            echo $ex->getMessage();
            return false;
        }
        
        
    }


}