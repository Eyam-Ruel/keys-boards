<?php
session_start();
// Inclusion de la classe pour se connecter à la base de données
require_once 'class/Database.class.php';
$pdo = Database::getLink();

// Préparation de la requête pour enregistrer le nouveau message
$sql = "INSERT INTO messages (sender_id, receiver_id, message_text) VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);

// Exécution avec l'ID de l'expéditeur (session), du destinataire et le contenu (POST)
$stmt->execute([$_SESSION['user_id'], $_POST['to_id'], $_POST['text']]);

// Retourne une réponse JSON pour indiquer à l'interface que tout s'est bien passé
echo json_encode(['status' => 'success']);