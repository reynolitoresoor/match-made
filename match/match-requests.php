<?php 
require_once('../database.php');
require_once('../classes.php'); 
$page_title = "Match Requests";

if(!isset($_SESSION['match_made_data'])) {
   header('Location: '.base_url);
}
$user = new User();
$user_data = $user->getUserData($_SESSION['match_made_data']['user_id']);
$friends = $user->getAllFriendRequests($user_data[0]['user_id']);

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
                <h2 class="text-center">Match Friend Requests.</h2>
              <?php foreach($friends as $friend): ?>
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-3 d-flex flex-row" style="text-align: left;">
                <img class="img-responsive profile mr-3" width="150" height="150" src="<?php if(isset($friend['profile'])){echo base_url.$friend['profile'];}else{echo base_url.'uploads/profile/profile.png';} ?>" /> 
                <div class="d-flex flex-column">
                  <a href="#"><?php echo $friend['username']; ?></a>
                  <button type="button" class="btn btn-primary" data-user_id="<?php echo $friend['user_id']; ?>" id="user-<?php echo $friend['user_id']; ?>" onclick="confirmFriend(this)">Confirm</button>
                </div>

              </div>
              <?php endforeach; ?>
              <?php } else { ?>
              <h2 class="text-primary text-center">You don't have match friend request yet.</h2>
              <?php } ?>
            </div>
          </form>
        </div>
      </div>
   </div>
  </div>

  <a href="#" class="back-to-top btn-primary d-flex align-items-center justify-content-center" style="border: 1px solid #e6ddd1;"><i class="bi bi-arrow-up-short"></i></a>
  
  <script type="text/javascript">
    function confirmFriend(el) {
      var base_url = '<?php echo base_url; ?>';
      var user_id = <?php echo $user_data[0]['user_id']; ?>;
      var friend_id = $(el).data('user_id');
      
      $.ajax({
        url: base_url+'requests/confirm-friend.php', 
        method: "post",  
        data:{
          user_id: user_id,
          friend_id: friend_id
        },
        success: function(response){
          var obj = JSON.parse(response);

          for(var i = 0; i < obj.length; i++) {
            $('button#user-'+obj[i].user_id).removeClass('btn-primary').addClass('btn-success').html('Confirmed');
          }
        }
      });
    }
  </script>
  <?php include '../partials/footer.php'; ?>