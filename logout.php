<?php
include 'config.php'; // IMPORTANT: starts session

session_unset();
session_destroy();

header("Location: login.php");
exit;