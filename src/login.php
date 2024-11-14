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
    <title>Login</title>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="../static/resources/logo-epita-en.png">  
                    <img src="static/resources/logo-epita-en.png" alt="Bootstrap" width="30" height="24">Epita</a>
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
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    
    <div class="containerForm my-5">
        <?php
        $email = "";
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $email = $_POST['email'];

            require "phpFiles/dbConnection.php";
            $sql = "SELECT * FROM users WHERE email='$email'";

            $result = $conn->query($sql);
            $user = $result->fetch_assoc();
            if ($user){
                if (password_verify($_POST['password'],  $user['userPassword'])){
                    session_start();
                    $_SESSION["user"] = $user['firstname'];
                    header("Location: welcome.php");
                    die();
                } else {
                    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                Password does not match
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>";
                }
            }else {
                echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                Email does not match
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>";
            }


        }
        ?>


        <h1>Login</h1>
        <!-- action was login.php -->
        <form action="login.php" method="post">
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email:" value="<?php echo $email; ?>" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password:" required>
            </div>
            <p>Don't have an account? <a href="register.php">Sign Up</a></p>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Login" name="login">
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