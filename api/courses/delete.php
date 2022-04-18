<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// include database and object file
include_once __DIR__.'/../config/database.php';
include_once __DIR__.'/../model/course.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare course object
$course = new Course($db);
 
// get course id
$data = json_decode(file_get_contents("php://input"));
 
// set course id to be deleted
$course->fcourseid = $data->fcourseid;
 
// delete the course
if($course->delete()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
    echo json_encode(array("success" => true, "message" => "Course was deleted."));
}
 
// if unable to delete the course
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
    echo json_encode(array("success" => false, "message" => "Unable to delete course."));
}
?>