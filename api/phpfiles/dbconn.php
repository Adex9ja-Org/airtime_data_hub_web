<?php
	$host ="localhost";
	$username = "airtimed_a";
	$password = "*D21]Mpa9qN2So";
	 $dbname ="airtime_data_hub";
	 $mysqli = new mysqli($host,$username,$password,$dbname);

	function clean($str)
    {
        $str = @trim($str);
        $str = addslashes($str);
        if(get_magic_quotes_gpc())
        {
            $str = stripslashes($str);
        }
        return htmlentities($str);
    }
    function sendPushNotificationToUser($mysqli, $email, $message, $title){
    //Send Notification
    $query = "SELECT token from user_entity where email = '$email' and token <> ''";
    $result = $mysqli->query($query);
    $registrationIDs = array();
    while($row =mysqli_fetch_assoc($result)){
        $registrationIDs[] =  $row["token"];
    }
    $url = "https://fcm.googleapis.com/fcm/send";
    $serverKey = 'AAAAEKboYi0:APA91bGDYaspjA2aeGkyf2YJ0yS_XcuxEDa82wfwNEVZL32lBZ09jaS9Hqp1QQjWs-dI5wWAwG5WNtfpWYKBn02SQuf-aoubpyyn0LlDeM7o7e-a5H1I3Xav9tCiMg3lcFZPvCBMbLgw';
    $notification = array('title' =>$title , 'body' =>  $message, 'sound' => 'default', 'badge' => '1');
    $arrayToSend = array('registration_ids' => $registrationIDs, 'data' => $notification, 'priority'=>'high');
    $json = json_encode($arrayToSend);
    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Authorization: key='. $serverKey;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //Send the request
    curl_exec($ch);
}
    function sendPushNotificationToAdmin($mysqli, $message, $title){
    //Send Notification
    $query = "SELECT token from user_entity where userRole = 'Admin' and token <> ''";
    $result = $mysqli->query($query);
    $registrationIDs = array();
    while($row =mysqli_fetch_assoc($result)){
        $registrationIDs[] =  $row["token"];
    }
    $url = "https://fcm.googleapis.com/fcm/send";
    $serverKey = 'AAAAEKboYi0:APA91bGDYaspjA2aeGkyf2YJ0yS_XcuxEDa82wfwNEVZL32lBZ09jaS9Hqp1QQjWs-dI5wWAwG5WNtfpWYKBn02SQuf-aoubpyyn0LlDeM7o7e-a5H1I3Xav9tCiMg3lcFZPvCBMbLgw';
    $notification = array('title' =>$title , 'body' =>  $message, 'sound' => 'default', 'badge' => '1');
    $arrayToSend = array('registration_ids' => $registrationIDs, 'data' => $notification, 'priority'=>'high');
    $json = json_encode($arrayToSend);
    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Authorization: key='. $serverKey;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //Send the request
    curl_exec($ch);
}
    function getRef(){
	    return Date("Ymdhis");
    }
    function sendPasswordRecoveryEmail($email, $fullname, $token){
	    $encEmail = base64_encode($email);
	    $encToken = base64_encode($token);
        $htmlContent = '<html>
                  <body style="background: #e4e2e2">
		<center>
                  <div style="width: 100%;border-radius: 5px 5px 5px 5px;height:60%">
    
                  <div style="background: white;padding:10%;margin-top:25px">
                   <div>
                    <h1>Airtime Data Hub </h1>
                   </div>
                  <h3  style="color: #1ab394;font-family: Open Sans, helvetica, arial, sans-serif;margin:3%">Account Recovery</h3>
                  <p style="padding:10px;line-height:200%">Hi '.$fullname.', You requested a password reset on your account.</p>
                  <p><a a href="https://www.airtimedatahub.com/mobile/forgot_password.php?email='.$encEmail.'&otp_code='.$encToken.'">Click Here</a> to complete the recovery process. If the link is inaccessible, copy and paste the url below in your browser</p>
                  <p>href="https://www.airtimedatahub.com/mobile/forgot_password.php?email='.$encEmail.'&otp_code='.$encToken.'"</p>
                 <div >
                    <div></div>
                     </div>
                   <br>
                   <div>
                    <p><strong>Copyright</strong> &copy; <i id="today_d">2019</i> Airtime Data Hub</p>
                   </div>
               </div>
    
               </div>
             </center>
          </body>
          </html>';
        $from ='<info@airtimedatahub.com>';
        $headers = 'From: ' . $from. "\r\n";
        $headers  .= 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $subject = "Account Recovery";
        $send = mail($email,$subject,$htmlContent,$headers);
    }
    function sendRegistrationMail($fullname, $email, $phoneno, $address){
        $htmlContent = '<html>
                  <body style="background: #e4e2e2">
		<center>
                  <div style="width: 100%;border-radius: 5px 5px 5px 5px;height:60%">
    
                  <div style="background: white;padding:10%;margin-top:25px">
                   <div>
                    <h1>Airtime Data Hub </h1>
                   </div>
                  <h3  style="color: #1ab394;font-family: Open Sans, helvetica, arial, sans-serif;margin:3%">Welcome to Airtime Data Hub. Please find your registration details below</h3>
                  <p style="padding:10px;line-height:200%">Full Name:  '.$fullname.'</p>
                  <p style="padding:10px;line-height:200%">Email:  '.$email.'</p>
                  <p style="padding:10px;line-height:200%">Phone Number:   '.$phoneno.'</p>
                  <p style="padding:10px;line-height:200%">Address:   '.$address.'</p><br/><br/><br/>
                  <p>Our services include: Airtime sales and purchase, data purchase and Bill payments</p>
                  <p>If you have any enquiry about our products and services, kindly contact our customer care via call or whatsapp on +2347062242007 . Thanks</p>
                 <div >
                    <div></div>
                     </div>
                   <br>
                   <div>
                    <p><strong>Copyright</strong> &copy; <i id="today_d">2019</i> Airtime Data Hub</p>
                   </div>
               </div>
    
               </div>
             </center>
          </body>
          </html>';
        $from ='<info@airtimedatahub.com>';
        $headers = 'From: ' . $from. "\r\n";
        $headers  .= 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $subject = "Account Registration";
        $send = mail($email,$subject,$htmlContent,$headers);
    }
    function validate_mobile($mobile)
    {
        return preg_match('/^[0-9]{11}+$/', $mobile) || preg_match( '/(0|\+?\d{2})(\d{7,8})/', $mobile);
    }
    function validate_name($name){
	    return preg_match("/^[a-zA-Z ]*$/",$name);
    }
    $msgTemplate = "<div class='alert alert-success alert-dismissible' role='alert'><a href='#' class='close' data-dismiss='alert' aria-label='close'>×</a><center>%s</center></div>";
    $errTemplate = "<div class='alert alert-warning alert-dismissible' role='alert'><a href='#' class='close' data-dismiss='alert' aria-label='close'>×</a><center>%s</center></div>";

