<?php 
session_start();
// // variables for queries
$firstName = "";
$lastName = "";
$email = "";
$epitaMail = "";
$popPeriod = "";
$status = "";
$year = "";
$program = "";

// // success and error messages
$errorMsg = array();
$successMsg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    // get values from form
    $firstName = $_POST['fname'];
    $lastName = $_POST['lname'];
    $email = $_POST['email'];
    $year = $_POST['year'];
    $program = $_POST['program'];
    $popPeriod = strtoupper($_POST['popPeriod']);
    $epitaMail = strtolower($firstName) . "." . strtolower($lastName) . "@epita.fr";
    $status = 'completed';
    $examType = 'Project'; 

    // check if values are empty
    do {
        if (empty($firstName) || empty($lastName) || empty($email) || empty($popPeriod)) {
            array_push($errorMsg ,"All fields are required");
            break;
        }

        // create new students (sql)
        require("dbConnection.php");

        // insert names into contacts
        $contact = $conn->query("INSERT INTO contacts (contact_email, contact_first_name, contact_last_name) 
                                    VALUES ('$email', '$firstName', '$lastName')");

        if (!$contact){
            array_push($errorMsg, "An error occured adding new student.");
        }


        // insert into students
        $students = $conn->query("INSERT INTO students (student_epita_email, student_contact_ref, student_enrollment_status, student_population_period_ref, student_population_year_ref, student_population_code_ref)
                                    VALUES ('$epitaMail', '$email', '$status', '$popPeriod', $year, '$program');");

        if (!$students){
            array_push($errorMsg, "An error occured adding new student.");
        }

        // get courses for this program
        $getCourses = "SELECT c.COURSE_CODE, c.COURSE_REV
                        FROM COURSES c 
                        JOIN PROGRAMS p ON c.COURSE_CODE = p.PROGRAM_COURSE_CODE_REF 
                        WHERE p.PROGRAM_ASSIGNMENT = '$program';";

        $result = $conn->query($getCourses);

        if (!$result){
            array_push($errorMsg, "An error occured adding new student.");
        }
        
        while ($row = $result->fetch_assoc()){
            $courseCode = $row['COURSE_CODE'];
            $courseRev = $row['COURSE_REV'];

            // insert values into grades table
            $insertGrades = $conn->query("INSERT INTO grades (grade_student_epita_email_ref, grade_course_code_ref, grade_course_rev_ref, grade_exam_type_ref, grade_score)
                                                            VALUES('$epitaMail', '$courseCode', $courseRev, '$examType', NULL)");

        }
        $_SESSION['message'] = "Student added successfully";
        echo "<script>history.go(-2)</script>";
        
    } while (false);
}
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../static/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
    <title>Add Student</title>
</head>
<body>

    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="../static/resources/logo-epita-en.png">  
                    <img src="../static/resources/logo-epita-en.png" alt="Bootstrap" width="30" height="24">Epita</a>
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
        <h2>New Student</h2>
    </div>

    <div class="containerForm my-5">
        <!-- show error messages -->
        <?php
        if (count($errorMsg)>0) {
            foreach ($errorMsg as  $error) {
                echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                        $error
                        <button onclick='history.back()' type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
            }
        }
        ?>
        <!-- show success message -->
        <?php
            if (!empty($successMsg)){
                echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                            $successMsg
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
            }
            ?>
        <?php
        // get year and program
        if (isset($_GET['year']) && isset($_GET['program'])) {
            $year = $_GET['year'];
            $program = $_GET['program'];
        }
        ?>
        <form action="addStudent.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="year" value="<?php echo $year; ?>" readonly>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="program" value="<?php echo $program; ?>" readonly>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="fname" placeholder="First Name:" value="<?php echo $firstName; ?>" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="lname" placeholder="Last Name:" value="<?php echo $lastName; ?>" required>
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email:" value="<?php echo $email; ?>" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="popPeriod" placeholder="Intake: Spring or Fall" value="<?php echo $popPeriod; ?>" required>
            </div>
            
            <div class="buttons">
                <div class="form-btn">
                    <input type="submit" class="btn btn-primary" value="Add Student" name="submit">
                </div>
                <div class="form-btn">
                    <a onclick="history.back()" class="btn btn-danger" >Cancel</a>
                </div>
            </div>
        </form>
    </div>
    <footer>
        <div class="date">
            <p><script>document.write(new Date())</script></p>
        </div>
    </footer>
</body>
</html>