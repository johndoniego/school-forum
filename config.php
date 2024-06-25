<?php
  
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $dbName = "forum";
  

  $conn = mysqli_connect("localhost","root","","forum");

  if(mysqli_connect_error()) 
    echo "Failed to connect to MySQL: ". mysqli_connect_error();
  
