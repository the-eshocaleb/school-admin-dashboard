<?php
session_start();
require("dbConnection.php");

// define variables to be used
$firstName = "";
$lastName = "";
$contactEmail = "";
$year = "";
$program = "";

if(isset($_GET['year']) && isset($_GET['program'])){
    $year = $_GET['year'];
    $program = $_GET['program'];
}

$errorMsg = array();
$successMsg = "";


// if page is loaded with get method
if ($_SERVER['REQUEST_METHOD'] == 'GET'){
    // GET method , show the student names
    if(isset($_GET['contactEmail']) && isset($_GET['year']) && isset($_GET['program'])){
        $contactEmail = $_GET['contactEmail'];
        $year = $_GET['year'];
        $program = $_GET['program'];
    }
    
    // query to show student data in the form to edit
    // $contactEmail = $_GET['contactEmail'];
    $sqlQuery = "SELECT * from contacts WHERE contacts.CONTACT_EMAIL = '$contactEmail'";
    $result = $conn->query($sqlQuery);
    $row = $result->fetch_assoc();
    
    if (!$row){
        header("Location: " . $_SERVER['HTTP_REFERER']);
        array_push($errorMsg, "An error occurred at contacts.");
        exit;
    }
    //  variables displayed in form 
    $firstName = $row['contact_first_name'];
    $lastName = $row['contact_last_name'];
 
}
else {
    // POST method, update database with query
    $contactEmail = $_POST["contactEmail"];
    $firstName = $_POST["fname"];
    $lastName = $_POST["lname"];
    $year = $_POST['year'];
    $program = $_POST['program'];
    $url = $program.'-'.$year.'.php';


    do {
        if ( empty($contactEmail) || empty($firstName) || empty($lastName)) {
            array_push($errorMsg, "All fields are required.");
            break;
        }
        $sql = "UPDATE contacts
                SET contacts.contact_first_name = '$firstName', contacts.contact_last_name = '$lastName'
                WHERE contacts.contact_email = '$contactEmail';";

        $result = $conn->query($sql);
        // check if query was executed without error
        if (!$result){
            array_push($errorMsg, "An error occurred updating contacts.");
            break;
        }
        
        $_SESSION['message'] = "Student name edited successfully";
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
        <h2>Edit Student Names</h2> 
    </div>

    <div class="containerForm my-5"> 
        <!-- get url arguments -->
        <?php
        if(isset($_GET['contactEmail']) && isset($_GET['year']) && isset($_GET['program'])){
            $contactEmail = $_GET['contactEmail'];
            $year = $_GET['year'];
            $program = $_GET['program'];
        }        
        ?>

        <!-- Show error message-->
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

        <form action="editStudent.php" method="post">
            <input type="hidden" class="form-control" name="contactEmail"  value="<?php echo $contactEmail; ?>" >
            <input type="hidden" class="form-control" name="year"  value="<?php echo $year; ?>" >
            <input type="hidden" class="form-control" name="program"  value="<?php echo $program; ?>" >
            <div class="form-group">
                <input type="text" class="form-control" name="fname" placeholder=" First Name:" value="<?php echo $firstName; ?>" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="lname" placeholder="Last Name:" value="<?php echo $lastName; ?>" required>
            </div>
            <div class="buttons">
                <div class="form-btn">
                    <input type="submit" class="btn btn-primary" value="Edit" name="submit">
                </div>
                <div class="form-btn">
                    <a onclick="history.back()" class="btn btn-danger">Cancel</a>
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