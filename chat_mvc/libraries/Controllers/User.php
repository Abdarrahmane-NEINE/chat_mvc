<?php

namespace Controllers;

class User extends Controller 
{
   //contruction du nom du modele à utiliser
   protected $modelName = \Models\User::class;

   //méthode pour vérifier le mot de passe
   private function verifPassword($pass, $pass_hash) {
    //vérifier le mot de passe
    //https://www.php.net/manual/fr/function.openssl-decrypt


        if ($pass === openssl_decrypt($pass_hash, "AES-128-ECB", SECRETKEY)) {
            return true;
        }
        
        return false;

    }
    
    public function profil() {
        
        // vérifier si l'utilisateur est bien connecté :
        //tester si l'utilisateur est bien connecté
        \Session::redirectIfNotConnected();
        
        //construire le formulaire
        $myToken = new \Token();
        
        $this->tplVars = $this->tplVars + ['Token' => $myToken->encode(SECRETKEY)];
        
        //récupérer les infos de l'utilisateur
        $user_data = $this->model->find($_SESSION['user']['Id']);
        
        $this->tplVars = $this->tplVars + ['User' => $user_data];
        
        //affichage
        \Renderer::show("profil",$this->tplVars);
    }
    
    public function form() {
        
        //construire le formulaire
        $myToken = new \Token();
        
        $this->tplVars = $this->tplVars + ['Token' => $myToken->encode(SECRETKEY)];
        
        //affichage
        \Renderer::show("account",$this->tplVars);
    }
    
    //crypt mot de passe
    private function cryptPassword($pass) {
        //hasher le mot de passe
        //https://www.php.net/manual/fr/function.openssl-encrypt
        
        return openssl_encrypt($pass, "AES-128-ECB", SECRETKEY);
    }
   
   public function index() {

        $myToken = new \Token();
        
        $this->tplVars = $this->tplVars + ['Token' => $myToken->encode(SECRETKEY)];
        
        //affichage
        \Renderer::show("login",$this->tplVars);
        
    }
    
    public function logout() {
        
        //déconnection
        \Session::disconnect();
        
        //redirection
        \Http::redirect(WWW_URL); 
    }
    
