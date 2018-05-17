<?php
session_start();
require __DIR__ . '/../src/Template.php';
require __DIR__ . '/../src/Conversations.php';
require __DIR__ . '/../src/Messages.php';
require __DIR__ . '/../src/Database.php';
require __DIR__ . '/../src/Users.php';

$database = Database::getInstance();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['conversationSubject'])) {
        $subject = filter_input(INPUT_POST, 'conversationSubject');
        $conversation = new Conversations();
        $conversation->setClientId($_SESSION['user']);
        $conversation->setSubject($subject);
        $conversation->saveToDB($conn);
    } else {
        echo "empty data";
    }
}

$index = new Template(__DIR__ . '/../templates/index.tpl');
$content = "";
$index->add('content', $content);
echo $index->parse();
