<?php 

$type = $_GET['tp']; 
if($type=='signup') signup(); 
elseif($type=='login') login(); 
elseif($type=='feed') feed(); 
elseif($type=='feedInsert') feedInsert(); 
elseif($type=='feedEdit') feedEdit(); 
elseif($type=='feedUpdate') feedUpdate(); 
elseif($type=='feedDelete') feedDelete(); 
function login() 
{ 
    require 'config.php'; 
    $json = json_decode(file_get_contents('php://input'), true); 
    $username = $json['username']; 
	$password = $json['password']; 
	$password = hash('ripemd160', $password);
    $userData =''; $query = "select * from users where username='$username' and password='$password'"; 
    $result= $db->query($query);
    $rowCount=$result->num_rows;
    if($rowCount>0)
    {
		$userData = $result->fetch_object();
		$user_id=$userData->user_id;
		$userData = json_encode($userData);
		echo '{"userData":'.$userData.'}';
    }
    else 
    {
    	echo '{"error":"Wrong username and password"}';
    }
}

function signup() {
    
	require 'config.php';
	$json = json_decode(file_get_contents('php://input'), true);
	$username = $json['username'];
	$password = $json['password'];
	$password = hash('ripemd160', $password);
	$email = $json['email'];
	$name = $json['name'];
	$username_check = preg_match("/^[A-Za-z0-9_]{4,10}$/i", $username);
	$email_check = preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$/i', $email);
	$password_check = preg_match('/^[A-Za-z0-9!@#$%^&*()_]{4,20}$/i', $password);
	
	//if($username_check==0)
	if($username_check==0)
		echo '{"error":"Invalid username"}';
	elseif($email_check==0) 
		echo '{"error":"Invalid email"}';
	//elseif($password_check ==0) 
	elseif($password =='') 
		echo '{"error":"Invalid password"}';
	elseif (strlen(trim($username))>0 && strlen(trim($password))>0 && strlen(trim($email))>0 && 
		$email_check>0 && $username_check>0 )
	{
		$userData = '';
		$result = $db->query("select * from users where username='$username' or email='$email'");
		$rowCount=$result->num_rows;
		//echo '{"text": "'.$rowCount.'"}';
		if($rowCount==0)
		{				
			$db->query("INSERT INTO users(username,password,email,name)
						VALUES('$username','$password','$email','$name')");
			$userData ='';
			$query = "select * from users where username='$username' and password='$password'";
			$result= $db->query($query);
			$userData = $result->fetch_object();
			$user_id=$userData->user_id;
			$userData = json_encode($userData);
			echo '{"userData":'.$userData.'}';
		} 
		else {
			echo '{"error":"username or email exists"}';
		}
	}
	else{
		echo '{"text":"Enter valid data2"}';
	}
}

function feed(){
    
    require 'config.php';
    $json = json_decode(file_get_contents('php://input'), true);
    $user_id=$json['user_id'];
    $query = "SELECT * FROM feed WHERE user_id=$user_id ";
    //$query = "SELECT * FROM feed ";
    $result = $db->query($query); 
    $feedData = mysqli_fetch_all($result,MYSQLI_ASSOC);
    $feedData=json_encode($feedData);
    echo '{"feedData":'.$feedData.'}';
}

function feedInsert(){

    require 'config.php';
    $json = json_decode(file_get_contents('php://input'), true);
    $user_id=$json['user_id'];
    $feed=$json['feed'];
    $feedData = '';
    if($feed !='')
    {
        $query = "INSERT INTO feed ( feed, user_id) VALUES ('$feed','$user_id')";
        $db->query($query);              
    }
    $query = "SELECT * FROM feed WHERE user_id=$user_id";
    $result = $db->query($query); 
    $feedData = mysqli_fetch_all($result,MYSQLI_ASSOC);
    $feedData=json_encode($feedData);
    echo '{"feedData":'.$feedData.'}';
}

function feedDelete(){

    require 'config.php';
    $json = json_decode(file_get_contents('php://input'), true);
    $user_id=$json['user_id'];
    $feed_id=$json['feed_id'];
    $query = "Delete FROM feed WHERE user_id=$user_id AND feed_id=$feed_id";
    $result = $db->query($query);
    if($result)       
    {        
        echo '{"success":"Feed deleted"}';
    } else{

        echo '{"error":"Delete error"}';
    }
}


function feedEdit(){

    require 'config.php';
    $json = json_decode(file_get_contents('php://input'), true);
    $feed_id=$json['feed_id'];
    $query = "SELECT * FROM feed where feed_id = $feed_id";
    $result = $db->query($query); 
    $feedData = mysqli_fetch_all($result,MYSQLI_ASSOC);
    $feedData=json_encode($feedData);
    echo '{"feedData":'.$feedData.'}';
}

function feedUpdate(){

    require 'config.php';
    $json = json_decode(file_get_contents('php://input'), true);
    $feed_id=$json['feed_id'];
    $feed=$json['feed'];
    if($feed !='')
    {
    $query = "UPDATE feed SET feed='$feed' WHERE feed_id = $feed_id";
    $db->query($query);   
    }
    $query = "SELECT * FROM feed WHERE feed_id = $feed_id ";
    $result = $db->query($query); 
    $feedData = mysqli_fetch_all($result,MYSQLI_ASSOC);
    $feedData=json_encode($feedData);
    echo '{"feedData":'.$feedData.'}';
}

?>