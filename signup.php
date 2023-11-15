  <?php 
  require_once('database.php');
  require_once('classes.php');
  
  $page_title = "Sign Up";

  if(isset($_SESSION['match_made_data'])) {
    header('Location: '.base_url.'home');
  }

  if($_POST) {
    $user = new User();
    $user_id = $user->save($_POST);
    if($user_id > 0) {
      $_SESSION['account_created'] = "You can now login with your account.";
      header('Location: '.base_url.'login.php');
      exit;
    }

  }

  include 'partials/header.php';
  include 'partials/sidebar.php';
  ?>
  
  <style>
    .login-form {
      padding: 30px 20px 30px 20px;
    }
    h2 {
      font-weight: 600;
    }
    label {
      font-weight: bold;
    }
    input, input:focus {
      border: 1px solid #DDE6ED;
      padding: 10px;
      border-radius: 5px;
    }
  </style>
  <!-- ======= Hero Section ======= -->
  <section id="hero" class="d-flex flex-column justify-content-center align-items-center">
    <div class="hero-container" data-aos="fade-in">
      <div class="row">
        <div class="col-lg-6 col-md-6 offset-lg-4 offset-md-4 card login-form">
          <form method="POST" action="">
            <h2 class="text-center mb-3 mt-3" style="color: #e94f37;">Sign Up</h2>
            <div class="row">
              <div class="col-sm form-group mb-3">
                <label class="form-label" for="first-name">First Name</label>
                <input type="text" name="first_name" id="first-name" placeholder="" style="width: 100%;">
              </div>
              <div class="col-sm form-group">
                <label class="form-label" for="last-name">Last Name</label>
                <input type="text" name="last_name" id="last-name" placeholder="" style="width: 100%;">
              </div>
            </div>
            <div class="row">
              <div class="col-sm form-group">
                <label class="form-label" for="middle-name">Middle Name</label>
                <input type="text" name="middle_name" id="middle-name" placeholder="" style="width: 100%;">
              </div>
              <div class="col-sm form-group">
                <label class="form-label" for="username">Username</label>
                <input type="text" name="username" id="username" placeholder="" style="width: 100%;">
              </div>
            </div>
            <div class="row">
              <div class="col-sm form-group">
                <label class="form-label" for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="" style="width: 100%;">
              </div>
              <div class="col-sm form-group">
                <label class="form-label" for="age">Age</label>
                <input type="number" name="age" id="age" placeholder="" style="width: 100%;">
              </div>
            </div>
            <div class="row">
              <div class="col-sm form-group">
                <label class="form-label" for="confirm-pass">Confirm Password</label>
                <input type="password" name="confirm_pass" id="confirm-pass" placeholder="" style="width: 100%;">
              </div>
              <div class="col-sm form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="" style="width: 100%;">
              </div>
            </div>
            <div class="row">
              <div class="col-sm form-group">
                <label class="form-label" for="about-yourself">Tell About Your Self</label>
                <textarea rows="4" class="form-control" id="about-yourself" name="about_yourself"></textarea>
              </div>
            </div>
            <div class="mt-3">
              <button type="submit" class="btn btn-primary" style="width: 100%;padding: 10px;border-radius: 5px;" name="login">Sign Up</button>
            </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section><!-- End Hero -->

<?php include 'partials/footer.php'; ?>
