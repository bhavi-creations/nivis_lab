<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ✅ Form Fields (Updated as per your form)
    $name       = $_POST['name'] ?? '';
    $email      = $_POST['email'] ?? '';
    $number     = $_POST['number'] ?? '';
    $skin_type  = $_POST['skin_type'] ?? '';
    $concerns   = $_POST['concerns'] ?? '';
    $issues     = $_POST['issues'] ?? [];
    $solution   = $_POST['solution'] ?? '';
    $priority   = $_POST['priority'] ?? '';
    $doubt      = $_POST['doubt'] ?? '';
    $trial      = $_POST['trial'] ?? '';

    // ✅ Convert checkbox array to string
    $issues_list = !empty($issues) ? implode(", ", $issues) : 'None';

    $mail = new PHPMailer(true);

    try {
        // SMTP Settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'manimalladi05@gmail.com';
        $mail->Password   = 'yimoqjwaksrpchky'; // App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true
            )
        );

        // Recipients
        $mail->setFrom('manimalladi05@gmail.com', 'PHD Skincare');
        $mail->addAddress('manimalladi05@gmail.com', 'PHD Skincare');

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'New Skin Assessment Form Submission';

        $mail->Body = "
            <h2>Skin Assessment Details</h2>

            <p><strong>Name:</strong> {$name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Phone Number:</strong> {$number}</p>

            <hr>

            <p><strong>Skin Type:</strong> {$skin_type}</p>
            <p><strong>Concerns:</strong> {$concerns}</p>

            <p><strong>Where skincare failed:</strong> {$issues_list}</p>

            <p><strong>Perfect Solution Expectation:</strong><br>{$solution}</p>

            <p><strong>Skincare Priority:</strong> {$priority}</p>

            <p><strong>User Doubt:</strong><br>{$doubt}</p>

            <p><strong>Interested in Trials:</strong> {$trial}</p>
        ";

        $mail->send();

        // ✅ Popup (unchanged)
        echo '
        <script>
            setTimeout(function(){
                const popup = document.createElement("div");
                popup.style.position = "fixed";
                popup.style.top = "0";
                popup.style.left = "0";
                popup.style.width = "100%";
                popup.style.height = "100%";
                popup.style.background = "rgba(0,0,0,0.65)";
                popup.style.display = "flex";
                popup.style.justifyContent = "center";
                popup.style.alignItems = "center";
                popup.style.zIndex = "99999";

                popup.innerHTML = `
                    <div style="
                        background: black;
                        padding: 30px;
                        width: 380px;
                        text-align: center;
                        border-radius: 12px;
                    ">
                        <h3 style="margin-bottom:10px; color:#ffffff;">
                           Submission Successful
                        </h3>
                        <p style="font-size:16px; color:#ffffff;">
                            Thank you for your response ❤️
                        </p>
                        <button onclick="closePopup()" style="
                            margin-top:20px;
                            padding:10px 25px;
                            background:#007bff;
                            border:none;
                            color:white;
                            border-radius:6px;
                            cursor:pointer;
                        ">OK</button>
                    </div>
                `;

                document.body.appendChild(popup);

                window.closePopup = function(){
                    document.body.removeChild(popup);
                    window.location.href = "index.php";
                }
            }, 200);
        </script>
        ';

    } catch (Exception $e) {
        echo "Message could not be sent.<br>Mailer Error: {$mail->ErrorInfo}";
    }

} else {
    echo 'Access Denied';
}
?>