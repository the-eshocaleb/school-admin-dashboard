<?php
session_start();
if(isset($_GET['contactEmail']) && isset($_GET['epitaMail']) && isset($_GET['year']) && isset($_GET['program'])){
    $contactEmail = $_GET['contactEmail'];
    $epitaMail = $_GET['epitaMail'];
    $year = $_GET['year'];
    $program = $_GET['program'];
}

require("dbConnection.php");

// delete contact details
$conn->query("DELETE FROM contacts WHERE contact_email = '$contactEmail';");

// delete student details
$conn->query("DELETE FROM students WHERE student_epita_email = '$epitaMail'; ");

// delete student grades
$getCourses = " SELECT c.COURSE_CODE, c.COURSE_REV
                FROM COURSES c 
                JOIN PROGRAMS p ON c.COURSE_CODE = p.PROGRAM_COURSE_CODE_REF 
                WHERE p.PROGRAM_ASSIGNMENT = '$program';";

        $result = $conn->query($getCourses);
        while ($row = $result->fetch_assoc()){
            $courseCode = $row['COURSE_CODE'];
            $courseRev = $row['COURSE_REV'];

            // delete grades
            $conn->query("DELETE FROM grades
                            WHERE grade_student_epita_email_ref = '$epitaMail'
                            AND grade_course_code_ref = '$courseCode'
                            AND grade_course_rev_ref = $courseRev
                            AND grade_exam_type_ref = 'Project'; ");
        }

$_SESSION['message'] = "Student deleted successfully";
// header("Location: ".$_SERVER['HTTP_REFERER'])
echo "<script>history.go(-1)</script>";
?>