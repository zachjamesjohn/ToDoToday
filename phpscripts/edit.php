<?php
//edit.php
//COIS 3420 Culmination Project - To-Do App
//Author: Zachary John - zjohn - 0593938
//Description: Script for updating database values from live editing to do's

session_start(); //Access Session Variables

include "usertables.php"; //Access the user tables

$conn = OpenCon(); //Open the connection

if(isset($_POST['id'])){ // If the AJAX POST is successful
   $field = $_POST['field']; //Save the POST values as variables
   $value = $_POST['value'];
   $editid = $_POST['id'];

   $sql = "UPDATE todo".$_SESSION['id']." SET ".$field." = '".$value."' WHERE toDoId =".$editid.";"; //Create the sql query
   $conn->query($sql); //Execute the update query
   header("location: ../homepage.php"); //Refresh the page
   exit();

}else{
   header("Location: ../homepage.php"); //If the user got to this script in a non traditional means send them back to the homepage
   exit();
}
