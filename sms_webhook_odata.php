<?php

// CE FICHIER EST A HEBERGER SUR UN SITE INTERNET

// _______________________________________________________________________
// Lire le contenu JSON envoyé par le callback
$data = file_get_contents("php://input");

// Datas Test
/*
$data = [
    "nom" => "RdvFM2025",
    "date" => 16/05/2025,
    "ville" => "Reims"
];
$json = json_encode($data);

*/
$json = $data;

$data = [
    "scriptParameterValue" => $json
];

// _______________________________________________________________________
// LE PLUS IMPORTANT = LA SEULE PARTIE A CONFIGURER  +++++++++++

// CONFIGURATION CONNEXION BASE DE DONNÉES FILEMAKER

// Format OData appel script FileMaker
// https://hôte/fmi/odata/v4/nom-base/Script.nom-script
// https://help.claris.com/fr/odata-guide/content/run-scripts.html

// Serveur
//$server = "https://hôte/fmi/odata/v4";
$server = "https://nom_site_hebergemnt_base_filemaker/fmi/odata/v4";

// Base de Données
//mettre le nom de la base de donnée FileMaker (sans mettre.fmp12)
$database = "NOM_BASE_FILEMAKER";

// Script
$scriptName = "nom_script_a_lancer";

// Login et Mot de Passe
//$username = "votre_utilisateur";
//$password = "votre_mot_de_passe";
$username = "login";
$password = "mot_de_passe";

//Paramètre du script
$param = $data;

// _________________________Construction de la requête____________________

// Encodage de l'authentification en Base64
$authHeader = "Authorization: Basic " . base64_encode("$username:$password");

// Construction de l'URL avec le paramètre du script ( le body du POST contient le Paramètre du script )
$url = "$server/$database/Script.$scriptName";
//$url = "$server/$database";

// Initialisation de cURL
$ch = curl_init($url);

// Configuration des OPTIONS cURL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    $authHeader,
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $param )); // Envoi JSON data

// _______________________________________________________________________


// EXECUTION de la requête vers FILEMAKER SERVEUR
$response = curl_exec($ch);

// Gestion des erreurs
if (curl_errno($ch)) {
    echo "Erreur cURL : " . curl_error($ch);
}

// Fermeture de la connexion cURL
curl_close($ch);

// Affichage de la réponse
echo "Réponse du serveur : " . $response;
