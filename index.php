<?php include 'inc/header.php';?>
<?php 
      include 'lib/config.php';
      include 'lib/Database.php';
      $db = new Database;
?>
 <div class="myform">
  <?php 
    if ($_SERVER["REQUEST_METHOD"]=="POST") {
      $permited = array('jpg','jpeg','png','gif','pdf');
      $file_name = $_FILES['image']['name'];
      $file_size = $_FILES['image']['size'];
      $file_temp = $_FILES['image']['tmp_name'];
      $div = explode('.', $file_name);
      $file_ext = strtolower(end($div));
      $unique_image = substr(md5(time()), 0,10).'.'.$file_ext;
      $uploaded_image = "uploads/".$unique_image;
      if (empty($file_name)) {
        echo "<span class='danger'>please select any image</span>";
      }elseif ($file_size > 148500) {
        echo "<span class='danger'>image size should be less then 1mb</span>";
      }elseif (in_array($file_ext, $permited)==false) {
        echo "<span class='danger'>you can upload only:".implode(', ', $permited)."</span>";
      }else{
      move_uploaded_file($file_temp, $uploaded_image);
      $query = "insert into tbl_image(image) values('$uploaded_image')";
      $inserted_rows = $db->insert($query);
      if ($inserted_rows) {
        echo "<span class='success'>image inserted successfully</span>";
      }else{
        echo "<span class='danger'>image not inserted</span>";
      }
    }
  }
  ?>

  <form action="" method="post" enctype="multipart/form-data">
   <table>
    <tr>
     <td>Select Image</td>
     <td><input type="file" name="image"/></td>
    </tr>
    <tr>
     <td></td>
     <td><input type="submit" name="submit" value="Upload"/></td>
    </tr>
   </table>
  </form>

        
        
  <?php 
    if (isset($_GET['del'])) {
    $id = $_GET['del'];
    $getquery = "select * from tbl_image where id = $id";
    $getImg = $db->select($getquery);
    if ($getImg) {
      while ($imgdata = $getImg->fetch_assoc()) { 
        $delimg = $imgdata['image'];
        unlink($delimg);
      }
    }
    
    $query = "delete from tbl_image where id = $id ";
    $delImage = $db->delete($query);
    if ($delImage) {
        echo "<span class='success'>image deleted successfully</span>";
      }else{
        echo "<span class='danger'>image not deleted</span>";
      }
      }
   ?>
  <?php 
    $query = "select * from tbl_image";
    $getImage = $db->select($query);
    if ($getImage) {
      $i = 0;
      while ($result = $getImage->fetch_assoc()) { 
      $i++;
  ?>
          <table width="100%">
          <tr>
            <th width="30%">NO.</th>
            <th width="40%">Image</th>
            <th width="30%">Action</th>
          </tr>
          <tr>
            <td><?php echo $i; ?></td>
            <td><img src="<?php echo $result['image'] ?>" height="100px" width="200px"></td>
            <td><a href="?del=<?php echo $result['id']; ?>">Delete</a></td>
          </tr>
        </table>
  <?php  } } ?>
 </div>
<?php include 'inc/footer.php';?>