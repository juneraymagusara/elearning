<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// include database and object files
include_once __DIR__.'/../config/database.php';
include_once __DIR__.'/../model/course.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare course object
$course = new Course($db);
 
// get id of course to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of course to be edited
$course->fcourseid = $data->fcourseid;
 
// set course property values
$course->fcourse_title = isset($data->fcourse_title) ? $data->fcourse_title : "";
$course->fcourse_memo = isset($data->fcourse_memo) ? $data->fcourse_memo : "";
$course->fauthor = isset($data->fauthor) ? $data->fauthor : "";
 
// update the course
if($course->update()) {
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
    echo json_encode(array("success"=> true, "message" => "Course was updated."));
}
 
// if unable to update the course, tell the user
else {
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
    echo json_encode(array("success"=> false, "message" => "Unable to update course."));
}
?>