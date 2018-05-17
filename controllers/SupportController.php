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

if($_SESSION['role'] == 'client') {
    header("Location: ClientController.php");
    exit();
}


$conversations = Conversations::loadConversationsBySupportId($conn, $_SESSION['user']);
if($conversations) {
    foreach ($conversations as $conv) {
        $row = new Template(__DIR__ . '/../templates/conv_support.tpl');
        $row->add('conversationSubject', $conv->getSubject());
        $row->add('conversationId', $conv->getId());
        $rowsTemplate[] = $row;
    }
    $rowsContent = Template::joinTemplates($rowsTemplate);
    $conversation = new Template(__DIR__ . '/../templates/conv_support.tpl');
    $conversation->add('conversationSubject', $rowsContent);
} else {
    $conversation = new Template(__DIR__ . '/../templates/conv_support.tpl');
    $conversation->add('conversationSubject', 'No conversations');
    $conversation->add('conversationId', null);
}

$openConversations = Conversations::loadOpenConversations($conn);
if($openConversations) {
    foreach ($openConversations as $openConv) {
        $row = new Template(__DIR__ . '/../templates/open_conversation.tpl');
        $row->add('conversationSubject', $openConv->getSubject());
        $row->add('conversationId', $openConv->getId());
        $row->add('supportId', $_SESSION['userID']);
        $rowsTemplate[] = $row;
    }
    $rowsContent = Template::joinTemplates($rowsTemplate);
    $openConversation = new Template(__DIR__ . '/../templates/open_conversation.tpl');
    $openConversation->add('conversationSubject', $rowsContent);
} else {
    $openConversation = new Template(__DIR__ . '/../templates/open_conversation.tpl');
    $openConversation->add('conversationSubject', 'No open conversations');
}

if (isset($_GET['convId'])) {
    $conversationId = $_GET['convId'];

    $messages = Messages::loadMessagesByConversationId($conn, $conversationId);
    foreach ($messages as $msg) {
        $row = new Template(__DIR__ . '/../templates/message.tpl');
        $user = $msg->getSenderId();
        $row->add('messageSender', Users::loadUserById($conn, $user)->getLogin());
        $row->add('messageText', $msg->getText());
        $rowsTemplate[] = $row;
    }
    $rowsContentMessages = Template::joinTemplates($rowsTemplate);
    $allMessages = new Template(__DIR__ . '/../templates/message.tpl');
    $allMessages->add('messageSender', $rowsContentMessages);
    $allMessages->add('messageText', ' ');
} else {
    $allMessages = new Template(__DIR__ . '/../templates/message.tpl');
    $allMessages->add('messageSender', '');
    $allMessages->add('messageText', 'Choose conversation');
    $conversationId = null;
}
$index = new Template(__DIR__ . '/../templates/index.tpl');
$content = new Template(__DIR__ . '/../templates/support_content.tpl');
$content->add('conversations', $conversation->parse());
$content->add('openConversations', $openConversation->parse());
$content->add('messages', $allMessages->parse());
$content->add('convId', $conversationId);
$index->add('content', $content->parse());
echo $index->parse();



