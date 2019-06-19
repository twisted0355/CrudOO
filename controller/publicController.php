<?php
/*
 * Public
 */


// on crée notre menu pour toutes les pages publiques
$menu = $thesectionM->creerMenu();

/*
 * Page d'une section
 */

if(isset($_GET['idthesection'])&&ctype_digit($_GET['idthesection'])){

    $idsection = (int) $_GET['idthesection'];

    // on sélectionne les détails de la section
    $detailsection = $thesectionM->selectionnerSectionParId($idsection);

    // on sélectionne les étudiants de la section actuelle
    $recupStudent = $thestudentM->selectionnerStudentBySectionId($idsection);

    echo $twig->render("sectionPublic.html.twig",["lemenu" => $menu,"detailsection"=>$detailsection,"student"=>$recupStudent]);


/*
 * Page de connexion
 */

}elseif(isset($_GET['connect'])) {

    // on a pas envoyé le formulaire
    if(empty($_POST)){

        // on appel le formulaire
        echo $twig->render("connectPublic.html.twig",["lemenu" => $menu]);

    }else{
        // instanciation de l'objet theuser avec hydratation des variables de notre formulaire (indispensable pour l'encodage en sha256 du mot de passe et bonne pratique)
        $user = new theuser($_POST);

        // utilisation du manager de theuser pour vérifier la validité de la connexion à notre administration
        $recupUser = $theuserM->connecterUser($user);

        // si on est connecté
        if($recupUser){
            // redirection vers le contrôleur frontal
            header("Location: ./");
        }else{
            // retour à notre formulaire de connexion avec affichage d'une erreur
            echo $twig->render("connectPublic.html.twig",["lemenu" => $menu, "erreur"=>"Login ou mot de passe incorrect"]);
        }

    }




/*
 * Page d'accueil publique
 */
}else {


// on sélectionne toutes les sections avec leur description pour les afficher sur la page d'accueil
    $section = $thesectionM->selectionnerSectionIndexPublic();

// on appelle la vue générée par twig

    echo $twig->render('accueilPublic.html.twig', ["lemenu" => $menu, "sections" => $section]);

}