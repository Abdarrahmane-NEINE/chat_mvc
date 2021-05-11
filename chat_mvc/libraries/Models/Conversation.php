<?php

namespace Models;

class Conversation extends Model
{
    //pour utiliser Model, on doit définir une propriété protected $table
    //qui contient le nom de la table principale
    protected $table = T_CONVERSATION;


    /**
     * Permet de récupérer la liste des données
     *
     * @return array
     */
     public function findAllWithNickname()
     {
         
         // Retrouver l'article et le retourner
         $query = $this->db->prepare("SELECT 
         conversation.Id, 
         User_id,
         Nickname, 
         Avatar,
         Content, 
         conversation.Created_at 
       FROM $this->table 
       INNER JOIN ".T_USER." ON conversation.User_id = user.Id 
       ORDER BY Created_at");
         $query->execute();
 
         return $query->fetchAll(\PDO::FETCH_ASSOC);
     }
     
     public function deleteConversation(int $id, int $userId, int $admin)
     {
             //tester si on est Admin
        if ($admin === 1) {
          //préparer la requete
          $query = $this->db->prepare("DELETE FROM $this->table WHERE Id=:id");
              
          //executer la requete
          $query->execute(['id' => $id]);  
        }
        else {
          //préparer la requete
          $query = $this->db->prepare("DELETE FROM $this->table WHERE Id=:id AND User_id=:userid");
              
          //executer la requete
          $query->execute(['id' => $id, 'userid' => $userId]);
        }
     }
    
}

?>