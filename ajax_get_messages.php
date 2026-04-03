<?php
session_start();
// Inclusion de la classe pour la connexion à la base de données
require_once 'class/Database.class.php';
$pdo = Database::getLink();

// Récupération de l'ID de l'utilisateur connecté et de celui de l'interlocuteur
$monId = $_SESSION['user_id'];
$contactId = $_GET['contact_id'];

// Requête pour récupérer toute la conversation (les messages envoyés ET reçus entre les deux utilisateurs)
$sql = "SELECT * FROM messages 
        WHERE (sender_id = :m AND receiver_id = :c) 
        OR (sender_id = :c AND receiver_id = :m) 
        ORDER BY created_at ASC"; // Tri par date croissante pour avoir le plus ancien en haut et le plus récent en bas
$stmt = $pdo->prepare($sql);

// Exécution avec les paramètres sécurisés
$stmt->execute([':m' => $monId, ':c' => $contactId]);

// Renvoi de tous les résultats au format JSON pour que le JavaScript puisse les afficher
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));