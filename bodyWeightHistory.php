<?php
// Include necessary files and establish database connection
require("connect-db.php");
require("database-functions.php");

// Initialize variables
$start_date = date("Y-m-d", strtotime("-1 month")); // Default start date is 1 month before today
$end_date = date("Y-m-d");
$weights = array();
$username = isset($_GET['username']) ? $_GET['username'] : "";

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_date = $_POST["start_date"];
    $end_date = $_POST["end_date"];

    // Retain the username information in the URL
    $username = $_POST["username"];

    // Query database for weights between start and end dates
    $query = "SELECT date, weight FROM Body_Weight_History WHERE date BETWEEN :start_date AND :end_date AND user_id = :username";
    $statement = $db->prepare($query);
    $statement->bindValue(':start_date', $start_date);
    $statement->bindValue(':end_date', $end_date);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $weights = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor();
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Body Weight History</title>
  <!-- Include necessary libraries for chart -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <h1>Body Weight History</h1>
    <form id="dateRangeForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <!-- Retain the username information in a hidden input field -->
      <input type="hidden" name="username" value="<?php echo $username; ?>">
      <label for="start_date">Start Date:</label>
      <input type="date" name="start_date" value="<?php echo $start_date; ?>" required>
      <br>
      <label for="end_date">End Date:</label>
      <input type="date" name="end_date" value="<?php echo $end_date; ?>" required>
      <br>
      <input type="submit" value="Submit" class="btn">
    </form>

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($weights)) : ?>
      <!-- Display chart if weights are available -->
      <canvas id="weightChart" width="800" height="400"></canvas>
      <script>
        // Extract dates and weights from PHP array
        var dates = <?php echo json_encode(array_column($weights, 'date')); ?>;
        var weights = <?php echo json_encode(array_column($weights, 'weight')); ?>;

        // Create chart
        var ctx = document.getElementById('weightChart').getContext('2d');
        var myChart = new Chart(ctx, {
          type: 'line',
          data: {
            labels: dates,
            datasets: [{
              label: 'Weight',
              data: weights,
              backgroundColor: 'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgba(54, 162, 235, 1)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              x: {
                type: 'time',
                time: {
                  unit: 'day',
                  displayFormats: {
                    day: 'MMM D'
                  }
                },
                title: {
                  display: true,
                  text: 'Date'
                }
              },
              y: {
                title: {
                  display: true,
                  text: 'Weight (lbs)'
                }
              }
            }
          }
        });
      </script>
    <?php endif; ?>
  </div>
</body>
</html>
