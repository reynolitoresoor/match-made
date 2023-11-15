<?php 
require_once('../database.php');
require_once('../classes.php'); 
$page_title = "Messages";

if(!isset($_SESSION['match_made_data'])) {
   header('Location: '.base_url);
}

$user_id = $_SESSION['match_made_data']['user_id'];

$message = new Message();
$user = new User();
$user_data = $user->getUserData($user_id);
$messages = $message->getMessages($user_id);

include '../partials/header.php';
include '../partials/sidebar.php';
?>
  
  <div class="main">
  	<div class="container">
	  	<div class="row mt-5">
	  		<div class="col-lg-9 col-md-9 offset-lg-3 offset-md-3 box">
	  			<form method="POST" action="">
            <div class="row">
              <h2 class="text-center text-primary">Messages</h2>
              <hr class="border" />
              <?php if(isset($messages)): foreach($messages as $message): 
                 if($user_id == $message['friend_id']){
                   $friend_id = $message['user_id'];
                 } else {
                   $friend_id = $message['friend_id'];
                 }
              ?>
              <div class="col-lg-12 col-md-12">
                <a href="<?php echo base_url.'message?friend_id='.$friend_id; ?>"><img class="img-responsive profile" src="<?php if(isset($message['profile'])){echo base_url.$message['profile'];}else{echo base_url.'uploads/profile/profile.png';} ?>" width="100" height="100" /> <span><?php echo $message['username']; ?></span></a>
              </div>
              <?php endforeach; else: ?>
              <p>You have you messages.</p>
              <?php endif; ?>
            </div>
          </form>
	  		</div>
	  	</div>
	 </div>
  </div>

  <a href="#" class="back-to-top btn-primary d-flex align-items-center justify-content-center" style="border: 1px solid #e6ddd1;"><i class="bi bi-arrow-up-short"></i></a>

  <?php include '../partials/footer.php'; ?>