<?php


$creds = file('jash.txt', FILE_IGNORE_NEW_LINES);


if (count($creds) < 4) {
    die("Error: credentials.txt must have 4 lines (host, user, password, database).");
}


$servername = $creds[0];
$username   = $creds[1];
$password   = $creds[2]; 
$dbname     = $creds[3];


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$name   = $_POST['name'];
$phone  = $_POST['phone'];
$age    = $_POST['age'];
$gender = $_POST['gender'];
$date   = $_POST['date'];
$blood  = $_POST['blood'];
$doctor = $_POST['doctor'];


$sql = "INSERT INTO data(name, phone, age, gender, date, blood, doctor)
        VALUES ('$name', '$phone', $age, '$gender', '$date', '$blood', '$doctor')";

if ($conn->query($sql) === TRUE) {
    echo "<h2>Booking successful!</h2>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
