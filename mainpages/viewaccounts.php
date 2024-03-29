<?php 
    session_start();

    //initializing values
    $accountname = $description = $location ="";

    include_once('../FirebaseConfig/dbcon.php');
    //initializing errors array
    $errors = array("error" => "", "success" => "");

    if (isset($_POST['createBiz'])) {


      //getting session variables
      $phonenumber = $_SESSION['phonenumber'];
      $description = $_POST['description'];
      $accountname = $_POST['bizname'];
      $location = $_POST['location'];
      $profileurl = $_FILES['file']['name'];
     

        if(!empty($description) || !empty($description) || !empty($location) || !empty($profileurl)) {

            
        $sql1="SELECT * FROM bizaccounts where accountName = '$accountname' and phonenumber= '$phonenumber' Limit 1";
    
		$result= mysqli_query($con,$sql1);
		$queryResults= mysqli_num_rows($result);
		
		
        if($queryResults) {

            $errors['error'] = "You have an account with the same name, Use a different name.";
          
        }else{


            $profileurl = $_FILES['file']['name'];
            $tmp = $_FILES['file']['tmp_name'];
            move_uploaded_file($tmp,"../files/bizprofiles/bizprofiles".$profileurl);

            $sql = "INSERT INTO bizaccounts(phonenumber, accountName, description,location,profileurl) 
            values('$phonenumber', '$accountname','$description','$location','$profileurl');";
            $res = mysqli_query($con,$sql);
            
        
            if($res ==1){
        
             $errors['success'] ="Account Creation Success.";
                 
         
            }
         }
        }else{
            $errors['error'] ="Fill all fields and choose a business profile picture.";
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
    <link rel="stylesheet" type="text/css" href="../css/bizaccounts.css">
    <!--Bootstrap css Links -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <!--Bootstrap JS Links -->
    <script type="text/javascript" src="../bootstrap/js/bootstrap.min.js"></script>

</head>



</head>
<body>
<div class = "container-fluid">
    <div class = "row">
    
        <div class="col-sm-12">
        <?php include_once('../header.php'); ?>
        </div>
            
    
    </div>

    <div class="col-sm-12">
        <h4 id="h4">Your Business Accounts:</h4>
<?php

include_once('../FirebaseConfig/dbcon.php');
  $phonenumber = $_SESSION['phonenumber'];

  $ref_table ="bizaccounts";
  $fetchData = $database->getReference($ref_table)->getValue();

  if($fetchData >0) {
      foreach($fetchData as $key =>$row){
        if($row['phonenumber'] === $phonenumber) {
            echo "
                    
            <div style='margin-bottom: 5%;text-align:centre;margin-left: 15%;'>
           
            <div style='text-transform: uppercase;color: green;margin-left:0%; text-align:centre;
            margin-top: 4%;margin-bottom: 4%;'>
            <h2 style='text-decoration: underline;text-align: 'centre';'>".$row['accountName']."</h2>
            <div>
            
            <img src='../files/bizprofiles/bizprofiles".$row['profileurl']."' style = 'width: 20%;border-radius:100%; height:auto;'>
                   
            </div>

            <div style='margin-top: 1%; '>
           
                <p>".$row['description']."</p>
                <a href='intobizacc.php?acc_id=".$key."'>
                <button style='margin-left: 0%;margin-top:19px; color:red;'>View</button>
                </a>

                <a href='viewchatmates.php?acc_id=".$key."'>
                <button style='margin-left: 10%;margin-top:19px; color:red;'>messages</button>
                </a>

                <hr>
             </div>
            </div>
            
            </div>
            ";

        }
      }
    }


?>

    </div>


</div>




</body>
</html>