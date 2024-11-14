<?php
error_reporting(0);
session_start();
if (!isset($_SESSION["user"])){
    header("Location: ../login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="../static/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Lora&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
    <title>Students</title>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="../welcome.php">  
                    <img src="../static/resources/logo-epita-en.png" alt="Epita" width="30" height="24">Epita</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../welcome.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://www.epita.fr/en/homepage/">Epita</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Logout</a>
                    </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="population">
        <h2>DSA-2020</h2>
    </div>
    
    <div class="container my-5">
        <div class="students">
            <h2>Students</h2>
            <!-- get url arguments -->
            <?php
            $year = $_GET['year'];
            $program = $_GET['program'];

            echo "<a class='btn btn-primary' href='../phpFiles/addStudent.php?year=$year&program=$program' role='button'>Add</a>
                <a class='btn btn-primary' href='../phpFiles/searchStudent.php?year=$year&program=$program' role='button'>Search</a>";
            ?>
        </div>
        <!-- show confirmation message -->
        <?php
            if ($_SESSION['message']) {
                $successMsg = $_SESSION['message']; // Assuming $_SESSION['message'] contains the success message
                echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                        $successMsg
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
            }
            unset($_SESSION['message']);
        ?>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Passed</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php
                require("../phpFiles/dbConnection.php");
                require("../queries/students.php");
                
                $year = $_GET['year'];
                $program = $_GET['program'];

                $results = students($conn, $year, $program);
                makeStudentTable($results);
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>   


    

    <div class="container my-5">
        <div class="courses">
            <h2>Courses</h2>
            <?php
            $year = $_GET['year'];
            $program = $_GET['program'];

            echo "<a class='btn btn-primary' href='../phpFiles/addCourses.php?year=$year&program=$program' role='button'>Add</a>
                <a class='btn btn-primary' href='../phpFiles/searchCourses.php?year=$year&program=$program' role='button'>Search</a>";
            ?>
        </div>

        <?php
            if ($_SESSION['message']) {
                $successMsg = $_SESSION['message']; // Assuming $_SESSION['message'] contains the success message
                echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                        $successMsg
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
            }
            unset($_SESSION['message']);
        ?>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Course Code</th>
                    <th>Course Name</th>
                    <th>Sessions</th>
                </tr>
            </thead>

            <tbody>
                <?php
                require("../phpFiles/dbConnection.php");
                require("../queries/courses.php");
                
                $year = $_GET['year'];
                $program = $_GET['program'];

                $results = courses($conn, $year, $program);
                makeCoursesTable($results, $program, $year);
                $conn->close();
                ?>
            </tbody>

        </table>
    </div>

    <footer>
        <div class="date">
            <p><script>document.write(new Date())</script></p>
        </div>
    </footer>
</body>
</html>