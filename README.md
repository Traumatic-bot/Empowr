![Empowr logo 3 colour invert](https://github.com/user-attachments/assets/2a460387-0d53-4cad-86ac-d9f77c882579)

# Empowr

Empowr is a full-stack e-commerce web application for tech accessories. It allows users to browse products, apply filters, manage a shopping cart, place orders, and track order progress.

---

## Features

* User authentication (login / register)
* Product browsing with category and brand filters
* Search and sorting functionality
* Shopping cart with live basket preview
* Order placement and tracking system
* Order history dashboard
* Dynamic order status progression
* Dark mode toggle

---

## Tech Stack

* **Frontend:** HTML, CSS, JavaScript
* **Backend:** PHP
* **Database:** MySQL
* **Environment:** XAMPP / Localhost

---

## Setup Instructions

1. Clone the repository:

   ```bash
   git clone https://github.com/Traumatic-bot/Empowr.git
   ```

2. Move the project into your XAMPP `htdocs` folder.

3. Start **Apache** and **MySQL** in XAMPP.

4. Create a new database in phpMyAdmin (e.g. `cs2team6_db`).

5. Import the SQL file (or use the provided schema).

6. Update `config.php` with your database credentials:

   ```php
   $host = 'localhost';
   $username = 'root';
   $password = '';
   $database = 'cs2team6_db';
   ```

7. Open in browser:

   ```
   http://localhost/Empowr
   ```

---

## Database Structure

Main tables used in the project:

* `users`
* `products`
* `cart`
* `orders`
* `order_items`
* `user_addresses`

---

## Usage

* Create an account or log in
* Browse products and apply filters
* Add items to your basket
* Proceed to checkout
* View and track your orders
* Access order history from your dashboard

---

## Project Structure

```
/CSS        → Stylesheets  
/Images     → Icons and assets  
/PHP        → Backend logic  
config.php  → Database connection  
header.php  → Shared header + basket logic  
```

---

## Known Issues

* Order status is currently auto-generated based on time (temporary solution)
* No admin panel for manual order updates yet
* Passwords are stored without hashing (for development purposes)

---

## Future Improvements

* Admin dashboard for order management
* Secure password hashing
* Payment gateway integration
* Email notifications for orders
* Improved filtering (multi-select UX)

---

## Authors

* Traumatic-bot
* Joseph-OD
* Kevin-Kler
* ctahmid
* faisalAlotaibi5
* FaeezSafder
* Shadrach-240109985
* Yusuf-240364827

---

## License

This project is for educational purposes.
