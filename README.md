# Workout Blog Application

This is a Workout Blog Application built with pure PHP, JavaScript and CSS. The application combines a robust PHP backend with a dynamic JavaScript with CSS frontend to provide a seamless user experience for fitness enthusiasts.

## Key Features
 - User authentication.
 - Create and share custom workout plans.
 - Dynamic filtering, sorting, and pagination through workouts.
 - A like feature to appreciate other users' contributions.
 - Database seeding endpoint (`/api/seed-db`) for quick setup and testing.

## Technical Overview
  - **Backend**
    - REST API built with PHP.
    - Single entry point routing system.
    - Server-side view rendering.
  - **Frontend**
    - Supports asynchronous API calls for real-time content updates.
    - Fully responsive design optimized for all device sizes, from desktops to small mobile screens.

## Installation Using XAMPP (Recommended)
### Pre-requisites
Ensure XAMPP is installed and properly configured.

### Steps
1. Enable `mod_rewrite` in Apache:
   - Open `xampp/apache/conf/httpd.conf`.
   - Find and uncomment the line: `LoadModule rewrite_module modules/mod_rewrite.so`.
   - Find the `<Directory>` section and ensure it has:
     ```
     <Directory "C:/xampp/htdocs">
       AllowOverride All
       Require all granted
     </Directory>
     ```
   - Restart the Apache server.
2. Clone the repository to XAMPP's `htdocs` directory:
3. Start XAMPP's Apache and MySQL services.
4. Visit `http://localhost/workout-blog` in your browser.
5. Database will be created automatically when you acess the application for the first time.
