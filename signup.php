<?php
session_start();

//initializing input values
$fullname = $email= $password = $passwordconfirm = $securitykeyConfirm = $securitykey =$phonenumber = '';

$errors = array("Err" => "", "passwordErr" => "", "success" => "");

    //Requiring DB configs
include_once('./FirebaseConfig/dbcon.php');


if(isset($_POST['submit'])){

    $fullname = $_POST['fullname'];
    $securitykeyConfirm = $_POST['securitykeyConfirm'];
    $securitykey = $_POST['securitykey'];
    $ThePhonenumber = $_POST['phonenumber'];
    $email = $_POST['email'];
   
    $password = $_POST['password'];
    $passwordconfirm = $_POST['passwordconfirm'];
   
    //Ensure Phonenumber has country code.
    if($ThePhonenumber[0] === "0") {
        $phonenumber = "+254".substr($ThePhonenumber,1, 9);
    }else if($ThePhonenumber[0] === "+") {
        $phonenumber = $ThePhonenumber;
    }


     if($password != $passwordconfirm || $securitykey != $securitykeyConfirm){
         $errors['passwordErr'] = "Password or security key with their confirmations does not match";
      
     }elseif(empty($email) || empty($fullname) || empty($securitykey)|| empty($securitykeyConfirm)||empty($password) || empty($passwordconfirm) || empty($phonenumber)){

        $errors['Err'] = "Fill all the fields.";
     }else{


  
        $ref_table ="authentication";
        $fetchData = $database->getReference($ref_table)->getValue();
    
        if($fetchData >0) {
            foreach($fetchData as $key =>$row) {
                if($row['phonenumber'] === $phonenumber) {
                    $_SESSION['alreadyExists'] = "true";
                    $errors['passwordErr'] = "A user with same phonenumber already exist.";
                }
            }

           

            if($_SESSION['alreadyExists'] != "true"){
                $password1 = md5($password);//encryption of password
                $securitykey2 = md5($securitykey);
     
                 //Insert Data Into firebase Realtime Database
     
                 $postData = [
                     "fullname" => $fullname,
                     "phonenumber" => $phonenumber,
                     "securitykey" => $securitykey2,
                     "password" => $password1,
                     "email" => $email,
                     'status' => "Notblocked"
     
                 ];
                 
                 
                 $postRef = $database->getReference($ref_table)->push($postData);
         
                 if($postRef){
     
                     //set session variables
                     $_SESSION['fullname'] = $fullname;
                     $_SESSION['phonenumber'] = $phonenumber;
                     $_SESSION['email'] = $email;
     
                     $errors['success'] = "Registration successful. You are now logged in.";
                     
                    

                     echo "
                     <script>
                             navigator.geolocation.getCurrentPosition(function(pos) {
                                 var ab = pos.coords.latitude;
                                 var ac = pos.coords.longitude;
                                 window.open('mainpages/radius.php?lat=' + ab + '&long=' + ac, '_self')
                             });
                         
                     </script>
     
                     "; 
                  } 
            }

            $_SESSION['alreadyExists'] = "false";
     
        }else{
           $password1 = md5($password);//encryption of password
           $securitykey2 = md5($securitykey);

            //Insert Data Into firebase Realtime Database

            $postData = [
                "fullname" => $fullname,
                "phonenumber" => $phonenumber,
                "securitykey" => $securitykey2,
                "password" => $password1,
                "email" => $email,
                'status' => "Notblocked"
            ];
            
            
            $postRef = $database->getReference($ref_table)->push($postData);
	
            if($postRef){

                //set session variables
                $_SESSION['fullname'] = $fullname;
                $_SESSION['email'] = $email;
                $_SESSION['phonenumber'] = $phonenumber;
            

                $errors['success'] = "Registration successful. You are now logged in.";
                
                
                echo "
                <script>
                        navigator.geolocation.getCurrentPosition(function(pos) {
                            var ab = pos.coords.latitude;
                            var ac = pos.coords.longitude;
                            window.open('mainpages/radius.php?lat=' + ab + '&long=' + ac, '_self')
                        });
                    
                </script>

                "; 
             }

        }
    }

  


}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location-Based Ecommerce System</title>

    <!--Css link-->
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <!--Bootstrap css Links -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!--Bootstrap JS Links -->
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>

<script>
function adminLogin() {
    location.replace("home.php");
}

</script>


</head>
<body>
<div class="col-sm-12">
        <?php include_once('header1.php'); ?>
</div>

<div class = "container" id="headerbody">
<div class = "row" style='margin-top: -2%;'>

    <div class="row">
        <div class="col-sm-12">
            <p id="topparagraph" style="font-size: 20px; margin-top: 5px;">Create An Account:</p>
        </div>
    </div>

    <div class="row">
    <div class="col-sm-12" id="topparagraph" style="text-align: center; font-style: bold; background: lightgrey;border-radius: 20px;">
            <form action="signup.php" method="post" >
                 <br>
                <div><h5 style="color: red;"><?php echo $errors['Err']; ?></h5></div>

                <input  class="reginput" type="text" name = "fullname" placeholder ="Enter Full Name" value="<?php echo $fullname;?>"> <br><br>
            
                <input  class="passinput" type="phone" name = "phonenumber" placeholder ="Phone Number" value="<?php echo $phonenumber;?>"> <br><br>
                <input  class="passinput" type="email" name = "email" placeholder ="Email" value="<?php echo $email;?>"> <br><br>
                <input  class="passinput" type="text" name = "securitykey" placeholder ="Set Security Key" value="<?php echo $securitykey;?>"> <br><br>
                <input  class="passinput" type="text" name = "securitykeyConfirm" placeholder ="Confirm Security Key" value="<?php echo $securitykeyConfirm;?>"> <br><br>
                
                <input  class="passinput" type="password" name = "password" placeholder ="Set password" value="<?php echo $password;?>"> <br><br>
                <input  class="passinput" type="password" name = "passwordconfirm" placeholder ="Repeat password" value="<?php echo $passwordconfirm;?>"> <br><br>
            
                <div><h5 style="color: red;"><?php echo $errors['passwordErr']; ?></h5></div>
                <div><h5 style="color: green;"><?php echo $errors['success']; ?></h5></div>

                <button class='btn btn-success' name="submit" title="sign Up" >Sign Up</button>

            </form>

            <div class="row" id="topparagraph">
            <div class="col-sm-12" id ="bottomdiv">
            <br>
                <a id="reset" href="index.php"> Go back to login page.</a>
                
            </div>
            </div>
        </div>
    </div> <br> 


</div>

</div>

</body>
</html>