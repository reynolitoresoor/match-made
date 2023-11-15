<?php 
require_once('../database.php');
require_once('../classes.php'); 
$page_title = "Match";

if(!isset($_SESSION['match_made_data'])) {
   header('Location: '.base_url);
}
$user = new User();
$user_data = $user->getUserData($_SESSION['match_made_data']['user_id']);
$friends = $user->getUserConfirmedFriends($user_data[0]['user_id']);

include '../partials/header.php';
include '../partials/sidebar.php';
?>
  
  <div class="main">
  	<div class="container">
	  	<div class="row mt-5">
	  		<div class="col-lg-9 col-md-9 offset-lg-3 offset-md-3 box">
	  			<form method="POST" action="">
            <div class="row">
              <?php if($friends){ ?>
                <h2 class="text-center">Your matched friend.</h2>
              <?php foreach($friends as $friend): ?>
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-3 d-flex flex-row" style="text-align: left;">
                <img class="img-responsive profile mr-5" style="margin-right: 10px" width="100" height="100" src="<?php if(isset($friend['profile'])){echo base_url.$friend['profile'];}else{echo base_url.'uploads/profile/profile.png';} ?>" /> 
                <div class="d-flex flex-column">
                  <a href="#"><?php echo $friend['username']; ?></a>
                  <a class="mt-3" href="<?php echo base_url.'message?friend_id='.$friend['user_id']; ?>"><i class="bi bi-chat"></i> Message</a>
                </div>
              </div>
              <?php endforeach; ?>
              <?php } else { ?>
              <h2 class="text-primary text-center">You have no match friend yet.</h2>
              <?php } ?>
            </div>
          </form>
	  		</div>
	  	</div>
	 </div>
  </div>

  <a href="#" class="back-to-top btn-primary d-flex align-items-center justify-content-center" style="border: 1px solid #e6ddd1;"><i class="bi bi-arrow-up-short"></i></a>

  <?php include '../partials/footer.php'; ?>