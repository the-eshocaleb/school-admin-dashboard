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
    <title>Search Courses</title>
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
        <h2>Search Existing Courses</h2>
    </div>
    <div class="search">
        <?php
        if (isset($_GET['year']) && isset($_GET['program'])) {
            $year = $_GET['year'];
            $program = $_GET['program'];
        }
        echo "<form action='searchCourses.php?year=$year&program=$program' method='post'>"
        ?>
            <input type="hidden" name="year" value="<?php echo $_GET['year']; ?>">
            <input type="hidden" name="program" value="<?php echo $_GET['program']; ?>">
            <input type='text' placeholder='Search courses' name='searchCourses' required>
            <input type="submit" class="btn btn-primary btn-sm" value="Search" name="submit">
            <button onclick="history.back()" class="btn btn-danger btn-sm" >Back</button>
        </form> 
    </div>
    <div class="container my-5">
        <?php
        require("dbConnection.php");
        if(isset($_POST['submit'])){
            $year = $_POST['year'];
            $program = $_POST['program'];
            $search = $_POST['searchCourses'];
            if(!empty($search)){
                $sql = "SELECT course_code as courseCode,
                                course_name as courseName, 
                                course_description as courseDescription, 
                                duration, 
                                course_rev as courseRev, 
                                course_last_rev as courseLastRev 
                                FROM courses
                                WHERE course_name like '%$search%'";

                $result = $conn->query($sql);
                if($result){
                    if(($result->num_rows) > 0){
                        echo "<table class='table'>".
                                    "<thead>".
                                        "<tr>".
                                            "<th>"."Course Code"."</th>".
                                            "<th>"."Course Name"."</th>".
                                            "<th>"."Course Description"."</th>".
                                            "<th>"."Duration"."</th>".
                                            "<th>"."Course Rev"."</th>".
                                            "<th>"."Course Last Rev"."</th>".
                                            "<th>"."Action"."</th>".
                                        "</tr>".
                                    "</thead>".
                                    "<tbody>";
                        while($row = $result->fetch_assoc()){
                            echo        
                                    // "<tbody>".
                                        "<tr>".
                                            "<td>". $row['courseCode']. "</td>".
                                            "<td>". $row['courseName']. "</td>".
                                            "<td>". $row['courseDescription']. "</td>".
                                            "<td>". $row['duration']. "</td>".
                                            "<td>". $row['courseRev']. "</td>".
                                            "<td>". $row['courseLastRev']. "</td>".
                                            "<td>". 
                                                "<a class='btn btn-primary btn-sm' href='../phpFiles/searchAddCourse.php?courseCode=". $row['courseCode'] ."&"."courseName=".$row['courseName']."&"."courseDescription=".$row['courseDescription']."&"."duration=".$row['duration']."&"."courseRev=".$row['courseRev']."&"."year="."$year"."&"."program="."$program"."'>Add</a>".                                             
                                            "</td>".
                                        "</tr>";
                        }
                        echo
                            "</tbody>".
                            "</table>";
                    }
                    else {
                        echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                No course with that name!
                                <button onclick='history.back()' type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>";
                    }
                        
                }
                else{
                    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            An error occured searching courses!
                            <button onclick='history.back()' type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
                        }
                    }
            else {
                echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            Course cannot be empty
                            <button onclick='history.back()' type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
            }
        }
        ?>
    </div>
    <footer>
        <div class="date">
            <p><script>document.write(new Date())</script></p>
        </div>
    </footer>
</body>
</html>
