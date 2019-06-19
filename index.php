<?php
/*
 *
 *
 * Front Controller
 *
 *
 */

/*
 * Lancement d'une session
 */

session_start();

/*
 * Load Dependencies
 */

require_once "config.php";

// composer vendor loading (for twig)
require_once 'vendor/autoload.php';



// Initialize twig templating system
$loader = new \Twig\Loader\FilesystemLoader('view/');
$twig = new \Twig\Environment($loader);
// twig extension for text
$twig->addExtension(new Twig_Extensions_Extension_Text());



/*
 * create class autoload - find class into model's folder
 */

spl_autoload_register(function ($class) {
    require_once 'model/' . $class . '.php';
});


// connexion to our DB
try {
    $connexion = new MyPDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';port=' . DB_PORT . ';charset=' . DB_CHARSET,
        DB_LOGIN,
        DB_PWD,
        null,
        PRODUCT);
} catch (PDOException $e) {
    echo $e->getMessage();
    die();
}


// create common's Managers

$thesectionM = new thesectionManager($connexion);
$thestudentM = new thestudentManager($connexion);
$theuserM = new theuserManager($connexion);

// we're connected

if(isset($_SESSION['myKey'])&&$_SESSION['myKey']==session_id()){

    /*
     * admin
     */
    require_once "controller/adminController.php";

}else {

    /*
     * public
     */

    require_once "controller/publicController.php";

}