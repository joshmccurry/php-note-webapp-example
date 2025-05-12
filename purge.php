<?php
    require 'notes.php';
    $notes = new Notes();
    $notes->delete("all");


    echo "All notes deleted";
    $notes->create("Webjob Fired", "Purge completed on: " . date("Y-m-d H:i:s"));
?>
