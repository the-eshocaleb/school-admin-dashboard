<?php
session_start();
// declare variables
$epitaMail = "";
$courseCode = "";
$courseRev = "";
$examType = "";
$grade = "";

// // success and error messages
$errorMsg = array();
$successMsg = "";

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $epitaMail = $_POST['epitaMail'];
    $courseCode = $_POST['courseCode'];
    $courseRev = $_POST['courseRev'];
    $examType = $_POST['examType'];
    $grade = $_POST['grade'];


    do {
        if (empty($epitaMail) || empty($courseCode) || empty($courseRev) || empty($examType) || empty($grade)) {
            array_push($errorMsg, "All fields are required.");
            break;
        }

        // update grade
        require("dbConnection.php");

        $grade = $conn->query("UPDATE grades
                                SET grades.grade_score = $grade
                                WHERE grades.grade_student_epita_email_ref = '$epitaMail'
                                AND grades.grade_course_code_ref = '$courseCode'
                                AND grades.grade_course_rev_ref = $courseRev
                                AND grades.grade_exam_type_ref = '$examType';");

        if (!$grade){
            array_push($errorMsg, "An error occured at grades");
            break;
        }

        // success message
        $_SESSION['message'] = "Student grade edited successfully";
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
    <title>Edit Student</title>
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
        <h2>Edit Student Grade</h2>
    </div>

    <div class="containerForm my-5">
        <!-- get url variables -->
        <?php
        if(isset($_GET['epitaMail']) && isset($_GET['courseCode']) && isset($_GET['courseRev']) && isset($_GET['examType'])){
            $epitaMail = $_GET['epitaMail'];
            $courseCode = $_GET['courseCode'];
            $courseRev = $_GET['courseRev'];
            $examType = $_GET['examType'];
        }        
        ?>
        
        <!-- Error and Success Messages -->
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
        
        <form action="editGrades.php" method="post">
            <input type="hidden" name="courseRev" value="<?php echo $courseRev; ?>">
            <input type="hidden" name="examType" value="<?php echo $examType; ?>">
            <div class="form-group">
                <input type="text" class="form-control" name="epitaMail" placeholder=" Epita Mail" value="<?php echo $epitaMail; ?>" readonly>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="courseCode" placeholder="Course Code:" value="<?php echo $courseCode; ?>" readonly>
            </div>
            <div class="form-group">
                <input type="number" class="form-control" name="grade" placeholder="Student grade:" required>
            </div>
            <div class="buttons">
                <div class="form-btn">
                    <input type="submit" class="btn btn-primary" value="Edit" name="submit">
                </div>
                <div class="form-btn">
                    <a onclick='history.back()' class="btn btn-danger">Cancel</a>
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