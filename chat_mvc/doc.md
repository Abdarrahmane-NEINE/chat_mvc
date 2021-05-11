# présentation du framework MVC

Ce framework permet de séparer les modèles, 
les controllers et les views.

## Arborescence de l'outils

+ assets
    - css //contient les css utilisés dans le projet
+ libraries
    + Controllers
        - Controller.php
        - Home.php
    + Models
        - Model.php
    + templates
        + partials
            - error.phtml
            - home.phtml
        - layout.phtml
    - Application.php
    - autoload.php
    - Database.php
    - Http.php
    - Renderer.php
    - Session.php
    - Token.php
- configuration.php //contient le nom des tables et les infos pour se connecter à mysql
- index.php

## les views
    
layout.phtml contient le markup html global.

Dans le sous-dossier /partials/ 
vous retrouverez tous les views spécifiques à chaque page.

La class static Renderer s'occupe de récupérer les différents templates (avec la méthode show()), et d'y transmettre toutes les variables dynamiques qui sont contenues dans un tableau $tplVars.

Les données de $tplVars sont initialisés dans le controller.

La variable $tplVars['WWW_URL'] est calculé par le framework et contient l'url de la racine du projet.


## les modèles

Les différents modèles utilisés sont des class, 
et sont rangés dans /Models.

Il existe un Model.php, qui contient la structure de base. 
La mise en place d'un modèle consiste à créer une nouvelle class qui étend ce Model.php

Vous avez des exemples avec Conversation.php et User.php

## les controllers

Comme pour les modèles, les controllers sont aussi des class, et rangés dans /Controllers.

Il existe un Controller.php, qui contient la structure de base. La mise en place d'un nouveau controller consiste à créer une nouvelle class qui étend ce Controller.php

Vous avez des exemples avec Home.php, Conversation.php et User.php

## le routing

Tout affichage de page, ou traitement d'un formulaire passe le fichier index.php de la racine du projet.

Cela s'appelle le routing.

Par default, c'est le controller Home.php qui est instancié, et la méthode par default est la méthode index().

Le controller et la méthode peuvent être personnalisés pour tout appel de page, ex : 

<?= $tplVars['WWW_URL']; ?>index.php?controller=conversation&task=message

Ici, on va créé une instance du controller Conversation.php et appeller la méthode message().

Vous remarquerez, que dans l'url, on laisse toute les variables en minuscule.

Pas de besoin de s'occuper des requires pour charger les différentes class, c'est le framework qui le gère tout seul.

### parcours du framework.

tout passe par /index.php à la racine du projet :
    - calcul de l'url racine du projet
    - chargement des données de configuration (configuration.php)
    - chargement de l'autoload, une function qui automatiquement va inclure tous les class utilisées par le php
    - appel d'une méthode static dans la class Application, qui va créer l'instance du controller, et appeler la méthode 
    - si cet appel ne fonctionne pas, l'erreur est attrapé, le template error.phtml s'affiche avec des informations sur le dysfonctionnement

### ce qu'il faut retenir

Normalement, nous n'avons pas besoin de modifier le code du routing, tout ce fait automatiquement, 
en fonction du controller et de la méthode demandé.

L'essentiel de la production doit se faire dans /Models /Controllers et dans /templates

Le fichier configuration.php est à modifier avec vos données.

## Règles de nommage

- les class sont nommées avec la première lettre en majuscule, et la class doit porter le même nom que son fichier.

### les variables transmises à la view

L'ensemble des variables transmises à une view, est contenu dans le tableau $tplVars, le remplissage de ce tableau
se fait depuis le controller.

si nous avons besoin d'interagir avec la base de données, nous utiliserons une instance d'un modèle dans le controller, et appelerons des méthodes de ce Modèle.



## les classes statics

Les class statics sont des class qui peuvent être utilisées directement sans créer d'instance, elles fonctionnent comme l'appelle d'une fonction.

### Http.php

gère les diverses redirections 

### Renderer.php

gère le rendu des views avec les données dynamiques remplacées

### Session.php

gère la variable Session de l'utilisateur

### Token.php

permet d'utiliser un token pour les divers formulaires du projet, 
pour se prémunir des failles CSRF (Cross-Site Request Forgery)

### Database.php

utilisé dans les Models, cette class gère pdo