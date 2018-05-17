<?php

require __DIR__ . '/../src/Template.php';
require __DIR__ . '/../src/Database.php';
require __DIR__ . '/../src/Users.php';

session_start();
$database = Database::getInstance();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        unset($_SESSION['user']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login']) && isset($_POST['password'])) {
        $login = $_POST['login'];
        $password = $_POST['password'];
        $user = Users::loadUserByLogin($conn, $login);
        if (isset($user) && password_verify($password, $user->getPassword()) == $password) {
            $_SESSION['user'] = $user->getId();
            $_SESSION['role'] = $user->getRole();
            if($_SESSION['role'] == 'support'){
                header("Location: SupportController.php");
            }elseif($_SESSION['role'] == 'client'){
                header("Location: ClientController.php");
            }
        } else {
            echo 'Login Failed';
        }
    }
}

$index = new Template(__DIR__ . '/../templates/index.tpl');
$content = new Template(__DIR__ . '/../templates/login.tpl');
$index->add('content', $content->parse());
echo $index->parse();