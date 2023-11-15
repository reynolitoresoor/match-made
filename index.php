  <?php 
  require_once('database.php');
  require_once('classes.php');
  
  $page_title = "Home";

  if(isset($_SESSION['match_made_data'])) {
    header('Location: '.base_url.'home');
  }

  if($_POST) {
    $user = new User();
      $result = $user->login($_POST);
      if($result) {
        $_SESSION['match_made_data'] = $result;
        header('Location: '.base_url);
      } else {
          $_SESSION['login_error'] = "Invalid username or password.";   
      }
  }

  include 'partials/header.php';
  include 'partials/sidebar.php';
  ?>
  <!-- ======= Hero Section ======= -->
  <section id="hero" class="text-center d-flex flex-column justify-content-center align-items-center">
    <div class="hero-container" data-aos="fade-in">
      <img src="<?php echo base_url.'uploads/images/logo.png'; ?>" />
      <p><span class="typed" data-typed-items="Looking for a date?, Signup now..."></span></p>
    </div>
  </section><!-- End Hero -->

<?php include 'partials/footer.php'; ?>
