<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Index - Clinic Bootstrap Template</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Clinic
  * Template URL: https://bootstrapmade.com/clinic-bootstrap-template/
  * Updated: Jul 23 2025 with Bootstrap v5.3.7
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">

<?php include 'includes/navbar.php' ?>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row align-items-center">
          <div class="col-lg-6">
            <div class="hero-content">
              <div class="trust-badges mb-4" data-aos="fade-right" data-aos-delay="200">
                <div class="badge-item">
                  <i class="bi bi-shield-check"></i>
                  <span>Accredited</span>
                </div>
                <div class="badge-item">
                  <i class="bi bi-clock"></i>
                  <span>24/7 Emergency</span>
                </div>
                <div class="badge-item">
                  <i class="bi bi-star-fill"></i>
                  <span>4.9/5 Rating</span>
                </div>
              </div>

              <h1 data-aos="fade-right" data-aos-delay="300">
                Excellence in <span class="highlight">Healthcare</span> With Compassionate Care
              </h1>

              <p class="hero-description" data-aos="fade-right" data-aos-delay="400">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.
              </p>

              <div class="hero-stats mb-4" data-aos="fade-right" data-aos-delay="500">
                <div class="stat-item">
                  <h3><span data-purecounter-start="0" data-purecounter-end="15" data-purecounter-duration="2"
                      class="purecounter"></span>+</h3>
                  <p>Years Experience</p>
                </div>
                <div class="stat-item">
                  <h3><span data-purecounter-start="0" data-purecounter-end="5000" data-purecounter-duration="2"
                      class="purecounter"></span>+</h3>
                  <p>Patients Treated</p>
                </div>
                <div class="stat-item">
                  <h3><span data-purecounter-start="0" data-purecounter-end="50" data-purecounter-duration="2"
                      class="purecounter"></span>+</h3>
                  <p>Medical Experts</p>
                </div>
              </div>

              <div class="hero-actions" data-aos="fade-right" data-aos-delay="600">
                <a href="appointment.html" class="btn btn-primary">Book Appointment</a>
                <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8" class="btn btn-outline glightbox">
                  <i class="bi bi-play-circle me-2"></i>
                  Watch Our Story
                </a>
              </div>

              <div class="emergency-contact" data-aos="fade-right" data-aos-delay="700">
                <div class="emergency-icon">
                  <i class="bi bi-telephone-fill"></i>
                </div>
                <div class="emergency-info">
                  <small>Emergency Hotline</small>
                  <strong>+1 (555) 911-2468</strong>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="hero-visual" data-aos="fade-left" data-aos-delay="400">
              <div class="main-image">
                <img src="assets/img/health/staff-10.webp" alt="Modern Healthcare Facility" class="img-fluid">
                <div class="floating-card appointment-card">
                  <div class="card-icon">
                    <i class="bi bi-calendar-check"></i>
                  </div>
                  <div class="card-content">
                    <h6>Next Available</h6>
                    <p>Today 2:30 PM</p>
                    <small>Dr. Sarah Johnson</small>
                  </div>
                </div>
                <div class="floating-card rating-card">
                  <div class="card-content">
                    <div class="rating-stars">
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                    </div>
                    <h6>4.9/5</h6>
                    <small>1,234 Reviews</small>
                  </div>
                </div>
              </div>
              <div class="background-elements">
                <div class="element element-1"></div>
                <div class="element element-2"></div>
                <div class="element element-3"></div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Hero Section -->




  </main>

  <?php include 'includes/footer.php' ?>

  <!-- Scroll Top -->
  <a href="#!" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>