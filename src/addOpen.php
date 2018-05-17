<?php
$conn=mysqli_connect("localhost","root","coderslab","bok");

if (mysqli_connect_errno())
{
    echo "Cannot connect to database " . mysqli_connect_error();
}
$conversationId = $_POST['convId'];
$supportId = $_POST['supportId'];
$query = "UPDATE Conversations SET supportId = $supportId WHERE id = $conversationId";
$result = mysqli_query($conn, $query) or die(mysqli_query());