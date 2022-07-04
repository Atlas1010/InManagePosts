<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="style.css"> 
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <title>Post Board</title>
</head>
<body>
  <?php
$database =new DataBase();
//5
$sel="SELECT * FROM users  where active='TRUE';";
 $result=$database->select($sel);
 //


 if (mysqli_num_rows($result) > 0) {?>
 <div class="grid-containe">
   <h1>Welcome to our post board! </h1>
<?php 
     while($row = mysqli_fetch_assoc($result)) { ?>
 <div class="user">
    <span >
      <span>    <img class="img" src="user.jpg" alt="" >
</span>
&nbsp;&nbsp;

    <!--   <span scope="col" >Id:</span>-->
    <span class="title"><?php echo $row["id"] ?> |</span>       
<!--<span scope="col" >Name:</span>-->
    <span class="title">	<?php echo $row["name"] ?> | </span >      
<!--<span scope="col" >email:</span>-->
    <span class="title"><?php echo $row["email"] ?></span>
   </span>
     </br>
     </br>
    <?php 
  
  $selpost="SELECT * FROM post  where userId='".$row["id"]."'";
 $resultposts=$database->select($selpost);
  while($rowpost = mysqli_fetch_assoc($resultposts)) { ?>
  <div class="post">
    <span class="grid-item" >
      <div >
      <!--  <span class="posttitle">Id:</span>-->
  <!--  <span class="postbody"><?php echo $rowpost["id"]?></span>  -->      
      </div>
<div>
  <!--  <span class="posttitle">userId:</span>-->
  <!--  <span  class="postbody"><?php echo $rowpost["userId"]?></span> -->
</div>
  <div>
     <!-- <span class="posttitle" >title:</span>-->
    <span  class="postbody"><?php echo $rowpost["title"]?> \</span>   

     <!--<span class="posttitle">productionDate:</span>-->
    <span  class="postbody"><?php echo $rowpost["productionDate"]?></span> 
</div>
 <div>
  <!--  <span class="posttitle">body:</span>-->
    <span  class="postbody1"><?php echo $rowpost["body"]?></span>   
 </div>

  </span>
</div>
    <?php 
  } ?>
  </div>
<?php
  
  } ?>
   </div>
   <?php
   } 
  
   else 
   {
     echo "0 results";
   }
?> 
<?php
//7
$sel="SELECT SUBSTRING(productionDate, 1,POSITION(' ' IN productionDate) ) as 'date' ,SUBSTRING(productionDate,POSITION(' ' IN productionDate),LENGTH(productionDate) ) as 'time', COUNT(productionDate) as 'sum' FROM post group by productionDate";

$result=$database->select($sel);
if (mysqli_num_rows($result) > 0) {?>

  <tr class="column">
        <th  scope="col" >Date</th>
        <th  scope="col">Time</th>
        <th  scope="col">Count</th>
      </tr>
      <?php
while($row = mysqli_fetch_assoc($result)) { ?>
<table>
     <tr >
     <td ><?php echo $row["date"]?></td>
     <td ><?php echo $row["time"]?></td>
     <td ><?php echo $row["sum"]?></td>
    </tr>
  </table>
     <?php 
   }
   }
   else 
   {
     echo "0 results";
   }

//3
$url    = 'http://cdn2.vectorstock.com/i/1000x1000/23/81/default-avatar-profile-icon-vector-18942381.jpg';
$img    = 'user.jpg';
$file   = file($url);
$result = file_put_contents($img, $file);



