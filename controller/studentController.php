<?php
/*
 *
 * Contrôleur gérant les étudiants
 *
 * La variable get adminstudent doit être présente pour accèder à ce contrôleur (et on doit être connecté évidemment!)
 *
 *
 */
    if(isset($_GET['addstudent'])){

        $student = $thestudentM->afficherStudent();
            // Ajouter un stagiaire
            $count = $_GET['count'];
            echo $twig->render("admin/student/ajoutStudent.html.twig",array('student'=>$student, 'count'=> $count));
    }
    elseif(isset($_GET['update']) && ctype_digit($_GET['update'])){
        //modifier stagiaire
    }
    else{

        /*
         * Page d'accueil
         */

    // récupérer tous les stagiaires avec les sections dans lesquelles ils sont, affichez les stagiaires qui n'ont pas de section également

        $student = $thestudentM->afficherStudent();
        echo $twig->render("admin/student/accueilAdminStudent.html.twig",array('student'=>$student));

    }