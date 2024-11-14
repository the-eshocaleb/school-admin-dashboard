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
    <title>Search student</title>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="../static/resources/logo-epita-en.png">  
                    <img src="../static/resources/logo-epita-en.png" alt="Epita" width="30" height="24">Epita</a>
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
        <h2>Search Students</h2>
    </div>

    <div class="search">
        <form action='searchStudent.php' method='post'>
            <input type='search' placeholder='Search ...' name='searchStudent' required>
            <button class="btn btn-primary btn-sm" name='submit'>Search</button>
            <button onclick="history.back()" class="btn btn-danger btn-sm" >Back</button>
        </form>
    </div> 

    <div class="container my-5">
        <?php
        require("dbConnection.php");
        if(isset($_POST['submit'])){
            $search = $_POST['searchStudent'];
            if(!empty($search)){
                $sql = "SELECT c.CONTACT_FIRST_NAME as FIRSTNAME, c.CONTACT_LAST_NAME as LASTNAME, studentMail as EPITAMAIL, s.STUDENT_POPULATION_CODE_REF as PROGRAM, s.STUDENT_POPULATION_YEAR_REF as YEAR, c.CONTACT_EMAIL as EMAIL, concat(SUM(totalGrade), '/' , COUNT(totalGrade)) AS PASSED
                FROM 
                    ( SELECT g.GRADE_STUDENT_EPITA_EMAIL_REF AS studentMail, g.GRADE_COURSE_CODE_REF, (CASE WHEN (ROUND(SUM(g.GRADE_SCORE * e.EXAM_WEIGHT) / SUM(e.EXAM_WEIGHT)) >= 10) THEN 1 ELSE 0 END) AS totalGrade 
                    FROM GRADES g JOIN EXAMS e ON g.GRADE_COURSE_CODE_REF = e.EXAM_COURSE_CODE 
                    AND g.GRADE_COURSE_REV_REF = e.EXAM_COURSE_REV 
                    AND g.GRADE_EXAM_TYPE_REF = e.EXAM_TYPE 
                    GROUP BY studentMail, g.GRADE_COURSE_CODE_REF ) AS subquery 
                JOIN STUDENTS s ON studentMail = s.STUDENT_EPITA_EMAIL JOIN CONTACTS c ON s.STUDENT_CONTACT_REF = c.CONTACT_EMAIL 
                WHERE c.CONTACT_FIRST_NAME LIKE '%$search%' OR c.CONTACT_LAST_NAME LIKE '%$search%'
                GROUP BY studentMail LIMIT 0, 25;";

                $result = $conn->query($sql);
                if($result){
                    if(($result->num_rows) > 0){
                        echo "<table class='table'>".
                                    "<thead>".
                                        "<tr>".
                                            "<th>"."Firstname"."</th>".
                                            "<th>"."Lastname"."</th>".
                                            "<th>"."Epita Mail"."</th>".
                                            "<th>"."Program"."</th>".
                                            "<th>"."Year"."</th>".
                                            "<th>"."Email"."</th>".
                                            "<th>"."Passed"."</th>".
                                        "</tr>".
                                    "</thead>".
                                    "<tbody>";
                        while($row = $result->fetch_assoc()){
                            echo        
                                    // "<tbody>".
                                        "<tr>".
                                            "<td>". $row['FIRSTNAME']. "</td>".
                                            "<td>". $row['LASTNAME']. "</td>".
                                            "<td>". $row['EPITAMAIL']. "</td>".
                                            "<td>". $row['PROGRAM']. "</td>".
                                            "<td>". $row['YEAR']. "</td>".
                                            "<td>". $row['EMAIL']. "</td>".
                                            "<td>". $row['PASSED']. "</td>".
                                        "</tr>";}
                        echo
                            "</tbody>".
                            "</table>";
                        
                    }
                    else {
                        echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                No student with that name!
                                <button onclick='history.back()' type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>";
                    }
                        
                }
                else{
                    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            An error occured searching students!
                            <button onclick='history.back()' type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
                        }
                    }
            else {
                echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            Name cannot be empty
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
