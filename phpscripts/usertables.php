<?php
// usertables.php
//COIS 3420 Culmination Project - To-Do App
//Author: Zachary John - zjohn - 0593938
//Description: Module used by other php files to connect with myPHPAdmin and to access specific user tables

function OpenCon() //Function to open a connection with the database
{
    $dbhost = "loki.trentu.ca"; //The database host
    $dbuser = "zjohn"; //The database user
    $dbpass = ".GraifGik2"; //The database password
    $db = "zjohn"; //the database name

    $conn = new mysqli($dbhost, $dbuser, $dbpass); //Create connection
 
    //Check connection prints error if connection doesn't work
    if ($conn->connect_error){
        die("Connection failed: ". $conn->connect_error); 
    }
    
    //Checks to see if the database exists. If not creates database
    if (!mysqli_select_db($conn,$db)){//If database does not exist
        $sql = "CREATE DATABASE ".$db; //SQL command to create database
        if($conn->query($sql)==TRUE){ //If received confirmation that the query was successful print a message saying so
            echo "Database successfully created";
        }else{
            echo "Error creating database: " . $conn->error; //Error creating the database
        }
    }

    $conn -> select_db($db); //Adds the database to the connection so it does not need to be added manually every time

    //Checks to see if table exists if not creates table
    $table = "todo".$_SESSION['id']; #Table name with user id number
    $sql = "SELECT toDoId FROM ".$table.";";  #SQL query to see if you can select a single id from the table
    $result = mysqli_query($conn, $sql); //Put the results of the sql query into result
    if(empty($result)){ //If the result is empty then create the table
        $sql = "CREATE TABLE ".$table." (toDoId INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
        toDoDescription TEXT NOT NULL,
        toDoDueDate DATETIME DEFAULT NULL,
        archived BOOL DEFAULT '0',
        checked BOOL DEFAULT '0'
        );"; //SQL query to create the table with default values for checked and archived
        if($conn->query($sql)==TRUE){ //If recieved confirmation that the query was successful print a message saying so
            //echo "Database successfully created";
        }else{
            echo "Error creating database: " . $conn->error; //Error creating the database
        }
    }

    return $conn; //Return the connection to be used by the caller
}
 
function CloseCon($conn) //Function to close the connection
    {

    // Close Database connection    
    $conn -> close();
 
    }
   
?>