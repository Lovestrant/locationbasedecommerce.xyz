<?php 
    session_start();

    $_SESSION['acc_id'] = $_GET['acc_id'];

    include_once('../FirebaseConfig/dbcon.php');
    //initializing errors array
    $errors = array("error" => "", "success" => "");

    $accId = $_GET['acc_id'];
    if (isset($_POST['updateProfile'])) {


    //getting session variables
    $phonenumber = $_SESSION['phonenumber'];
   
    $accId = $_POST['hiddenid'];


    $ref_table ="bizaccounts";
    $fetchData = $database->getReference($ref_table)->getValue();
    if($fetchData >0) {
        foreach($fetchData as $key =>$row){
            if($row['phonenumber'] === $phonenumber && $key === $accId) { 

            $imgurl = $_FILES['file']['name'];
            $tmp = $_FILES['file']['tmp_name'];
            move_uploaded_file($tmp,"../files/bizprofiles/bizprofiles".$imgurl);

            $uid = $key;
            $UpdateData = [
                "accountName" => $row['accountName'],
                "description" => $row['description'],
                "location" => $row['location'],
                'phonenumber' => $row['phonenumber'],
                "profileurl" => $imgurl,
          
            ];

            $postData = [
            
                "accountName" => $row['accountName'],
                "description" => $row['description'],
                "location" => $row['location'],
                'phonenumber' => $row['phonenumber'],
                "profileurl" => $imgurl,
            
            ];
            

            // Create a key for a new post
            $ref_table = 'bizaccounts/'.$uid;
             $queryResult = $database->getReference($ref_table)->update($UpdateData);
          
            if($queryResult) {
                echo "<script>alert('Success')</script>"; 
                echo "<script>location.replace('../mainpages/intobizacc.php?acc_id=$key');</script>"; 
           
            }else {
                echo "<script>alert('Failed.')</script>"; 
                echo "<script>location.replace('../mainpages/intobizacc.php?acc_id=$key');</script>"; 
              
            }
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
    <link rel="stylesheet" type="text/css" href="../css/bizaccounts.css">
    <!--Bootstrap css Links -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <!--Bootstrap JS Links -->
    <script type="text/javascript" src="../bootstrap/js/bootstrap.min.js"></script>

<style>
.zoom2 {
    width:45%;
    height:auto;
    transition: transform ease-in-out 0.3s;
    }
.zoom2:hover {
    transform: scale(1.5);
    text-align: center;
    justify-content: center;
    }
    .zoom{
    width:45%;
    height:auto;
    transition: transform ease-in-out 0.3s;
    }
.zoom:hover {
    transform: scale(1.1);
    text-align: center;
    justify-content: center;
    
    }
</style>




</head>
<body>
<div class = "container-fluid">
    <div class = "row">
    
        <div class="col-sm-12">
        <?php include_once('../header.php'); ?>
        </div>
            
    
    </div>

    <div class="row">
<div class = "row" style="margin-left: 5%;text-align: centre;">
 
        <button style="color: red; margin-top: 1%;"><a style="color: red;" href="viewaccounts.php">Back to Business Profiles</a></button>
  
    <?php 
    
    if($_SESSION['phonenumber']) {

        include_once('../FirebaseConfig/dbcon.php');
          
        $phonenumber = $_SESSION['phonenumber'];
        $accId = $_GET['acc_id'];
      

        $ref_table ="bizaccounts";
        $fetchData = $database->getReference($ref_table)->getValue();
        if($fetchData >0) {
        foreach($fetchData as $key =>$row){
        if($row['phonenumber'] === $phonenumber && $key === $accId) {
            $accountName = $row['accountName'];

            echo "  
            <div>
                <h3 style='color: green;'>".$row['accountName']."</h3>
                <a href='createadvert.php?acc_id=$accId'><button style='color: red;'>Create Advert</button></a>
                <a href='vieworders.php?accountName=$accountName'><button style='color: blue;'>View Orders</button></a>
                </div>

            <div style='margin-top: 3%; text-align:centre; margin-bottom: 5%;'>
            <img src='../files/bizprofiles/bizprofiles".$row['profileurl']."' style = 'width: 20%;border-radius:100%; height:auto;'>
                    
            </div>
            ";
            
        }
      }
        }

                   
    }else{
    echo "<script>alert('Your Session has expired.You need to login again')</script>";
    echo "<script>location.replace('../index.php')</script>";
    }

                              
    ?>

<div class="row" style="margin: 3%;">
 <div class="col-sm-6">
    <form action = "intobizacc.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name= "hiddenid" value=<?php $id= $_GET['acc_id']; echo $id; ?>> <!-- Hidden input-->

        <input type="file" accept ="image/*" name="file"><br><br>
        <button name="updateProfile">Change Profile</button>
    </form>

    <div><h5 style="color: red;"><?php echo $errors['error']; ?></h5></div>
     <div><h5 style="color: green;"><?php echo $errors['success']; ?></h5></div>

 </div> 
 <br><br><br>
    
    <div class="col-sm-6">
        <p>This will change this accounts ads' cordinates to the current location; Cordinates determines your radius and who sees your ads. </p>
        <form action="intobizacc.php" method="post">
        <input type="hidden" name= "hiddenid" value=<?php $id= $_GET['acc_id']; echo $id; ?>> <!-- Hidden input-->
            <input type="hidden" name='lat' value="<?php $lat = $_SESSION['latitude']; echo $lat;?>">
            <input type="hidden" name='long' value="<?php $long = $_SESSION['longitude']; echo $long;?>">
            <button type="submit" name="changeLocation">Change All Ads' cordinates</button>
        </form>
  
    </div>



 </div>

 </div>

         <div class="container">
             <div class="row">
                 <div class="col-sm-12" id="ads">
                     <h2>This Account's Adverts:</h2>
     <?php 
    //Requiring DB configs

    include_once('../FirebaseConfig/dbcon.php');

          
        $phonenumber = $_SESSION['phonenumber'];
        $accId = $_GET['acc_id'];
      
        $ref_table ="Adverts";
        $fetchData = $database->getReference($ref_table)->getValue();

        if($fetchData >0) {
            foreach($fetchData as $key =>$row){
                if($row['phonenumber'] === $phonenumber && $row['id'] === $accId) {
                    if(!$row['picurl'] && !$row['picurl2']){
                        echo "  
                        <div>
                            <h3 style='color: green;'>".$row['adtitle']."</h3>
                            
                        </div>

                        <div style='margin-top: 1%; text-align:centre; margin-bottom: 5%;'>
                        
                        <p style='color: black;font-size:20px;margin-left:5%;margin-right:5%; '>".$row['description']."</p>  
                        <p style='color: green;text-decoration:bold;font-size:20px; '>Price: ".$row['price']."</p>  
                        <div>
                        <a href='deletepage.php?postId=".$key."'><button style='color: red;margin-right: 10%;'>Delete</button></a>
                        <a href='editpage.php?postId=".$key."'><button>Edit</button></a>
                        </div>
                        <hr>
                        </div>

                       
                      ";

                        } elseif(!$row['picurl'] && $row['picurl2']){
                            echo "  
                        <div>
                            <h3 style='color: green;'>".$row['adtitle']."</h3>
                            
                        </div>

                        <div style='margin-top: 3%; text-align:centre; margin-bottom: 5%;'>
                        <img class='zoom' src='../files/adpics/adpics".$row['picurl2']."' style = 'width: 80%; height:auto;'>
                        <p style='color: black;font-size:20px;margin-left:5%;margin-right:5%; '>".$row['description']."</p>  
                        <p style='color: green;text-decoration:bold;font-size:20px; '>Price: ".$row['price']."</p>  
                        <div>
                        <a href='deletepage.php?postId=".$key."'><button style='color: red;margin-right: 10%;'>Delete</button></a>
                        <a href='editpage.php?postId=".$key."'><button>Edit</button></a>
                        </div>
                        <hr>
                        </div>

                       
                      ";
                        } elseif($row['picurl'] && !$row['picurl2']){
                            echo "  
                        <div>
                            <h3 style='color: green;'>".$row['adtitle']."</h3>
                            
                        </div>

                        <div style='margin-top: 3%; text-align:centre; margin-bottom: 5%;'>
                        <img class='zoom' src='../files/adpics/adpics".$row['picurl']."' style = 'width: 80%; height:auto;'>
                        <p style='color: black;font-size:20px;margin-left:5%;margin-right:5%; '>".$row['description']."</p>  
                        <p style='color: green;text-decoration:bold;font-size:20px; '>Price: ".$row['price']."</p>  
                        <div>
                        <a href='deletepage.php?postId=".$key."'><button style='color: red;margin-right: 10%;'>Delete</button></a>
                        <a href='editpage.php?postId=".$key."'><button>Edit</button></a>
                        </div>
                        <hr>
                        </div>

                       
                      ";
                        } elseif($row['picurl'] && $row['picurl2']){
                            echo "  
                        <div>
                            <h3 style='color: green;'>".$row['adtitle']."</h3>
                            
                        </div>

                        <div style='margin-top: 3%; text-align:centre; margin-bottom: 5%;'>
                        <img class='zoom2' src='../files/adpics/adpics".$row['picurl']."' style = 'width: 45%; height:auto;'>
                        <img class='zoom2' src='../files/adpics/adpics".$row['picurl2']."' style = 'width: 45%; height:auto;'>
                        <p style='color: black;font-size:20px;margin-left:5%;margin-right:5%; '>".$row['description']."</p>  
                        <p style='color: green;text-decoration:bold;font-size:20px; '>Price: ".$row['price']."</p>  
                        <div>
                        <a href='deletepage.php?postId=".$key."'><button style='color: red;margin-right: 10%;'>Delete</button></a>
                        <a href='editpage.php?postId=".$key."'><button>Edit</button></a>
                        </div>
                        <hr>
                        </div>

                       
                      ";

                        }
                }
                              
            }
        }
                             
    ?>
                    
        </div>
    </div>
</div>



</div>



</body>
</html>

<?php
    include_once('../FirebaseConfig/dbcon.php');
    
    if (isset($_POST['changeLocation'])) {


        //getting session variables
      
        $lat =  $_POST['lat'];
        $long = $_POST['long'];
      
     
        $postid = $_POST['hiddenid'];

                                  
        $accId = $_GET['acc_id'];
      
        $ref_table ="Adverts";
        $fetchData = $database->getReference($ref_table)->getValue();

        if($fetchData >0) {
            foreach($fetchData as $key =>$row){
                if($row['id'] == $postid) {
                    $uid = $key;
                    $UpdateData = [
                        'accountName' => $row['accountName'],
                        'adtitle' => $row['adtitle'],
                        'description' => $row['description'],
                        'id' => $row['id'],
                        'latitude' => $lat,
                        'location' => $row['location'],
                        'longitude' => $long,
                        'phonenumber' => $row['phonenumber'],
                        'picurl' => $row['picurl'],
                        'picurl2' => $row['picurl2'],
                        'price' => $row['price']
                  
                    ];
    
                    // Create a key for a new post
                    $ref_table = 'Adverts/'.$uid;
                     $queryResult = $database->getReference($ref_table)->update($UpdateData);
                     if($queryResult) {
                        //$errors['success'] ="Ad Creation Success.";
                        echo "<script>alert('All account's Ads Cordinates changed success.')</script>"; 
                        echo "<script>location.replace('../mainpages/intobizacc.php?acc_id=$postid');</script>"; 

                     }
                }else {
                    echo "<script>alert('Failed,No ads for this Account.')</script>"; 
                    echo "<script>location.replace('../mainpages/intobizacc.php?acc_id=$postid');</script>"; 
         
                }

            }
        }else {
            echo "<script>alert('Failed,No ads for this Account.')</script>"; 
            echo "<script>location.replace('../mainpages/intobizacc.php?acc_id=$postid');</script>"; 

        }
            
           
    }

?>