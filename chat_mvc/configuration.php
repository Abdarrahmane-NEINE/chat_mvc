<?php

/**
 * FICHIER DE CONFIGURATION DU SITE !
 * ----------------------
 * On met en place ici tout ce qui sert à la configuration
 */

/**
 * MISE EN PLACE DE LA SESSION
 * ---------------------------
 * On le sait très bien, on aura besoin de la session dans ce site dans plus ou moins tous les fichiers
 * C'est donc une bonne pratique de la mettre en oeuvre tout de suite ici avant toute autre action
 *
 * Comprenez bien : le fichier configuration.php sera la première chose qui s'exécutera quand on appellera notre site.
 * On est donc surs que session_start() sera appelé tout le temps, et avant tout le reste !
 */
session_start();

/**
 * CONFIGURATION DE LA BASE DE DONNEES
 * -----------------------------------
 * Ca facilite l'évolution, le jour où on change de base de données ou que l'utilisateur
 * ou que le mot de passe change, on peut simplement modifier ce fichier
 * et hop ! Tout remarche à nouveau
 */
 
const DB_HOST = "localhost";
const DB_USER = "root";
const DB_PASSWORD = "";
const DB_NAME = "chat_mvc";



const SECRETKEY = 'mysecretkey1234';

/**
 * LISTE DES TABLES de LA BDD
 */
 
const T_USER = "user";
const T_CONVERSATION = "conversation";











