<?php

namespace Models;

class User extends Model
{
    //pour utiliser Model, on doit définir une propriété protected $table
    //qui contient le nom de la table principale
    protected $table = T_USER;
    
 
    
    
    
    //tester si un email est déjà présent dans la table Users
    public function is_exist_user(string $email)
    {
        $query = $this->db->prepare("SELECT Id, Nickname, Password, Avatar, Admin FROM $this->table WHERE Email LIKE :email LIMIT 0,1");
        $query->execute([
            ':email' => $email,
        ]);

        return $query->fetch(\PDO::FETCH_ASSOC);
    }

    
    
    public function updateLoginDate(int $userId)
    {

        
        //mettre à jour la date de Last_login
        $sql = "UPDATE $this->table SET Last_login = NOW() WHERE Id= :Id";
        
        $query = $this->db->prepare($sql);

        $query->execute(['Id'=> $userId]);
        

        
    }
}

?>