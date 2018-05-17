<?php

require __DIR__ . '/../src/Template.php';
require __DIR__ . '/../src/Users.php';
require __DIR__ . '/../src/Conversations.php';
require __DIR__ . '/../src/Messages.php';
require __DIR__ . '/../src/Database.php';

session_start();
$database = Database::getInstance();
$conn = $database->getConnection();

if (!isset($_SESSION['user'])) {
    header("Location: LoginController.php");
    exit();
}

if($_SESSION['role'] == 'support') {
    header("Location: SupportController.php");
    exit();
}

$conversations = Conversations::loadConversationsByClientId($conn, $_SESSION['user']);
if($conversations) {
    foreach ($conversations as $conv) {
        $row = new Template(__DIR__ . '../templates/conv_client.tpl');
        $row->add('conversationId', $conv->getId());
        $row->add('conversationSubject', $conv->getSubject());
        $rowsTemplate[] = $row;
    }
    $rowsContent = Template::joinTemplates($rowsTemplate);
    $conversation = new Template(__DIR__ . '../templates/conv_client.tpl');
    $conversation->add('conversationSubject', $rowsContent);
} else {
    $conversation = new Template(__DIR__ . '../templates/conv_client.tpl');
    $conversation->add('conversationSubject', 'No conversations');
}

if(isset ($_GET['convId'])){
    $conversationId = $_GET['convId'];
    $allMessages = Messages::loadMessagesByConversationId($conn, $conversationId);
    foreach ($allMessages as $msg) {
        $row = new Template(__DIR__ . '../templates/message.tpl');
        $user = $msg->getSenderId();
        $row->add('messageSender', Users::loadUserById($conn, $user)->getLogin());
        $row->add('messageText', $msg->getText());
        $rowsTemplate[] = $row;
    }
    $rowsContent = Template::joinTemplates($rowsTemplate);
    $messages = new Template(__DIR__ . '/../templates/message.tpl');
    $messages->add('messageSender', $rowsContent);
    $messages->add('messageText', ' ');
}


$index = new Template(__DIR__ . '/../templates/index.tpl');
$content = new Template(__DIR__ . '/../templates/client_content.tpl');


$content->add('conversations', $conversation->parse());
$content->add('messages', $messages->parse());
$content->add('convId', $conversationId);
$index->add('content', $content->parse());

echo $index->parse();