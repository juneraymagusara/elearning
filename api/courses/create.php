<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once __DIR__.'/../config/database.php';
include_once __DIR__.'/../model/course.php';

$database = new Database();
$db = $database->getConnection();
 
$course = new Course($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(
    !empty($data->fcourse_title) &&
    !empty($data->fcourse_memo) &&
    !empty($data->fauthor)
){
 
    // set course property values
    $course->fcourse_title = $data->fcourse_title;
    $course->fcourse_memo = $data->fcourse_memo;
    $course->fauthor = $data->fauthor;
 
    // create the course
    if($course->create()){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("success" => true, "message" => "Course was created."));
    }
 
    // if unable to create the course, tell the user
    else {
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
        echo json_encode(array("success" => false, "message" => "Unable to create course."));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array($data,"message" => "Unable to create course. Data is incomplete."));
}
?>