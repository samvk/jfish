<?php
error_reporting(0);

// correct server connection test
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
	exit;
}

require "libraries/PHPMailer/class.phpmailer.php";

// data
$name = trim($_POST["name"]);
$email = trim($_POST["email"]);
$phone = trim($_POST["phone"]);
$message = trim($_POST["message"]);

//  empty fields check (only if bypassed client-side validation)
if (empty($name) || empty($email) || empty($message)) {
	$response = array(
		"status" => "error",
		"message" => "You left some required fields blank."
	);
	echo json_encode($response);
	exit;
}

// Set email message
$message = htmlspecialchars($message, ENT_NOQUOTES);
$message = nl2br($message); // preserve line breaks

$body = <<<HTML
<html>
	<head>
		<title>Emma Simon - Message</title>

		<style>
            .body {
                width: 480px;
                max-width: calc(100% - 140px);
                padding: 50px 35px 25px;
                font-size: 16px;
                color: #575757;
                background-color: #F8F8F8;
                border-top: 65px solid #61006A;
                border-bottom: 65px solid #61006A;
                border-left: 35px solid #61006A;
                border-right: 35px solid #61006A;

            }
            .text-center {
                text-align: center;
            }
            .title {
                display: inline-block;
                width: 55px;
                margin-right: 10px;
                font-weight: 600;
            }
            .logo {
                width: 120px;
                padding-bottom: 30px;
            }
            .info, .line {
                width: 300px;
                max-width: 100%;
                margin: 0 auto;
            }
            .info {
                padding-left: 20px;
            }
			.line {
				border-left: 4px solid transparent;
				border-right: 4px solid transparent;
				border-top: 4px solid #8B008B;
				height: 0;
			}
            .copyright {
                padding-top: 20px;
                font-size: 12px;
                font-weight: 600;
            }
		</style>
	</head>
	<body>
        <div class='body'>
            <div class='text-center'>
                <img class='logo' alt="Emma Simon" src="https://avatars0.githubusercontent.com/u/12971446?v=4&s=400" />
            </div>
            <div class='info'>
                <p><span class='title'>Name</span> {$name}</p>
                <p><span class='title'>Email</span> {$email}</p>
                <p><span class='title'>Phone</span> {$phone}</p>
            </div>
            <div class='line'></div>
            <p>{$message}</p>
            <p class='copyright text-center'>2017 &copy; Emma Simon</p>
        </div>
	</body>
</html>
HTML;

// SEND EMAIL TO RECIPIENT
$mail = new PHPMailer(true);
$mail->CharSet = "UTF-8";

try {
	$mail->AddAddress("samvnkauffman@gmail.com");
	$mail->SetFrom("noreply@jellyfish.software", "Admin");
	$mail->Subject = "You've Received a new message from jellyfish.software!";
	$mail->IsHTML(true);
	$mail->Body = $body;
	$mail->Send();

	// SUCCESS
	$response = array(
		"status" => "success",
		"message" => "Sent!"
	);

	// ERROR
} catch (phpmailerException $e) {
	$response = array(
		"status" => "error",
		"message" =>  "PHPMailer error: " . $e->errorMessage() // PHPMailer error
	);
} catch (Exception $e) {
	$response = array(
		"status" => "error",
		"message" => "Error: " . $e->getMessage()
	);
}
echo json_encode($response);