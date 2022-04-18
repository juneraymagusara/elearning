<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
// include database and object files
include_once __DIR__.'/../config/database.php';
include_once __DIR__.'/../model/course.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
$course = new Course($db);
$data = json_decode(file_get_contents("php://input"));


// set ID property of record to read
$course->fcourseid = isset($data->fcourseid) ? $data->fcourseid : "";
$course->fcourse_title = isset($data->fcourse_title) ? $data->fcourse_title : "";
$course->fcourse_memo = isset($data->fcourse_memo) ? $data->fcourse_memo : "";
$course->fauthor = isset($data->fauthor) ? $data->fauthor : "";
 
// read the details of course to be edited


if($course->readOne()){
    // create array
    $course_arr = array(
        "fcourseid" =>  $course->fcourseid,
        "fcourse_title" => $course->fcourse_title,
        "fcourse_memo" => $course->fcourse_memo,
        "fauthor" => $course->fauthor,
        "fcreated_date" => $course->fcreated_date,
        "fupdated_date" => $course->fupdated_date,
        "error" => false
    );

    // set response code - 200 OK
    http_response_code(200);

    // make it json format
    echo json_encode($course_arr);
}
 
else{
    // set response code - 404 Not found
    // http_response_code(404);
 
    // tell the user course does not exist
    echo json_encode(array("error" => true, "message" => "Course does not exist."));
}
?>