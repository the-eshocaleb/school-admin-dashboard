<?php
function attendance($connection) {
    $result = $connection->query("SELECT p.POPULATION_CODE , p.POPULATION_YEAR, (sum(a.ATTENDANCE_PRESENCE) * 100)/COUNT(a.ATTENDANCE_PRESENCE) AS PERCENTAGE
                                FROM ATTENDANCE a 
                                JOIN STUDENTS s ON s.STUDENT_EPITA_EMAIL = a.ATTENDANCE_STUDENT_REF
                                JOIN POPULATIONS p  ON p.POPULATION_CODE = s.STUDENT_POPULATION_CODE_REF
                                GROUP BY POPULATION_YEAR , POPULATION_CODE;");

    return $result;
}

function makeAttendanceList($queryResult){
    # check if the result table is empty
    if ($queryResult->num_rows > 0) {
        #fetch each row and show its data
        while($row = $queryResult->fetch_assoc()) {
            echo "<p>". $row["POPULATION_CODE"] . "-" .$row["POPULATION_YEAR"] . "-" .  '(' . (int)$row["PERCENTAGE"]. '%)' . "</p>";
        }
        
    } else {
        echo "No results";
    }

}
?>