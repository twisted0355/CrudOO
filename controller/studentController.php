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
    /*
     * on veut ajouter un stagiaire
     */

}else {

    /*
     * Page d'accueil
     */

// récupérer tous les stagiaires avec les sections dans lesquelles ils sont, affichez les stagiaires qui n'ont pas de section également
    $recupStudents = $thestudentM->selectionnerAllStudent();
    
    var_dump($recupStudents);

    // appel de la vue
    echo $twig->render("admin/student/accueilAdminStudent.html.twig");

}