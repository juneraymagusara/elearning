<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once __DIR__.'/../config/database.php';
include_once __DIR__.'/../model/course.php';
 
// instantiate database and course object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$course = new Course($db);
 
// query courses
$stmt = $course->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // courses array
    $courses_arr = array();
    $courses_arr["records"] = array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
        
        $course_item = array(
            "fcourseid" => $fcourseid,
            "fcourse_title" => $fcourse_title,
            "fcourse_memo" => html_entity_decode($fcourse_memo),
            "fauthor" => $fauthor,
            "fcreated_date" => $fcreated_date,
            "fupdated_date" => $fupdated_date
        );
 
        array_push($courses_arr["records"], $course_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show courses data in json format
    echo json_encode($courses_arr);
} 
else{
 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no courses found
    echo json_encode(
        array("records" => [], "message" => "No courses found.")
    );
}