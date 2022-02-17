//script.js
//COIS 3420 Culmination Project - To-Do App
//Author: Zachary John - zjohn - 0593938
//Description: Using JQuery and AJAX allow to do's to be edited and update the database

$(document).ready(function () {//JQuery
   
    // When a form field with the txtedit class is no longer being focused on
    $(".txtedit").focusout(function () {

        // Get edit id, field name and value
        var id = this.id;
        var split_id = id.split("_");//Split the toDoID and the field name for the database query
        var field_name = split_id[0]; 
        var edit_id = split_id[1];
        var value = $(this).val();
     
        // Sending AJAX request
        $.ajax({
            url: 'phpscripts/edit.php',
            type: 'POST',
            data: { field: field_name, value: value, id: edit_id }
        });
        
    });
});