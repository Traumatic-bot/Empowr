<?php
require_once 'config.php';

$pageTitle = 'Home';

require_once 'header.php';
?>

<style>
/* Global Styles */
:root {
    --primary-yellow: #ffe100;
    --primary-yellow-dark: #e0d129;
    --text-dark: #1a1a1a;
    --text-gray: #4a4a4a;
    --text-light: #666666;
    --bg-light: #ffffff;
    --bg-gray: #f8f8f8;
    --border-light: #e5e5e5;
    --success-green: #1cd456;
    --accent-blue: #0079bf;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: var(--bg-light);
    color: var(--text-dark);
}

.container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 24px;
}

/* Hero Section */
.hero {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
    padding: 60px 24px;
    background: linear-gradient(135deg, #fff9e6 0%, #ffffff 100%);
    border-radius: 0 0 40px 40px;
}

.hero-left {
    animation: fadeInUp 0.6s ease-out;
}

.breadcrumb {
    font-size: 14px;
    color: var(--text-gray);
    margin-bottom: 20px;
}

.breadcrumb a {
    color: var(--text-gray);
    text-decoration: none;
    transition: color 0.3s;
}

.breadcrumb a:hover {
    color: var(--primary-yellow-dark);
}

.breadcrumb span {
    color: var(--text-light);
}

.hero-left h1 {
    font-size: 48px;
    line-height: 1.2;
    margin-bottom: 20px;
    color: var(--text-dark);
}

.hero-left p {
    font-size: 18px;
    line-height: 1.6;
    color: var(--text-gray);
    margin-bottom: 30px;
}

.hero-cta {
    display: flex;
    gap: 20px;
    margin-bottom: 40px;
    flex-wrap: wrap;
}

.btn {
    display: inline-block;
    padding: 12px 32px;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn.primary {
    background-color: var(--primary-yellow);
    color: var(--text-dark);
    border: 2px solid var(--primary-yellow);
}

.btn.primary:hover {
    background-color: var(--primary-yellow-dark);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.btn.secondary {
    background-color: transparent;
    color: var(--text-dark);
    border: 2px solid var(--text-dark);
}

.btn.secondary:hover {
    background-color: var(--text-dark);
    color: white;
    transform: translateY(-2px);
}

.feature-list {
    list-style: none;
    padding: 0;
}

.feature-list li {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
    color: var(--text-gray);
    font-size: 14px;
}

.feature-list svg {
    stroke: var(--primary-yellow-dark);
    stroke-width: 2;
    width: 18px;
    height: 18px;
}

.hero-media {
    position: relative;
    animation: fadeInRight 0.6s ease-out;
}

.hero-image-container {
    background: linear-gradient(135deg, var(--primary-yellow), var(--primary-yellow-dark));
    border-radius: 30px;
    padding: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.hero-image-container img {
    width: 100%;
    height: auto;
    border-radius: 20px;
    display: block;
}

.hero-badge {
    position: absolute;
    bottom: -20px;
    right: -20px;
    background: var(--text-dark);
    color: white;
    padding: 12px 24px;
    border-radius: 50px;
    font-weight: bold;
    font-size: 14px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

/* Section Styles */
.section {
    padding: 80px 24px;
}

.section-header {
    text-align: center;
    margin-bottom: 60px;
}

.section-header h2 {
    font-size: 36px;
    margin-bottom: 15px;
    color: var(--text-dark);
}

.section-header p {
    font-size: 18px;
    color: var(--text-gray);
}

/* Features Grid */
.features {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 40px;
}

.feature-card {
    text-align: center;
    padding: 40px 30px;
    background: var(--bg-light);
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.feature-icon {
    margin-bottom: 25px;
}

.feature-card h3 {
    font-size: 22px;
    margin-bottom: 15px;
    color: var(--text-dark);
}

.feature-card p {
    font-size: 16px;
    line-height: 1.6;
    color: var(--text-gray);
}

/* Carousel Styles */
.carousel {
    margin: 40px auto;
    padding: 0 24px;
}

.featured-carousel {
    max-width: 1400px;
    margin: 0 auto;
}

.featured-advert {
    display: none;
}

.featured-advert:first-child {
    display: block;
}

.featured-advert img {
    border-radius: 30px;
    width: 100%;
    height: auto;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

/* About Container */
.about-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
}

.section-modern {
    background: var(--bg-light);
    padding: 50px;
    border-radius: 30px;
    margin-bottom: 30px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
}

.section-modern h2 {
    font-size: 32px;
    margin-bottom: 20px;
    color: var(--text-dark);
}

.section-modern p {
    font-size: 18px;
    line-height: 1.7;
    color: var(--text-gray);
}

/* Values Grid */
.values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-top: 30px;
}

.value-card {
    background: var(--bg-gray);
    padding: 30px;
    border-radius: 20px;
    text-align: center;
    transition: transform 0.3s ease;
}

.value-card:hover {
    transform: translateY(-5px);
}

.value-card h3 {
    font-size: 20px;
    margin-bottom: 15px;
    color: var(--text-dark);
}

.value-card p {
    font-size: 15px;
    line-height: 1.6;
    color: var(--text-gray);
}

/* Featured Categories */
.featured-categories {
    margin: 60px auto;
    padding: 0 24px;
}

.categories-title {
    text-align: center;
    font-size: 36px;
    margin-bottom: 50px;
    color: var(--text-dark);
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
}

.category-card {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    cursor: pointer;
    transition: transform 0.3s ease;
}

.category-card:hover {
    transform: translateY(-5px);
}

.category-card img {
    width: 100%;
    height: 300px;
    object-fit: cover;
    display: block;
}

.category-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
    padding: 30px;
    color: white;
}

.category-overlay h3 {
    font-size: 24px;
    margin-bottom: 8px;
}

.category-overlay p {
    font-size: 14px;
    opacity: 0.9;
}

/* Testimonials Section */
.testimonials {
    background: var(--bg-gray);
    padding: 80px 24px;
    margin: 60px auto;
}

.testimonials h2 {
    text-align: center;
    font-size: 36px;
    margin-bottom: 50px;
    color: var(--text-dark);
}

.testimonials-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 30px;
    max-width: 1200px;
    margin: 0 auto;
}

.testimonial-card {
    background: var(--bg-light);
    padding: 30px;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.testimonial-card img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 20px;
}

.testimonial-card p {
    font-size: 16px;
    line-height: 1.6;
    color: var(--text-gray);
    margin-bottom: 20px;
}

.testimonial-card h4 {
    font-size: 18px;
    margin-bottom: 8px;
    color: var(--text-dark);
}

.rating {
    color: #ffc107;
    font-size: 18px;
}

/* FAQ Accordion */
.accordion {
    max-width: 800px;
    margin: 0 auto;
}

.accordion-item {
    margin-bottom: 15px;
    border: 1px solid var(--border-light);
    border-radius: 12px;
    overflow: hidden;
    background: var(--bg-light);
}

.accordion-button {
    width: 100%;
    padding: 20px;
    background: var(--bg-light);
    border: none;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    font-size: 18px;
    font-weight: 600;
    color: var(--text-dark);
    transition: background 0.3s;
}

.accordion-button:hover {
    background: var(--bg-gray);
}

.accordion-button svg {
    transition: transform 0.3s;
}

.accordion-button[aria-expanded="true"] svg {
    transform: rotate(180deg);
}

.accordion-panel {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease-out;
    padding: 0 20px;
}

.accordion-panel p {
    padding-bottom: 20px;
    color: var(--text-gray);
    line-height: 1.6;
}

/* Newsletter Section */
.newsletter {
    background: linear-gradient(135deg, #f8f8f8 0%, #ffffff 100%);
    padding: 80px 24px;
    text-align: center;
    margin: 40px auto;
    border-radius: 40px;
    max-width: 1000px;
}

.newsletter h2 {
    font-size: 32px;
    margin-bottom: 15px;
    color: var(--text-dark);
}

.newsletter p {
    font-size: 18px;
    color: var(--text-gray);
    margin-bottom: 30px;
}

.newsletter-form {
    display: flex;
    gap: 15px;
    max-width: 500px;
    margin: 0 auto;
    flex-wrap: wrap;
    justify-content: center;
}

.newsletter-form input {
    flex: 1;
    padding: 14px 20px;
    border: 2px solid var(--border-light);
    border-radius: 50px;
    font-size: 16px;
    transition: border-color 0.3s;
}

.newsletter-form input:focus {
    outline: none;
    border-color: var(--primary-yellow);
}

.newsletter-form button {
    padding: 14px 30px;
    background: var(--text-dark);
    color: white;
    border: none;
    border-radius: 50px;
    cursor: pointer;
    font-weight: 600;
    transition: background 0.3s;
}

.newsletter-form button:hover {
    background: #333;
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Responsive Design */
@media (max-width: 1024px) {
    .hero {
        grid-template-columns: 1fr;
        gap: 40px;
        text-align: center;
    }
    
    .hero-left h1 {
        font-size: 36px;
    }
    
    .hero-cta {
        justify-content: center;
    }
    
    .feature-list {
        display: inline-block;
        text-align: left;
    }
}

@media (max-width: 768px) {
    .section {
        padding: 50px 20px;
    }
    
    .section-header h2 {
        font-size: 28px;
    }
    
    .section-modern {
        padding: 30px;
    }
    
    .section-modern h2 {
        font-size: 24px;
    }
    
    .section-modern p {
        font-size: 16px;
    }
    
    .categories-title {
        font-size: 28px;
    }
    
    .testimonials h2 {
        font-size: 28px;
    }
    
    .newsletter h2 {
        font-size: 24px;
    }
}

@media (max-width: 480px) {
    .hero-left h1 {
        font-size: 28px;
    }
    
    .hero-left p {
        font-size: 16px;
    }
    
    .btn {
        padding: 10px 24px;
    }
    
    .feature-card {
        padding: 30px 20px;
    }
    
    .newsletter-form {
        flex-direction: column;
    }
    
    .newsletter-form input,
    .newsletter-form button {
        width: 100%;
    }
}
</style>

<main id="main">
    <!-- Hero Section -->
    <section class="container hero" aria-labelledby="hero-heading">
        <div class="hero-left">
            <div class="breadcrumb">
                <a href="index.php"></a><span>Accessible Computer Peripherals</span>
            </div>
            <h1 id="hero-heading">Computer peripherals designed for accessibility</h1>
            <p>Empowr builds assistive computer peripherals — ergonomic keyboards, adaptive mice, voice-controlled devices and accessible displays — to help people with disabilities use computers more effectively.</p>
            <div class="hero-cta">
                <a class="btn primary" href="products.php">Shop devices</a>
                <a class="btn secondary" href="find_device.php">Find my device</a>
            </div>
            <ul class="feature-list">
                <li>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    High contrast UI
                </li>
                <li>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Keyboard & screen-reader friendly
                </li>
                <li>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Secure payments & encrypted accounts
                </li>
            </ul>
        </div>
        <div class="hero-media" aria-hidden="false" role="img" aria-label="Accessible computer peripherals showcase">
            <div class="hero-image-container">
                <img src="http://localhost/Empowr/Images/hero_product.png" alt="Empowr product showcase" loading="lazy">
            </div>
            <div class="hero-badge">
                <span>Accessibility First</span>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="container section" aria-labelledby="features-heading">
        <div class="section-header">
            <h2 id="features-heading">Why Empowr</h2>
            <p>Our commitment to accessibility and innovation</p>
        </div>
        <div class="features">
            <div class="feature-card" role="article">
                <div class="feature-icon">
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M24 4L29.5 9.5L24 15L18.5 9.5L24 4Z" fill="#ffe100"/>
                        <path d="M38 18L43.5 23.5L38 29L32.5 23.5L38 18Z" fill="#ffe100"/>
                        <path d="M10 18L15.5 23.5L10 29L4.5 23.5L10 18Z" fill="#ffe100"/>
                        <path d="M24 32L29.5 37.5L24 43L18.5 37.5L24 32Z" fill="#ffe100"/>
                        <path d="M38 32L43.5 37.5L38 43L32.5 37.5L38 32Z" fill="#ffe100"/>
                        <path d="M10 32L15.5 37.5L10 43L4.5 37.5L10 32Z" fill="#ffe100"/>
                    </svg>
                </div>
                <h3>Designed for accessibility</h3>
                <p>Interfaces that support screen readers, keyboard navigation and large readable text with WCAG 2.1 AA compliance.</p>
            </div>
            <div class="feature-card" role="article">
                <div class="feature-icon">
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="8" y="12" width="32" height="24" rx="2" stroke="#ffe100" stroke-width="2"/>
                        <path d="M16 8V12" stroke="#ffe100" stroke-width="2" stroke-linecap="round"/>
                        <path d="M32 8V12" stroke="#ffe100" stroke-width="2" stroke-linecap="round"/>
                        <circle cx="24" cy="24" r="6" stroke="#ffe100" stroke-width="2"/>
                        <path d="M24 18V24L27 27" stroke="#ffe100" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h3>Secure & private</h3>
                <p>User data is encrypted at rest and in transit. We follow best practices for authentication and payment processing with GDPR compliance.</p>
            </div>
            <div class="feature-card" role="article">
                <div class="feature-icon">
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M24 4V12" stroke="#ffe100" stroke-width="2" stroke-linecap="round"/>
                        <path d="M24 36V44" stroke="#ffe100" stroke-width="2" stroke-linecap="round"/>
                        <path d="M12 24H4" stroke="#ffe100" stroke-width="2" stroke-linecap="round"/>
                        <path d="M44 24H36" stroke="#ffe100" stroke-width="2" stroke-linecap="round"/>
                        <path d="M34.9282 13.0718L29.6569 18.3431" stroke="#ffe100" stroke-width="2" stroke-linecap="round"/>
                        <path d="M18.3431 29.6569L13.0718 34.9282" stroke="#ffe100" stroke-width="2" stroke-linecap="round"/>
                        <path d="M34.9282 34.9282L29.6569 29.6569" stroke="#ffe100" stroke-width="2" stroke-linecap="round"/>
                        <path d="M18.3431 18.3431L13.0718 13.0718" stroke="#ffe100" stroke-width="2" stroke-linecap="round"/>
                        <circle cx="24" cy="24" r="8" stroke="#ffe100" stroke-width="2"/>
                    </svg>
                </div>
                <h3>Scalable & reliable</h3>
                <p>Plug-and-play devices with reliable drivers and software updates. 99.9% compatibility guarantee with all major operating systems.</p>
            </div>
        </div>
    </section>

    <!-- Carousel Section -->
    <section class="carousel">
        <div class="featured-carousel">
            <div class="featured-advert">
                <a href="products.php">
                    <img src="http://localhost/Empowr/Images/Banner_1.png" alt="Empowr Products Banner">
                </a>
            </div>
            <div class="featured-advert">
                <a href="product_details.php?id=3">
                    <img src="http://localhost/Empowr/Images/Banner_3.png" alt="Smart Home Assistant Banner">
                </a>
            </div>
            <div class="featured-advert">
                <a href="products.php?category=voice_control">
                    <img src="http://localhost/Empowr/Images/Banner_2.png" alt="Voice Control Devices Banner">
                </a>
            </div>
            <div class="featured-advert">
                <a href="deals.php">
                    <img src="http://localhost/Empowr/Images/Banner_5.png" alt="Easter Sale Banner">
                </a>
            </div>
        </div>
        <br>
        <div style="text-align:center">
            <span class="arrow" onclick="plusSlides(-1)">&#10094;</span>
            <span class="dot" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
            <span class="dot" onclick="currentSlide(3)"></span>
            <span class="dot" onclick="currentSlide(4)"></span>
            <span class="dot" onclick="currentSlide(5)"></span>
            <span class="arrow" onclick="plusSlides(1)">&#10095;</span>
        </div>
    </section>

    <!-- About Section -->
    <div class="about-container">
        <div class="section-modern">
            <h2>Welcome to Empowr</h2>
            <p>Welcome to Empowr, your committed collaborator in changing accessibility. We are a movement based on the idea that everyone should have access to ease and independence, not simply a store. Making accessibility genuinely accessible is our straightforward but significant goal. This entails removing the obstacles that all too frequently make necessary equipment seem unattainable, including financial, logistical, and informational ones in addition to physical ones.</p>
        </div>

        <div class="section-modern">
            <h2>Our Commitment to Quality</h2>
            <p>From computer aids to sensory-friendly products and state-of-the-art technology, Empowr carefully selects a range of cutting-edge solutions that are intended to empower everyday living. However, our dedication extends beyond the catalogue. Our goal is to offer a seamless, friendly experience where you can easily find the perfect solution, receive compassionate counsel, and make confident decisions using items that are presented with clear, useful information.</p>
        </div>

        <div class="section-modern">
            <h2>Our Core Values</h2>
            <div class="values-grid">
                <div class="value-card">
                    <h3>Accessibility First</h3>
                    <p>Every product is carefully selected to ensure it meets the needs of individuals with diverse abilities.</p>
                </div>
                <div class="value-card">
                    <h3>Expert Guidance</h3>
                    <p>Our team provides personalized support to help you find the perfect solution for your unique needs.</p>
                </div>
                <div class="value-card">
                    <h3>Quality Assurance</h3>
                    <p>We only partner with trusted manufacturers who share our commitment to quality and reliability.</p>
                </div>
                <div class="value-card">
                    <h3>Community Focused</h3>
                    <p>We're dedicated to building an inclusive community where everyone can thrive independently.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Categories -->
    <div class="featured-categories">
        <h2 class="categories-title">Shop by Category</h2>
        <div class="categories-grid">
            <div class="category-card">
                <img src="http://localhost/Empowr/Images/category_voice.jpg" alt="Voice Control Devices">
                <div class="category-overlay">
                    <h3>Voice Control</h3>
                    <p>Hands-free smart home solutions</p>
                </div>
            </div>
            <div class="category-card">
                <img src="http://localhost/Empowr/Images/category_mobility.jpg" alt="Mobility Aids">
                <div class="category-overlay">
                    <h3>Mobility Aids</h3>
                    <p>Freedom to move independently</p>
                </div>
            </div>
            <div class="category-card">
                <img src="http://localhost/Empowr/Images/category_sensory.png" alt="Sensory Products">
                <div class="category-overlay">
                    <h3>Sensory Products</h3>
                    <p>Comfort and calm solutions</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials Section -->
    <div class="testimonials">
        <h2>What Our Customers Say</h2>
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <img src="http://localhost/Empowr/Images/testimonial_1.jpg" alt="Customer Sarah">
                <p>"Empowr has transformed my daily life. The voice-controlled devices allow me to manage my home independently. Highly recommended!"</p>
                <h4>Sarah Johnson</h4>
                <div class="rating">★★★★★</div>
            </div>
            <div class="testimonial-card">
                <img src="http://localhost/Empowr/Images/testimonial_2.jpg" alt="Customer Michael">
                <p>"The customer support team went above and beyond to help me find the right mobility solutions. Thank you Empowr!"</p>
                <h4>Michael Chen</h4>
                <div class="rating">★★★★★</div>
            </div>
            <div class="testimonial-card">
                <img src="http://localhost/Empowr/Images/testimonial_3.jpg" alt="Customer Emma">
                <p>"Finally, a company that truly understands accessibility. The products are high quality and the service is exceptional."</p>
                <h4>Emma Thompson</h4>
                <div class="rating">★★★★☆</div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <section class="container section" aria-labelledby="faq-heading">
        <div class="section-header">
            <h2 id="faq-heading">Frequently Asked Questions</h2>
            <p>Common questions about our devices, accessibility features and ordering.</p>
        </div>
        <div class="accordion" id="faqList">
            <div class="accordion-item">
                <button class="accordion-button" aria-expanded="false" aria-controls="panel-1" id="accordion-1">
                    <span>How long is the warranty?</span>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div id="panel-1" role="region" aria-labelledby="accordion-1" class="accordion-panel">
                    <p>All devices have a 2-year warranty covering manufacturing defects. We also offer extended plans at checkout for up to 5 years of coverage.</p>
                </div>
            </div>
            <div class="accordion-item">
                <button class="accordion-button" aria-expanded="false" aria-controls="panel-2" id="accordion-2">
                    <span>Are these compatible with my operating system?</span>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div id="panel-2" role="region" aria-labelledby="accordion-2" class="accordion-panel">
                    <p>Most devices work with Windows, macOS, and Linux. Compatibility is listed on each product page. We also provide specialized drivers for enhanced accessibility features.</p>
                </div>
            </div>
            <div class="accordion-item">
                <button class="accordion-button" aria-expanded="false" aria-controls="panel-3" id="accordion-3">
                    <span>Can I return an item?</span>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div id="panel-3" role="region" aria-labelledby="accordion-3" class="accordion-panel">
                    <p>Yes — returns accepted within 30 days in original packaging. Some accessibility-customised items may be final sale. We offer free return shipping for all US customers.</p>
                </div>
            </div>
            <div class="accordion-item">
                <button class="accordion-button" aria-expanded="false" aria-controls="panel-4" id="accordion-4">
                    <span>Do you offer setup assistance?</span>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div id="panel-4" role="region" aria-labelledby="accordion-4" class="accordion-panel">
                    <p>Yes, we offer remote setup assistance for all our products. For complex installations, we partner with certified accessibility specialists across the US and Canada.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <div class="newsletter">
        <h2>Stay Updated</h2>
        <p>Subscribe to our newsletter for exclusive offers, new product launches, and accessibility tips</p>
        <form class="newsletter-form" method="post" action="subscribe.php">
            <input type="email" name="email" placeholder="Enter your email address" required>
            <button type="submit">Subscribe</button>
        </form>
    </div>

    <script>
    // Carousel functionality
    let slideIndex = 1;
    showSlides(slideIndex);

    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    function currentSlide(n) {
        showSlides(slideIndex = n);
    }

    function showSlides(n) {
        let i;
        let slides = document.getElementsByClassName("featured-advert");
        let dots = document.getElementsByClassName("dot");
        if (n > slides.length) { slideIndex = 1 }
        if (n < 1) { slideIndex = slides.length }
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" active", "");
        }
        slides[slideIndex - 1].style.display = "block";
        dots[slideIndex - 1].className += " active";
    }

    // Auto-advance slides
    let slideInterval = setInterval(function() {
        plusSlides(1);
    }, 5000);

    // Pause on hover
    const carousel = document.querySelector('.featured-carousel');
    if (carousel) {
        carousel.addEventListener('mouseenter', function() {
            clearInterval(slideInterval);
        });
        carousel.addEventListener('mouseleave', function() {
            slideInterval = setInterval(function() {
                plusSlides(1);
            }, 5000);
        });
    }

    // Accordion functionality
    document.querySelectorAll('.accordion-button').forEach(button => {
        button.addEventListener('click', () => {
            const expanded = button.getAttribute('aria-expanded') === 'true';
            button.setAttribute('aria-expanded', !expanded);
            const panel = button.nextElementSibling;
            if (!expanded) {
                panel.style.maxHeight = panel.scrollHeight + 'px';
            } else {
                panel.style.maxHeight = null;
            }
        });
    });
    </script>
</main>

<?php require_once 'footer.php'; ?>