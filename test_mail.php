<?php
include 'config.php';
include 'includes/mailer.php';

sendWelcomeEmail('murray@graphicser.com', 'Test User');
echo "Email sent!";