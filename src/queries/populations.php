<?php
function population($connection) {
    $result = $connection->query("SELECT p.POPULATION_CODE , p.POPULATION_YEAR, count(s.STUDENT_EPITA_EMAIL) as POPULATION
                                FROM POPULATIONS p 
                                JOIN STUDENTS s ON p.POPULATION_CODE = s.STUDENT_POPULATION_CODE_REF
                                WHERE s.STUDENT_POPULATION_YEAR_REF = p.POPULATION_YEAR 
                                GROUP BY POPULATION_YEAR , POPULATION_CODE ;");

    return $result;
}

function labelArray($queryResult){
    $labels = array();
    if ($queryResult->num_rows > 0) {
        #fetch each row and show its data
        while ($row = $queryResult->fetch_assoc()) {
            array_push($labels, $row["POPULATION_CODE"] . '-' . $row["POPULATION_YEAR"]);
        }        
        
    } else {
        echo "No results";
    }

    return $labels;

}

function dataArray ($queryResult){
    $data = array();
    if ($queryResult->num_rows > 0) {
        #fetch each row and show its data
        while ($row = $queryResult->fetch_assoc()) {
            array_push($data, $row["POPULATION"]);
        }        
        
    } else {
        echo "No results";
    }
    
    return $data;
}

function makePopulationList($queryResult){
    # check if the result table is empty
    if ($queryResult->num_rows > 0) {
        #fetch each row and show its data
        while ($row = $queryResult->fetch_assoc()) {
            echo "<p><a href='./populations/" . $row["POPULATION_CODE"] . '-' . $row["POPULATION_YEAR"] . ".php?year=" . $row["POPULATION_YEAR"] . "&program=" . $row["POPULATION_CODE"] . "'>" . $row["POPULATION_CODE"] . "-" . $row["POPULATION_YEAR"] . "-" . '(' . $row["POPULATION"] . ')' . "</a></p>";
        }        
        
    } else {
        echo "No results";
    }

}

function populations($connection) {
    $result = $connection->query("SELECT p.POPULATION_CODE , p.POPULATION_YEAR, count(s.STUDENT_EPITA_EMAIL) as POPULATION
                                FROM POPULATIONS p 
                                JOIN STUDENTS s ON p.POPULATION_CODE = s.STUDENT_POPULATION_CODE_REF
                                WHERE s.STUDENT_POPULATION_YEAR_REF = p.POPULATION_YEAR 
                                GROUP BY POPULATION_YEAR , POPULATION_CODE ;");

    return $result;
}
?>

