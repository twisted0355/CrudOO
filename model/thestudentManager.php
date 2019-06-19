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
}