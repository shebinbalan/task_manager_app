# task_manager_app

## Description

This is a simple Task Management System built with **Core PHP**, **MySQL**, **AJAX**, and **Bootstrap**. It includes user authentication, task CRUD operations, filtering options, and dynamic task updates using AJAX.

---

## Features

### 🔐 User Authentication
- Simple login/logout system
- Hardcoded user credentials stored in the database
- No user registration required

### ✅ Task Management (CRUD)
- Add a new task with title, description, deadline, and status
- Edit existing tasks
- Delete tasks
- List all tasks for the logged-in user

### 🔍 Task Filters
- Filter by **Status**: Pending, In Progress, Completed
- Filter by **Deadline**:
  - **Past** (Deadline has passed)
  - **Today**
  - **Upcoming** (Deadline in the future)

### ⚡ AJAX
- Mark tasks as "Completed" using a checkbox without reloading the page (AJAX)

### 🎨 Frontend
- Clean, responsive UI using **Bootstrap 5**
- Mobile-friendly layout

---

## Setup Instructions

### 1. Clone or Download the Project

Download the ZIP and extract it, or clone from GitHub (if applicable):

```bash
git clone https://github.com/your-username/task-manager-php.git
```

### 2. Set Up the Database

- Open `phpMyAdmin` or any MySQL client.
- Create a database (e.g., `task_manager`).
- Import the provided SQL file `task_manager.sql` into the database.

### 3. Configure the Database Connection

Edit the file at `config/database.php`:

```php
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'task_manager');
```

### 4. Start the Project

- Open a local server (XAMPP/WAMP/Laragon)
- Place the project folder inside the `htdocs` directory
- Navigate to `http://localhost/task_manager/` in your browser 

---

## File Structure

```
task_manager/
  ├── config/
  │   └── database.php
  ├── includes/
  │   ├── functions.php
  │   ├── header.php
  │   └── footer.php
  ├── assets/
  │   ├── css/
  │   │   └── style.css
  │   └── js/
  │       └── script.js
  ├── index.php
  ├── dashboard.php
  ├── add_task.php
  ├── edit_task.php
  ├── task_actions.php
  ├── logout.php
  ├── task_manager.sql
  └── README.md
```

---

## Default Login Credentials

> ⚠️ These are hardcoded in the database. No registration required.

- **Username**: `admin`
- **Password**: `12345678` (stored as plain text or hashed in DB depending on setup)

---

