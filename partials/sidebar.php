<!-- ======= Header ======= -->
  <header id="header">
    <div class="d-flex flex-column">
      <?php if(isset($user_data)): ?>
      <div class="profile">
        <a href="<?php echo base_url.'profile'; ?>"><img src="<?php if(isset($user_data[0]['profile'])){echo base_url.$user_data[0]['profile'];}else{echo base_url.'uploads/profile/profile.png';} ?>" alt="" class="img-fluid rounded-circle"></a>
        <h1 class="text-light"><a href="<?php echo base_url.'profile'; ?>"><?php echo $user_data[0]['username']; ?></a></h1>
        <!-- <div class="social-links mt-3 text-center">
          <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
          <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
          <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
          <a href="#" class="google-plus"><i class="bx bxl-skype"></i></a>
          <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
        </div> -->
      </div>
      <?php endif; ?>
      <nav id="navbar" class="nav-menu navbar">
        <ul>
          <li><a href="<?php echo base_url; ?>" class="nav-link scrollto <?php if(strtolower($page_title) == 'home'){echo 'active';}  ?>"><i class="bx bx-home"></i> <span>Home</span></a></li>
          <?php if(isset($user_data)): ?>
          <li><a href="<?php echo base_url.'profile'; ?>" class="nav-link scrollto <?php if(strtolower($page_title) == 'profile'){echo 'active';}  ?>"><i class="bx bx-user"></i> <span>Profile</span></a></li>
          <li><a href="<?php echo base_url.'match'; ?>" class="nav-link scrollto <?php if(strtolower($page_title) == 'match'){echo 'active';}  ?>"><i class="bx bx-user-plus"></i> <span>Match</span></a></li>
          <li><a href="<?php echo base_url.'match/match-requests.php'; ?>" class="nav-link scrollto <?php if(strtolower($page_title) == 'match requests'){echo 'active';}  ?>"><i class="bx bx-user-plus"></i> <span>Match Request</span></a></li>
          <li><a href="<?php echo base_url.'message/messages.php'; ?>" class="nav-link scrollto <?php if(strtolower($page_title) == 'messages' || strtolower($page_title) == 'message'){echo 'active';}  ?>"><i class="bx bx-chat"></i> <span>Messages</span></a></li>
          <li><a href="<?php echo base_url.'posts'; ?>" class="nav-link scrollto <?php if(strtolower($page_title) == 'posts'){echo 'active';}  ?>"><i class="bx bx-server"></i> <span>Posts</span></a></li>
          <li><a href="<?php echo base_url.'posts/your-posts.php'; ?>" class="nav-link scrollto <?php if(strtolower($page_title) == 'your posts'){echo 'active';}  ?>"><i class="bx bx-server"></i> <span>Your Posts</span></a></li>
          <li><a href="<?php echo base_url.'logout.php'; ?>" class="nav-link scrollto"><i class="bx bx-lock"></i> <span>Logout</span></a></li>
          <?php else: ?>
          <li><a href="<?php echo base_url.'login.php'; ?>" class="nav-link scrollto <?php if(strtolower($page_title) == 'login'){echo 'active';}  ?>"><i class="bx bx-lock"></i> <span>Login</span></a></li>
          <li><a href="<?php echo base_url.'signup.php'; ?>" class="nav-link scrollto <?php if(strtolower($page_title) == 'sign up'){echo 'active';}  ?>"><i class="bx bx-user"></i> <span>Sign Up</span></a></li>
          <li><a href="forgot-password.php" class="scrollto <?php if(strtolower($page_title) == 'forgot password'){echo 'active';}  ?>">Forgot your password?</a></li>
          <?php endif; ?>
        </ul>
      </nav><!-- .nav-menu -->
    </div>
  </header><!-- End Header -->