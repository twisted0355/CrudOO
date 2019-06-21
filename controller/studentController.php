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

    
}elseif(isset($_GET['update'])&& ctype_digit($_GET['update'])){
    $idstagiaire= (int) $_GET['update'];
    /*
     * on veut modifier un stagiaire
     */

}elseif(isset($_GET['delete'])&& ctype_digit($_GET['delete'])){
    /*
     * on veut supprimer un stagiaire
     */
    $idstagiaire= (int) $_GET['delete'];
    
}else {

    /*
     * Page d'accueil
     */

// récupérer tous les stagiaires avec les sections dans lesquelles ils sont, affichez les stagiaires qui n'ont pas de section également
    $recupStudents = $thestudentM->selectionnerAllStudent();
    

    // appel de la vue avec le passage des étudiants
    echo $twig->render("admin/student/accueilAdminStudent.html.twig",["student"=>$recupStudents]);

}