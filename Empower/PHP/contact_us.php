<?php
require_once 'config.php';

$pageTitle = 'about_us';

require_once 'header.php';
?>

<style>
    /* --- Contact Page Specific Styles --- */

.contact-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px; 
    align-items: start;
    margin-top: 10px;
}

.contact-image {
    margin-top: 0;
}

.contact-image img {
    width: 60%;
    height: auto;
    border-radius: 2px;
}

/* Responsive adjustments */
@media (max-width: 992px) {
    .contact-info-grid {
        grid-template-columns: 1fr;
        gap: 10px; /* Also reduce gap for mobile */
    }
}


/* Styling for the Form container and inputs */
.contact-form .form-group {
    margin-bottom: 20px;
}

.contact-form label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: var(--dark-color);
    font-size: 0.95em;
}

.contact-form .required {
    color: #dc3545; /* Red asterisk */
    font-weight: bold;
    margin-left: 2px;
}

.contact-form input[type="text"],
.contact-form input[type="email"],
.contact-form textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 1em;
    font-family: inherit;
    transition: border-color 0.3s ease;
}

.contact-form input:focus,
.contact-form textarea:focus {
    border-color: var(--primary-color);
    outline: none;
    box-shadow: 0 0 5px rgba(0, 86, 179, 0.3);
}

.contact-form textarea {
    resize: none;
    min-height: 150px;
}

.contact-form button[type="submit"] {
    width: 100%;
    padding: 12px 20px;
    font-size: 1.1em;
    margin-top: 10px;
}

.contact-form .form-note {
    font-size: 0.85em;
    color: var(--secondary-color);
    margin-top: 15px;
}

/* Responsive adjustments for contact page layout */
@media (max-width: 992px) {
    .contact-content-wrapper {
        grid-template-columns: 1fr; /* Stack columns */
        gap: 40px;
    }
}

</style>

<main class="main"> 

    <section class="page-title-section section" style="background-color: var(--light-color); padding-top: 40px; padding-bottom: 40px;"> 

          <div class="container"> 
                <h1 style="text-align: center;">Reach Out To Us</h1> 
                    <p style="text-align: center; max-width: 700px; margin: 0 auto;">We are happy to assist you! Use the form below to send a message to our admin team, or view our contact information.</p> 

            </div> 

        </section> 

 

        <section class="contact-section section"> 

            

 			<div class="container contact-content-wrapper"> 

                 <div class="contact-info-container"> 
    				<h2>Contact Information</h2> 
    
    				<div class="contact-info-content">
						<div class="contact-image">
           					<a href="https://maps.app.goo.gl/TNNfdAcCTrwwNSJw8" target="_blank" rel="noopener noreferrer">
                				<img src="Images/aston_map.png" alt="Click to view map on Google Maps">
            				</a>
       					</div>
        				<ul class="contact-info-container"> 
            				<li><i class="fas fa-map-marker-alt"></i> 123 Aston Lane, City Centre, CV1 1JB</li> 
            				<li><i class="fas fa-phone-alt"></i> 01234 567890</li> 
            				<li><i class="fas fa-envelope"></i> <a href="mailto:empowr@gmail.com. MOCK">empowr@gmail.com. </a></li> 
            				<li><i class="fas fa-clock"></i> Mon - Fri: 9:00 AM - 6:00 PM</li> 
           					<li><i class="fas fa-clock"></i> Saturday: By Appointment</li> 
            				<li><i class="fas fa-clock"></i> Sunday: Closed</li> 
        				</ul> 
    				</div>
				</div>

 

                <div class="contact-form-container"> 

                    <h2>Send Us a Message</h2> 

                    <form action="submit-message.php" method="POST" class="contact-form">

                         <div class="form-group"> 

                            <label for="contact-name">Full Name <span class="required">*</span></label> 

                            <input type="text" id="contact-name" name="contact-name" required placeholder="Enter your full name"> 

                        </div> 

                        <div class="form-group"> 

                            <label for="contact-email">Email Address <span class="required">*</span></label> 

                            <input type="email" id="contact-email" name="contact-email" required placeholder="Enter your email"> 

                        </div> 

                         <div class="form-group"> 

                            <label for="contact-subject">Subject <span class="required">*</span></label> 

                            <input type="text" id="contact-subject" name="contact-subject" required placeholder="Enter the message subject"> 

                        </div> 

                        <div class="form-group"> 

                            <label for="contact-message">Your Message <span class="required">*</span></label> 

                            <textarea id="contact-message" name="contact-message" rows="7" required placeholder="Type your message here..."></textarea> 

                        </div> 

                        <div class="form-group"> 

                            <button type="submit" class="button button--primary">Send Message</button> 

                        </div> 

                         <p class="form-note"><span class="required">*</span> Required fields.</p> 

                    </form> 

                </div> 

 

            </div></section> 

 

    </main> 

<?php require_once 'footer.php'; ?>
