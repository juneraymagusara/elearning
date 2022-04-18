<?php
class Course {
    private $conn;
    private $table_name = 'courses';

    public $fcourseid;
    public $fcourse_title;
    public $fcourse_memo;
    public $fauthor;
    public $fcreated_date;
    public $fupdated_date;

    public function __construct($db){
        $this->conn = $db;
    }

    function read(){
    
        // select all query
        $query = "SELECT
                    *
                FROM
                    " . $this->table_name . " 
                ORDER BY fcourseid";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function create() {
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                fcourse_title=:fcourse_title, fcourse_memo=:fcourse_memo, fauthor=:fauthor";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->fcourse_title=htmlspecialchars(strip_tags($this->fcourse_title));
        $this->fcourse_memo=htmlspecialchars(strip_tags($this->fcourse_memo));
        $this->fauthor=htmlspecialchars(strip_tags($this->fauthor));
    
        // bind values
        $stmt->bindParam(":fcourse_title", $this->fcourse_title);
        $stmt->bindParam(":fcourse_memo", $this->fcourse_memo);
        $stmt->bindParam(":fauthor", $this->fauthor);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
        
    }

    // used when filling up the update course form
    function readOne(){
        
        // query to read single record
        $query = "SELECT
                   *
                FROM
                    " . $this->table_name . "
                WHERE
                    fcourseid=:fcourseid OR (fcourse_title=:fcourse_title AND fcourse_memo=:fcourse_memo AND fauthor=:fauthor)
                LIMIT
                    0,1";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // bind id
        $stmt->bindParam(":fcourseid", $this->fcourseid);
        $stmt->bindParam(":fcourse_title", $this->fcourse_title);
        $stmt->bindParam(":fcourse_memo", $this->fcourse_memo);
        $stmt->bindParam(":fauthor", $this->fauthor);
        
        // execute query
        $stmt->execute();
    
 
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            // set values to object properties
            $this->fcourseid = $row['fcourseid'];
            $this->fcourse_title = $row['fcourse_title'];
            $this->fcourse_memo = $row['fcourse_memo'];
            $this->fauthor = $row['fauthor'];
            $this->fcreated_date = $row['fcreated_date'];
            $this->fupdated_date = $row['fupdated_date'];

            return true;
        } 

        return false;
    }

    function update(){
    
        // update query
        $query = "UPDATE 
                    " . $this->table_name . "
                SET
                    fcourse_title = :fcourse_title,
                    fcourse_memo = :fcourse_memo,
                    fauthor = :fauthor
                WHERE
                    fcourseid = :fcourseid";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // sanitize
        $this->fcourse_title = htmlspecialchars(strip_tags($this->fcourse_title));
        $this->fcourse_memo = htmlspecialchars(strip_tags($this->fcourse_memo));
        $this->fauthor = htmlspecialchars(strip_tags($this->fauthor));
        $this->fcourseid = htmlspecialchars(strip_tags($this->fcourseid));

        // bind new values
        $stmt->bindParam(':fcourse_title', $this->fcourse_title);
        $stmt->bindParam(':fcourse_memo', $this->fcourse_memo);
        $stmt->bindParam(':fauthor', $this->fauthor);
        $stmt->bindParam(':fcourseid', $this->fcourseid);

        // execute the query
        if($stmt->execute()){
            return true;
        }

        return false;
    }

    function delete(){
    
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE fcourseid = ?";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->fcourseid=htmlspecialchars(strip_tags($this->fcourseid));
    
        // bind id of record to delete
        $stmt->bindParam(1, $this->fcourseid);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
        
    }
}
?>