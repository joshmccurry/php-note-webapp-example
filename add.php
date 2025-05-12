<?php
    require 'notes.php';
    $notes = new Notes();
    $notes->create("Continuous Trigger Fired", "Run completed on: " . date("Y-m-d H:i:s"));
    header('Location: index.php');
?>