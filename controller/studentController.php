<?php

/*
 *
 * Contrôleur gérant les étudiants
 *
 * La variable get adminstudent doit être présente pour accèder à ce contrôleur (et on doit être connecté évidemment!)
 *
 *
 */


if (isset($_GET['addstudent'])) {

    /*
     * on veut ajouter un stagiaire
     */

    // si le formulaire n'est pas envoyé
    if (empty($_POST)) {

        // on utilise la méthode qui prend titre et id de toutes les sections
        $recupSections = $thesectionM->creerMenu();

        // appel de la vue avec le passage des sections
        echo $twig->render("admin/student/ajoutAdminStudent.html.twig", ["sections" => $recupSections]);
    } else {

        // le formulaire est envoyé
        // on va instancier la classe thestudent pour hydrater thename et thesurname
        $student = new thestudent($_POST);

        // si on a au moins une section
        if (isset($_POST['idthesection'])) {

            // insertion de l'étudiant et des sections 
            $insert = $thestudentM->insertStudentWithSectionTransaction($student, $_POST['idthesection']);

            // on a pas de sections    
        } else {
            // insertion de l'étudiant
            $insert = $thestudentM->insertStudentWithSectionTransaction($student);
        }

        if ($insert) {
            header("Location: ./?adminstudent");
        } else {
            //header("Location: ./?adminstudent&addstudent");
        }
    }
} elseif (isset($_GET['update']) && ctype_digit($_GET['update'])) {
    $idstagiaire = (int) $_GET['update'];
    /*
     * on veut modifier un stagiaire
     */
} elseif (isset($_GET['delete']) && ctype_digit($_GET['delete'])) {
    /*
     * on veut supprimer un stagiaire
     */
    $idstagiaire = (int) $_GET['delete'];

    // si on a pas validé la suppression
    if (!isset($_GET['ok'])) {

        // on récupère l'étudiant grâce à son ID
        $recupStudent = $thestudentM->selectionnerStudentById($idstagiaire);
        
        // appel de la vue avec le passage d'un étudiant
        echo $twig->render("admin/student/deleteAdminStudent.html.twig",array("user"=>$recupStudent));
        
        
    // on a validé la suppression (existance de la variable get "ok")    
    }else{
        
        
        
    }
} else {

    /*
     * Page d'accueil
     */

// récupérer tous les stagiaires avec les sections dans lesquelles ils sont, affichez les stagiaires qui n'ont pas de section également
    $recupStudents = $thestudentM->selectionnerAllStudent();


    // appel de la vue avec le passage des étudiants
    echo $twig->render("admin/student/accueilAdminStudent.html.twig", ["student" => $recupStudents]);
}