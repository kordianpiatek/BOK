<?php

class Users
{
    private $id;
    private $login;
    private $password;
    private $role;

    public function __construct()
    {
        $this->id = -1;
        $this->login = '';
        $this->password = '';
        $this->role = '';
    }

    public function getId()
    {
        return $this->id;
    }

    public function setLogin($login)
    {
        $this->login = $login;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function setPassword($newPassword)
    {
        $newHashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $this->password = $newHashedPassword;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function saveToDB(PDO $conn)
    {
        if ($this->id == -1) {
            $sql = 'INSERT INTO Users(login, hashPass, role) VALUES(:login, :password, :role)';
            $stmt = $conn->prepare($sql);
            $result = $stmt->execute(['login' => $this->login, 'password' => $this->password, 'role' => $this->role]);
            if ($result !== false) {
                $this->id = $conn->lastInsertId();

                return true;
            }
        } else {
            $stmt = $conn->prepare('UPDATE Users SET login=:login, hashPass=:password, role=:role WHERE  id=:id ');
            $result = $stmt->execute([
                'login' => $this->login,
                'password' => $this->password,
                'id' => $this->id,
                'role' => $this->role
            ]);
            if ($result === true) {
                return true;
            }
        }

        return false;
    }

    static public function loadUserById(PDO $conn, $id)
    {
        $stmt = $conn->prepare('SELECT * FROM Users WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);
        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedUser = new Users();
            $loadedUser->id = $row['id'];
            $loadedUser->login = $row['login'];
            $loadedUser->password = $row['hashPass'];
            $loadedUser->role = $row['role'];

            return $loadedUser;
        }

        return null;
    }

    static public function loadUserByLogin(PDO $conn, $login)
    {
        $stmt = $conn->prepare('SELECT * FROM Users WHERE login=:login');
        $result = $stmt->execute(['login' => $login]);
        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedUser = new Users();
            $loadedUser->id = $row['id'];
            $loadedUser->login = $row['login'];
            $loadedUser->password = $row['hashPass'];
            $loadedUser->role = $row['role'];

            return $loadedUser;
        }

        return null;
    }

}