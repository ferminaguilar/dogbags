<?php
$to = "bags@dogbags.dog"; // replace with your real email
$subject = "PHP Mail Test";
$message = "This is a test email from PHP mail() function.";
$headers = "From: bags@dogbags.dog\r\n";

if (mail($to, $subject, $message, $headers)) {
    echo "Mail sent successfully!";
} else {
    echo "Mail sending failed.";
}
?>