//2
//Users
$handleUser = curl_init();
curl_setopt($handleUser, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($handleUser, CURLOPT_SSL_VERIFYPEER, false);
$urlUser = "https://jsonplaceholder.typicode.com/users";
curl_setopt($handleUser, CURLOPT_URL, $urlUser);
curl_setopt($handleUser, CURLOPT_RETURNTRANSFER, true);
$responseUser = curl_exec($handleUser);
curl_close($handleUser);

$responseUser=substr($responseUser,2,-3);
$activeUser = array('TRUE','FALSE');
$responseUser = explode("id" , $responseUser);
foreach($responseUser as $j){
    $j=substr($j,0,strpos($j,'address'));
    $j=explode("{",$j);
    $j=implode(",",$j);
    $j=explode(",",$j);
    $index=0;
    $user=new User();
   foreach($j as $ind){
   if($index==4){
  $index=0;
  $user->set_active( $activeUser[rand(0,1)]);
  $sql = "INSERT INTO Users (id, name, email,active)
  VALUES ('".$user->get_id()."'
      ,'".$user->get_name()."', '".$user->get_email()."', '".$user->get_active()."')";
//$database->insert($sql);
 }
 $ind=substr($ind,strpos($j[0],':')+1);
 $ind=substr($ind,strpos($j[0],'name:')+1);
$ind=str_replace("name", "", $ind);
$ind=str_replace("email", "", $ind);
$ind=str_replace(":", "", $ind);
$ind=str_replace('"', "", $ind);
if($index==0){
  $user->set_id(trim($ind));
}
if($index==1){
  $user->set_name(trim($ind));
}
if($index==3){
  $user->set_email(trim($ind));
}
 $index++;
}
}
//Post
$handle = curl_init();
curl_setopt($handle, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
$url = "https://jsonplaceholder.typicode.com/posts";
curl_setopt($handle, CURLOPT_URL, $url);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($handle);
curl_close($handle);

$response=substr($response,2,-3);
$response = explode("{" , $response);
$response= implode(" ",$response); 
$response=str_replace('"', "", $response);
$response = explode("}," , $response);
$post=new Post();
$active = array('TRUE','FALSE');

   foreach($response as $i){
   $i = explode("," , $i);
   $i[0]=substr($i[0],strpos($i[0],':')+1);
   $i[1]=substr($i[1],strpos($i[1],':')+1);
   $i[2]=substr($i[2],strpos($i[2],':')+2);
   $i[3]=substr($i[3],strpos($i[3],':')+2);
  
$post->set_userId(intval($i[0]) );
$post->set_id( intval($i[1]));
$post->set_title( $i[2]);
$post->set_body( $i[3]);
$post->set_productionDate(new DateTime());
$post->set_active( $active[rand(0,1)]);
 
$sql = "INSERT INTO Post (userId, title, body,productionDate,active)
VALUES ('".$post->get_userId()."' ,'".$post->get_title()."', '".$post->get_body()."'
   , '".date_format( $post->get_productionDate(),"Y/m/d H:i:s")."','".$post->get_active()."')";
//$database->insert($sql);

   }
//1
class DataBase{

function insert($sql){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "inmanage";
    $conn= new mysqli($servername, $username, $password, $dbname);

   if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);

}

 if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  } 

  $conn->close();   

}
function select($sel){
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "inmanage";
  $conn= new mysqli($servername, $username, $password, $dbname);

 if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
$result = $conn -> query($sel);

$conn->close();  
return $result;
    }
    function delete($sel){
      $servername = "localhost";
      $username = "root";
      $password = "";
      $dbname = "inmanage";
      $conn= new mysqli($servername, $username, $password, $dbname);
    
     if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }
    if ($conn->query($sql) === TRUE) {
      echo "record deleted successfully";
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    } 

    $conn->close();  
  
        }
 function update($sel){
     $servername = "localhost";
     $username = "root";
     $password = "";
     $dbname = "inmanage";
     $conn= new mysqli($servername, $username, $password, $dbname);
   
    if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
   }
   if ($conn->query($sql) === TRUE) {
     echo "record updated successfully";
   } else {
     echo "Error: " . $sql . "<br>" . $conn->error;
   } 
 
   $conn->close();  
 
       }
  }
class User {
    // Properties
    public $id;
    public $name;
    public $email;
    public $active;
  
    // Methods
    function set_id($id) {
      $this->id = $id;
    }
    function get_id() {
      return $this->id;
    }
    function set_name($name) {
      $this->name = $name;
    }
    function get_name() {
      return $this->name;
    }

    function set_email($email) {
      if( preg_match('/\A[a-z0-9]+([-._][a-z0-9]+)*@([a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,4}\z/', $email)
      && preg_match('/^(?=.{1,64}@.{4,64}$)(?=.{6,100}$).*/', $email)) {
        $this->email = $email;
   }
  
    }
    function get_email() {
      return $this->email;
    }
      function set_active($active) {
          $this->active = $active;
        }
        function get_active() {
          return $this->active;
        }
  }
  class Post {
      // Properties
      public $id;
      public $userId;
      public $title;
      public $body;
      public $productionDate;
      public $active;
      // Methods
      function set_id($id) {
        $this->id = $id;
      }
      function get_id() {
        return $this->id;
      }
      function set_userId($userId) {
        $this->userId = $userId;
      }
      function get_userId() {
        return $this->userId;
      }
      function set_title($title) {
        $this->title = $title;
      }
      function get_title() {
        return $this->title;
      }
      function set_body($body) {
        $this->body = $body;
      }
      function get_body() {
        return $this->body;
      }
      function set_productionDate($productionDate) {
        $this->productionDate = $productionDate;
      }
      function get_productionDate() {
        return $this->productionDate;
      }
      function set_active($active) {
        $this->active = $active;
      }
      function get_active() {
        return $this->active;
      }
      function postByCurrentMonth(){
        $data=new dataBase();
      $sql='select p.id,p.userId,p.title,p.body,p.ProductionDate, min(p.productionDate) from post p,users s
            where p.userId= s.id and MONTH(s.BirthDate)=MONTH(CURRENT_DATE)' ; 
      $result=$date->select($sql);
return $result;
      }
  }
?>

</body>
</html>
