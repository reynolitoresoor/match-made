<?php 
require_once('../database.php');
require_once('../classes.php'); 
$page_title = "Message";

if(!isset($_SESSION['match_made_data'])) {
   header('Location: '.base_url);
}

if($_GET['friend_id']) {
  $friend_id = intval($_GET['friend_id']);
  $user_id = $_SESSION['match_made_data']['user_id'];
}
$message = new Message();
$user = new User();
$user_data = $user->getUserData($user_id);
$messages = $message->getMessage($user_id, $friend_id);

if($_POST) {
  $data = array();

  $data['user_id'] = intval($user_id);
  $data['friend_id'] = $friend_id;
  $data['message'] = $_POST['message'];

  $result = $message->message($data);

}

include '../partials/header.php';
include '../partials/sidebar.php';
?>
  
  <div class="main">
  	<div class="container">
	  	<div class="row mt-5">
	  		<div class="col-lg-9 col-md-9 offset-lg-3 offset-md-3 box">
	  			<form method="POST" action="">
            <div class="row">
              <h2 class="text-center text-primary">Start messaging</h2>
              <hr class="border" />
              <div class="col-lg-12 col-md-12">
                <p class="text-success"><?php if(isset($result)){echo 'Message sent.';} ?></p>
                <form method="POST" action="">
                  <div class="row">
                    <div class="col-sm">
                      <textarea class="form-control" id="message" name="message" rows="2"></textarea>
                      <button type="submit" class="btn btn-primary mt-2 mb-3">Submit</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12 col-md-12">
                <?php if(isset($result)): foreach($result as $message): 
                  $users = $user->getUserData($message['user_id']);
                  if($user_id != $message['user_id']): ?>
                  <p style="<?php if($user_data[0]['user_id'] != $message['user_id']){echo 'text-align: right';} ?>"><?php echo $message['message']; ?> <img src="<?php if(isset($users[0]['profile'])){echo base_url.$users[0]['profile'];}else{echo base_url.'uploads/profile/profile.png';} ?>" width="50" height="50" /></p>
                 <?php else: ?>
                 <p style="<?php if($user_data[0]['user_id'] != $message['user_id']){echo 'text-align: right';} ?>"><img src="<?php if(isset($users[0]['profile'])){echo base_url.$users[0]['profile'];}else{echo base_url.'uploads/profile/profile.png';} ?>" width="50" height="50" /> <?php echo $message['message']; ?></p>
                 <?php endif; ?>
                <?php endforeach; else: ?>

                 <?php foreach($messages as $message): 
                    $users = $user->getUserData($message['user_id']);
                 ?>
                 <?php if($user_id != $message['user_id']): ?>
                  <p style="<?php if($user_data[0]['user_id'] != $message['user_id']){echo 'text-align: right';} ?>"><?php echo $message['message']; ?> <img src="<?php if(isset($users[0]['profile'])){echo base_url.$users[0]['profile'];}else{echo base_url.'uploads/profile/profile.png';} ?>" width="50" height="50" /></p>
                 <?php else: ?>
                 <p style="<?php if($user_data[0]['user_id'] != $message['user_id']){echo 'text-align: right';} ?>"><img src="<?php if(isset($users[0]['profile'])){echo base_url.$users[0]['profile'];}else{echo base_url.'uploads/profile/profile.png';} ?>" width="50" height="50" /> <?php echo $message['message']; ?></p>
                 <?php endif; ?>
                 <?php endforeach; ?>
                <?php endif; ?>
              </div>
            </div>
          </form>
	  		</div>
	  	</div>
	 </div>
  </div>

  <a href="#" class="back-to-top btn-primary d-flex align-items-center justify-content-center" style="border: 1px solid #e6ddd1;"><i class="bi bi-arrow-up-short"></i></a>

  <?php include '../partials/footer.php'; ?>