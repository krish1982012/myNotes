<?php
$update=false;
$insert=false;
$delete=false;
$servername = "localhost";
$username = "root";
$password = "";
$database = "notes";
// Create connection
$conn = mysqli_connect($servername, $username, $password,$database);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
if(isset($_GET["del"])){
  $recId=$_GET["del"];
  $sql="DELETE FROM `notes` WHERE `notes`.`sno` ='$recId' ;";
  $result = mysqli_query($conn, $sql);
    $delete=true;
    header("Location:/myNotes/index.php");
}
if ($_SERVER["REQUEST_METHOD"] == "POST"){
  if(isset($_POST["sno"])){
    $d=$_POST["editdescription"];
    $t=$_POST["edittitle"];
    $eid=$_POST["sno"];
    $sql = "UPDATE `notes` SET `description` = '$d',  title='$t' WHERE `sno` = '$eid';";
    $result = mysqli_query($conn, $sql);
    $update=true;
  }
  else{
    $d=$_POST["description"];
  $t=$_POST["title"];
  if($t!=''){
    $sql = "INSERT INTO `notes` ( `title`, `description`) VALUES ('$t','$d')";
   mysqli_query($conn, $sql);
   $insert=true;
  }
  }
  
  
}



?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>MyNotes</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
   <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
   <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
   <script>$(document).ready(function() {
    $('#example').DataTable();
} );</script>
     
</head>

<body>

<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit The Note</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post" action="/myNotes/index.php">
        <div class="container px-5 py-3">
          <input type="hidden" name="sno" id="hiddensno">
          <input type="hidden" name="oldtitle" id="oldtitle">
          <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="edittitle" class="form-control" id="edittitle" placeholder="title of task">
          </div>
          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" name="editdescription" id="editdescription" rows="3"></textarea>
          </div>
          <input type="submit" name="submit" class="btn btn-sm btn-primary" value="update">
        </div>
      </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">MyNotes</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="About.html">About</a>
          </li>


        </ul>
        
      </div>
    </div>
  </nav>
  <?php
     if($insert==true){
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        Your record has been inserted successfully
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
     }
  ?>
  <?php
     if($update==true){
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        Your record has been updated successfully
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
     }
  ?>
  <?php
     if($delete==true){
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        Your record has been deleted successfully
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
     }
  ?>
  
  <form method="post" action="/myNotes/index.php">
    <div class="container px-5 py-3">
      <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" name="title" class="form-control" id="title" placeholder="title of task">
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" name="description" id="description" rows="3"></textarea>
      </div>
      <input type="submit" name="submit" value="submit">
    </div>
  </form>
  <div class="container" style="margin-bottom: 30px;">
     <table id="example" class="display compact hover " style="width:90%">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Title</th>
                <th>Description</th>
                <th>Time</th>
                <th>Action</th>
                
            </tr>
        </thead>
        <tbody>
        <?php
      $sql = "SELECT * FROM notes";
      $result = mysqli_query($conn, $sql);
      $ind=1;
      while($row = mysqli_fetch_assoc($result)){
           echo '<tr>
                <td>'.$ind.'</td>
                <td>'. $row["title"].'</td>
                <td>'.$row["description"].'</td>
                <td>'.$row["time"].'</td>
                <td><button id=' .$row["sno"].' class="edit btn-sm btn-primary" style="display: inline;">edit</button>
                <button class="delete btn-sm btn-danger" id=del'.$row["sno"].' style="display: inline;">delete</button></td>
                </tr>';
            $ind=$ind+1;
      }

      ?>
           
        </tbody>
        <tfoot>
            <tr>
            <th>S.No</th>
                <th>Title</th>
                <th>Description</th>
                <th>Time</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>
<div class="container">
  </div>
 
 

  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
    crossorigin="anonymous"></script>

  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
   <script>
     edits=document.getElementsByClassName('edit');
      Array.from(edits).forEach(element => {
        element.addEventListener('click',(e)=>{
         
          tr=e.target.parentNode.parentNode;
           console.log(tr);
           title=tr.getElementsByTagName("td")[1].innerHTML;
           description=tr.getElementsByTagName("td")[2].innerHTML;
           document.getElementById('edittitle').value=title;
           document.getElementById('oldtitle').value=title;
           document.getElementById('editdescription').value=description;
           document.getElementById('hiddensno').value=e.target.id;
           //document.getElementById('hiddensno').value=tr.getElementsByTagName("td")[0].innerHTML;

           $('#editModal').modal('toggle');
           localStorage.setItem('titleToUpdate',tr.getElementsByTagName("td")[1].innerHTML);
        });
      });
      deletes=document.getElementsByClassName('delete');
      Array.from(deletes).forEach(element => {
        element.addEventListener('click',(e)=>{
         str=e.target.id;
         
          sno=str.substr(3,);
          if(confirm("Do you want to delete the record?")){
            location.href=`/myNotes/index.php?del=${sno}`;
          }
           
        });
      });
     
      
   </script>
</body>

</html>
<?php

?>