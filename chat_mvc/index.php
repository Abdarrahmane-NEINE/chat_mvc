<?php
//declare(strict_types=1);

error_reporting(E_ALL);
ini_set("display_errors", 1);



//constante définissant le chemin vers le projet
define('WWW_URL', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));

//fichier contenant toutes les constantes de configuration (acces base de données, ....)
require_once("configuration.php");


//essaie de l'action, si erreur, interception de celle-ci avec catch
try {
    
    //chargement automatique des fichiers contenant les objects necessaires dans le script
    require_once("libraries/autoload.php");
    
    //création de l'instance du controller et appel de la méthode principale
    \Application::process();
    
    /*$controller = new \Controllers\User();
    $controller->index();*/



} catch (Exception $e) {
    // Si il y a eu la moindre erreur :
                //affichage
                \Renderer::showError([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage()
                ]);    
 
}