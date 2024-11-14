<?php
session_start();
if (isset($_SESSION["user"])){
    header("Location: welcome.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./static/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
    <title>Sign Up</title>
</head>
<body>
    <div class="population">
        <h2>Register</h2>
    </div>
    <div class="containerForm my-5">
        <?php
        // variables to display on form
        $firstName = "";
        $lastName = "";
        $email = "";
        $password = "";
        $passwordRepeat = "";

        // check if form is submitted
        if (isset($_POST["submit"])) {
            $firstName = $_POST["fname"];
            $lastName = $_POST["lname"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $passwordRepeat = $_POST["confirmPassword"];
            $passwordHash = password_hash($_POST["password"], PASSWORD_DEFAULT);

            // error tracking
            $errors = array();

            // if empty
            if (empty($firstName)OR empty($lastName) OR empty($email) OR empty($password) OR empty($passwordRepeat)) {
                array_push($errors,"All fields are required");
               }
               if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Email is not valid");
               }
               if (strlen($password) < 4) {
                array_push($errors,"Password must be at least 4 characters long");
               }
               if ($password !== $passwordRepeat) {
                array_push($errors,"Password does not match");
               }
            //    check if email is already taken
               require("./phpFiles/dbConnection.php");
               $sqlemail = "SELECT * FROM users WHERE email = '$email'";
               $result = $conn->query($sqlemail);
               $rowCount = $result->num_rows;
               if ($rowCount>0) {
                array_push($errors,"Email is taken!");
               }
            //    if there is an error , display it
               if (count($errors)>0) {
                    foreach ($errors as  $error) {
                        echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                $error
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>";
                    }
                }else{
                    // insert form data using prepared statements to avoid sql injection attacks
                    // avoid sql injection
                    $sql = "INSERT INTO users (firstname, lastname, email, userPassword) 
                            VALUES (?, ?, ?, ?)";

                    // initialize statement variable
                    $stmt = $conn->stmt_init();
                    $stmt->prepare($sql);

                    // error catching
                    if (! $stmt->prepare($sql)){
                        die("SQL Error");
                    }

                    // bind parameters 
                    $stmt->bind_param("ssss", $_POST["fname"], $_POST["lname"], $_POST["email"], $passwordHash);
                    
                    // execute statement
                    $result = $stmt->execute();

                    // error catching
                    if ($result){
                        echo "<div class='alert alert-success'>You are registered successfully.</div>";
                        echo("<script>window.location = 'login.php';</script>");
                    } else {
                        echo "<div class='alert alert-danger'>An error occured when registering you!</div>";
                    }
                }
        }
        ?>
        
        <form action="register.php" method="post">
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
                <input type="password" class="form-control" name="password" placeholder="Password:" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="confirmPassword" placeholder="Confirm Password:" required>
            </div>
            <p>Have an account? <a href="login.php">Login</a></p>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Register" name="submit">
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