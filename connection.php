<?php
try
{
	// connexion à MySQL
	$mysqlClient = new PDO('mysql:host=localhost;dbname=habilitations;charset=utf8;port=3306', 'root', 'biBip');
}
catch(Exception $e)
{

        die('Erreur : '.$e->getMessage());
}
?>