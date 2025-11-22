<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Boncan Dental Clinic</title>
  <link rel="stylesheet" href="assets/css/index.css">
  <script src="https://kit.fontawesome.com/a2d9d5a4b9.js" crossorigin="anonymous"></script>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<style>
  .services-section {
  padding: 80px 0;
  text-align: center;
  background-color: #f8f8f8;
  font-family: 'Poppins', sans-serif;
}

.services-title {
  font-size: 32px;
  color: #015b57;
  margin-bottom: 40px;
  font-weight: 600;
}

.services-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
  gap: 25px;
  max-width: 1100px;
  margin: 0 auto;
  padding: 0 20px;
}

.service-card {
  background: #ffffff;
  border-radius: 15px;
  padding: 25px 20px;
  box-shadow: 0 8px 15px rgba(0, 0, 0, 0.08);
  transition: 0.3s ease;
  border-top: 7px solid #00e0b0;
}

.service-card h3 {
  font-size: 20px;
  color: #015b57;
  margin-bottom: 8px;
  font-weight: 600;
}

.service-card p {
  font-size: 14px;
  color: #555;
}

.service-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 18px rgba(0, 0, 0, 0.12);
}
.footer {
  background: #015b57;
  color: white;
  text-align: center;
  padding: 20px 10px;
  margin-top: 60px;
  font-size: 14px;
}

.footer-content {
  max-width: 1100px;
  margin: 0 auto;
}

.footer-socials {
  margin-top: 8px;
}

.footer-socials a {
  color: white;
  margin: 0 8px;
  font-size: 18px;
  text-decoration: none;
  transition: 0.2s ease;
}

.footer-socials a:hover {
  opacity: 0.7;
}



  </style>
<body>
  <!-- Navigation -->
  <nav>
    <div class="nav-container">
      <a href="#" class="logo">
        <div class="logo-icon">
          <img src="assets/img/logo.png" alt="Dental Logo">
          
        </div>
        <div class="logo-text">
          <div class="logo-title">Ricardo B. Boncan</div>
          <div class="logo-subtitle">Dental Clinic</div>
        </div>
      </a>


      <ul class="nav-links">
        <li><a href="#home">Home</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="#services">Services</a></li>
        <li><a href="#contact">Contact</a></li>
        
      </ul>


      <div class="nav-buttons">
        <a href="register.html" class="btn btn-ghost">Register</a>
        <a href="login.html" class="btn btn-primary">Login</a>
      </div>
    </div>
  </nav>

   <!-- Hero Section -->
  <section class="hero" id="home">
    <div class="hero-bg">
      <div class="hero-overlay"></div>
    </div>
    <div class="hero-content">
      <h1 class="font-serif">
        Precision,<br>
        <span class="italic">Care,</span><br>
        <span class="large">CONFIDENCE</span>
      </h1>
      <p>
        With years of trusted experience, Dr. Ricarda Boncan and our team are committed to delivering healthy, confident smiles through professional and compassionate care
      </p>
      <div class="hero-buttons">
        <a href="bookappointment.html" class="btn btn-primary btn-lg">Book an Appointment</a>
        <a href="#faq" class="btn btn-outline btn-lg">FAQS</a>
      </div>
    </div>
  </section>


  <!-- About Us Section -->
 <section class="about-us-section">
    <div class="container">
        
        <div class="about-us-content">
            <div class="graphic-elements">
                <div class="circle circle-dark"></div>
                <div class="circle circle-light"></div>
            </div>

            <h2>About Us</h2>
            
            <p>
                At Boncan Dental Clinic, we are dedicated to providing high-quality dental care in a comfortable and friendly environment. 
          Our goal is to help patients achieve healthy, beautiful smiles through advanced techniques and personalized care.
            </p>
        </div>
        
         <div class="about-right">
        <div class="image-frame">
          <img src="assets/img/doctor.jpg" alt="Dentist">
        </div>
      </div>

    </div>
</section>





  <!-- Our Services Section -->
 <section class="services-section" id="services">
  <h2 class="services-title">Our Services</h2>

  <div class="services-container">

    <div class="service-card">
      <h3>TMD Consult</h3>
      <p>Jaw (Panga)</p>
    </div>

    <div class="service-card">
      <h3>Cleaning</h3>
      <p>Oral Prophylaxis</p>
    </div>

    <div class="service-card">
      <h3>Filling</h3>
      <p>(Pasta)</p>
    </div>

    <div class="service-card">
      <h3>Extraction</h3>
      <p>(Bunot)</p>
    </div>

    <div class="service-card">
      <h3>Denture</h3>
      <p>(Pustiso)</p>
    </div>

    <div class="service-card">
      <h3>Regular Consultation</h3>
    </div>

  </div>
