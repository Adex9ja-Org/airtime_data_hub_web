<?php
include_once 'phpfiles/dbconn.php';
if(isset($_GET["email"]) && isset($_GET["otp_code"])){
    $email = clean(base64_decode($_GET["email"]));
    $otp_code = clean(base64_decode($_GET["otp_code"]));
    $query = "select O.*, U.fullname from otp_entity as O inner  join user_entity as U on O.email = U.email where O.email = '$email' and O.otp_code = '$otp_code' and timestampdiff(HOUR, O.gen_date, now())<= 24 and O.active = 1";
    $result = $mysqli->query($query);
    if(mysqli_num_rows($result) > 0){
        $data = mysqli_fetch_object($result);
    }
    else
        header("location:recovery_error.php");
}
else{
    if(!isset($_POST["submit"]))
        header("location:recovery_error.php");
}


if(isset($_POST["submit"])){
    $password = clean($_POST["password"]);
    $repassword = clean($_POST["repassword"]);
    $email = clean(base64_decode($_POST["email"]));
    $otp_code = clean(base64_decode($_POST["otp_code"]));
    if($password == $repassword){
        $encPassword = base64_encode($password);
        $query = "update user_entity set password = '$encPassword' where email = '$email'";
        if($mysqli->query($query) === true){
            $query = "update otp_entity set active = 0 where otp_code = '$otp_code' and email = '$email'";
            $mysqli->query($query);
            header("location:recovery_success.php");
        }
        else
            $msg = sprintf($errTemplate, "Could not update password...Please try again!");
    }
    else
        $msg = sprintf($errTemplate, "Password Mis-match");
}


?>
<!DOCTYPE html>
<html lang="en">
<!--<![endif]-->
<head>
    <?php include 'include/head.php'; ?>
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Aitime Data Hub</a>
        </div>

</nav>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <h4>Hi <?php echo $data->fullname; ?></h4>
            <form method="post">
                <div class="row">
                    <?php if(isset($msg)) echo $msg;?>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="device_name">Enter New Password</label>
                            <input type="text"  name="password" class="form-control" placeholder="Enter New Password here" required>
                        </div>
                        <div class="form-group">
                            <label for="sn">Re-Enter New Password</label>
                            <input type="text" name="repassword" class="form-control" placeholder="Re-enter Password here..." required>
                        </div>
                        <input type="hidden" value="<?php if(isset($_GET["email"])) echo $_GET["email"] ?>" name="email">
                        <input type="hidden" value="<?php if(isset($_GET["otp_code"])) echo $_GET["otp_code"] ?>" name="otp_code">
                        <input type="submit" name="submit" value="Reset Password" class="btn btn-primary">
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>


</body>
</html>
