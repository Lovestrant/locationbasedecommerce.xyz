<?php 
//session_start();

$windowTabbed = $_SESSION['windowTabbed'];


include_once('../FirebaseConfig/dbcon.php');  
$buyerPhone = $_SESSION['phonenumber'];

$ref_table ="cart";
$fetchData = $database->getReference($ref_table)->getValue();
$num = 0;

if($fetchData >0) {
    foreach($fetchData as $key =>$row){
     
      if($row['buyerPhone'] ===$buyerPhone){
        $num = $num+1;  
          
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
    <link rel="stylesheet" type="text/css" href="../css/index.css">
    <!--Bootstrap css Links -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <!--Bootstrap JS Links -->
    <script type="text/javascript" src="../bootstrap/js/bootstrap.min.js"></script>

</head>

    <script>
        function toProfile() {
            location.replace("profile.php");
        }


        function toRadius() {
            location.replace('radius.php?lat=' + <?php echo $_SESSION["latitude"] ?> + '&long=' + <?php echo $_SESSION["longitude"] ?>, '_self')
            
        }

    </script>
  

</head>
<body>
<div class = "container_fluid" id="headerbody1">
    <div class = "row">
        
        <div class ="col-sm-6">
        <h3 class="institution">Location-Based E-commerce System</h3>
        </div>

        <div class="col-sm-6">
        <h3 class="elearningLabel">Shop and advertise</h3>
        </div>

    </div>

    <div class = "row">
        <div class="col-sm-12">
        <h3 class="motto">Let's talk business.</h3>
        </div>
    </div>

<div class="col-sm-12" style="text-align: right; margin-right: 2%; margin-top: -2%;">
  
<?php 

 if($windowTabbed === "Radius"){
  echo "
  <button style='color: red;' id='radius' onClick = 'toRadius()'>Radius</button>
  <a href='profile.php'><button>Profile</button></a>
  <a href='cart.php'><button>Cart <span style='color: red;'> $num</span></button></a>  
  ";
 } else if( $windowTabbed === "Profile") {
    echo "
    <button  id='radius' onClick = 'toRadius()'>Radius</button>
    <a href='profile.php'><button style='color: red;'>Profile</button></a>
    <a href='cart.php'><button>Cart <span style='color: red;'>$num</span></button></a>  
    ";

 } else if($windowTabbed === "Cart") {

    echo "
    <button id='radius' onClick = 'toRadius()'>Radius</button>
    <a href='profile.php'><button>Profile</button></a>
    <a href='cart.php'><button style='color: red;'>Cart <span style='color: red;'> $num</span></button></a>  
    ";
 }


?>

</div>
  
</div> 
</body>
</html>