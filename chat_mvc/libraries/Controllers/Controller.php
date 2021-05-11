<?php

namespace Controllers;

/**
 * LA CLASSE DE BASE D'UN CONTROLLER
 * ---------------------------------
 * Elle est abstraite car un Controller de rien ça veut rien dire. On ne veut pas que quelqu'un puisse un jour se dire "Allez, je créé un Controller sans préciser de quoi je parle".
 *
 * Elle ne sert qu'à avoir les méthodes et les comportements de base communs à tous les controllers ! Elle ne doit donc pas être utilisée telle quelle.
 *
 * Il faut obligatoirement en hériter dans une autre classe qui, elle, précisera plus de quoi on parle (des utilisateurs ? des produits ? des commandes ?)
 *
 * LES PROPRIETES :
 * ---------------------
 * $model représente l'objet Model à utiliser pour nos requêtes
 * $modelName représente le nom de l'objet Model à créer avant toute action sur un controller
 *
 * LES METHODES :
 * ----------------------
 * - Le constructeur permet de mettre en place le model ou de faire une exception si on le model indiqué n'existe pas !
 */
abstract class Controller
{
    /**
     * C'est l'objet Model qui servira à toutes nos actions dans un controller, c'est lui qui fait le lien avec une table de la base de données
     *
     * Par exemple, dans le UsersController, cette propriété contiendra un UsersModel, dans le StatusController, cette propriété contiendra un StatusModel
     *
     * Donc dans toutes les fonctions d'un controller, on peut appeler $this->model pour pouvoir manipuler les données de la table facilement !
     *
     * Le model contenu dans la propriété dépend exclusivement de l'autre propriété $modelName (voir la description)
     *
     * @var Model
     */
    protected $model;

    /**
     * C'est le nom de la classe Model qu'on veut dans $this->model (la propriété ci-dessus)
     *
     * Exemple : si $modelName contient "User", alors $model contiendra un objet de la classe User
     *
     *
     * @var string
     */
    protected $modelName;
    
    protected $tplVars = []; //un tableau qui contiendra toutes les valeurs par default à transmettre au template

    /**
     * Le constructeur a pour seul but de vérifier la validité du model demandé et de créer un objet issu de la classe demandée
     */
    public function __construct()
    {

    // 1. Vérifier que le développeur a bien renseigné le nom d'un model
    if (!empty($this->modelName)) {
            // 2. Vérifier si le model existe
            $chemin = str_replace("\\", "/", "libraries/{$this->modelName}.php");
            // Si $this->modelName contient "User", ça donne "libraries/Models/User.php"
            if (!file_exists($chemin)) {
                // Si le fichier n'existe pas, on fait une nouvelle erreur
                throw new \Exception("Le model défini dans " . get_called_class() . " ({$this->modelName}) n'existe pas ! Nous n'avons pas trouvé le fichier qui aurait du se trouver ici : $chemin !");
            }

            
        
        $this->model = new $this->modelName();
        // Si $this->modelName contient User, ça donne $this->model = new User()
    }
    
    

        
        
        /*
        initialisation des données par default
        */
        
   
        //### chemin absolue du projet
        //$tplVars['WWW_URL'] = WWW_URL;
        
        $this->tplVars = $this->tplVars + ['WWW_URL' => WWW_URL];
 
        
   
    }
    
    

}
