<?php
try { 
    new PDO('mysql:host=127.0.0.1;dbname=hrappsprod;port=3306', 'root', ''); 
    echo 'OK'; 
} catch (PDOException $e) { 
    echo 'ERROR: ' . $e->getMessage(); 
}
