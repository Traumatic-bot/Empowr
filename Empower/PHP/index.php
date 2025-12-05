<?php
require_once 'config.php';

$pageTitle = 'Home';

require_once 'header.php';
?>

<main>
    
<!-- replace links with images stored in /Images in the format Images/example.img -->
    <section class="carousel">
        <div class="featured-carousel">
            <div class="featured-advert">
                <img src="Images/Mechanical_Keyboard.png" style="width:100%">
            </div>

            <div class="featured-advert">
                <img src="Images/Premium_Keyboard.jpg" style="width:100%">
            </div>

            <div class="featured-advert">
                <img src="Images/Basic_Gaming_Mouse.jpg" style="width:100%">
            </div>

             <div class="featured-advert">
                <img src="Images/Pro_Gaming_Mouse.jpg" style="width:100%">
            </div>

             <div class="featured-advert">
                <img src="Images/Pro_Wireless_Gaming_Mouse.png" style="width:100%">
            </div>

             <div class="featured-advert">
                <img src="Images/Curved_Gaming_Monitor.jpg" style="width:100%">
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

       <!-- add text into here -->
        <div style="text-align:center; padding-top:50px;">
        <p>
            Welocome to Empower!
        </p>
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
        </script>
    </section>

</main>

<?php require_once 'footer.php'; ?>
