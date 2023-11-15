<?php 
require_once('../database.php');
require_once('../classes.php');

$page_title = "Profile";

if(!isset($_SESSION['match_made_data'])) {
   header('Location: '.base_url);
}

$user = new User();
$user_data = $user->getUserData($_SESSION['match_made_data']['user_id']);

if($_POST) {
  $update = $user->update($_POST);
  $user_data = $user->getUserData($_SESSION['match_made_data']['user_id']);
}

include '../partials/header.php'; 
?>
  <style type="text/css">
    .form-group {
      margin-top: 10px;
      margin-bottom: 10px;
    }
  </style>
  <div class="main">
  	<div class="container">
	  	<div class="row mt-5">
	  		<div class="col-lg-9 col-md-9 offset-lg-3 offset-md-3 box">
	  			<form method="POST" action="" enctype="multipart/form-data">
            <?php if(isset($update)): ?>
            <div class="row">
              <h3 class="text-success">Profile successfully saved</h3>  
            </div>
            <?php endif; ?>
            <div class="row">
              <div class="col-lg-6 form-group">
                <label class="form-label" for="profile">Upload Profile</label>
                <input type="file" name="profile" id="profile" onchange="loadFile(event)" class="form-control">
              </div>
              <div class="col-lg-6">
                <img id="preview-profile" src="" width="100" height="100" class="img-responsive profile" style="display: none;">
              </div>
            </div>
            <div class="row">
              <div class="col-sm form-group">
                <label class="form-label" for="username">Username</label>
                <input type="text" class="form-control" id="email" name="username" value="<?= isset($user_data[0]['username']) ? $user_data[0]['username'] : '' ?>" />
              </div>
              <div class="col-sm form-group">
                <label class="form-label" for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= isset($user_data[0]['email']) ? $user_data[0]['email'] : '' ?>" />
              </div>
            </div>
            <div class="row">
              <div class="col-sm form-group">
                <label class="form-label" for="old-password">Old Password</label>
                <input type="password" class="form-control" id="old-password" name="old_password" />
              </div>
              <div class="col-sm form-group">
                <label class="form-label" for="new-password">New Password</label>
                <input type="password" class="form-control" id="new-password" name="new_password" />
              </div>
            </div>
            <div class="row">
              <div class="col-sm form-group">
                <label class="form-label" for="first-name">First Name</label>
                <input type="text" class="form-control" id="first-name" name="first_name" value="<?= isset($user_data[0]['first_name']) ? $user_data[0]['first_name'] : '' ?>" />
              </div>
              <div class="col-sm form-group">
                <label class="form-label" for="last-name">Last Name</label>
                <input type="text" class="form-control" id="last-name" name="last_name" value="<?= isset($user_data[0]['last_name']) ? $user_data[0]['last_name'] : '' ?>" />
              </div>
            </div>
            <div class="row">
              <div class="col-sm form-group">
                <label class="form-label" for="middle-name">Middle Name</label>
                <input type="text" class="form-control" id="middle-name" name="middle_name" value="<?= isset($user_data[0]['middle_name']) ? $user_data[0]['middle_name'] : '' ?>"/>
              </div>
              <div class="col-sm form-group">
                <label class="form-label" for="phone">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?= isset($user_data[0]['phone']) ? $user_data[0]['phone'] : '' ?>" />
              </div>
            </div>
            <div class="row">
              <div class="col-sm form-group">
                <label class="form-label" for="age">Age</label>
                <input type="number" class="form-control" id="age" name="age" value="<?= isset($user_data[0]['age']) ? $user_data[0]['age'] : '' ?>"/>
              </div>
              <div class="col-sm form-group">
                <label class="form-label" for="about-yourself">About Yourself</label>
                <textarea class="form-control" rows="4" id="about-yourself" name="about_yourself"><?= isset($user_data[0]['about_yourself']) ? $user_data[0]['about_yourself'] : '' ?></textarea>
              </div>
            </div>
            <div class="mt-3" style="text-align: left;">
              <input type="hidden" name="user_id" value="<?php echo $user_data[0]['user_id']; ?>">
              <button type="submit" class="btn btn-primary">Update Profile</button>
            </div>
          </form>
	  		</div>
	  	</div>
	 </div>
  </div>
  <?php include '../partials/sidebar.php'; ?>
  <a href="#" class="back-to-top btn-primary d-flex align-items-center justify-content-center" style="border: 1px solid #e6ddd1;"><i class="bi bi-arrow-up-short"></i></a>
  <script type="text/javascript">
    var loadFile = function(event) {
      var reader = new FileReader();
      reader.onload = function(){
        var output = document.getElementById('preview-profile');
        output.src = reader.result;
      };
      reader.readAsDataURL(event.target.files[0]);
      $('img#preview-profile').show();
    };
  </script>
  <?php include '../partials/footer.php'; ?>