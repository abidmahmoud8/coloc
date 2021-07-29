<?php
// Nom de la hote
$host = 'localhost';
// Nom de la base de donné
$dbname = 'voti';
// Nom d'utilisteur
$user = 'root';
// Mot de passe 
$password = '';
// Mot de passe 
// Connexions de la base de donnés
$pdo = new PDO('mysql:host='.$host.';
  dbname='.$dbname.';
  charset=utf8', 
  $user, 
  $password,
  [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
);

// PDO                          : une extension PHP qui définit une interface d'accès à une base de données.
// PDO::ATTR_DEFAULT_FETCH_MODE : Définit le mode de récupération par défaut.
// PDO::FETCH_ASSOC             : retourne un tableau indexé par le nom de la colonne comme retourné dans le jeu de résultats