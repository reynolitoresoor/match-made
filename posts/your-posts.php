<?php 
require_once('../database.php');
require_once('../classes.php'); 
$page_title = "Your Posts";

if(!isset($_SESSION['match_made_data'])) {
   header('Location: '.base_url);
}
$user = new User();
$post = new Post();
$user_data = $user->getUserData($_SESSION['match_made_data']['user_id']);
$posts = $post->getAllUserPosts($user_data[0]['user_id']);

if(isset($_POST['edit_post'])) {
  $data = $_POST;
  $update_post = $post->updateUserPost($data);
  header('Location: '.$_SERVER['REQUEST_URI']);
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
              <?php if($posts){ ?>
                <h2 class="text-center">Your posts.</h2>
              <?php foreach($posts as $post): ?>
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 d-flex flex-row justify-content-between">
              <div class="d-flex flex-column text-left">
                <div class="mb-3 mr-2">
                  <img src="<?php if(!empty($post['profile'])){echo base_url.$post['profile'];}else{echo base_url.'uploads/profile/profile.png';} ?>" width="40" height="40" class="img-responsive profile"/> <span><?php echo $post['username']; ?></span>
                </div>
                <div>
                  <?php if($post['attachments']): ?>
                  <img src="<?php echo base_url.$post['attachments']; ?>" width="200" height="200">
                  <?php endif; ?>
                  <p id="post-<?php echo $post['post_id']; ?>"><?php echo $post['post']; ?></p>
                </div>
              </div>
              <?php if($post['user_id'] == $user_data[0]['user_id']): ?>
              <div>
                <div class="dropdown">
                  <span class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="bi bi-three-dots-vertical"></i>
                  </span>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="edit text-success" title="edit post" data-id="<?php echo $post['post_id']; ?>" data-post="<?php echo $post['post']; ?>" onclick="editPost(this)"><i class="bi bi-pencil"></i></a>
                    <a class="delete text-danger" title="delete post" data-id="<?php echo $post['post_id']; ?>" onclick="deletePost(this)"><i class="bi bi-trash"></i></a>
                  </div>
                </div>
              </div>
              <?php endif; ?>
            </div>
              <?php endforeach; ?>
              <?php } else { ?>
              <h2 class="text-primary text-center">You have no post yet.</h2>
              <?php } ?>
            </div>
          </form>
        </div>
      </div>
   </div>
  </div>
  <?php include '../modals.php'; ?>
  <a href="#" class="back-to-top btn-primary d-flex align-items-center justify-content-center" style="border: 1px solid #e6ddd1;"><i class="bi bi-arrow-up-short"></i></a>
  
  <script type="text/javascript">
    function editPost(el) {
      var user_id = <?php echo $user_data[0]['user_id']; ?>;
      $('form#edit-post input#edit-post').val($(el).data('post'));
      $('form#edit-post input#post-id').val($(el).data('id'));
      $('form#edit-post input#user-id').val(user_id);
      
      $('#editPostModal').modal('show');
    }

    function deletePost(el) {
      var base_url = '<?php echo base_url; ?>';
      var user_id = <?php echo $user_data[0]['user_id']; ?>;
      var post_id = $(el).data('id');
      
      if(confirm('Are you sure you want to delete this post?') == true) {
        $.ajax({
          url: base_url+'requests/delete-post.php', 
          method: "post",  
          data:{
            post_id: post_id,
            user_id: user_id
          },
          success: function(response){
            if(response) {
              alert('Your post successfully deleted!');
              location.reload();
            }
          }
        });
      }
    }
  </script>
  <?php include '../partials/footer.php'; ?>