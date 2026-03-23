<?php
require_once 'config.php';

$pageTitle = 'T&Cs';

require_once 'header.php';
?>

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
        <h1>Terms and Conditions</h1>
    </div>

    <div class="about-container">
        <div class="section-modern">
            <h2>Terms &amp; Conditions</h2>
            <p>
                These Terms &amp; Conditions apply to every order placed with Empowr Tech and the use of our website.
                We sell technology (including assistive and accessibility-focused products) and we want things to be clear and fair.
                Please read these terms before ordering.
            </p>

            <h2>Who we are</h2>
            <p>
                Empowr Tech ("we", "us", "our")<br>
                Address: 123 Aston Lane, City Centre, CV1 1JB<br>
                Email: support@empowrtech.com<br>
                Phone: 01234 567890
            </p>

            <h2>Our contract with you</h2>
            <p>
                When you place an order, you are offering to buy the product(s) from us.
                We will send an order acknowledgement, but your order is only accepted once your product(s) are ready to be dispatched.
            </p>
            <p>
                Until then, we may decline or cancel an order (we try not to!) for reasons like pricing errors, stock problems,
                or if we cannot get hold of the item you need. If we cancel your order, we will refund anything you paid for that item.
            </p>

            <h2>Availability</h2>
            <p>
                We show availability and estimated delivery dates on the website. Sometimes stock systems get it wrong.
                If that happens, we will tell you as soon as we can and work with you on the best option (replacement, alternative, or refund).
            </p>

            <h2>Website information</h2>
            <p>
                We do our best to keep product details accurate (including compatibility notes, dimensions, and photos),
                but some info may be approximate and manufacturers can change specifications without notice.
                Colours may look different depending on your screen.
            </p>

            <h2>Choosing the right product (important for assistive tech)</h2>
            <p>
                A lot of our products are designed to support accessibility and disability needs, but suitability can vary person to person.
                If you are unsure about compatibility or whether something will meet your needs, contact us before ordering and we will help.
            </p>
            <p>
                Any guidance we give is general product support and is not medical advice.
                If you need clinical advice or an assessment, please speak to a qualified professional.
            </p>

            <h2>Prices</h2>
            <p>
                Prices are shown in GBP (£). Prices include VAT where applicable (unless we say otherwise) and delivery charges are shown at checkout.
                Promotions or sale prices may apply to selected items and can be withdrawn at any time.
            </p>

            <h2>Payment</h2>
            <p>
                You can pay using the payment methods shown at checkout. Payment is taken before we dispatch your order.
                If there is a problem processing payment, we will let you know.
            </p>

            <h2>Delivery</h2>
            <p>
                We deliver to the UK (and any delivery limits will be shown at checkout if they apply).
                Delivery dates are estimates and can change due to things outside our control (for example courier delays or severe weather).
            </p>
            <p>
                Please make sure someone is available to receive the order. Once delivered, the product becomes your responsibility.
            </p>
            <p>
                If your order arrives damaged, missing items, or appears opened, tell us within 48 hours of delivery so we can sort it quickly.
            </p>

            <h2>Cancellations &amp; returns (change of mind)</h2>
            <p>
                If you are a consumer buying online, you normally have 14 days after delivery to cancel your order (cooling-off period).
                To cancel, email us at <strong>support@empowrtech.com</strong> with your order number.
            </p>
            <p>
                You will need to return the item unused, complete, and in the original packaging where possible.
                Unless the item is faulty, you are responsible for return postage costs.
                When we receive the return, we will refund your purchase within two business weeks.
            </p>

            <h2>Returns exceptions</h2>
            <p>
                The right to cancel may not apply to:
            </p>
            <ul style="font-size:18px; line-height:1.7; color:#333; margin-left:18px;">
                <li>Unsealed software, digital downloads once started, or licence keys that have been revealed/used</li>
                <li>Custom-made or personalised items</li>
                <li>Hygiene-sensitive items if seals are broken (where applicable)</li>
            </ul>

            <h2>If something is faulty</h2>
            <p>
                If your product is faulty or not as described, you have legal rights under the Consumer Rights Act 2015.
                Contact us and we will put it right (repair, replacement, or refund depending on the issue and timing).
            </p>
            <p>
                We may ask for photos/video or to test the item to confirm the fault.
            </p>

            <h2>Warranty</h2>
            <p>
                Some products come with a manufacturer warranty and/or our standard warranty (where stated on the product page).
                Warranties do not cover accidental damage, misuse, incorrect installation, or normal wear and tear.
            </p>

            <h2>Data and backups (for computers, tablets, and devices)</h2>
            <p>
                If a device needs repair or replacement, it may be reset and data could be lost.
                We recommend backing up your data regularly. We cannot accept responsibility for lost data.
            </p>

            <h2>Our liability (plain English)</h2>
            <p>
                We are responsible for foreseeable loss or damage caused by us.
                We are not responsible for losses that are not foreseeable, or for business losses if you are buying as a business.
                Nothing in these terms limits liability where the law says it cannot be limited (like death or personal injury caused by negligence, or fraud).
            </p>

            <h2>Things beyond our control</h2>
            <p>
                We are not liable for delays or failure to supply caused by events outside our reasonable control
                (for example courier disruption, supplier issues, or extreme weather). If this happens, we will keep you updated and offer options.
            </p>

            <h2>Privacy</h2>
            <p>
                We use your personal information to process your order and provide support. For more information, see our <a href="privacy_policy.php">Privacy Policy</a>
            </p>

            <h2>Governing law</h2>
            <p>
                These Terms &amp; Conditions are governed by the laws of England and Wales, and disputes will be handled by the courts of England and Wales.
            </p>

        </div>
    </div>
</main>


<?php require_once 'footer.php'; ?>
