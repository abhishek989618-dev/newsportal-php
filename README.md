# 📰 NewsPortal – Organization Website & API Management System

A professional PHP-based web application that allows organizations to manage their **news, sponsors, website data, and API keys** — all from a single dashboard.  
Built using **Core PHP**, **MySQL**, and **Bootstrap**, this project is lightweight yet powerful for real-world use.

---

## 🚀 Features

✅ Manage organization websites from one dashboard  
✅ Add and edit **news, sponsors, and logos**  
✅ Auto-generate **API keys** for connected websites  
✅ Secure admin login with authentication  
✅ Send email notifications using **SMTP** (with `.env` support)  
✅ Razorpay payment gateway integration (for donations or subscriptions)  
✅ Responsive design using **Bootstrap**  
✅ Simple and clean UI built for speed and clarity

---

## 🧩 Tech Stack

| Component | Technology |
|------------|-------------|
| Language | PHP 8+ |
| Frontend | HTML5, CSS3, JavaScript, Bootstrap |
| Backend | PHP (Core) |
| Database | MySQL |
| Server | XAMPP / Apache |
| Libraries | PHPMailer, Razorpay PHP SDK, Dotenv |

---

## ⚙️ Installation Guide

### 1️⃣ Clone the repository
```bash
git clone https://github.com/<your-username>/newsportal.git
cd newsportal

2️⃣ Install dependencies

Make sure you have Composer installed.
Then run:
composer install
3️⃣ Create a .env file

In the project root, create a file named .env and add the following:

# App Settings
APP_NAME="NewsPortal"
APP_URL="http://localhost/newsportal"

# Database
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=newsportal_db
DB_USERNAME=root
DB_PASSWORD=

# SMTP Configuration
MAIL_HOST=smtp-relay.sendinblue.com
MAIL_USERNAME=your_email@smtp-brevo.com
MAIL_PASSWORD=your_smtp_password
MAIL_PORT=587
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="News Portal Admin"

# Razorpay API Keys
RAZORPAY_KEY=rzp_test_W9oVyC9ofMxh0L
RAZORPAY_SECRET=6URB0nJL4MAGwtZZcRvs0jbJ

4️⃣ Import the database

Import the included SQL file (database/newsportal.sql) into your MySQL using phpMyAdmin.

5️⃣ Run the project

Start your XAMPP server and open in your browser:

http://localhost/newsportal/

📁 Folder Structure
newsportal/
│
├── admin/               # Admin dashboard files
├── api/                 # API endpoints
├── assets/              # Images, logos, CSS, JS
├── includes/            # Database connection & helper files
├── vendor/              # Composer dependencies
├── .env.example         # Example environment configuration
├── index.php            # Homepage
├── composer.json        # PHP dependencies
└── README.md            # Project documentation

🧠 Developer Notes

You can manage multiple websites and assign API keys for secure communication.

The admin panel allows you to manage logos, sponsors, and articles.

Email services are handled by PHPMailer.

Payment processing is powered by Razorpay.

Use .env variables for all sensitive credentials.
💻 Demo (Optional)

🔗 Coming soon — host it on GitHub Pages or your own domain.

🧑‍💻 Author

Abhishek Kumar
📧 abhishek.wsckkr@gmail.com

🔗 LinkedIn

🐙 GitHub

🪪 License

This project is open-source and available under the MIT License.

⭐ If you like this project, give it a star on GitHub to support future updates!

---

Would you like me to generate the matching **`.env.example`** file automatically so you can upload it along with the README? (It will not include secrets — just placeholders.)

