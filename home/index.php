<?php 
require_once('../database.php');
require_once('../classes.php'); 
$page_title = "Home";

if(!isset($_SESSION['match_made_data'])) {
   header('Location: '.base_url);
}
$user_obj = new User();
$post_obj = new Post();
$comment = new Comment();

$user_data = $user_obj->getUserData($_SESSION['match_made_data']['user_id']);
$posts = $post_obj->getPosts($user_data[0]['user_id']);
$friends = $user_obj->getAllUsers($user_data[0]['user_id']);

if(isset($_POST['submit_post']) && !empty($_POST['post'])) {
  $_POST['user_id'] = $user_data[0]['user_id'];
  $post_id = $post_obj->save($_POST);
  $result = $post_obj->getUserPost($post_id);
  header('Location: '.$_SERVER['REQUEST_URI']);
}

if(isset($_POST['edit_post'])) {
  $data = $_POST;
  $attachment = $_FILES;
  $update_post = $post->updateUserPost($data, $attachment);
  header('Location: '.$_SERVER['REQUEST_URI']);
}

if(isset($_POST['add_comment'])) {
  $result = $comment->save($_POST);
  header('Location: '.$_SERVER['REQUEST_URI']);
} 


include '../partials/header.php';
?>
  <main id="main">

    <section class="inner-page">
      <div class="container">
        <div class="row pt-3">
          <div class="col-lg-9 col-md-9 offset-lg-3 offset-md-3">
            <h2>People you may like to date</h2>
            <div class="row mt-3">
            <?php if($friends): foreach($friends as $user): ?>
            <div class="col-lg-3 col-md-3 col-sm-2 col-xs-12" style="margin-bottom: 15px;padding: 5px;">
              <div class="card p-3">
              <img src="<?php if(!empty($user['profile'])){echo base_url.$user['profile'];}else{echo base_url.'uploads/profile/profile.png';} ?>" width="100%" height="180" class="img-responsive profile mb-2">
              <h6 style="margin-bottom: 0px;" class="d-flex justify-content-between align-items-center"><?php if(strlen($user['username']) <= 18){echo ucfirst($user['username']);}else{substr($user['username'], 0, 18).'...';} ?> <span class="text-success"><?php echo $user['age']; ?></span></h6>
              <p style="margin-bottom: 0px;font-size: 15px;" class="mb-2"><?php if(strlen($user['about_yourself']) <= 18){ echo $user['about_yourself'];}else{echo substr(ucfirst($user['about_yourself']), 0, 18).'...';}; ?></p>
              <button type="button" class="btn btn-primary" id="friend-<?php echo $user['user_id']; ?>" data-friend_id="<?php echo $user['user_id']; ?>" onclick="addFriend(this)">Match</button>
              </div>
            </div>
            <?php endforeach; else: ?>
            <p>No data available</p>
            <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </section>

  </main><!-- End #main -->

  <?php 
  include '../partials/sidebar.php';
  include '../modals.php'; ?>

  <a href="#" class="back-to-top btn-primary d-flex align-items-center justify-content-center" style="border: 1px solid #e6ddd1;"><i class="bi bi-arrow-up-short"></i></a>

  <script type="text/javascript">
    $(document).ready(function() {
       var base_url = '<?php echo base_url; ?>';
       var user_id = <?php echo $user_data[0]['user_id']; ?>;
       
       //Get user reactions
       $.ajax({
        url: base_url+'requests/get-user-reactions.php', 
        method: "post",  
        data:{
          user_id: user_id
        },
        success: function(response){
          var obj = JSON.parse(response);
          
          for(var i = 0; i < obj.length; i++) {
            if(obj[i]['status'] == 1 && !$('span#post-like-'+obj[i].post_id).hasClass('text-primary')) {
              $('span#post-like-'+obj[i].post_id).removeClass('bi-hand-thumbs-up').addClass('bi-hand-thumbs-up-fill text-primary');
            } else if(obj[i].status == 2 && !$('span#post-dislike-'+obj[i].post_id).hasClass('text-primary')) {
              $('span#post-dislike-'+obj[i].post_id).removeClass('bi-hand-thumbs-down').addClass('bi-hand-thumbs-down-fill text-primary');
            } 
          }
        }
      });
      
      //Get user friends
      $.ajax({
        url: base_url+'requests/get-user-friends.php', 
        method: "post",  
        data:{
          user_id: user_id
        },
        success: function(response){
          var obj = JSON.parse(response);

          for(var i = 0; i < obj.length; i++) {
            if(obj[i]['friend_status'] == 0) {
              $('button#friend-'+obj[i].friend_id).removeClass('btn-primary').addClass('btn-success').html('Match request');
            }
          }
        }
      });

      $('.owl-carousel').owlCarousel({
          loop:true,
          margin:10,
          nav:false,
          responsive:{
              0:{
                  items:1
              },
              600:{
                  items:4
              },
              1000:{
                  items:4
              }
          }
      })

      $('button.add-media').click(function() {
         $('input#add-media').click();
      });

      $('button#edit-post').click(function() {
        var user_id = <?php echo $user_data[0]['user_id']; ?>;
        var post = $('form#edit-post').find('.post').val();
        var post_id = $('form#edit-post').find('.post-id').val();

        $.ajax({
          url: base_url+'requests/update-post.php', 
          method: "post",  
          data:{
            post_id: post_id,
            user_id: user_id,
            post: post
          },
          success: function(response){
            var obj = JSON.parse(response);
            if(ojb) {
              for(var i = 0; i < obj.length; i++) {
                $('post-'+obj[i]).html(obj[i].post);
              }
              alert('Your post successfully updated!');
            }
          }
        });

      });

    });

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

    function comment(el) {
      var user_id = <?php echo $user_data[0]['user_id']; ?>;
      var post_id = $(el).data('post_id');

      $('form#add-comment input#post-id').val($(el).data('post_id'));
      $('form#add-comment input#user-id').val(user_id);
      
      $('.comment-'+post_id).show();
      //$('#commentModal').modal('show');
    }

    function addComment(el) {
      var post_id = $(el).data('post_id');
      $('form#add-comment-'+post_id).submit();
    }

    function like(el) {
      var post_id = $(el).data('post_id');
      var user_id = <?php echo $user_data[0]['user_id']; ?>;
      var base_url = '<?php echo base_url; ?>';
      var like_counter = parseInt($('span#like-counter-'+post_id).html());
      var dislike_counter = parseInt($('span#dislike-counter-'+post_id).html());
      
      $(el).removeClass('bi-hand-thumbs-up').addClass('bi-hand-thumbs-up-fill text-primary');
      $('span#like-counter-'+post_id).html(like_counter + 1);
      if(dislike_counter > 1) {
        $('span#dislike-counter-'+post_id).html(dislike_counter - 1);
      }

      $.ajax({
        url: base_url+'requests/reacts.php', 
        method: "post",  
        data:{
          post_id: post_id,
          user_id: user_id,
          type: 'like'
        },
        success: function(response){
          console.log(response);
          if(response && $(el).hasClass('text-primary')) {
            $('span#post-dislike-'+post_id).removeClass('bi-hand-thumbs-down-fill text-primary').addClass('bi-hand-thumbs-down');
          }
        }
      });
    }

    function dislike(el) {
      var post_id = $(el).data('post_id');
      var user_id = <?php echo $user_data[0]['user_id']; ?>;
      var base_url = '<?php echo base_url; ?>';
      var dislike_counter = parseInt($('span#dislike-counter-'+post_id).html());
      var like_counter = parseInt($('span#like-counter-'+post_id).html());
      
      $(el).removeClass('bi-hand-thumbs-down').addClass('bi-hand-thumbs-down-fill text-primary');
      $('span#dislike-counter-'+post_id).html(dislike_counter + 1);
      if(like_counter > 1) {
        $('span#like-counter-'+post_id).html(like_counter - 1);
      }

      $.ajax({
        url: base_url+'requests/reacts.php', 
        method: "post",  
        data:{
          post_id: post_id,
          user_id: user_id,
          type: 'dislike'
        },
        success: function(response){
          console.log(response);
          if(response && $(el).hasClass('text-primary')) {
            $('span#post-like-'+post_id).removeClass('bi-hand-thumbs-up-fill text-primary').addClass('bi-hand-thumbs-up');
          }
        }
      });
    }

    function addFriend(el) {
      var user_id = <?php echo $user_data[0]['user_id']; ?>;
      var base_url = '<?php echo base_url; ?>';
      var friend_id = $(el).data('friend_id');
      $(el).removeClass('btn-primary').addClass('btn-success').html('Match request');
      
      $.ajax({
        url: base_url+'requests/add-friend.php', 
        method: "post",  
        data:{
          friend_id: friend_id,
          user_id: user_id
        },
        success: function(response){
          
        }
      });
    }

    function editComment(el) {
      var base_url = '<?php echo base_url; ?>';
      var user_id = <?php echo $user_data[0]['user_id']; ?>;
      var post_id = $(el).data('post_id');
      var comment_id = $(el).data('comment_id');
      var comment = $('p#comment-'+comment_id).html();
      
      $('form#edit-comment').find('textarea#comment').val(comment);
      $('#editCommentModal').modal('show');

      $('button#edit-comment').click(function() {
        var comment = $('form#edit-comment').find('textarea#comment').val();
          $.ajax({
            url: base_url+'requests/edit-comment.php', 
            method: "post",  
            data:{
              user_id: user_id,
              comment_id: comment_id,
              comment: comment
            },
            success: function(response){
              var obj = JSON.parse(response);
              
              for(var i = 0; i < obj.length; i++) {
                $('p#comment-'+obj[i].comment_id).html(obj[i].comment);
                $('#editCommentModal').modal('hide');
              }
            }
          });
      });

    }

    function deleteComment(el) {
      var base_url = '<?php echo base_url; ?>';
      var user_id = <?php echo $user_data[0]['user_id']; ?>;
      var comment_id = $(el).data('comment_id');
      var post_id = $(el).data('post_id');
      var comment_counter = parseInt($('span#comment-counter-'+post_id).html());

      if(confirm('Are you sure you want to delete your comment?') == true) {
        $.ajax({
          url: base_url+'requests/delete-comment.php', 
          method: "post",  
          data:{
            user_id: user_id,
            comment_id: comment_id
          },
          success: function(response){
            if(response) {
              $('#comment-counter-'+post_id).html(comment_counter - 1);
              $('.comment-box-'+comment_id).fadeOut();
            }
          }
        });
      }
    }


  </script>

  <?php include '../partials/footer.php'; ?>
  