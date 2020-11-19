<?php
    include 'phpfiles/dbconn.php';
    include 'phpfiles/JsonResponse.php';

    if(isset($_POST["resetPassword"])){
    $user = json_decode($_POST["resetPassword"]);
    $phoneno = clean($user->phoneno);
    $password = clean(base64_encode($user->password));
    $query = "update user_entity set password = '$password' where phoneno = '$phoneno'";
    if( $mysqli->query($query) === true )
        echo '{"responseCode":"00","responseStatus":"Updated Successfully!"}';
    else
        echo '{"responseCode":"-01","responseStatus":"Error Occur...Please try again!"}';

    }
    elseif(isset($_POST["forgotPassword"])){
        $email = clean($_POST["forgotPassword"]);
        $query = "select * from user_entity where email = '$email'";
        $result = $mysqli->query($query);
        if(mysqli_num_rows($result) > 0) {//does user exist
            $data = mysqli_fetch_object($result);
            $email = $data->email;
            $fullname = $data->fullname; //check if otp generated earlier hasn't elapse 24hrs
            $query = "select count(*) as counter, concat(conv(floor(rand() * 99999999999999), 20, 36),conv(floor(rand() * 99999999999999), 20, 36),conv(floor(rand() * 99999999999999), 20, 36)) as otp, otp_code from otp_entity where email = '$email' and timestampdiff(HOUR, gen_date, now())<= 24 and active = 1;";
            $result = $mysqli->query($query);
            if(mysqli_num_rows($result) > 0){
                $data = mysqli_fetch_object($result);
                $otp_code = $data->counter == 0 ? $data->otp : $data->otp_code;
                if($data->counter == 0){//Insert new otp if none exist / already expired
                    $query = "INSERT INTO otp_entity(email, otp_code) VALUES('$email', '$otp_code')";
                    $mysqli->query($query);
                }
                sendPasswordRecoveryEmail($email, $fullname, $otp_code);
                echo '{"responseCode":"00","responseStatus":"A password reset link has been sent to your email"}';
            }
            else
                echo '{"responseCode":"-02","responseStatus":"Error Occurs!"}';
        }
        else
            echo '{"responseCode":"-02","responseStatus":"Email does not exist!"}';
    }
	else
	    echo '{"responseCode":"-01","responseStatus":"Update your app to the latest version from playstore"}';