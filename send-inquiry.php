<?php

// ================= SECURITY CHECK =================
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.html");
    exit();
}

// ================= HONEYPOT (ANTI-SPAM) =================
if (!empty($_POST['website'])) {
    exit();
}

// ================= SANITIZE INPUT =================
$name   = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
$phone  = htmlspecialchars(trim($_POST['phone'] ?? ''), ENT_QUOTES, 'UTF-8');
$device = htmlspecialchars(trim($_POST['device'] ?? ''), ENT_QUOTES, 'UTF-8');
$issue  = htmlspecialchars(trim($_POST['issue'] ?? ''), ENT_QUOTES, 'UTF-8');

// ================= VALIDATION =================
if ($name === '' || $phone === '' || $device === '' || $issue === '') {
    echo "Required fields missing.";
    exit();
}

if (!preg_match('/^[0-9]{10}$/', $phone)) {
    echo "Invalid phone number.";
    exit();
}

// ================= EMAIL SETTINGS =================
$to = "sancomsmartphonecare@gmail.com";
$subject = "New Mobile Repair Inquiry – BangaloreGadgets.in";

$fromEmail = "no-reply@bangaloregadgets.in";
$fromName  = "Bangalore Gadgets";

// ================= EMAIL BODY =================
$message = "
<html>
<head>
<style>
body { font-family: Arial; }
table { border-collapse: collapse; width:100%; }
td { padding:10px; border:1px solid #ddd; }
h2 { color:#0b2c4d; }
</style>
</head>
<body>

<h2>📩 New Mobile Repair Inquiry</h2>

<table>
<tr><td><strong>Name</strong></td><td>{$name}</td></tr>
<tr><td><strong>Mobile</strong></td><td>{$phone}</td></tr>
<tr><td><strong>Device</strong></td><td>{$device}</td></tr>
<tr><td><strong>Issue</strong></td><td>{$issue}</td></tr>
</table>

<br>
<p>
<strong>Website:</strong> https://bangaloregadgets.in <br>
<strong>Location:</strong> Bangalore
</p>

</body>
</html>
";

// ================= HEADERS =================
$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type:text/html;charset=UTF-8\r\n";
$headers .= "From: {$fromName} <{$fromEmail}>\r\n";
$headers .= "Reply-To: {$fromEmail}\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// ================= SEND =================
if (mail($to, $subject, $message, $headers)) {
    header("Location: thankyou.html");
    exit();
} else {
    echo "Mail sending failed. Please try again.";
}
?>
