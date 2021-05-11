<?php

namespace Controllers;

class Home extends Controller 
{
   //contruction du nom du modele à utiliser
   protected $modelName = \Models\Conversation::class;
   
   public function index() {
        
        //tester si l'utilisateur est bien connecté
        \Session::redirectIfNotConnected();


        $this->tplVars = $this->tplVars + [
            'conversations' => $this->model->findAllWithNickname()
        ];

        $myToken = new \Token();
        
        $this->tplVars = $this->tplVars + ['Token' => $myToken->encode(SECRETKEY)];

        //affichage
        \Renderer::show("home",$this->tplVars);
        
    }
}