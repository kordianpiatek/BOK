<?php

class Messages
{
    private $id;
    private $conversationId;
    private $senderId;
    private $text;

    public function getId()
    {
        return $this->id;
    }

    public function getConversationId()
    {
        return $this->conversationId;
    }

    public function setConversationId($conversationId)
    {
        $this->conversationId = $conversationId;
    }

    public function getSenderId()
    {
        return $this->senderId;
    }

    public function setSenderId($senderId)
    {
        $this->senderId = $senderId;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function saveToDB(PDO $conn)
    {
        $sql = 'INSERT INTO Messages(conversationId, senderId, text) VALUES(:conversationId, :senderId, :text)';
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute(['conversationId' => $this->conversationId, 'senderId' => $this->senderId, 'text' => $this->text]);
        if ($result !== false) {
            $this->id = $conn->lastInsertId();
            return true;
        }
        return false;
    }

    static public function loadMessagesByConversationId(PDO $conn, $conversationId)
    {
        $r = [];
        $sql = "SELECT * FROM Messages WHERE conversationId=$conversationId";
        $result = $conn->query($sql);
        if ($result !== false && $result->rowCount() > 0) {
            foreach ($result as $row) {
                $loadedMessage = new Messages();
                $loadedMessage->id = $row['id'];
                $loadedMessage->conversationId = $row['conversationId'];
                $loadedMessage->senderId = $row['senderId'];
                $loadedMessage->text = $row['text'];
                $r[] = $loadedMessage;
            }
        }
        return $r;
    }


}