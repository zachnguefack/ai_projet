<?php
// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
error_reporting(E_ALL);

// S'assurer que nous renvoyons toujours du JSON
header('Content-Type: application/json');

try {
    // Récupérer le message de l'utilisateur
    $input = file_get_contents('php://input');
    if ($input === false) {
        throw new Exception('Impossible de lire les données d\'entrée');
    }

    $data = json_decode($input, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Erreur de décodage JSON des données d\'entrée: ' . json_last_error_msg());
    }

    $userMessage = $data['message'] ?? '';
    if (empty($userMessage)) {
        throw new Exception('Le message est vide');
    }

    // Configuration de l'API GPT4All
    $apiUrl = 'http://localhost:4891/v1/chat/completions';

    // Préparer la requête pour GPT4All
    $requestData = [
        'model' => 'mistral-open-orca',
        'messages' => [
            [
                'role' => 'user',
                'content' => $userMessage
            ]
        ],
        'max_tokens' => 500,
        'temperature' => 0.7
    ];

    // Initialiser cURL
    $ch = curl_init($apiUrl);
    if ($ch === false) {
        throw new Exception('Impossible d\'initialiser cURL');
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    // Exécuter la requête
    $response = curl_exec($ch);
    if ($response === false) {
        throw new Exception('Erreur cURL: ' . curl_error($ch));
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    // Vérifier la réponse brute
    if (empty($response)) {
        throw new Exception('Réponse vide du serveur GPT4All');
    }

    // Débogage
    $debug = [
        'httpCode' => $httpCode,
        'curlError' => $curlError,
        'rawResponse' => $response,
        'requestData' => $requestData
    ];

    if ($httpCode === 200) {
        $responseData = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Erreur de décodage JSON de la réponse: ' . json_last_error_msg() . '. Réponse brute: ' . substr($response, 0, 1000));
        }

        // Vérifier la structure de la réponse
        if (!isset($responseData['choices'])) {
            throw new Exception('Structure de réponse invalide: ' . json_encode($responseData));
        }

        if (!isset($responseData['choices'][0]['message']['content'])) {
            throw new Exception('Contenu de réponse manquant: ' . json_encode($responseData));
        }

        $botResponse = $responseData['choices'][0]['message']['content'];
        if (empty($botResponse)) {
            throw new Exception('Réponse vide du modèle');
        }

        // Ajouter la réponse parsée au débogage
        $debug['parsedResponse'] = $responseData;
    } else {
        throw new Exception('Erreur HTTP ' . $httpCode . ': ' . $curlError);
    }

    // Renvoyer la réponse avec les informations de débogage
    echo json_encode([
        'success' => true,
        'response' => $botResponse,
        'debug' => $debug
    ]);

} catch (Exception $e) {
    // En cas d'erreur, renvoyer un message d'erreur au format JSON
    http_response_code(200); // Forcer le code 200 pour éviter l'erreur 500
    echo json_encode([
        'success' => false,
        'response' => 'Erreur: ' . $e->getMessage(),
        'debug' => [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]
    ]);
} 