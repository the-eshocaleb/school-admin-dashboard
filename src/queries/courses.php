<?php
function courses($connection, $year, $program) {
    $result = $connection->query("SELECT c.COURSE_CODE, c.COURSE_NAME, COUNT(s.SESSION_COURSE_REF) as SESSIONS 
                                FROM COURSES c 
                                JOIN SESSIONS s ON c.COURSE_CODE = s.SESSION_COURSE_REF
                                JOIN PROGRAMS p ON c.COURSE_CODE = p.PROGRAM_COURSE_CODE_REF 
                                WHERE p.PROGRAM_ASSIGNMENT = '$program' AND s.SESSION_POPULATION_YEAR  = $year
                                GROUP BY s.SESSION_COURSE_REF");

    return $result;
}

function makeCoursesTable($queryResults, $program, $year){
    while($row = $queryResults->fetch_assoc()){
        echo "<tr>".
                "<td>". $row['COURSE_CODE']. "</td>".
                "<td>"."<a href='../grades/$program-$year-grade.php?year=$year&program=$program'".">". $row['COURSE_NAME']. "</a></td>".
                "<td>". $row['SESSIONS']. "</td>".
            "</tr>";
    }
}

