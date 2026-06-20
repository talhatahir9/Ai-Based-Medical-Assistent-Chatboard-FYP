# Medical.AI | AI Medical Chatboard

Medical.AI is an intelligent, compact medical assistant and symptom checker built with PHP. It leverages advanced Large Language Models (LLMs) via the Groq and Gemini APIs to provide users with AI-driven health diagnostics, chat capabilities, and secure profile management.

> **Disclaimer:** This application is for informational purposes only and is **NOT** a substitute for professional medical advice, diagnosis, or treatment. Always consult a healthcare professional.

## Features

- **Symptom AI Checker:** Analyze your symptoms using the Groq API (powered by Llama 3.3 70B Versatile) or Gemini Pro to get a detailed health report including possible conditions, home care recommendations, and emergency warning signs.
- **Smart Medical Chat:** Interactive, real-time chat interface for medical inquiries with chat history saved securely. Includes a responsive dark mode.
- **User Authentication:** Secure user registration, login, and profile management systems.
- **Admin Dashboard:** Administrative controls for managing user roles and deleting accounts.
- **Responsive & Modern UI:** Built with sleek, glassmorphism aesthetics, dynamic background orbs, and Plus Jakarta Sans typography.

## Tech Stack

- **Backend:** PHP
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, JavaScript (Vanilla)
- **AI Integration:** Groq API / Google Gemini API

## Prerequisites

- Local Server Environment (e.g., WAMP, XAMPP, MAMP)
- PHP 7.4 or higher
- MySQL Database

## Installation

1. **Clone the repository** (or place the project folder into your web server directory, e.g., `d:\wamp64\www\PHP\FYP`).
2. **Setup Database:**
   - Open your MySQL management tool (e.g., phpMyAdmin).
   - Create a new database named `ai_medical_ch`.
   - Import the provided `ai_medical_ch.sql` file into this database.
3. **Environment Configuration:**
   - Create a `.env` file in the root directory based on `.env.example` (if available), or create a new `.env` file with the following keys:
     ```env
     GEMINI_API_KEY=your_gemini_api_key_here
     GROQ_API_KEY=your_groq_api_key_here
     ```
   - Update `db.php` if your database credentials differ from the default (`root` with no password).
4. **Run the Application:**
   - Start your local server (WAMP/XAMPP).
   - Access the application in your browser via `http://localhost/PHP/FYP/` (or your corresponding path).

## Project Structure

- `index.php`: Landing page introducing the app features.
- `chat.php`: The main AI medical chat interface.
- `symptom_checker.php`: The dedicated symptom checking and analysis tool.
- `login.php` & `register.php`: User authentication.
- `admin_dashboard.php`: The dashboard for administrators to manage users.
- `config.php`: System configuration and environment variables loader.
- `db.php`: Database connection setup using PDO.

## License

This project is created as a Final Year Project (FYP). All rights reserved.
