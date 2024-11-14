<?php
// declare variables
$courseCode = "";
$courseRev = "";
$duration = "";
$courseName = "";
$courseDescription = "";
$year = "";
$program = "";
$numOfSession = "";
$sessionDate = "";
$sessionStartTime = "";
$sessionEndTime = "";

// // success and error messages
$errorMsg = array();
$successMsg = "";

// get form data

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    // get values from form
    $courseCode = $_POST['courseCode'];
    $courseRev = $_POST['courseRev'];
    $duration = $_POST['duration'];
    $program = $_POST['program'];
    $year = $_POST['year'];
    $courseLastRev = $_POST['year'];
    $courseName = $_POST['courseName'];
    $courseDescription = $_POST['courseDescription'];
    $numOfSession = $_POST['numOfSession'];
    $sessionDate = $_POST['sessionDate'];
    $sessionStartTime = $_POST['sessionStartTime'];
    $sessionEndTime = $_POST['sessionEndTime'];

    // check if fields are empty
    do {
        if (empty($courseCode) || empty($courseRev) || empty($duration) || empty($program) || empty($courseLastRev) || empty($courseName) || empty($courseDescription) 
            || empty($numOfSession) || empty($sessionDate) || empty($sessionStartTime) || empty($sessionEndTime)) {
            array_push($errorMsg ,"All fields are required");
            break;
        }

        // database connection
        require("dbConnection.php");

        // insert course into courses
        $courses = $conn->query("INSERT INTO courses (course_code, course_rev, duration, course_last_rev, course_name, course_description) 
                                VALUES ('$courseCode', $courseRev, $duration, $courseLastRev, '$courseName', '$courseDescription')");

        if (!$courses){
            array_push($errorMsg, "An error occured at courses");
            break;
        }

        // insert into programs
        $programs = $conn->query("INSERT INTO programs (program_course_code_ref, program_course_rev_ref, program_assignment) 
                                    VALUES ('$courseCode', $courseRev, '$program')");
        
        if (!$programs){
            array_push($errorMsg, "An error occured at programs");
            break;
        }

        // insert into sessions

        $originalDate = $sessionDate;

        // insert first session
        $firstSession = $conn->query("INSERT INTO sessions (session_course_ref, session_course_rev_ref, session_date, session_start_time, session_end_time, session_population_year) 
                                    VALUES ('$courseCode', $courseRev, '$originalDate', '$sessionStartTime', '$sessionEndTime', '$year')");

        if (!$firstSession){
            array_push($errorMsg, "An error occured at first session");
            break;
        }

        // increment the date by the number of sessions
        for ($num = 1; $num < $numOfSession; $num++){
            $tempDate = strtotime("+1 day", strtotime("$originalDate"));
            $newDate = date("Y-m-d", $tempDate);
            $sessions = $conn->query("INSERT INTO sessions (session_course_ref, session_course_rev_ref, session_date, session_start_time, session_end_time, session_population_year) 
                                    VALUES ('$courseCode', $courseRev, '$newDate', '$sessionStartTime', '$sessionEndTime', $year)");
            
            $originalDate = $newDate;

            if (!$sessions){
                array_push($errorMsg, "An error occured at sessions");
                break;
            }

        }

        // set course grade to null for all students
        // get student epita mails
        $students = $conn->query("SELECT student_epita_email FROM students 
                                    WHERE student_population_year_ref = $year AND student_population_code_ref = '$program' ");

        if (!$students){
            array_push($errorMsg, "An error occured at students");
            break;
        }

        while ($row = $students->fetch_assoc()){
            $epitaMail = $row['student_epita_email'];

            $insertGrades = $conn->query("INSERT INTO grades (grade_student_epita_email_ref, grade_course_code_ref, grade_course_rev_ref, grade_exam_type_ref, grade_score)
                                                            VALUES('$epitaMail', '$courseCode', $courseRev, 'Project', NULL)");

        if (!$insertGrades){
            array_push($errorMsg, "An error occured at grades");
            break;
            }
        }

        $_SESSION['message'] = "Course added successfully";  
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
    <title>Add Course</title>
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
        <h2>New Course</h2>
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
            // if (!empty($successMsg)){
            //     echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
            //                 $successMsg
            //                 <button onclick='history.back()' type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            //             </div>";
            // }
        ?>

        <!-- get year and program -->
        <?php
        if (isset($_GET['year']) && isset($_GET['program'])) {
            $year = $_GET['year'];
            $program = $_GET['program'];
        }
        ?>
        
        <form action="addCourses.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="year" value="<?php echo $year; ?>" readonly>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="program" value="<?php echo $program; ?>" readonly>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="courseCode" placeholder="Course Code: COURSE_CODE" required>
            </div>
            <div class="form-group">
                <input type="number" class="form-control" name="courseRev" placeholder="Course Rev: 1 - 3" required>
            </div>
            <div class="form-group">
                <input type="number" class="form-control" name="duration" placeholder="Duration (Hours): " required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="courseName" placeholder="Course Name:" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="courseDescription" placeholder="Course Description: " required>
            </div>
            <div class="form-group">
                <input type="number" class="form-control" name="numOfSession" placeholder="Number of sessions:" required>
            </div>
            <div class="form-group">
                <input type="date" class="form-control" name="sessionDate" placeholder="Session date: " required>
            </div>
            <div class="form-group">
                Start time:
                <input type="time" class="form-control" name="sessionStartTime" placeholder="Session Start Time: " required>
            </div>
            <div class="form-group">
                End time:
                <input type="time" class="form-control" name="sessionEndTime" placeholder="Session End Time: " required>
            </div>
            <div class="buttons">
                <div class="form-btn">
                    <input type="submit" class="btn btn-primary" value="Add Course" name="submit">
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