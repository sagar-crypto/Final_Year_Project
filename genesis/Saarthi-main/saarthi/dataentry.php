<!DOCTYPE html>
<html>
  
<head>
    <title>SAARTHI</title>
</head>
  
<body>
    <center>
        <?php        
        $conn = mysqli_connect("localhost", "root", "", "project");
     
        if($conn === false){
            die("ERROR: Could not connect. " 
                . mysqli_connect_error());
        }
          
        $name =  $_REQUEST['name'];
        $cname =  $_REQUEST['cname'];
        $location =  $_REQUEST['location'];
        $price =  $_REQUEST['price'];
        ;

        $sql = "INSERT INTO `crops`(`name`, `cname`, `latitude`, `longitude`, `location`, `price`)VALUES ('$name','$cname',82.8628,135.0000,'$location','$price')";
         
        if(mysqli_query($conn, $sql)){
            header("Location: dataentry.html");
			die;

        } else{
            echo "ERROR ". mysqli_error($conn);
        }
       
        mysqli_close($conn);
        ?>
    </center>
</body>
  
</html>

