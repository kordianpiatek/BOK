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
    if (isset($_POST['conversationId']) && isset($_POST['message'])) {
        $conversationId = filter_input(INPUT_POST, 'conversationId');
        $text = filter_input(INPUT_POST, 'message');
        $message = new Messages();
        $message->setConversationId($conversationId);
        $message->setSenderId($_SESSION['user']);
        $message->setText($text);
        $message->saveToDB($conn);
    } else {
        echo "Error";
        header( "ClientController.php" );
    }
}

$index = new Template(__DIR__ . '/../templates/index.tpl');
$content = "";
$index->add('content', $content);
echo $index->parse();