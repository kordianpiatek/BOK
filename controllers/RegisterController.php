<?php

require __DIR__ . '/../src/Template.php';
require __DIR__ . '/../src/Users.php';
require __DIR__ . '/../src/Database.php';

session_start();
$database = Database::getInstance();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login']) and isset($_POST['password']) and isset($_POST['role'])) {
        $login = $_POST['login'];
        $password = $_POST['password'];
        $role = $_POST['role'];
        $user = Users::loadUserByLogin($conn, $login);
        if($user){
            echo "User exists";
        } elseif (!$login or !$password or !$role) {
            echo 'Empty data';
        } else {
            $user = new Users();
            $user->setLogin($login);
            $user->setPassword($password);
            $user->setRole($role);
            $user->saveToDB($conn);
            header("Location: LoginController.php");
        }
    }
}

$index = new Template(__DIR__ . '/../templates/index.tpl');
$content = new Template(__DIR__ . '/../templates/register.tpl');
$index->add('content', $content->parse());
echo $index->parse();

