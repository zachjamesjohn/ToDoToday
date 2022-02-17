<?php
//homepage.php
//COIS 3420 Culmination Project - To-Do App
//Author: Zachary John - zjohn - 0593938
//Description: The main page of the web app. This is where the user can perform CRUD functions on to do's.
//There is also the option to archive to do's, show or hide archived items, and check or uncheck to do's

session_start(); //Make session varibles available on this page
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://loki.trentu.ca/~zjohn/COIS_3420/Project/css/style.css" /> <!--Stylesheet-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> <!--JQuery CDN-->
    <script src="script.js" defer></script> <!--Script containing live editing script-->
    <!--Title Font-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=New+Tegomin&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/c1a37ef24d.js" crossorigin="anonymous"></script> <!--Font Awesome Icons-->
    <title>ToDoToday</title>
</head>
<body>
    <?php 
        if(isset($_SESSION['name'])){ //If the session contains a name, meaning a user is logged in. Show the page.
            include "phpscripts/signout.php";
            include "phpscripts/usertables.php";
            $conn = OpenCon(); //Open the connection with the user's todo database. Create if one is not present.
            
            echo "<ul class='homepage_list'>";
            echo "<li><a href='phpscripts/signout.php?message=loggedOut'><button class='homepage_listitem'>Log Out</button></a></li>"; //Log out and pass a message by get
            
            if($_SESSION['showArchived'] == true){ //Change the option shown depending on if showArchived session variable is visible.
                echo "<li><a href='homepage.php?archived=change1'><button class='homepage_listitem'>Hide Archived To-Do's</button></a></li>"; //Click to hide archived items
            }else if($_SESSION['showArchived'] == false){
                echo "<li><a href='homepage.php?archived=change2'><button class='homepage_listitem'>Show Archived To-Do's</button></a></li>"; //Click to show archived items.
            }
            echo "</ul>";
            echo "<section class='homepage_content'>";
            echo "<h1 class='homepage_title'>Welcome ". $_SESSION['name']."!</h1>"; //Great the user with their name using session variable.
            
            //Form for creating new To Do Item
            echo "<form method='POST'> 
                  <section class='newToDo'>
                    <input class='newToDoComponent homepage_input' type='text' name='description' placeholder='Todo Description'></input>
                    <input class='newToDoComponent homepage_input' type='datetime-local' name='dueDate'></input>
                    <button class='newToDoComponent' type='submit' name='submit'>Add this to your list</button>
                  </section>
                  </form>";
            
            if(isset($_POST['submit'])){ //If the Create New To Do Form Is Submitted
                $description = $_POST['description']; //Create variable for description
                if (!$_POST['dueDate'] == NULL){ //If the Due Date is not NUll include that value in the sql query.
                    $dueDate = $_POST['dueDate']; //Create due date variable
                    $sql = "INSERT INTO todo".$_SESSION['id']." (toDoDescription, toDoDueDate) VALUES ('$description', '$dueDate');";
                    //SQL query including both description and due Date
                }else{
                    $sql = "INSERT INTO todo".$_SESSION['id']." (toDoDescription) VALUES ('$description');"; //SQL query including only the description
                }
               
                
     
                $conn->query($sql); //Execute the query

                header("location: homepage.php"); //Refresh the page
                exit();
              
            }


            if(isset($_GET['archived'])){ //If the toggle archive button is pushed
                if($_GET['archived'] == 'change1'){ //If the hide archive button is pushed
                    $_SESSION['showArchived'] = false; //Update the session value 
                    header("location: homepage.php");//Refresh the page
                    exit();
                }else if($_GET['archived'] == 'change2'){//If the show archive button is pushed
                    $_SESSION['showArchived'] = true;//Update the session value 
                    header("location: homepage.php");//Refresh the page
                    exit();
                }
            }

            $sql = "SELECT *, DATE_FORMAT(toDoDueDate, '%Y-%m-%dT%H:%i') AS custom_date  FROM todo".$_SESSION['id'].";";
            //SQL Query to select all values from the table. Select Due Date in a specific format that is able to be fed into the input field for editing.
            
            $result = $conn->query($sql); //Put the result of the SQL query into $result
            
            if($result->num_rows>0){ //If there is more then 0 rows
                //output data of each row
                while($row = $result->fetch_assoc()){ //While there are more rows
                    if($row['archived'] == false || $_SESSION['showArchived'] == true){ //If the row is not archived or if the Session is showing all archived items
                        echo "<br>"; //Print a line break
                        echo "<tr>"; //Print a new row
                        echo "<section class='toDoItem'>";
                        if($row['checked'] == false){ //Display a check mark if the value is checked
                            echo "<th ><a class='toDoComponent' href='phpscripts/check.php?id=".$row['toDoId']."'><i class='far fa-square'></i></a></th>"; //Display checked
                        }else{
                            echo "<th><a class='toDoComponent' href='phpscripts/check.php?id=".$row['toDoId']."'><i class='far fa-check-square'></i></a></th>"; //Display Unchecked
                        }
                        echo "<th><textarea class='txtedit toDoComponent homepage_input' id='toDoDescription_".$row['toDoId']."' >".$row['toDoDescription']."</textarea></th>";
                        //Print the toDoDescription in a editable input box
                        echo "<th><input class='txtedit toDoComponent homepage_input' id='toDoDueDate_".$row['toDoId']."'type='datetime-local' value=".$row['custom_date']."></input></th>";
                        //Print the toDoDueDate in a editable input box that features a date and time picker
                        if($row['archived'] == false){
                        echo "<th><a href='phpscripts/archive.php?id=".$row['toDoId']."'><button class='toDoComponent'>Archive</button></a></th>";
                        //Archive button
                        }else{
                        echo "<th><a href='phpscripts/archive.php?id=".$row['toDoId']."'><button class='toDoComponent'>Unarchive</button></a></th>"; //Unarchive button
                        }
                        echo "<th><a href='phpscripts/delete.php?id=".$row['toDoId']."'><button class='toDoComponent'>Delete</button></a></th>"; //Delete button 
                        echo "</section>
                        </tr><br>"; 
                        
                    }    
                }
            
            }
            echo "</table>"; //End table
            echo "</section>";
            

        }else{
            header("location: signin.php"); //The user is not logged in redirect to sign in page
            exit();
        }
    ?>
</body>
</html> 