  <?php 
  require_once('database.php');
  require_once('classes.php');
  
  $page_title = "Login";

  if(isset($_SESSION['match_made_data'])) {
    header('Location: '.base_url.'home');
  }

  if($_POST) {
    $user = new User();
      $result = $user->login($_POST);
      if($result) {
        $_SESSION['match_made_data'] = $result;
        header('Location: '.base_url.'home');
      } else {
          $_SESSION['login_error'] = "Invalid username or password.";   
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
            <div class="text-center">
              <img src="<?php echo base_url.'uploads/images/logo-icon.png'; ?>">
            </div>
            <h2 class="text-center mt-3 mb-3" style="color: #e94f37;">Login</h2>
            <?php if(isset($_SESSION['account_created'])){ ?>
            <p class="text-success login-success"><?php echo $_SESSION['account_created']; ?></p>
            <?php } ?>
            <?php if(isset($_SESSION['login_error'])){ ?>
            <p class="text-danger login-error"><?php echo $_SESSION['login_error']; ?></p>
            <?php } ?>
            <div class="row">
              <div class="col-lg-12 col-md-12 form-group mb-3">
                <label class="form-label" for="username">Username</label>
                <input type="text" name="username" id="username" placeholder="" style="width: 100%;">
              </div>
              <div class="col-lg-12 col-md-12 form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="" style="width: 100%;">
              </div>
              <div class="mt-3">
                <button type="submit" class="btn btn-primary" style="width: 100%;padding: 10px;border-radius: 5px;" name="login">Login</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section><!-- End Hero -->

<?php include 'partials/footer.php'; ?>
