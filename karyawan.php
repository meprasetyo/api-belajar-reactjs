<?php 

$type = $_GET['tp']; 
if($type=='signup') signup(); 
elseif($type=='login') login(); 
elseif($type=='karyawan') karyawan(); 
elseif($type=='karyawanInput') karyawanInput(); 
elseif($type=='karyawanUpdate') karyawanUpdate(); 
elseif($type=='karyawanDelete') karyawanDelete();
elseif($type=='karyawanDeleteTableR') karyawanDeleteTableR();
elseif($type=='karyawanEdit') karyawanEdit(); 
elseif($type=='selectKota') selectKota(); 

elseif($type=='karyawanSelect') karyawanSelect(); 
elseif($type=='karyawanInputSelect') karyawanInputSelect(); 
elseif($type=='karyawanUpdateSelect') karyawanUpdateSelect(); 
elseif($type=='karyawanDeleteSelect') karyawanDeleteSelect();
elseif($type=='karyawanDeleteTableRSelect') karyawanDeleteTableRSelect();
elseif($type=='karyawanEditSelect') karyawanEditSelect(); 

elseif($type=='kotaInput') kotaInput(); 

function login() 
{ 
    require 'config.php'; 
    $json = json_decode(file_get_contents('php://input'), true); 
    $username = $json['username']; $password = $json['password']; 
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
	$email = $json['email'];
	$name = $json['name'];
	$username_check = preg_match("/^[A-Za-z0-9_]{4,10}$/i", $username);
	$email_check = preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$/i', $email);
	$password_check = preg_match('/^[A-Za-z0-9!@#$%^&*()_]{4,20}$/i', $password);
	
	if($username_check==0) 
		echo '{"error":"Invalid username"}';
	elseif($email_check==0) 
		echo '{"error":"Invalid email"}';
	elseif($password_check ==0) 
		echo '{"error":"Invalid password"}';
	elseif (strlen(trim($username))>0 && strlen(trim($password))>0 && strlen(trim($email))>0 && 
		$email_check>0 && $username_check>0 && $password_check>0)
	{
		
		$userData = '';
		
		$result = $db->query("select * from users where username='$username' or email='$email'");
		$rowCount=$result->num_rows;
		
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

function karyawan(){

    require 'config.php';
    $json = json_decode(file_get_contents('php://input'), true);
    $user_id=$json['user_id'];
    $query = "SELECT * FROM karyawan order by id_karyawan DESC";
    $result = $db->query($query); 
    $karyawanData = mysqli_fetch_all($result,MYSQLI_ASSOC);
    $karyawanData=json_encode($karyawanData);
    echo '{"karyawanData":'.$karyawanData.'}';
}

function karyawanInput(){

    require 'config.php';
    $json = json_decode(file_get_contents('php://input'), true);
    $nama=$json['nama'];
	$KTP=$json['KTP'];
	$no_hp=$json['no_hp'];
    $feedData = '';
    if($nama !='')
    {
        $query = "INSERT INTO karyawan ( KTP, nama, no_hp) VALUES ('$KTP','$nama','$no_hp')";
        $db->query($query);              
    }
    $query = "SELECT * FROM karyawan WHERE KTP = $KTP ";
    $result = $db->query($query); 
    $karyawanData = mysqli_fetch_all($result,MYSQLI_ASSOC);
    $karyawanData=json_encode($karyawanData);
    echo '{"karyawanData":'.$karyawanData.'}';
}

function karyawanDelete(){
    require 'config.php';
    $json = json_decode(file_get_contents('php://input'), true);
    $id_karyawan=$json['id_karyawan'];
    $query = "Delete FROM karyawan WHERE id_karyawan=$id_karyawan ";
    $result = $db->query($query);
    if($result)       
    {        
        echo '{"success":"karyawan deleted"}';
    } else{

        echo '{"error":"Delete error"}';
    } 
}

function karyawanDeleteTableR(){
    require 'config.php';
    $json = json_decode(file_get_contents('php://input'), true);
    $id_karyawan=$json['id_karyawan'];
	if($id_karyawan !='')
    {
        $query = "Delete FROM karyawan WHERE id_karyawan=$id_karyawan";
        $db->query($query);              
    }
    $query = "SELECT * FROM karyawan order by id_karyawan DESC";
    $result = $db->query($query); 
    $karyawanData = mysqli_fetch_all($result,MYSQLI_ASSOC);
    $karyawanData=json_encode($karyawanData);
    echo '{"karyawanData":'.$karyawanData.'}';
}

function karyawanEdit(){

    require 'config.php';
    $json = json_decode(file_get_contents('php://input'), true);
    $id_karyawan=$json['id_karyawan'];
    $query = "SELECT * FROM karyawan where id_karyawan = $id_karyawan";
    $result = $db->query($query); 
    $karyawanData = mysqli_fetch_all($result,MYSQLI_ASSOC);
    $karyawanData=json_encode($karyawanData);
    echo '{"karyawanData":'.$karyawanData.'}';
}

function karyawanUpdate(){

    require 'config.php';
    $json = json_decode(file_get_contents('php://input'), true);
    $id_karyawan=$json['id_karyawan'];
    $nama=$json['nama'];
    $KTP=$json['KTP'];
    $no_hp=$json['no_hp'];
    if($nama !='')
    {
    $query = "UPDATE karyawan SET KTP='$KTP', nama='$nama', no_hp='$no_hp' WHERE id_karyawan = $id_karyawan";
    $db->query($query);   
    }
    $query = "SELECT * FROM karyawan WHERE KTP = $KTP ";
    $result = $db->query($query); 
    $karyawanData = mysqli_fetch_all($result,MYSQLI_ASSOC);
    $karyawanData=json_encode($karyawanData);
    echo '{"karyawanData":'.$karyawanData.'}';
}

function selectKota(){
    require 'config.php';
    $json = json_decode(file_get_contents('php://input'), true);
	$user_id=$json['user_id'];
    $query = "SELECT * FROM select_kota order by kota ASC";
    $result = $db->query($query); 
    $dataKota = mysqli_fetch_all($result,MYSQLI_ASSOC);
    $dataKota=json_encode($dataKota);
    echo '{"dataKota":'.$dataKota.'}';
}

/* -------- select --------- */

function karyawanSelect(){

    require 'config.php';
    $json = json_decode(file_get_contents('php://input'), true);
    $user_id=$json['user_id'];
    $query = "SELECT * FROM view_karyawan order by id_karyawan DESC";
    $result = $db->query($query); 
    $karyawanData = mysqli_fetch_all($result,MYSQLI_ASSOC);
    $karyawanData=json_encode($karyawanData);
    echo '{"karyawanData":'.$karyawanData.'}';
}

function karyawanInputSelect(){

    require 'config.php';
    $json = json_decode(file_get_contents('php://input'), true);
    $nama=$json['nama'];
	$KTP=$json['KTP'];
	$no_hp=$json['no_hp'];
	$id_kota=$json['id_kota'];
    $feedData = '';
    if($nama !='')
    {
        $query = "INSERT INTO karyawannew ( KTP, nama, no_hp, id_kota) VALUES ('$KTP','$nama','$no_hp', '$id_kota')";
        $db->query($query);              
    }
    $query = "SELECT * FROM karyawannew WHERE KTP = $KTP ";
    $result = $db->query($query); 
    $karyawanData = mysqli_fetch_all($result,MYSQLI_ASSOC);
    $karyawanData=json_encode($karyawanData);
    echo '{"karyawanData":'.$karyawanData.'}';
}

function karyawanDeleteSelect(){
    require 'config.php';
    $json = json_decode(file_get_contents('php://input'), true);
    $id_karyawan=$json['id_karyawan'];
    $query = "Delete FROM karyawannew WHERE id_karyawan=$id_karyawan ";
    $result = $db->query($query);
    if($result)       
    {        
        echo '{"success":"karyawan deleted"}';
    } else{

        echo '{"error":"Delete error"}';
    } 
}

function karyawanDeleteTableRSelect(){
    require 'config.php';
    $json = json_decode(file_get_contents('php://input'), true);
    $id_karyawan=$json['id_karyawan'];
	if($id_karyawan !='')
    {
        $query = "Delete FROM karyawannew WHERE id_karyawan=$id_karyawan";
        $db->query($query);              
    }
    $query = "SELECT * FROM karyawannew order by id_karyawan DESC";
    $result = $db->query($query); 
    $karyawanData = mysqli_fetch_all($result,MYSQLI_ASSOC);
    $karyawanData=json_encode($karyawanData);
    echo '{"karyawanData":'.$karyawanData.'}';
}

function karyawanEditSelect(){

    require 'config.php';
    $json = json_decode(file_get_contents('php://input'), true);
    $id_karyawan=$json['id_karyawan'];
    $query = "SELECT * FROM karyawannew where id_karyawan = $id_karyawan";
    $result = $db->query($query); 
    $karyawanData = mysqli_fetch_all($result,MYSQLI_ASSOC);
    $karyawanData=json_encode($karyawanData);
    echo '{"karyawanData":'.$karyawanData.'}';
}

function karyawanUpdateSelect(){

    require 'config.php';
    $json = json_decode(file_get_contents('php://input'), true);
    $id_karyawan=$json['id_karyawan'];
    $nama=$json['nama'];
    $KTP=$json['KTP'];
    $no_hp=$json['no_hp'];
	$id_kota=$json['id_kota'];
    if($nama !='')
    {
    $query = "UPDATE karyawannew SET KTP='$KTP', nama='$nama', no_hp='$no_hp', id_kota='$id_kota' WHERE id_karyawan = $id_karyawan";
    $db->query($query);   
    }
    $query = "SELECT * FROM karyawannew WHERE KTP = $KTP ";
    $result = $db->query($query); 
    $karyawanData = mysqli_fetch_all($result,MYSQLI_ASSOC);
    $karyawanData=json_encode($karyawanData);
    echo '{"karyawanData":'.$karyawanData.'}';
}

function kotaInput(){

    require 'config.php';
    $json = json_decode(file_get_contents('php://input'), true);
    $kota=$json['kota'];
	
    $feedData = '';
    if($kota !='')
    {
        $query = "INSERT INTO select_kota (kota) VALUES ('$kota')";
        $db->query($query);              
    }
    $query = "SELECT * FROM select_kota ";
    $result = $db->query($query); 
    $dataKota = mysqli_fetch_all($result,MYSQLI_ASSOC);
    $dataKota=json_encode($dataKota);
    echo '{"dataKota":'.$dataKota.'}';
}
?>