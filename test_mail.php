<?php
include 'config.php';
include 'includes/mailer.php';

sendWelcomeEmail('cutthislink@gmail.com', 'Test User');
echo "Email sent!";