</section>



  <!-- CONTACT US SECTION -->
<!-- CONTACT US SECTION -->
  <section class="contact-section" id="contact">
    <div class="contact-header">
      <div class="circles">
        <div class="circle big"></div>
        <div class="circle small"></div>
        <div class="circle mid"></div>
      </div>
      <h2>CONTACT US</h2>
    </div>

    <div class="contact-container">
      <!-- LEFT SIDE INFO -->
      <div class="contact-info">
        <p><i class="fa-solid fa-location-dot"></i> St. Luke’s Medical Center, Quezon City</p>
        <p><i class="fa-solid fa-envelope"></i> boncandentalclinic@gmail.com</p>
        <p><i class="fa-solid fa-phone"></i>098571947683</p>

        <div class="clinic-hours">
          <h3><i class="fa-solid fa-clock"></i> CLINIC HOURS</h3>
          <p>Monday - Friday: 8:00 AM - 5:00 PM</p>
        </div>

        <div class="social-links">
          <h3><i class="fa-solid fa-share-nodes"></i> FOLLOW US</h3>
          <p><i class="fa-brands fa-facebook"></i> RicardoBoncanMedicalClinic</p>
          <p><i class="fa-brands fa-instagram"></i> @RicardoBoncan_MedicalClinic</p>
        </div>
      </div>

      <!-- RIGHT SIDE MAP -->
      <div class="contact-map">
        <iframe 
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3859.870128847365!2d121.04636847582415!3d14.631232177662939!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b7b7d6f55805%3A0xb8ed94cc00c796b4!2sSt.%20Luke&#39;s%20Medical%20Center%20Quezon%20City!5e0!3m2!1sen!2sph!4v1707648732473!5m2!1sen!2sph" 
          width="100%" 
          height="350" 
          style="border:0;" 
          allowfullscreen="" 
          loading="lazy">
        </iframe>
      </div>
    </div>
  </section>


<!-- FAQ SECTION -->
<section class="faq" id="faq">
  <div class="faq-container">
    <h2 class="faq-title">Frequently Asked Questions</h2>

    <div class="faq-item">
      <button class="faq-question">How do I book an appointment?</button>
      <div class="faq-answer">
        <p>You can book an appointment online by clicking the “Book Appointment” button on the homepage or by calling our clinic directly.</p>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question">What services do you offer?</button>
      <div class="faq-answer">
        <p>We offer preventive, restorative, cosmetic, and pediatric dental care — ensuring every smile is cared for.</p>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question">Do you accept walk-in patients?</button>
      <div class="faq-answer">
        <p>Yes, but we recommend scheduling an appointment to guarantee your preferred time slot.</p>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question">What should I bring to my first appointment?</button>
      <div class="faq-answer">
        <p>Please bring a valid ID, your dental insurance card (if applicable), and any recent dental X-rays or medical records.</p>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question">Do you accept dental insurance?</button>
      <div class="faq-answer">
        <p>Yes, we accept most major dental insurance plans. Please contact us to verify your coverage before your visit.</p>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question">How often should I visit the dentist?</button>
      <div class="faq-answer">
        <p>We recommend visiting every six months for a regular checkup and cleaning to maintain good oral health.</p>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question">What payment methods do you accept?</button>
      <div class="faq-answer">
        <p>We accept cash, major credit cards, and online payments. Flexible payment options are also available for certain treatments.</p>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question">What if I need to cancel or reschedule my appointment?</button>
      <div class="faq-answer">
        <p>Please call our clinic at least 24 hours in advance so we can accommodate other patients.</p>
      </div>
    </div>
  </div>
</section>


<footer class="footer">
  <div class="footer-content">
    <p>&copy; 2025 Ricardo B. Boncan Dental Clinic. All Rights Reserved.</p>

    <div class="footer-socials">
      <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
      <a href="#"><i class="fa-brands fa-instagram"></i></a>
      <a href="mailto:boncandentalclinic@gmail.com"><i class="fa-solid fa-envelope"></i></a>
    </div>
  </div>
</footer>

<script src="assets/js/faq.js" defer></script>




 
</body>
</html>