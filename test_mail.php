<?php
include 'config.php';
include 'includes/mailer.php';

sendWelcomeEmail('lynkpage.support@gmail.com', 'Test User');
echo "Email sent!";