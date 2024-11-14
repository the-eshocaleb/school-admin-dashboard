<?php
function grades($connection, $year, $program) {
    $result = $connection->query("SELECT g.GRADE_STUDENT_EPITA_EMAIL_REF as EMAIL , grade_course_rev_ref as COURSE_CODE_REV ,grade_exam_type_ref as EXAM_TYPE , c.CONTACT_FIRST_NAME AS FIRSTNAME, c.CONTACT_LAST_NAME AS LASTNAME, g.GRADE_COURSE_CODE_REF AS COURSE, GRADE_SCORE AS GRADE
                                FROM GRADES g
                                JOIN STUDENTS s ON g.GRADE_STUDENT_EPITA_EMAIL_REF = s.STUDENT_EPITA_EMAIL 
                                JOIN CONTACTS c ON s.STUDENT_CONTACT_REF = c.CONTACT_EMAIL
                                WHERE s.STUDENT_POPULATION_CODE_REF = '$program'
                                AND s.STUDENT_POPULATION_YEAR_REF = $year ;");

    return $result;
}

function makeGradesTable($queryResults){
    while ($row = $queryResults->fetch_assoc()) {
        echo "<tr>".
                "<td>". $row['EMAIL']. "</td>".
                "<td>". $row['FIRSTNAME']. "</td>".
                "<td>". $row['LASTNAME']. "</td>".
                "<td>". $row['COURSE']. "</td>".
                "<td>". $row['GRADE']. "</td>".
                "<td>".
                    "<a class='btn btn-primary btn-sm li' href='../phpFiles/editGrades.php?epitaMail=". $row['EMAIL']."&"."courseCode=".$row['COURSE']."&"."courseRev=".$row['COURSE_CODE_REV']."&"."examType=".$row['EXAM_TYPE']."'>"."Edit"."</a>".
                    "<a onClick=\" javascript:return confirm('This action sets grade to null, are you sure you want to delete?');\" class='btn btn-danger btn-sm li' href='../phpFiles/deleteGrades.php?epitaMail=". $row['EMAIL']."&"."courseCode=".$row['COURSE']."&"."courseRev=".$row['COURSE_CODE_REV']."&"."examType=".$row['EXAM_TYPE']."'>"."Delete"."</a>".
                "</td>".
            "</tr>";
    }
    
}

?>