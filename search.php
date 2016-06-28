<?php
       function sanitizeInput($data){
				$data = trim($data);
			  $data = stripslashes($data);
				$data = htmlspecialchars($data);
				return $data;
			   }

?>

<!DOCTYPE html>
<html lang="">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

  <title>Mzini Search</title>
 
  <style>
   
    header{
           background-color: lightgray;
           padding: 10px;
           border: solid 1px black;
           border-radius: 3px;
    } 
    
    body{
         font-size: 15px;
         font-family: Myriad Pro,Tahoma, Sagoe UI, Arial, Helvetica, sans-serif;
    }
    
    #body-info{
                margin-left: 10%;
                margin-right: 10%;
    }
 
    #body-info p {
                   margin: 0;
                   width: 450px;

    }

    #desc{
    	   color: blue;
    	   font-weight: bold;
    	   font-size: 15px;
    	   font-family: Myriad Pro;
    }
    
    a:link{
    	        text-decoration: none;
    }
    
    #desc:hover{
    	         background-color: yellow;
    	         text-decoration: underline;
    }
    
    #link{
    	   color: green;
    }

    #title{
    	    color: grey;
    }

 </style>

 </head>
 <body>
  <div class="container">
   <header>
  
    <a href='index.php' >Search Made Easy</a></p>
    <form action='search.php' method='GET'>
 
      <input style="width: 50%; margin-left: 15%;" type='text' name='q' value='<?php echo $_GET['q']; ?>' >
      <input style="background-color: green;" type='submit' name='search' value='GO!'> 


   </form>
  </header>
    
  <div id="body-info">  
               
    <?php
     
			if(isset($_GET['search'])){
			    
			   $search = sanitizeInput($_GET['q']);

			 if(mb_strlen($search)=== 0){
				echo "Search Term Too Short!", "<br /><br />";
				return false;
			   }else{
				echo "You Searched For: <b> '$search' </b> <hr size='1'> </br>";
			   }

			  try{
			      $conn = new PDO('mysql:host=127.0.0.1;dbname=mzini','root','');
			         
			       $query = "SELECT * FROM search WHERE MATCH(keywords) AGAINST('$search' IN NATURAL LANGUAGE MODE)";
			       $result = $conn->query($query);
			        
			 	if ($result == TRUE){
					
			                  $total = $result->rowCount();
			                  $start = microtime(true);
			                  $y = 0;
			                  for($x=0;$x<=1000000;$x++){

			                    $y = $x;
			                  }    
			                  $final = number_format((microtime(true) - $start), 2); 
			            echo "$total Results Found in $final Seconds!","<br /><br />";
			  	    
			             foreach($result->fetchAll(PDO::FETCH_OBJ) as $row){    
			   ?>

			  <a href="<?php echo $row->link; ?>"<p id="desc"> <?php echo "Name: " .$row->description; ?> <p></a>
			  <p id="link"> <?php echo "Serial Number: " .$row->link; ?> <p>
			  <p id="title"> <?php echo "Location: " .$row->title; ?> <p><br/>
			     
			   <?php         
			               } 
			       
			            }else{
			                   echo "No Results Found!";
			        }
			    
			 }catch(PDOException $error){
				echo "Could Not Establish Connection With Database";
				$error->getMessage();
			      	die();
			 }
			}

      $conn = null;

		  ?>
  </div>

  </div>
 </body>
</html>
