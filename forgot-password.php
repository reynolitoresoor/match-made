  <?php 
  require_once('database.php');
  require_once('classes.php');
  
  $page_title = "Forgot Password";

  if(isset($_SESSION['match_made_data'])) {
    header('Location: '.base_url.'home');
  }
  $user = new User();

  if($_POST) {
    $result = $user->forgotPassword($_POST);
      if(isset($result) && $result == 'success') {
        $_SESSION['match_made_data'] = $result;
        header('Location: '.base_url.'home');
      }
  }

  include 'partials/header.php';
  ?>
  <!-- ======= Mobile nav toggle button ======= -->
  <i class="bi bi-list mobile-nav-toggle d-xl-none"></i>

  <?php include 'partials/sidebar.php'; ?>
  
  <style>
    .login-form {
      padding: 30px 20px 30px 20px;
    }
    h2 {
      font-weight: 600;
    }
    .login-success, .login-error {
      font-size: 18px !important;
    }
  </style>
  <!-- ======= Hero Section ======= -->
  <section id="hero" class="d-flex flex-column justify-content-center align-items-center">
    <div class="hero-container" data-aos="fade-in">
      <div class="row">
        <div class="col-lg-4 col-md-4 offset-lg-5 offset-md-5 card login-form">
          <form method="POST" action="">
            <h2 class="text-center mt-3 mb-3" style="color: #e94f37;">Forgot My Password</h2>
            <?php if(isset($result) && $result == 'error'){ ?>
            <p class="text-danger">Email does not exist.</p>
            <?php } else if(isset($result) && $result == 'email not sent') { ?>
            <p class="text-danger"><?php echo $result; ?>.</p>
            <?php } ?>
            <div class="row">
              <div class="col-lg-12 col-md-12 form-group mb-3">
                <label class="form-label" for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="" style="width: 100%;">
              </div>
              <div class="mt-3">
                <button type="submit" class="btn btn-primary" style="width: 100%;padding: 10px;border-radius: 5px;" name="login">Submit</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section><!-- End Hero -->

<?php include 'partials/footer.php'; ?>
