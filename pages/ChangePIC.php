<?php
$mime = array('image/png', 'image/jpeg', 'image/pjpeg', 'image/gif', 'image/x-png', 'image/jpg');
$allowedExts = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);

function checkDir(){
    $dirname = $_POST["UserID"];
    $filename = "../img/users/" . $dirname . "/";
    if (!file_exists($filename)) {
        mkdir("../img/users/" . $dirname, 0777);
        chmod("../img/users/". $dirname, 0777);
        echo "Folder $dirname was successfully created.";
    } else {
        echo "Folder $dirname exists.";
    }
}

function checkPic(){
    checkDir();
    $extensions = array("gif", "jpeg", "jpg", "png");
    $name = $_POST['UserID'];
    foreach ($extensions as $ext) { 
        if (file_exists('../img/users/'.$name.'/profile.' . $ext)) { 
            unlink('../img/users/'.$name.'/profile.' . $ext);
            return true;          
        } else {
            return false;
        }
    }  
}

if ((in_array($_FILES['file']['type'], $mime))
&& ($_FILES["file"]["size"] < 5242880)
&& in_array($extension, $allowedExts)) {
  if ($_FILES["file"]["error"] > 0) {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
  } else {
    echo "Upload: " . $_FILES["file"]["name"] . "<br>";
    echo "Type: " . $_FILES["file"]["type"] . "<br>";
    echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
    echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
    if(checkPic()){
      echo $_FILES["file"]["name"] . " already exists. ";
      move_uploaded_file($_FILES["file"]["tmp_name"], "../img/users/".$_POST['UserID']."/profile.".$extension);
      chmod("../img/users/".$_POST['UserID']."/profile.".$extension, 0777);
      header("Location: ../Home.php?page=profile&a=settings&success=1");
    } else {
      move_uploaded_file($_FILES["file"]["tmp_name"], "../img/users/".$_POST['UserID']."/profile.".$extension);
      chmod("../img/users/".$_POST['UserID']."/profile.".$extension, 0777);
      echo "Stored in: " . "img/users/".$_POST['UserID']."/" . $_FILES["file"]["name"];
      header("Location: ../Home.php?page=profile&a=settings&success=1");
    }
  }
} else {
    header("Location: ../Home.php?page=profile&a=settings&error=1");
    echo "Invalid file";
}

?>