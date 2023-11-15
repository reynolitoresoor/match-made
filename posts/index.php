<?php 
require_once('../database.php');
require_once('../classes.php'); 
$page_title = "Posts";

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
        <div class="row">
          <div class="col-lg-9 col-md-9 offset-lg-3 offset-md-3">
             <form method="POST" class="mt-4" action="" enctype="multipart/form-data">
              <div class="row">
                <div class="col-lg-12 col-md-12">
                  <input type="text" name="post" class="form-control" placeholder="Start posting to find someone that match with you">
                  <div class="post" style="text-align: left;margin-top: 10px;">
                    <input type="file" id="add-media" name="add_media" hidden>
                    <button class="btn btn-primary" name="submit_post">Post</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
        <?php if(isset($post_data)): ?>
          <div class="row mt-5 box" style="text-align: left;">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 d-flex flex-row justify-content-between">
              <div>
                <div class="mb-3">
                  <img src="<?php if(!empty($user_data[0]['profile'])){echo base_url.$user_data[0]['profile'];}else{echo base_url.'uploads/profile/profile.png';} ?>" width="40" height="40" class="img-responsive profile"/> <span><?php echo $user_data[0]['username']; ?></span>
                </div>
                <div>
                  <p id="post-<?php echo $post_data[0]['post_id']; ?>"><?php echo $post_data[0]['post']; ?></p>
                </div>
              </div>
              <?php if($post_data[0]['user_id'] == $user_data[0]['user_id']): ?>
              <div>
                <a class="edit" style="color: #e6ddd1;" title="edit post" data-id="<?php echo $post_data[0]['post_id']; ?>" data-post="<?php echo $post_data[0]['post']; ?>" onclick="editPost(this)"><i class="bi bi-pencil"></i></a>
                <a class="delete" style="color: #231f1f;" title="delete post" data-id="<?php echo $post_data[0]['post_id']; ?>" onclick="deletePost(this)"><i class="bi bi-trash"></i></a>
              </div>
              <?php endif; ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 d-flex flex-row">
              <div class="d-flex flex-column mr-3">
                <span><?php if($post_data[0]['total_likes'] > 1){echo '<span id="like-counter-'.$post_data[0]['post_id'].'">'.$post_data[0]['total_likes'].'</span>'.' love';}else{echo '<span id="like-counter-'.$post_data[0]['post_id'].'">'.$post_data[0]['total_likes'].'</span>'.' love';} ?></span>
                <span data-post_id="<?php echo $post_data[0]['post_id'] ?>" id="post-like-<?php echo $post_data[0]['post_id']; ?>" onclick="like(this)" class="like bi <?php if($post_data[0]['total_likes'] > 0 && $user_data[0]['user_id'] == $post_data[0]['user_id']){ echo 'bi-heart-fill text-primary';}else{echo 'bi-heart';} ?>"></span>
              </div>
              <div class="d-flex flex-column mr-3">
                <span><?php if($post_data[0]['total_dislikes'] > 1){echo '<span id="dislike-counter-'.$post_data[0]['post_id'].'">'.$post_data[0]['total_dislikes'].'</span>'.' heart break';}else{echo '<span id="dislike-counter-'.$post_data[0]['post_id'].'">'.$post_data[0]['total_dislikes'].'</span>'.' heart break';} ?></span>
                <span data-post_id="<?php echo $post_data[0]['post_id'] ?>" id="post-dislike-<?php echo $post_data[0]['post_id']; ?>" onclick="dislike(this)" class="dislike bi <?php if($post_data[0]['total_dislikes'] > 0 && $user_data[0]['user_id'] == $post_data[0]['user_id']){ echo 'bi-heart-break-fill text-primary';}else{echo 'bi-heart-break';} ?>"></span>
              </div>
              <div class="d-flex flex-column mr-3">
                <span><?php if($post_data[0]['total_comments'] > 1){echo $post_data[0]['total_comments'].' comments';}else{echo $post_data[0]['total_comments'].' comment';} ?></span>
                <span data-post_id="<?php echo $post_data[0]['post_id'] ?>" data-user="<?php echo $post_data[0]['username'] ?>" id="post-comment-<?php echo $post_data[0]['post_id']; ?>" onclick="comment(this)" class="bi bi-chat comment"></span>
              </div>
            </div>
          </div>
          <?php endif; ?>
          <?php if($posts): foreach($posts as $post): 
            $users = $user_obj->getUserData($post['user_id']);
          ?>
          <div class="row mt-4 box" style="text-align: left;">
            <div class="col-lg-9 col-md-9 offset-lg-3 offset-md-3 d-flex flex-row justify-content-between">
              <div>
                <div class="mb-3">
                  <img src="<?php if(!empty($users[0]['profile'])){echo base_url.$users[0]['profile'];}else{echo base_url.'uploads/profile/profile.png';} ?>" width="40" height="40" class="img-responsive profile"/> <span><?php echo $users[0]['username']; ?></span>
                </div>
                <div>
                  <?php if($post['attachments']): ?>
                  <img src="<?php echo base_url.$post['attachments']; ?>" width="200" height="200">
                  <?php endif; ?>
                  <p id="post-<?php echo $post['post_id']; ?>"><?php echo $post['post']; ?></p>
                </div>
              </div>
              <?php if($users[0]['user_id'] == $user_data[0]['user_id']): ?>
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
            <div class="col-lg-9 col-md-9 offset-lg-3 offset-md-3 col-xs-12 d-flex flex-row">
              <div class="d-flex flex-column mr-3">
                <span><?php if($post['total_likes'] > 1){echo '<span id="like-counter-'.$post['post_id'].'">'.$post['total_likes'].'</span>'.' love';}else{echo '<span id="like-counter-'.$post['post_id'].'">'.$post['total_likes'].'</span>'.' love';} ?></span>
                <span data-post_id="<?php echo $post['post_id'] ?>" id="post-like-<?php echo $post['post_id']; ?>" onclick="like(this)" class="like bi <?php if($post['total_likes'] > 0 && $user_data[0]['user_id'] == $post['user_id']){ echo 'bi-heart-fill text-primary';}else{echo 'bi-heart';} ?>" <?php if($post['total_likes'] > 0 && $user_data[0]['user_id'] == $post['user_id']){ echo 'disabled="disabled"'; }?>></span>
              </div>
              <div class="d-flex flex-column mr-3">
                <span><?php if($post['total_dislikes'] > 1){echo '<span id="dislike-counter-'.$post['post_id'].'">'.$post['total_dislikes'].'</span>'.' heart break';}else{echo '<span id="dislike-counter-'.$post['post_id'].'">'.$post['total_dislikes'].'</span>'.' heart break';} ?></span>
                <span data-post_id="<?php echo $post['post_id'] ?>" id="post-dislike-<?php echo $post['post_id']; ?>" onclick="dislike(this)" class="dislike bi <?php if($post['total_dislikes'] > 0 && $user_data[0]['user_id'] == $post['user_id']){ echo 'bi-heartbreak-fill text-primary';}else{echo 'bi-heartbreak';} ?>" <?php if($post['total_dislikes'] > 0 && $user_data[0]['user_id'] == $post['user_id']){ echo 'disabled="disabled"'; }?>></span>
              </div>
              <div class="d-flex flex-column mr-3">
                <span><?php if($post['total_comments'] > 1){echo '<span id="comment-counter-'.$post['post_id'].'">'.$post['total_comments'].'</span> comments';}else{echo '<span id="comment-counter-'.$post['post_id'].'">'.$post['total_comments'].'</span> comment';} ?></span>
                <span data-post_id="<?php echo $post['post_id'] ?>" data-user="<?php echo $users[0]['username'] ?>" id="post-comment-<?php echo $post['post_id']; ?>" onclick="comment(this)" class="bi bi-chat comment"></span>
              </div>
            </div>
            <div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3 col-xs-12 comment-section comment-<?php echo $post['post_id']; ?>">
              <div class="row mt-3">
                <form method="POST" name="add_comment" action="" id="add-comment-<?php echo $post['post_id']; ?>">
                  <textarea class="form-control mb-1" rows="1" name="comment" placeholder="Write a comment"></textarea>
                  <input type="hidden" name="post_id" value="<?php echo $post['post_id']; ?>">
                  <input type="hidden" name="user_id" value="<?php echo $user_data[0]['user_id']; ?>">
                  <input type="hidden" name="add_comment" value="Add comment" />
                  <button type="button" class="btn btn-primary" data-post_id="<?php echo $post['post_id']; ?>" name="add_comment" onclick="addComment(this)"> Comment</button> 
                </form>
              </div>
              <?php  
                $comments = $post_obj->getCommentById($post['post_id']);
                if($comments):
                foreach($comments as $comment):
              ?>
              <div class="row comment-box-<?php echo $comment['comment_id']; ?>">
                <div class="box-dark d-flex flex-row justify-content-between">
                  <div class="d-flex flex-row">
                    <img class="img-responsive profile" src="<?php if(!empty($comment['profile'])){echo base_url.$comment['profile'];}else{echo base_url.'uploads/profile/profile.png';} ?>" width="40px" height="40px">
                    <p id="comment-<?php echo $comment['comment_id']; ?>" data-post_id="<?php echo $post['post_id']; ?>"><?php echo $comment['comment']; ?></p>
                  </div>
                  <?php if($comment['user_id'] == $user_data[0]['user_id']): ?>
                  <div>
                    <a title="Edit comment" data-comment_id="<?php echo $comment['comment_id']; ?>" data-post_id="<?php echo $post['post_id']; ?>" onclick="editComment(this)"><span class="bi bi-pencil"></span></a>
                    <a title="Delete comment" data-comment_id="<?php echo $comment['comment_id']; ?>" data-post_id="<?php echo $post['post_id']; ?>" onclick="deleteComment(this)"><span class="bi bi-trash"></span></a>
                  </div>
                  <?php endif; ?>
                </div>
                <div class="row reply-section reply-<?php echo $comment['comment_id']; ?>" style="display: none;">
                  <form method="POST" name="add_reply" action="" id="add-reply-<?php echo $comment['comment_id']; ?>">
                    <textarea class="form-control mb-1" rows="1" name="reply"></textarea>
                    <input type="hidden" name="comment_id" value="<?php echo $comment['comment_id']; ?>">
                    <input type="hidden" name="user_id" value="<?php echo $user_data[0]['user_id']; ?>">
                    <input type="hidden" name="add_reply" value="Add reply" />
                    <button type="button" class="btn btn-primary" data-comment_id="<?php echo $comment['comment_id']; ?>" name="add_reply" onclick="addReply(this)">Add Reply</button> 
                  </form>
                </div>
              </div>
            <?php endforeach; endif; ?>
            </div>
          </div>
          <?php endforeach; else: ?>
          <div class="row mt-4">
            <div class="col-lg-9 col-md-9 offset-lg-3 offset-md-3">
              <p>No posts available</p>
            </div>
          </div>
          <?php endif; ?>
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
      
      $(el).removeClass('bi-heart').addClass('bi-heart-fill text-primary');
      $('span#like-counter-'+post_id).html(like_counter + 1);
      if(dislike_counter >= 0) {
        $('span#dislike-counter-'+post_id).html(dislike_counter - 1);
      }

      if(!$('span#post-like-'+post_id).attr('disabled')){
        $.ajax({
          url: base_url+'requests/reacts.php', 
          method: "post",  
          data:{
            post_id: post_id,
            user_id: user_id,
            type: 'like'
          },
          success: function(response){
            if(response && $(el).hasClass('text-primary')) {
              $('span#post-dislike-'+post_id).removeClass('bi-heartbreak-fill text-primary').addClass('bi-heartbreak');
              $('span#like-counter-'+post_id).html(like_counter + 1);
              $('span#post-like-'+post_id).attr('disabled','true');
              $('span#post-dislike-'+post_id).removeAttr('disabled');
            }
          }
        });
      }

    }

    function dislike(el) {
      var post_id = $(el).data('post_id');
      var user_id = <?php echo $user_data[0]['user_id']; ?>;
      var base_url = '<?php echo base_url; ?>';
      var dislike_counter = parseInt($('span#dislike-counter-'+post_id).html());
      var like_counter = parseInt($('span#like-counter-'+post_id).html());
      
      $(el).removeClass('bi-heartbreak').addClass('bi-heartbreak-fill text-primary');
      $('span#dislike-counter-'+post_id).html(dislike_counter + 1);
      if(like_counter >= 0) {
        $('span#like-counter-'+post_id).html(like_counter - 1);
      }

      if(!$('span#post-dislike-'+post_id).attr('disabled')){
        $.ajax({
          url: base_url+'requests/reacts.php', 
          method: "post",  
          data:{
            post_id: post_id,
            user_id: user_id,
            type: 'dislike'
          },
          success: function(response){
            if(response && $(el).hasClass('text-primary')) {
              $('span#post-like-'+post_id).removeClass('bi-heart-fill text-primary').addClass('bi-heart');
              $('span#dislike-counter-'+post_id).html(dislike_counter + 1);
              $('span#post-dislike-'+post_id).attr('disabled','true');
              $('span#post-like-'+post_id).removeAttr('disabled');
            }
          }
        });
      }

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
  