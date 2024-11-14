<?php
session_start();
if (!isset($_SESSION["user"])){
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
      <link rel="stylesheet" href="./static/styles.css">
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Lora&display=swap" rel="stylesheet">
      <title>Welcome Page</title>
  </head>
  <body>
      <header>
          <nav class="navbar navbar-expand-lg bg-body-tertiary">
              <div class="container-fluid">
                  <a class="navbar-brand" href="../static/resources/logo-epita-en.png">  
                      <img src="../src/static/resources/logo-epita-en.png" alt="Epita" width="30" height="24">Epita</a>
                  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                      <span class="navbar-toggler-icon"></span>
                  </button>
                  <div class="collapse navbar-collapse" id="navbarNav">
                      <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="#">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="https://www.epita.fr/en/homepage/">Epita</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="logout.php">Logout</a>
                            </li>
                      </ul>
                  </div>
              </div>
          </nav>
      </header>

      <div class="population">
        <?php
        $loggedUser = $_SESSION["user"];
        echo "<h1>Welcome, $loggedUser</h1>";
        ?>
      </div>
    
        <div class="populationBody">

            <div class="container my-5 chart">
                <div class="populationsList">
                    <h2>Populations</h2>
                    <?php
                    require('./phpFiles/dbConnection.php');
                    require("./queries/populations.php");
                    $result = population($conn);
                    makePopulationList($result);
                    $conn->close();  //closing the connection
                    ?>
                </div>

                <div class="popchart">
                    <canvas id="popChart"></canvas>
                </div>

            </div>

            <div class="container my-5 chart">
                <div class="attendanceList">
                    <h2>Attendance</h2>
                    <?php
                    require('./phpFiles/dbConnection.php');
                    require("./queries/attendance.php");
                    $result = attendance($conn);
                    makeAttendanceList($result);
                    $conn->close();  //closing the connection
                    ?>
                </div>

                <div class="attchart">
                    <canvas id="attChart"></canvas>
                </div>
            </div>
        </div>

    <footer>
        <div class="date">
            <p><script>document.write(new Date())</script></p>
        </div>
    </footer>

    <script>
        <?php 
        require('./phpFiles/dbConnection.php');
        $result = population($conn); 
        $conn->close();
        $phplabel = labelArray($result);
        ?>

        <?php 
        require('./phpFiles/dbConnection.php');
        $result2 = population($conn); 
        $conn->close();
        $data = dataArray($result2);
        ?>

        const pop = document.getElementById('popChart');
        const att = document.getElementById('attChart');

        new Chart(pop, {
            type: 'doughnut',
            data: {
            labels: [<?php echo "'" . implode("','", $phplabel) . "'"; ?>],
            datasets: [{
                label: 'Populations',
                data: [<?php echo "'". implode("','", $data) . "'"; ?>],
                borderWidth: 1
            }]
            },
            options: {
            scales: {
                y: {
                beginAtZero: true
                }
            }
            }
        });

        new Chart(att, {
            type: 'bar',
            data: {
            labels: ['ISM','SE','CS','DSA','AIs'],
            datasets: [{
                label: 'Attendance',
                data: [69, 70, 65, 72, 74],
                borderWidth: 1
            }]
            },
            options: {
            scales: {
                y: {
                beginAtZero: true
                }
            }
            }
        });
    </script>

  </body>
</html>