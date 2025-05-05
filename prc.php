<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Search Bookings</title>
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
      max-width: 900px;
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
      background-color: rgb(20, 21, 21);
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

    .print-button, .action-btn {
      padding: 6px 12px;
      margin-top: 10px;
      background-color: #333;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .print-button:hover, .action-btn:hover {
      background-color: #0056b3;
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
    <select id="doctor" name="doctor">
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
          echo "<div id='result-section'>";
          echo "<h2>OP Results</h2>";
          echo "<table id='results-table'>
                  <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Date</th>
                    <th>Blood</th>
                    <th>Doctor</th>
                    <th>Action</th>
                  </tr>";
          while($row = $result->fetch_assoc()) {
              $data = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
              echo "<tr>
                      <td>{$row['name']}</td>
                      <td>{$row['phone']}</td>
                      <td>{$row['age']}</td>
                      <td>{$row['gender']}</td>
                      <td>{$row['date']}</td>
                      <td>{$row['blood']}</td>
                      <td>{$row['doctor']}</td>
                      <td><button class='action-btn single-print' data-booking='$data'>Print</button></td>
                    </tr>";
          }
          echo "</table>";
          echo "<button class='print-button' onclick='printResults()'>Print All</button>";
          echo "</div>";
      } else {
          echo "<p>No bookings found.</p>";
      }

      $conn->close();
  }
  ?>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  window.printResults = function () {
    const rows = document.querySelectorAll('#results-table tr');
    let printContent = '<h2 style="text-align:center;">All Bookings</h2>';

    for (let i = 1; i < rows.length; i++) {
      const cells = rows[i].querySelectorAll('td');
      printContent += `
        <div style="margin: 30px auto; padding: 20px; border: 1px solid #ccc; width: 60%; font-size: 16px; line-height: 1.6;">
          <strong>Name:</strong> ${cells[0].innerText}<br>
          <strong>Phone:</strong> ${cells[1].innerText}<br>
          <strong>Age:</strong> ${cells[2].innerText}<br>
          <strong>Gender:</strong> ${cells[3].innerText}<br>
          <strong>Date:</strong> ${cells[4].innerText}<br>
          <strong>Blood:</strong> ${cells[5].innerText}<br>
          <strong>Doctor:</strong> ${cells[6].innerText}
        </div>
      `;
    }
    openPrintWindow(printContent);
  };

  function openPrintWindow(content) {
    const iframe = document.createElement('iframe');
    iframe.style.position = 'fixed';
    iframe.style.right = '0';
    iframe.style.bottom = '0';
    iframe.style.width = '0';
    iframe.style.height = '0';
    iframe.style.border = 'none';
    document.body.appendChild(iframe);

    const doc = iframe.contentWindow.document;
    doc.open();
    doc.write(`
      <html><head><title>Print Op</title>
      <style>
        body { font-family: Arial, sans-serif; padding: 30px; text-align: center; }
        h2 { margin-bottom: 40px; }
        div { margin: auto; width: 60%; border: 1px solid #ccc; padding: 20px; text-align: left; }
      </style></head><body>${content}</body></html>
    `);
    doc.close();

    iframe.onload = () => {
      iframe.contentWindow.focus();
      iframe.contentWindow.print();
      setTimeout(() => document.body.removeChild(iframe), 1000);
    };
  }

  document.querySelectorAll('.single-print').forEach(button => {
    button.addEventListener('click', function () {
      const data = JSON.parse(this.dataset.booking);
      const content = `
        <h2 style="text-align:center;">Booking Details</h2>
        <div style="margin: auto; width: 60%; border: 1px solid #ccc; padding: 20px; text-align: left;">
          <strong>Name:</strong> ${data.name}<br>
          <strong>Phone:</strong> ${data.phone}<br>
          <strong>Age:</strong> ${data.age}<br>
          <strong>Gender:</strong> ${data.gender}<br>
          <strong>Date:</strong> ${data.date}<br>
          <strong>Blood:</strong> ${data.blood}<br>
          <strong>Doctor:</strong> ${data.doctor}
        </div>
      `;
      openPrintWindow(content);
    });
  });
});
</script>
</body>
</html>