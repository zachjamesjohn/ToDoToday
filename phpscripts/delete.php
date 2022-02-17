<?php
//delete.php
//COIS 3420 Culmination Project - -Do App
//Author: Zachary John - zjohn - 0593938
//Description: Script for deleting to do's

session_start(); //Access Session Variables
include "usertables.php"; //Access the user tables

$conn = OpenCon(); //Open the connection

if(isset($_GET['id'])){ //If the id of the ToDo item in question is successfully sent

    $sql = "DELETE FROM todo".$_SESSION['id']." WHERE toDoId='".$_GET['id']."';"; //Create the sql query
    $conn->query($sql); //Execute the sql query
    header("location: ../homepage.php"); //Refresh the page
    exit(); 

}else{
    header("Location: ../homepage.php"); //If the user got to this script in a non traditional means send them back to the homepage
    exit();
}