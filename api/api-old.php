<?php 
	include 'phpfiles/dbconn.php';
	include 'phpfiles/JsonResponse.php';
	if(isset($_POST["verifyUser"])){
		$user = json_decode($_POST["verifyUser"]);
		$email = clean($user->email);
		$password = clean(base64_encode($user->password));
		$query = "select * from user_entity where email = '$email' and password = '$password' and active = 1 ";
		$result = $mysqli->query($query);
		if(mysqli_num_rows($result) > 0)
			echo '{"responseCode":"00","userDetail": ' . json_encode($result->fetch_assoc()) . '}';
		else		
			echo '{"responseCode":"-01","responseStatus":"Invalid Username/Password"}';
	}
	elseif(isset($_POST["registerUser"])){
		$user = json_decode($_POST["registerUser"]);
		$fullname = clean($user->fullname);
		$email = clean($user->email);
		$phoneno = clean($user->phoneno);
		$password = clean(base64_encode($user->password));
        $address = clean($user->address);

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if(validate_mobile($phoneno) && strlen($phoneno) >= 11){
                if(validate_name($fullname)){
                    $query = "insert into user_entity (fullname, email, phoneno, password, address) value ('$fullname', '$email', '$phoneno', '$password', '$address')";
                    if( $mysqli->query($query) === true ){
                        $query = "select * from user_entity where email = '$email'";
                        $result = $mysqli->query($query);
                        if(mysqli_num_rows($result) > 0){
                            $data = mysqli_fetch_object($result);
                            echo json_encode(new JsonResponse("00", json_encode($data)));
                            sendRegistrationMail($fullname, $email, $phoneno, $address);
                        }
                        else
                            echo json_encode(new JsonResponse("-02", "Invalid User"));
                    }
                    else
                        echo json_encode(new JsonResponse("-02", "User already exist!"));
                }
                else
                    echo json_encode(new JsonResponse("-02", "Invalid Name"));
            }
            else
                echo json_encode(new JsonResponse("-02", "Invalid Phone Number"));
        }
        else
            echo json_encode(new JsonResponse("-02", "Invalid Email Address"));
       
	}
	elseif(isset($_POST["verifyPhoneNumber"])){
		$phoneno = clean($_POST["verifyPhoneNumber"]);
		$query = "select * from user_entity where phoneno = '$phoneno'";
		$result = $mysqli->query($query);
		if(mysqli_num_rows($result) > 0)
			echo '{"responseCode":"00","userDetail": ' . json_encode($result->fetch_assoc()) . '}';
		else		
			echo '{"responseCode":"-01","responseStatus":"Phone Number Verification Fails"}';
	}
	elseif(isset($_POST["resetPassword"])){
		$user = json_decode($_POST["resetPassword"]);
		$phoneno = clean($user->phoneno);
		$password = clean(base64_encode($user->password));
		$query = "update user_entity set password = '$password' where phoneno = '$phoneno'";
		if( $mysqli->query($query) === true )
            echo '{"responseCode":"00","responseStatus":"Updated Successfully!"}';
        else
        	echo '{"responseCode":"-01","responseStatus":"Error Occur...Please try again!"}';

	}
    elseif(isset($_POST["updateUserProfile"])){
        $user = json_decode($_POST["updateUserProfile"]);
        $email = clean($user->email);
        $phoneno = clean($user->phoneno);
        $fullname = clean($user->fullname);
        $address = clean($user->address);
        $acct_number = clean($user->acct_number);
        $bank_code = clean($user->bank_code);
        $acct_name = isset($user->acct_name) ? clean($user->acct_name) : '';
        $pin = isset($user->pin) ? clean($user->pin) : '';
        $dob = isset($user->dob) ? clean($user->dob) : '';
        $gender = isset($user->gender) ? clean($user->gender) : '';
        $doc_type = isset($user->password) ? clean($user->password) : 'NIL';
        $query = "update user_entity set acct_name = '$acct_name', pin = '$pin', dob = '$dob', gender = '$gender', password = '$password', updated_at = current_date,   fullname = '$fullname', phoneno = '$phoneno', address = '$address', bank_code = '$bank_code', acct_number = '$acct_number' where email = '$email'";
        if( $mysqli->query($query) === true ){
            echo '{"responseCode":"00", "responseStatus":"Updated Successfully!", "userDetail": '. $_POST["updateUserProfile"] .'}';//userDetail
        }

        else
            echo '{"responseCode":"-01","responseStatus":"Error Occur...Please try again!"}';
    }
    elseif(isset($_POST["registerToken"])){
        $registerToken = json_decode($_POST["registerToken"]);
        $token = clean($registerToken->token);
        $email = clean($registerToken->email);
        $query = "update user_entity set token = '$token' where email = '$email'";
        if( $mysqli->query($query) === true )
            echo '{"responseCode":"00","responseStatus":"Token Updated!"}';
        else
            echo '{"responseCode":"-01","responseStatus":"'.$mysqli->error.'"}';
    }
    elseif(isset($_POST["contactUs"])){
        $contactUs = json_decode($_POST["contactUs"]);
        $email = clean($contactUs->email);
        $message = clean($contactUs->message);
        $support_id = clean($contactUs->support_id);
        $query = "insert into support_entity( message, email, support_id ) values('$message', '$email', '$support_id')";
        if( $mysqli->query($query) === true ){
            echo '{"responseCode":"00","responseStatus":"Submitted Successfully!"}';
            sendPushNotificationToAdmin($mysqli, $_POST["contactUs"],"02");
        }
        else
            echo '{"responseCode":"-01","responseStatus":"Error occurs!"}';
    }
    elseif(isset($_POST["getBankList"])){
        $query = "select * from bank_entity where bank_code <> '000' order  by bank_name";
        $result = $mysqli->query($query);
        if(mysqli_num_rows($result) > 0){
            $emparray = array();
            while($row =mysqli_fetch_assoc($result))
                $emparray[] = $row;

            echo '{"responseCode":"00","bankList": ' . json_encode($emparray). '}';
        }
        else
            echo '{"responseCode":"-01","responseStatus":"No record found"}';
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
    elseif(isset($_POST["getProducts"])){
	    $serviceId = clean($_POST["getProducts"]);
        $query = "select product_id, product_name, product_description, product_icon, service_id, active from product_entity where service_id = '$serviceId'";
        $result = $mysqli->query($query);
        if(mysqli_num_rows($result) > 0){
            $emparray = array();
            while($row =mysqli_fetch_assoc($result)){
                $product_icon = explode('/', $row['product_icon']);
                $row['product_icon'] = $product_icon[2];
                $emparray[] = $row;
            }
            echo '{"responseCode":"00","productList": ' . json_encode($emparray). '}';
        }
        else
            echo '{"responseCode":"-01","responseStatus":"No record found"}';
    }
    elseif(isset($_POST["getAllProduct"])){
        $query = "select * from product_entity";
        $result = $mysqli->query($query);
        if(mysqli_num_rows($result) > 0){
            $emparray = array();
            while($row =mysqli_fetch_assoc($result)){
                $product_icon = explode('/', $row['product_icon']);
                $row['product_icon'] = $product_icon[2];
                $emparray[] = $row;
            }
            echo '{"responseCode":"00","productList": ' . json_encode($emparray). '}';
        }
        else
            echo '{"responseCode":"-01","responseStatus":"No record found"}';
    }
    elseif(isset($_POST["getSubProducts"])){
        $productId = clean($_POST["getSubProducts"]);
        $query = "select S.*, C.per_charges, P.service_id from sub_product_entity as S inner join product_entity as P on S.product_id = P.product_id  inner join conversion_rate_entity as C on S.conversion_id = C.conversion_id where S.product_id = '$productId'";
        $result = $mysqli->query($query);
        if(mysqli_num_rows($result) > 0){
            $emparray = array();
            while($row =mysqli_fetch_assoc($result))
                $emparray[] = $row;

            echo '{"responseCode":"00","subProductList": ' . json_encode($emparray). '}';
        }
        else
            echo '{"responseCode":"-01","responseStatus":"No record found"}';
    }
    elseif(isset($_POST["postTransaction"])){
        $postTransaction = json_decode($_POST["postTransaction"]);
        $ref = clean($postTransaction->ref);
        $email = clean($postTransaction->email);
        $amount = clean($postTransaction->amount);
        $sub_prod_id = clean($postTransaction->sub_prod_id);
        $cr_acc = clean($postTransaction->cr_acc);
        $dr_acc = clean($postTransaction->dr_acc);
        $acc_no = clean($postTransaction->acc_no);
        $created_at = date("Y-m-d H:i:s");
        $query = "insert into voucher_entity (ref, email, sub_prod_id, dr_acc, cr_acc, amount, acc_no, created_at) values ('$ref', '$email', '$sub_prod_id', '$dr_acc', '$cr_acc', $amount, '$acc_no', '$created_at')";
        if($mysqli->query($query) === true){
            echo '{"responseCode":"00","responseStatus":"Request Successful"}';
            sendPushNotificationToAdmin($mysqli, $_POST["postTransaction"],"03");
        }
        else
            echo '{"responseCode":"-01","responseStatus":"Error occurs!"}';
    }
    elseif(isset($_POST["sendRechargeCard"])){
        $sendRechargeCard = json_decode($_POST["sendRechargeCard"]);
        $ref = clean($sendRechargeCard->ref);
        $email = clean($sendRechargeCard->email);
        $amount = clean($sendRechargeCard->amount);
        $sub_prod_id = clean($sendRechargeCard->sub_prod_id);
        $cardPin = clean($sendRechargeCard->cardPin);
        $created_at = date("Y-m-d H:i:s");
        $query = "insert into voucher_entity (ref, email, sub_prod_id, cardPin, amount, created_at) values ('$ref', '$email', '$sub_prod_id', $cardPin, $amount, '$created_at')";
        if($mysqli->query($query) === true){
            echo '{"responseCode":"00","responseStatus":"Submitted Successful. Your Bank Account will be credited ounce transaction approved by admin"}';
            sendPushNotificationToAdmin($mysqli, $_POST["sendRechargeCard"],"03");
        }
        else
            echo '{"responseCode":"-01","responseStatus":"No record found"}';
    }
    elseif(isset($_POST["approveTransaction"])){
        $approveTransaction = json_decode($_POST["approveTransaction"]);
        $ref = clean($approveTransaction->ref);
        $email = clean($approveTransaction->email);
        $query = "update voucher_entity set approvalStatus = 1 where ref = '$ref'";
        if($mysqli->query($query) === true){
            echo '{"responseCode":"00","responseStatus":"Approved Successfully!"}';
            sendPushNotificationToUser($mysqli, $email, $_POST["approveTransaction"], "01");
        }
        else
            echo '{"responseCode":"-01","responseStatus":"Error Occurs!"}';
    }
    elseif(isset($_POST["getStatistics"])){
        $query = "SELECT sum(approvalStatus) as approved, sum( case when approvalStatus = 0 then 1 else 0 end) as pending, (select count(*) from user_entity) as users, (select count(*) from sub_product_entity where active = 1) as products, (select count(*) from support_entity) as feedbacks FROM `voucher_entity`";
        $result = $mysqli->query($query);
        if(mysqli_num_rows($result) > 0)
            echo '{"responseCode":"00","statistics":'.json_encode(mysqli_fetch_assoc($result)).'}';
        else
            echo '{"responseCode":"-01","responseStatus":"Error Occurs!"}';
    }
    elseif(isset($_POST["getPaymentAcct"])){
        $query = "select P.*, B.bank_name from payment_account_entity as P INNER join bank_entity as B on P.bank_code = B.bank_code";
        $result = $mysqli->query($query);
        if(mysqli_num_rows($result) > 0){
            $emparray = array();
            while($row =mysqli_fetch_assoc($result))
                $emparray[] = $row;

            echo '{"responseCode":"00","paymentAccList": ' . json_encode($emparray). '}';
        }
        else
            echo '{"responseCode":"-01","responseStatus":"No record found"}';
    }
    elseif(isset($_POST["getFeedBacks"])){
        $query = "select C.*, U.fullname, U.phoneno, U.address from support_entity as C INNER JOIN user_entity as U on C.email = U.email";
        $result = $mysqli->query($query);
        if(mysqli_num_rows($result) > 0){
            $emparray = array();
            while($row =mysqli_fetch_assoc($result))
                $emparray[] = $row;

            echo '{"responseCode":"00","feedbackList": ' . json_encode($emparray). '}';
        }
        else
            echo '{"responseCode":"-01","responseStatus":"No record found"}';
    }
    elseif(isset($_POST["getSubProdBySerID"])){
	    $serviceID = clean($_POST["getSubProdBySerID"]);
        $query = "select S.*, C.per_charges, P.service_id from sub_product_entity as S inner join conversion_rate_entity as C on S.conversion_id = C.conversion_id inner join product_entity as P on S.product_id = P.product_id where P.service_id = '$serviceID'";
        $result = $mysqli->query($query);
        if(mysqli_num_rows($result) > 0){
            $emparray = array();
            while($row =mysqli_fetch_assoc($result))
                $emparray[] = $row;

            echo '{"responseCode":"00","subProductList": ' . json_encode($emparray). '}';
        }
        else
            echo '{"responseCode":"-01","responseStatus":"No record found"}';
    }
    elseif(isset($_POST["deleteSubscription"])){
	    $deleteSubscription = json_decode($_POST["deleteSubscription"]);
	    $sub_prod_id = clean($deleteSubscription->sub_prod_id);
        $query = "update sub_product_entity set active = 0 where sub_prod_id = '$sub_prod_id'";
        if($mysqli->query($query) === true)
            echo '{"responseCode":"00","responseStatus":"Updated Successfully!"}';
        else
            echo '{"responseCode":"-01","responseStatus":"No record found"}';
    }
    elseif(isset($_POST["addNewSubProduct"])){
	    $addNewSubProduct = json_decode($_POST["addNewSubProduct"]);
	    $sub_desc = clean($addNewSubProduct->sub_desc);
	    $sub_name = clean($addNewSubProduct->sub_name);
	    $sub_price = clean($addNewSubProduct->sub_price);
	    $sub_prod_id = clean($addNewSubProduct->sub_prod_id);
	    $conversion_id = clean($addNewSubProduct->conversion_id);
	    $product_id = clean($addNewSubProduct->product_id);
	    $active = clean($addNewSubProduct->active);
        $query = "insert into sub_product_entity(sub_desc, sub_name, sub_price, sub_prod_id, conversion_id, product_id, active ) values ('$sub_desc', '$sub_name', $sub_price, '$sub_prod_id', '$conversion_id', '$product_id', $active )";
        if($mysqli->query($query) === true)
            echo '{"responseCode":"00","responseStatus":"Inserted Successfully!"}';
        else
            echo '{"responseCode":"-01","responseStatus":"Error occurs while inserting"}';
    }
    elseif(isset($_POST["getRequests"])){
        $query = "SELECT V.*, V.created_at as transDate, P.product_icon, S.sub_name, P.product_name, P.product_description from voucher_entity as V INNER JOIN sub_product_entity as S on V.sub_prod_id = S.sub_prod_id INNER JOIN product_entity as P on S.product_id = P.product_id where V.approvalStatus = 0";
        $result = $mysqli->query($query);
        if(mysqli_num_rows($result) > 0){
            $emparray = array();
            while($row =mysqli_fetch_assoc($result))
                $emparray[] = $row;

            echo '{"responseCode":"00","voucherList": ' . json_encode($emparray). '}';
        }
        else
            echo '{"responseCode":"-01","responseStatus":"No record found"}';
    }
    elseif(isset($_POST["getSubProds"])){
        $query = "select S.*, C.per_charges, P.service_id from sub_product_entity as S inner join product_entity as P on S.product_id = P.product_id  inner join conversion_rate_entity as C on S.conversion_id = C.conversion_id";
        $result = $mysqli->query($query);
        if(mysqli_num_rows($result) > 0){
            $emparray = array();
            while($row =mysqli_fetch_assoc($result))
                $emparray[] = $row;

            echo '{"responseCode":"00","subProductList": ' . json_encode($emparray). '}';
        }
        else
            echo '{"responseCode":"-01","responseStatus":"No record found"}';
    }
    elseif(isset($_POST["updateSubProduct"])){
	    $updateSubProduct = json_decode($_POST["updateSubProduct"]);
        $sub_name = clean($updateSubProduct->sub_name);
        $sub_price = clean($updateSubProduct->sub_price);
        $sub_prod_id = clean($updateSubProduct->sub_prod_id);
        $query = "update sub_product_entity set sub_name = '$sub_name', sub_price = $sub_price where  sub_prod_id = '$sub_prod_id'";
        if($mysqli->query($query) === true)
            echo '{"responseCode":"00","responseStatus":"Updated Successfully!"}';
        else
            echo '{"responseCode":"-01","responseStatus":"Error occurs while inserting"}';
    }
    elseif(isset($_POST["getDataBalCodes"])){
        $query = "select * from data_balance_entity";
        $result = $mysqli->query($query);
        if(mysqli_num_rows($result) > 0){
            $emparray = array();
            while($row =mysqli_fetch_assoc($result))
                $emparray[] = $row;

            echo '{"responseCode":"00","dataBalCodes": ' . json_encode($emparray). '}';
        }
        else
            echo '{"responseCode":"-01","responseStatus":"No record found"}';
    }
    elseif(isset($_POST["getRegisteredUsers"])){
        $query = "select U.*, B.bank_name from user_entity as U left join bank_entity as B on U.bank_code = B.bank_code where U.active = 1";
        $result = $mysqli->query($query);
        if(mysqli_num_rows($result) > 0){
            $emparray = array();
            while($row =mysqli_fetch_assoc($result))
                $emparray[] = $row;

            echo '{"responseCode":"00","userList": ' . json_encode($emparray). '}';
        }
        else
            echo '{"responseCode":"-01","responseStatus":"No record found"}';
    }
    elseif(isset($_POST["getRegisteredUsersByEmail"])){
	    $email = clean($_POST["getRegisteredUsersByEmail"]);
        $query = "select U.*, B.bank_name from user_entity as U LEFT join bank_entity as B on U.bank_code = B.bank_code where email = '$email'";
        $result = $mysqli->query($query);
        if(mysqli_num_rows($result) > 0)
            echo '{"responseCode":"00","userDetail": ' . json_encode($result->fetch_assoc()) . '}';
        else
            echo '{"responseCode":"-01","responseStatus":"Invalid Username/Password"}';
    }
	elseif(isset($_POST["getWalletBalance"])){
	    $getWalletBalance = json_decode($_POST["getWalletBalance"]);
	    $email = clean($getWalletBalance->email);
	    $query = "SELECT sum((case when dr_acc = '$email' then -amount else amount end )) as totalAmount from voucher_entity where channel_name = 'Wallet' and (dr_acc = '$email' or cr_acc = '$email') ";
        $result = $mysqli->query($query);
        if(mysqli_num_rows($result) > 0){
            $data = mysqli_fetch_object($result);
            echo '{"responseCode":"00","responseStatus":"'.$data->totalAmount.'"}';
        }
        else
            echo '{"responseCode":"-01","responseStatus":"Error Occurs!"}';
    }
    elseif(isset($_POST["fundWallet"])){
        $postTransaction = json_decode($_POST["fundWallet"]);
        $ref = clean($postTransaction->ref);
        $email = clean($postTransaction->email);
        $amount = clean($postTransaction->amount);
        $cr_acc = clean($postTransaction->cr_acc);
        $sub_prod_id = "WALLET";
        $channel_name = "Wallet";
        $dr_acc = "WALLET";
        $approvalStatus = 1;
        $created_at = date("Y-m-d H:i:s");
        $query = "insert into voucher_entity (ref, email, dr_acc, cr_acc, amount, sub_prod_id, approvalStatus, channel_name, created_at, narration) values ('$ref', '$email', '$dr_acc', '$cr_acc', $amount, '$sub_prod_id', $approvalStatus, '$channel_name', '$created_at', 'Fund Wallet')";
        if($mysqli->query($query) === true)
            echo '{"responseCode":"00","responseStatus":"Request Successful"}';
        else
            echo '{"responseCode":"-01","responseStatus":"Error Occurs!"}';
    }
	elseif(isset($_POST["getConversions"])){
        $query = "SELECT * from conversion_rate_entity where per_charges > 0";
        $result = $mysqli->query($query);
        if(mysqli_num_rows($result) > 0){
            $emparray = array();
            while($row =mysqli_fetch_assoc($result))
                $emparray[] = $row;

            echo '{"responseCode":"00","conversionList": ' . json_encode($emparray). '}';
        }
        else
            echo '{"responseCode":"-01","responseStatus":"No record found"}';
    }
	elseif(isset($_POST["updateConversionRate"])){
	    $updateConversionRate = json_decode($_POST["updateConversionRate"]);
	    $conversion_name = clean($updateConversionRate->conversion_name);
	    $per_charges = clean($updateConversionRate->per_charges);
	    $conversion_id = clean($updateConversionRate->conversion_id);

	    $query = "update conversion_rate_entity  set conversion_name = '$conversion_name', per_charges = $per_charges where conversion_id = '$conversion_id' ";
        if($mysqli->query($query) === true)
            echo '{"responseCode":"00","responseStatus":"Updated Successfully!"}';
        else
            echo '{"responseCode":"-01","responseStatus":"Error Occurs!"}';
    }
	elseif(isset($_POST["getAllBanners"])){
	    $query = "select * from banner_entity";
        $result = $mysqli->query($query);
        if(mysqli_num_rows($result) > 0){
            $emparray = array();
            while($row =mysqli_fetch_assoc($result))
                $emparray[] = $row;

            echo '{"responseCode":"00","bannerList": ' . json_encode($emparray). '}';
        }
        else
            echo '{"responseCode":"-01","responseStatus":"No record found"}';
    }
	elseif(isset($_POST["getWalletHistory"])){
	    $email = clean($_POST['getWalletHistory']);
	    $query = "SELECT * FROM wallet_entity where email = '$email'";
        $result = $mysqli->query($query);
        if(mysqli_num_rows($result) > 0){
            $emparray = array();
            while($row =mysqli_fetch_assoc($result))
                $emparray[] = $row;

            echo '{"responseCode":"00","walletList": ' . json_encode($emparray). '}';
        }
        else
            echo '{"responseCode":"-01","responseStatus":"No record found"}';
    }
	else echo '{"responseCode":"-01","responseStatus":"Invalid post parameter"}';