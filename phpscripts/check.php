<?php
//check.php
//COIS 3420 Culmination Project - To-Do App
//Author: Zachary John - zjohn - 0593938
//Description: Script for toggle the check variable on a to do item

session_start(); //Access Session Variables
include "usertables.php"; //Access the user tables

$conn = OpenCon(); //Open the connection

if(isset($_GET['id'])){ //If the id of the ToDo item in question is successfully sent

    $sql = "UPDATE todo".$_SESSION['id']." SET checked = NOT checked WHERE toDoId='".$_GET['id']."';"; //SQL Query to update the check variable
    $conn->query($sql); //Execute the sql query
    header("location: ../homepage.php"); //Refresh the page
    exit(); 

}else{
    header("Location: ../homepage.php");  //If the user got to this script in a non traditional means send them back to the homepage
    exit();
}