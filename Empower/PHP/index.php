<?php
require_once 'config.php';

$pageTitle = 'Home';

require_once 'header.php';
?>
<!-- required for boxes for text -->
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
    margin-bottom: 25px;
}

.hero-about h1 {
    font-size: 45px;
    margin-bottom: 5px;
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
    margin-bottom: 20px;
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



    <section class="carousel">
        <div class="featured-carousel">
            <div class="featured-advert">
                <img src="/Images/Banner_1.png" style="width:100%">
            </div>

            <div class="featured-advert">
                <img src="/Images/Banner_3.png" style="width:100%">
            </div>

            <div class="featured-advert">
                <img src="/Images/Banner_2.png" style="width:100%">
            </div>
        </div>
        <br>
        <div style="text-align:center">
            <span class="arrow" id="left-arrow" onclick="plusSlides(-1)">&lt;</span>
            <span class="dot" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
            <span class="dot" onclick="currentSlide(3)"></span>
            <span class="arrow" id="right-arrow" onclick="plusSlides(1)">&gt;</span>
        </div>



        <div class="about-container" style="margin-top:50px" ;>


            <div class="section-modern">
                <p>Welcome to Empower, your committed collaborator in changing accessibility. We are a movement based on
                    the idea that everyone should have access to ease and independence,
                    not simply a store. Making accessibility genuinely accessible is our straightforward but significant
                    goal. This entails removing the obstacles that all too frequently make
                    necessary equipment seem unattainable, including financial, logistical, and informational ones in
                    addition to physical ones.
                </p>
            </div>

            <div class="section-modern">
                <p>From computer aids to sensory-friendly products and state-of-the-art technology, Empower carefully
                    selects a range of cutting-edge solutions that are intended to empower
                    everyday living. However, our dedication extends beyond the catalogue. Our goal is to offer a
                    seamless, friendly experience where you can easily find the perfect solution,
                    receive compassionate counsel, and make confident decisions using items that are presented with
                    clear, useful information.necessary equipment seem unattainable, including
                    financial, logistical, and informational ones in addition to physical ones.
                </p>
            </div>

            <div class="section-modern">
                <p>We are aware that the concept of accessibility is not universally and differs for everyone. Our goal
                    is to create a community where empowerment is the norm because of this.
                    Our goal is to turn the process of finding the correct tools from a challenge into a chance for more
                    freedom by providing smart products, helpful information, and a dedication
                    to affordability and comprehension. Welcome to a place where your needs are acknowledged, your
                    independence is supported, and accessibility is at last easily accessible. Together,
                    let us all create a more welcoming world.
                </p>
            </div>


        </div>

        <script>
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
            if (n > slides.length) {
                slideIndex = 1
            }
            if (n < 1) {
                slideIndex = slides.length
            }
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            slides[slideIndex - 1].style.display = "block";
            dots[slideIndex - 1].className += " active";
        }
        </script>
    </section>

</main>

<?php require_once 'footer.php'; ?>