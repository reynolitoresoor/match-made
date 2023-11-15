  <?php 
  require_once('database.php');
  require_once('classes.php');
  
  $page_title = "Reset Password";

  if(isset($_SESSION['match_made_data'])) {
    header('Location: '.base_url.'home');
  }

  if($_GET['email']) {
    $email = $_GET['email'];
  } else {
    header('Location: '.base_url.'login.php');
  }

  $user = new User();

  if($_POST) {
    $result = $user->resetPassword($_POST);
      if(isset($result) && $result != 'error') {
        header('Location: '.base_url.'login.php');
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
            <h2 class="text-center mt-3 mb-3" style="color: #e94f37;">Reset Password</h2>
            <?php if(isset($result) && $result == 'error'){ ?>
            <p class="text-danger">Unable to reset your password.</p>
            <?php } ?>
            <div class="row">
              <div class="col-lg-12 col-md-12 form-group mb-3">
                <label class="form-label" for="password">New Password</label>
                <input type="password" name="password" id="password" placeholder="" style="width: 100%;">
              </div>
              <div class="col-lg-12 col-md-12 form-group mb-3">
                <label class="form-label" for="cofirm-password">Confirm Password</label>
                <input type="password" name="confirm_pass" id="confirm-password" placeholder="" style="width: 100%;">
                <input type="hidden" name="email" value="<?php echo $email; ?>">
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
