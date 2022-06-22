<?php 
    session_start();

    //initializing values
    $accountname = $description = $location ="";

    include_once('../db.php');
    //initializing errors array
    $errors = array("error" => "", "success" => "");

    if (isset($_POST['createBiz'])) {


      //getting session variables
      $phonenumber = $_SESSION['phonenumber'];
      $description = mysqli_real_escape_string($con, $_POST['description']);
      $accountname = mysqli_real_escape_string($con, $_POST['bizname']);
      $location = mysqli_real_escape_string($con, $_POST['location']);
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
    <div class = "row" >
    
        <div class="col-sm-12">
        <?php include_once('../header.php'); ?>
        </div>
            
    
    </div>

    <div class="row" id="ToCentreRow">
    <div class="col-sm-12"> 
     <button  id="viewaccounts"><a href="viewaccounts.php"  id="viewaccounts">See your Business Accounts</a></button>
    </div>

    </div>
    <div class="row">

        <div class="col-sm-12" id="bizaccform">
        
        <h4>Create a new business Account:</h4>
            <form  action="businessAccount.php" method="post" enctype="multipart/form-data">
            <input class="passinput" type ="text" name="bizname" placeholder="Enter business name" value="<?php echo $accountname; ?>"><br><br>
            <input class="passinput" type="text" name="description" placeholder="Enter business Description"  value="<?php echo $description;?>"> <br><br>
            <input class="passinput" type="text" name="location" placeholder="Enter Business Location" value="<?php echo $location; ?>"><br><br>

          <label style="color: pink;"> <input style="display: none;" type="file" name="file" accept="image/*" >Choose Business Profile picture</label> <br><br>
            
        <!--Error display-->
        <div><h5 style="color: red;"><?php echo $errors['error']; ?></h5></div>
        <div><h5 style="color: green;"><?php echo $errors['success']; ?></h5></div>

          <button name="createBiz">Create Account</button>
        
            </form>
        </div>
    </div>
</div>




</body>
</html>