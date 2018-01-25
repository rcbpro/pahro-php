<?php
session_start();
if (isset($_POST['country_code'])) $_SESSION['curr_country']['country_code'] = $_POST['country_code'];
if (isset($_POST['country_name'])) $_SESSION['curr_country']['country_name'] = $_POST['country_name'];
?>