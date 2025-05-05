<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>PHP search</title>
  <link rel="icon" type="image/png" href="fav.jpg">
  <style>
    * {
      box-sizing: border-box;
    }

   body {
  font-family: Arial, sans-serif;
  background: url('stc.jpg') no-repeat center center fixed;
  background-size: cover;
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100vh;
  margin: 0;
   }


    .container {
      width: 100%;
      max-width: 700px;
      background: #fff;
      padding: 30px 25px;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      color: #333;
      margin-bottom: 20px;
    }

    label {
      font-weight: bold;
      margin-top: 10px;
      display: block;
      color: #555;
    }

    input[type="date"],
    select,
    input[type="submit"] {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
    }

    input[type="submit"] {
      margin-top: 20px;
      background-color:rgb(20, 21, 21);
      color: white;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
    }

    input[type="submit"]:hover {
      background-color: #0056b3;
    }

    table {
      width: 100%;
      margin-top: 30px;
      border-collapse: collapse;
    }

    table, th, td {
      border: 1px solid #ccc;
    }

    th, td {
      padding: 10px;
      text-align: center;
      font-size: 14px;
    }

    p {
      margin-top: 20px;
      text-align: center;
      font-weight: bold;
      color: #555;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Search Bookings</h2>
  <form method="post">
    <label for="date">Appointment Date:</label>
    <input type="date" id="date" name="date" required>

    <label for="doctor">Select Doctor:</label>
    <select id="doctor" name="doctor" >
      <option value="">All Doctors</option>
      <option>Dr. Srivani(Dermotology)</option>
      <option>Dr. Akhila(Neurology)</option>
      <option>Dr. Aswini(Pathology)</option>
      <option>Dr. Kiran(Cardiology)</option>
      <option>Dr. Vinod(ENT)</option>
    </select>

    <input type="submit" value="Search">
  </form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $creds = file('jash.txt', FILE_IGNORE_NEW_LINES);
    $conn = new mysqli($creds[0], $creds[1], $creds[2], $creds[3]);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $date = $_POST['date'];
    $doctor = $_POST['doctor'];

    $query = "SELECT * FROM test1 WHERE 1=1";
    if (!empty($date)) {
        $query .= " AND date = '$date'";
    }
    if (!empty($doctor)) {
        $query .= " AND doctor = '$doctor'";
    }

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "<table><tr><th>Name</th><th>Phone</th><th>Age</th><th>Gender</th><th>Date</th><th>Blood</th><th>Doctor</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['name']}</td>
                    <td>{$row['phone']}</td>
                    <td>{$row['age']}</td>
                    <td>{$row['gender']}</td>
                    <td>{$row['date']}</td>
                    <td>{$row['blood']}</td>
                    <td>{$row['doctor']}</td>
                  </tr>";
        }
        echo "</table>";

        echo "<button onclick='window.print()' style='margin-top: 20px;'>Print Results</button>";

    } else {
        echo "<p>No bookings.</p>";
    }

    $conn->close();
}
?>
</div>
<!-- <script>
    
    const today = new Date().toISOString().split('T')[0];
  document.getElementById("date").setAttribute("min", today);
</script> -->
  </body>
</html>
