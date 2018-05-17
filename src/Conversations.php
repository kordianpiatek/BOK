<?php
class Conversations
{
    private $id;
    private $clientId;
    private $supportId;
    private $subject;

    public function getId()
    {
        return $this->id;
    }

    public function getClientId()
    {
        return $this->clientId;
    }

    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    public function getSupportId()
    {
        return $this->supportId;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function saveToDB(PDO $conn)
    {
        $sql = 'INSERT INTO Conversations(clientId, subject) VALUES(:clientId, :subject)';
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute(['clientId' => $this->clientId, 'subject' => $this->subject]);
        if ($result !== false) {
            $this->id = $conn->lastInsertId();
            return true;
        }
        return false;
    }

    static public function loadConversationById(PDO $conn, $id)
    {
        $stmt = $conn->prepare('SELECT * FROM Conversations WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);
        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedConversation = new Conversations();
            $loadedConversation->id = $row['id'];
            $loadedConversation->clientId = $row['clientId'];
            $loadedConversation->supportId = $row['supportId'];
            $loadedConversation->subject = $row['subject'];
            return $loadedConversation;
        }
        return null;
    }

    static public function loadAllConversation(PDO $conn)
    {
        $r = [];
        $sql = "SELECT * FROM Conversations";
        $result = $conn->query($sql);
        if ($result !== false && $result->rowCount() > 0) {
            foreach ($result as $row) {
                $loadedConversation = new Conversations();
                $loadedConversation->id = $row['id'];
                $loadedConversation->clientId = $row['clientId'];
                $loadedConversation->supportId = $row['supportId'];
                $loadedConversation->subject = $row['subject'];
                $r[] = $loadedConversation;
            }
        }
        return $r;
    }
    static public function loadConversationsByClientId(PDO $conn, $clientId)
    {
        $r = [];
        $stmt = $conn->prepare('SELECT * FROM Conversations WHERE clientId=:clientId ORDER BY id DESC');
        $result = $stmt->execute(['clientId' => $clientId]);
        if ($result !== false && $stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $loadedConversation = new Conversations();
                $loadedConversation->id = $row['id'];
                $loadedConversation->clientId = $row['clientId'];
                $loadedConversation->supportId = $row['supportId'];
                $loadedConversation->subject = $row['subject'];
                $r[] = $loadedConversation;
            }
            return $r;
        }
        return null;
    }
    static public function loadConversationsBySupportId(PDO $conn, $supportId)
    {
        $r = [];
        $stmt = $conn->prepare('SELECT * FROM Conversations WHERE supportId=:supportId ORDER BY id DESC');
        $result = $stmt->execute(['supportId' => $supportId]);
        if ($result !== false && $stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $loadedConversation = new Conversations();
                $loadedConversation->id = $row['id'];
                $loadedConversation->clientId = $row['clientId'];
                $loadedConversation->supportId = $row['supportId'];
                $loadedConversation->subject = $row['subject'];
                $r[] = $loadedConversation;
            }
            return $r;
        }
        return null;
    }
    static public function loadOpenConversations(PDO $conn)
    {
        $r = [];
        $result = $conn->query('SELECT * FROM Conversations WHERE supportId IS NULL ORDER BY id ASC');
        if ($result !== false && $result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $loadedConversation = new Conversations();
                $loadedConversation->id = $row['id'];
                $loadedConversation->clientId = $row['clientId'];
                $loadedConversation->supportId = $row['supportId'];
                $loadedConversation->subject = $row['subject'];
                $r[] = $loadedConversation;
            }
            return $r;
        }
        return null;
    }

}