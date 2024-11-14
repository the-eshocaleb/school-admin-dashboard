<?php
function students($connection, $year, $program) {
    $result = $connection->query("SELECT studentMail, s.STUDENT_POPULATION_CODE_REF, s.STUDENT_POPULATION_YEAR_REF, c.CONTACT_EMAIL, c.CONTACT_FIRST_NAME, c.CONTACT_LAST_NAME, concat(SUM(totalGrade), '/' , COUNT(totalGrade)) AS passed
                                FROM 
                                    ( SELECT g.GRADE_STUDENT_EPITA_EMAIL_REF AS studentMail, g.GRADE_COURSE_CODE_REF, (CASE WHEN (ROUND(SUM(g.GRADE_SCORE * e.EXAM_WEIGHT) / SUM(e.EXAM_WEIGHT)) >= 10) THEN 1 ELSE 0 END) AS totalGrade 
                                    FROM GRADES g JOIN EXAMS e ON g.GRADE_COURSE_CODE_REF = e.EXAM_COURSE_CODE 
                                    AND g.GRADE_COURSE_REV_REF = e.EXAM_COURSE_REV 
                                    AND g.GRADE_EXAM_TYPE_REF = e.EXAM_TYPE 
                                    GROUP BY studentMail, g.GRADE_COURSE_CODE_REF ) AS subquery 
                                JOIN STUDENTS s ON studentMail = s.STUDENT_EPITA_EMAIL JOIN CONTACTS c ON s.STUDENT_CONTACT_REF = c.CONTACT_EMAIL
                                WHERE s.STUDENT_POPULATION_CODE_REF = '$program' AND s.STUDENT_POPULATION_YEAR_REF = $year 
                                GROUP BY studentMail LIMIT 0, 25;");

    return $result;
}

function makeStudentTable($queryResults){
    while($row = $queryResults->fetch_assoc()){
        echo "<tr>".
                "<td>". $row['studentMail']. "</td>".
                "<td>". $row['CONTACT_FIRST_NAME']. "</td>".
                "<td>". $row['CONTACT_LAST_NAME']. "</td>".
                "<td>". $row['passed']. "</td>".
                "<td>".
                "<a class='btn btn-primary btn-sm li' href='../phpFiles/editStudent.php?contactEmail=". $row['CONTACT_EMAIL'] ."&"."year=".$row['STUDENT_POPULATION_YEAR_REF']."&"."program=".$row['STUDENT_POPULATION_CODE_REF']."'>Edit</a>". 
                "<a onClick=\" javascript:return confirm('This action is permanent, are you sure you want to delete?');\" class='btn btn-danger btn-sm li' href='../phpFiles/deleteStudent.php?contactEmail=". $row['CONTACT_EMAIL']."&"."epitaMail=".$row['studentMail']."&"."year=".$row['STUDENT_POPULATION_YEAR_REF']."&"."program=".$row['STUDENT_POPULATION_CODE_REF']."'>Delete</a>".
                "</td>".
            "</tr>";

    }
}

?>