    public function account() {
        //traitement du formulaire de création de compte
        //tester si les champs $_POST existents
        if (empty($_POST['inputEmail'])
        || empty($_POST['inputNickname'])
        || empty($_POST['inputPassword'])
        || empty($_POST['inputPassword2'])) {
             //rediriger l'utilisateur vers le formulaire de création de compte
             \Http::redirectBack();
        }
        
        // tester si les 2 mots de passe ont bien la même valeur
        if ($_POST['inputPassword'] != $_POST['inputPassword2']) {
            //rediriger l'utilisateur vers le formulaire de création de compte
             \Http::redirectBack();
        }
        
        //tester l'email (format)
        if (!filter_var($_POST['inputEmail'], FILTER_VALIDATE_EMAIL)) {
              //rediriger l'utilisateur vers le formulaire de création de compte
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
        
        //encrypter le mot de passe
        //voir https://www.php.net/manual/fr/function.openssl-encrypt
        $pwd_crypted = $this->cryptPassword($_POST['inputPassword']);
        
        //insertion du user dans la bdd
        $data = [
        'Nickname' => $_POST['inputNickname'],
        'Email' => $_POST['inputEmail'],
        'Password' => $pwd_crypted
        ];
        
        $this->model->insert($data);

        
        // redirection vers index.php
        \Http::redirect(WWW_URL);  
        
    }
    
    public function updateUser() {
        //tester si l'utilisateur est bien connecté
        \Session::redirectIfNotConnected();
        
        //valeur par default
        $pwd_crypted = '';
        $queryPassword = '';
        $queryFile = '';
        $filename = '';
        
        //tester si les champs $_POST existents
        if (empty($_POST['inputEmail'])
        || empty($_POST['inputNickname'])) {
            //rediriger l'utilisateur vers le formulaire 
             \Http::redirectBack();
        }
        
         /*
    le changement du mot de passe n'est pas obligatoire
    */
    // tester si les 2 mots de passe ont bien la même valeur
    if(!empty($_POST['inputPassword']) && !empty($_POST['inputPassword2'])) {
        //tester si les 2 champs sont égaux
      if ($_POST['inputPassword'] != $_POST['inputPassword2']) {
            //rediriger l'utilisateur vers le formulaire 
             \Http::redirectBack();   
        }
        //ici le mot de passe est vérifié, on le sauvegarder dans la base 
        
        //$_POST['inputPassword'] est la valeur en clair du mot de passe à enregister
        
        //encrypter le mot de passe
        //voir https://www.php.net/manual/fr/function.openssl-encrypt
        $pwd_crypted = $this->cryptPassword($_POST['inputPassword']);
        
        $queryPassword = ', Password = ?';
    }
    
    //tester l'email (format)
    if (!filter_var($_POST['inputEmail'], FILTER_VALIDATE_EMAIL)) {
            //rediriger l'utilisateur vers le formulaire 
             \Http::redirectBack();         
    }
    
    //test $files
            //test si on a un avatar à uploader
    if (!empty($_FILES['inputAvatar']['name'])) {
    	
    	//extensions autorisées
        $allowed_file_types = ['image/png', 'image/jpeg'];
        
        //tester si le fichier uploadé est bien un png ou un jpg
        //tester si le type MIME du fichier ($_FILES['file1']['type'] est dans le tableau $allowed_file_types 
        if (in_array(mime_content_type($_FILES["inputAvatar"]["tmp_name"]), $allowed_file_types)) 
        {
            //le type est bon
            
            //construction du nom du fichier
            /* pour qu'il soit unique, il sera nommé clé_primaire_du_user.ext */
            switch(mime_content_type($_FILES["inputAvatar"]["tmp_name"]))
            {
                case 'image/png':
                    $filename = $_SESSION['user']['Id'].'.png';
                    break;
                    
                case 'image/jpeg':
                    $filename = $_SESSION['user']['Id'].'.jpg';
                    break;
            }
            
            //il reste à déplacer le fichier temporaire vers ./upload
            $resultMoveFile = move_uploaded_file($_FILES['inputAvatar']['tmp_name'],"upload/".$filename);
            
            //tester si le fichier est bien arrivé dans notre dossier upload
            if ($resultMoveFile) {
                //renseigner $queryFile
                $queryFile = ', Avatar =?';
            }
        }
    }
    
        //construction du tableau qui contient les valeurs à mettre à jour
    $data = [
        'Nickname' => $_POST['inputNickname'], 
        'Email' =>   $_POST['inputEmail']
        ];
    
    //test du mot de passe
    if ($pwd_crypted !== '')
    {
        //ajout du mot de passe dans le tableau $dataUpdate
        $data['Password'] = $pwd_crypted;
    }
    
    
     //test de l'avatar
    if ($filename !== '')
    {
        //ajout du nom de l'avatar dans le tableau $dataUpdate
        $data['Avatar'] = $filename;
    }
    
    //ajouter la clé primaire pour la clause WHERE
    $data['Id'] = $_SESSION['user']['Id'];
    
    
    //mise à jour dans la base
    
    $return = $this->model->update($data); 
    
    
    //mettre à jour la session 
    //tester si la mise à jour de la bdd a bien fonctionné
    if ($return) {
       $_SESSION['user']['Nickname'] = htmlspecialchars($_POST['inputNickname']); 
       if ($filename !== '')
        {
            $_SESSION['user']['Avatar'] = htmlspecialchars($filename);  
        }
      
    }
    
    // redirection vers profil.php
    \Http::redirect(WWW_URL.'index.php?controller=user&task=profil');
    
}

    public function login() {
        //est-ce que j'ai les champs email et password
        if (empty($_POST['inputEmail'])  || 
        empty($_POST['token']) ||
        empty($_POST['inputPassword']))
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

         //chercher un user qui possède l'email
    $user = $this->model->is_exist_user($_POST['inputEmail']);
    
    //test si l'email n'existe pas dans la base de données
    if(!$user) {
        \Http::redirectBack(); 
    }
    
    //test si le mot de passe n'est pas le bon 
    if(!$this->verifPassword($_POST['inputPassword'], $user['Password'])) {
       \Http::redirectBack(); 
    }
    
    // l'identification est correct
    
    //mise en place de la variable SESSION
    //création d'un session utilisateur
    \Session::connect([
        'Id' => intVal($user['Id']),
        'Nickname' => htmlspecialchars($user['Nickname']),
        'Avatar' => htmlspecialchars($user['Avatar']),
        'Admin' => intVal($user['Admin'])
        ]);

    
    //mise à jour de la date de login
    $this->model->updateLoginDate(intVal($user['Id']));
    
    //l'identification a réussi
        
    \Http::redirect(WWW_URL);  
    }
}