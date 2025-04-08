<?php
require_once 'config/config.php';
require_once 'includes/Product.php';

header('Content-Type: application/json');

// Données des produits IT
$products = [
    // Claviers
    [
        'id' => 1,
        'name' => 'Clavier Mécanique RGB Gaming',
        'price' => 89.99,
        'category' => 'claviers',
        'image' => 'https://picsum.photos/300/200?random=1',
        'description' => 'Clavier mécanique gaming avec switches Cherry MX et rétroéclairage RGB personnalisable'
    ],
    [
        'id' => 2,
        'name' => 'Clavier Sans Fil Ergonomique',
        'price' => 49.99,
        'category' => 'claviers',
        'image' => 'https://picsum.photos/300/200?random=2',
        'description' => 'Clavier sans fil ergonomique avec autonomie de 6 mois et design compact'
    ],
    [
        'id' => 3,
        'name' => 'Clavier Mécanique Compact',
        'price' => 69.99,
        'category' => 'claviers',
        'image' => 'https://picsum.photos/300/200?random=3',
        'description' => 'Clavier mécanique compact 60% avec switches Blue et rétroéclairage LED'
    ],

    // Souris
    [
        'id' => 4,
        'name' => 'Souris Gaming Pro RGB',
        'price' => 59.99,
        'category' => 'souris',
        'image' => 'https://picsum.photos/300/200?random=4',
        'description' => 'Souris gaming haute précision avec capteur optique 16000 DPI et 8 boutons programmables'
    ],
    [
        'id' => 5,
        'name' => 'Souris Sans Fil Ergonomique',
        'price' => 39.99,
        'category' => 'souris',
        'image' => 'https://picsum.photos/300/200?random=5',
        'description' => 'Souris sans fil ergonomique avec autonomie de 12 mois et design confortable'
    ],
    [
        'id' => 6,
        'name' => 'Souris Gaming Légère',
        'price' => 45.99,
        'category' => 'souris',
        'image' => 'https://picsum.photos/300/200?random=6',
        'description' => 'Souris gaming ultra-légère (69g) avec capteur haute précision et design aérodynamique'
    ],

    // Écrans
    [
        'id' => 7,
        'name' => 'Écran Gaming 27" 4K',
        'price' => 299.99,
        'category' => 'ecrans',
        'image' => 'https://picsum.photos/300/200?random=7',
        'description' => 'Écran gaming 27 pouces 4K avec technologie HDR, 144Hz et temps de réponse 1ms'
    ],
    [
        'id' => 8,
        'name' => 'Écran Courbe 34" UltraWide',
        'price' => 399.99,
        'category' => 'ecrans',
        'image' => 'https://picsum.photos/300/200?random=8',
        'description' => 'Écran courbe 34 pouces UltraWide avec résolution 3440x1440 et taux de rafraîchissement 100Hz'
    ],
    [
        'id' => 9,
        'name' => 'Écran 24" Full HD',
        'price' => 149.99,
        'category' => 'ecrans',
        'image' => 'https://picsum.photos/300/200?random=9',
        'description' => 'Écran 24 pouces Full HD avec technologie IPS et temps de réponse 5ms'
    ],

    // Accessoires
    [
        'id' => 10,
        'name' => 'Tapis de Souris XXL Gaming',
        'price' => 24.99,
        'category' => 'accessoires',
        'image' => 'https://picsum.photos/300/200?random=10',
        'description' => 'Tapis de souris gaming grande taille (900x400mm) avec surface optimisée et bordure surpiqûre'
    ],
    [
        'id' => 11,
        'name' => 'Support Écran Double',
        'price' => 79.99,
        'category' => 'accessoires',
        'image' => 'https://picsum.photos/300/200?random=11',
        'description' => 'Support double écran ajustable avec gestion des câbles et installation sans outils'
    ],
    [
        'id' => 12,
        'name' => 'Kit Câbles Gaming',
        'price' => 29.99,
        'category' => 'accessoires',
        'image' => 'https://picsum.photos/300/200?random=12',
        'description' => 'Kit de câbles gaming avec extensions RGB, manchons et peignes de câbles'
    ],
    [
        'id' => 13,
        'name' => 'Webcam HD 1080p',
        'price' => 49.99,
        'category' => 'accessoires',
        'image' => 'https://picsum.photos/300/200?random=13',
        'description' => 'Webcam HD 1080p avec micro intégré et support ajustable'
    ],
    [
        'id' => 14,
        'name' => 'Casque Gaming 7.1',
        'price' => 89.99,
        'category' => 'accessoires',
        'image' => 'https://picsum.photos/300/200?random=14',
        'description' => 'Casque gaming avec son surround 7.1, micro détachable et éclairage RGB'
    ]
];

// Filtrer par catégorie si spécifiée
if (isset($_GET['category']) && $_GET['category'] !== 'all') {
    $category = $_GET['category'];
    $products = array_filter($products, function($product) use ($category) {
        return $product['category'] === $category;
    });
}

// Renvoyer les produits au format JSON
echo json_encode(array_values($products)); 