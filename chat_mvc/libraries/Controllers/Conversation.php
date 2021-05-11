<?php

namespace Controllers;

class Conversation extends Controller 
{
   //contruction du nom du modele à utiliser
   protected $modelName = \Models\Conversation::class;

   //traitement formulaire chat
   public function message() {
        //tester si l'utilisateur est bien connecté
        \Session::redirectIfNotConnected();

        //est-ce que j'ai le champ message
        if (empty($_POST['message']))
        {
             //rediriger l'utilisateur vers le formulaire de login
             \Http::redirectBack();
        }

        //tester le token
        $myToken = new \Token();
         
        if (!SECRETKEY == $myToken->decode($_POST['token']))
        {
            //détruire le token
            \Session::deleteToken();
            //rediriger l'utilisateur vers le formulaire
            \Http::redirectBack();
        }
        
        //détruire le token
        \Session::deleteToken();

        $data = [
          'User_id' => $_SESSION['user']['Id'],
          'Content' => $_POST['message']
        ];
        //insertion du message
        $this->model->insert($data);


        //redirection 
        \Http::redirect(WWW_URL);  

    }
    
    public function delete() {
        //tester si l'utilisateur est bien connecté
        \Session::redirectIfNotConnected();
        
        //tester $_GET['id']
        // Validation du paramètre id de la chaîne de requête
        //absence d'une clé id dans $_GET ou $_GET['id'] n'est pas numeric
        if( !array_key_exists('id', $_GET) OR !ctype_digit($_GET['id']) ) {
          //rediriger l'utilisateur vers la page précèdente
            \Http::redirectBack();
        }
        
        //supression de la conversation
        $this->model->deleteConversation(intVal($_GET['id']), $_SESSION['user']['Id'], $_SESSION['user']['Admin']);
        
        
        //redirection 
        \Http::redirect(WWW_URL); 
    }
 
}