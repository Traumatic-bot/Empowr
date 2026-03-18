<?php
require_once 'config.php';

$pageTitle = 'About Us';

require_once 'header.php';
?>
<!--this os needed for the text boxes-->
<style>
body {
    font-family: 'Segoe UI', Tahoma, sans-serif;
}

.hero-about {
    width: 100%;
    padding: 70px 0;
    background: linear-gradient(to right, #ffe100, #ffef7a);
    text-align: center;
    color: #000;
    font-weight: bold;
    margin-bottom: 50px;
}

.hero-about h1 {
    font-size: 45px;
    margin-bottom: 10px;
    letter-spacing: 2px;
}

.hero-about p {
    font-size: 18px;
    max-width: 700px;
    margin: 0 auto;
    opacity: 0.9;
}

.about-container {
    width: 85%;
    margin: 0 auto;
}

.section-modern {
    background: white;
    padding: 50px;
    border-radius: 20px;
    margin-bottom: 40px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.section-modern h2 {
    font-size: 30px;
    font-weight: bold;
    color: #1a1a1a;
    margin-bottom: 15px;
}

.section-modern p {
    font-size: 18px;
    line-height: 1.7;
    color: #333;
}

.values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.value-card {
    background: #f8f8f8;
    padding: 25px;
    border-radius: 18px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    transition: 0.2s ease;
}

.value-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.12);
}

.value-card h3 {
    font-size: 20px;
    font-weight: bold;
    color: #000;
    margin-bottom: 10px;
}

.value-card p {
    font-size: 16px;
    color: #444;
}
</style>

<main>
    <div class="hero-about">
        <h1>About Empowr</h1>
        <p>Innovating assistive technology to empower lives, enhance independence, and create equality through design.
        </p>
    </div>

    <div class="about-container">

        <div class="section-modern">
            <h2>Who We Are</h2>
            <p>
                Empowr is a technology-driven organisation dedicated to improving accessibility, independence, and
                quality of life for individuals with disabilities.
                We provide modern assistive products designed to support mobility, communication, comfort, and daily
                living.
                At Empowr, we believe that technology should empower — not exclude — and our mission is to ensure
                everyone has access to tools that help them thrive.
            </p>
        </div>

        <div class="section-modern">
            <h2>Our Mission</h2>
            <p>
                We specialise in offering a wide range of assistive devices and inclusive solutions that bridge the gap
                between people and the technology they need.
                Our platform brings together reliable, well-designed, and carefully tested products from trusted
                manufacturers, ensuring accessibility is available to everyone.
            </p>
        </div>

        <div class="section-modern">
            <h2>Our Values</h2>

            <div class="values-grid">

                <div class="value-card">
                    <h3>Inclusivity</h3>
                    <p>Empowr ensures our technology supports every ability, without limits.</p>
                </div>

                <div class="value-card">
                    <h3>Innovation</h3>
                    <p>We combine creativity with modern design to solve real accessibility challenges.</p>
                </div>

                <div class="value-card">
                    <h3>Reliability</h3>
                    <p>Each product is tested for durability, comfort, and safety.</p>
                </div>

                <div class="value-card">
                    <h3>Empowerment</h3>
                    <p>Our tools are built to promote independence and confidence.</p>
                </div>

            </div>

        </div>

        <div class="section-modern">
            <h2>Why Choose Empowr?</h2>
            <p>
                From powerful mobility solutions to communication technologies,
                we aim to redefine what accessible design means.
                Empowr stands for trust, quality, and a mission-driven approach that puts users first.
            </p>
        </div>

    </div>

</main>


<?php require_once 'footer.php'; ?>