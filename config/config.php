<?php
// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ecommerce');

// Configuration de l'API Gemini
define('GEMINI_API_KEY', 'VOTRE_CLE_API_GEMINI');
define('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent');

// Configuration du site
define('SITE_NAME', 'Mon E-commerce');
define('SITE_URL', 'http://localhost/ecommerce');

// Configuration des chemins
define('ROOT_PATH', dirname(__DIR__));
define('INCLUDES_PATH', ROOT_PATH . '/includes');
define('ASSETS_PATH', ROOT_PATH . '/assets');

// Gestion des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuration de la session
session_start(); 