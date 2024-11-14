<?php
// check for url variables
if(isset($_GET['epitaMail']) && isset($_GET['courseCode']) && isset($_GET['courseRev']) && isset($_GET['examType'])){
    $epitaMail = $_GET['epitaMail'];
    $courseCode = $_GET['courseCode'];
    $courseRev = $_GET['courseRev'];
    $examType = $_GET['examType'];

    require("dbConnection.php");

    // set grades to null
    $grade = $conn->query("UPDATE grades
                            SET grades.grade_score = NULL
                            WHERE grades.grade_student_epita_email_ref = '$epitaMail'
                            AND grades.grade_course_code_ref = '$courseCode'
                            AND grades.grade_course_rev_ref = $courseRev
                            AND grades.grade_exam_type_ref = '$examType';");


}
$_SESSION['message'] = "Student grade deleted successfully!";
echo "<script>history.go(-1)</script>";
?>
