<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://pyscript.net/releases/2023.11.1/core.css" />
    <script type="module" src="https://pyscript.net/releases/2023.11.1/core.js"></script>
    <title>Secret Santa Assignment</title>
</head>

<body>
    <h1>Secret Santa Assignment</h1>

    <?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

    function sendMail($to, $toName, $subject, $body, $mailHost, $userName, $password, $sendFrom, $sendFromName, $replyTo, $replyToName)
    {
        // Use variables instead of defines
        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host = $mailHost;
            $mail->SMTPAuth = true;
            $mail->Username = $userName;
            $mail->Password = $password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->setFrom($sendFrom, $sendFromName);
            $mail->addAddress($to, $toName);
            $mail->addReplyTo($replyTo, $replyToName);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AltBody = $body;
            $mail->send();
            echo 'Message sent';
        } catch (Exception $e) {
            echo "Error sending message: {$mail->ErrorInfo}";
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['totalParticipants'])) {
            $totalParticipants = intval($_POST['totalParticipants']);

            if ($totalParticipants > 0) {
                $participants = array();
                $participantEmails = array(); // New array to store participant emails

                for ($i = 1; $i <= $totalParticipants; $i++) {
                    $fieldName = "participantName" . $i;
                    $participants[] = $_POST[$fieldName];

                    // Assuming you have a corresponding email input for each participant
                    $fieldEmail = "participantEmail" . $i;
                    $participantEmails[] = $_POST[$fieldEmail];
                }

                // Function to generate a random rotation of an array
                function rotateArray($array)
                {
                    shuffle($array);
                    return $array;
                }

                // Generate a random rotation of participants and emails
                $participants = rotateArray($participants);

                // Create pairs
                $resultat = array();
                $nbrpart = count($participants);

                for ($i = 0; $i < $nbrpart - 1; $i++) {
                    $resultat[] = array($participants[$i], $participants[$i + 1]);
                }

                // Add the last pair forming a loop
                $resultat[] = array($participants[$nbrpart - 1], $participants[0]);

                // Display results step by step
                echo "<h2>Assignment step by step:</h2>";
                echo "<ul>";

                foreach ($resultat as $key => list($donor, $receiver)) {
                    echo "<li>$donor gives to $receiver</li>";

                    $subject = "Secret Santa Assignment";
                    $body = "Dear $donor, \n\nYou must offer your Secret Santa gift to: $receiver.";
                    $toEmail = $participantEmails[$key];

                    // Use variables instead of defines
                    $mailHost = "smtp.gmail.com";
                    $userName = "yourgmail@gmail.com";
                    $password = "16 char password";
                    $sendFrom = "yourgmail@gmail.com";
                    $sendFromName = "Secret Santa";
                    $replyTo = $toEmail;
                    $replyToName = $donor;

                    sendMail($toEmail, $donor, $subject, $body, $mailHost, $userName, $password, $sendFrom, $sendFromName, $replyTo, $replyToName);
                }

                echo "</ul>";
            } else {
                echo "<p>No participants found.</p>";
            }
        } else {
            echo "<p>Total participants field not found.</p>";
        }
    } else {
        echo "<p>No form data received.</p>";
    }
    ?>
</body>
<style>
    body {
        background-color: #0D1117;
        color: #C9D1D9;
    }
</style>
</html>
