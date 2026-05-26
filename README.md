# Bookly — Educational Resource Quality Assessment System

[![Laravel Version](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![TailwindCSS Version](https://img.shields.io/badge/TailwindCSS-v4.0-38BDF8?style=for-the-badge&logo=tailwind-css)](https://tailwindcss.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge)](LICENSE)

**Bookly** (also referred to as EduQual) is a premium, full-stack educational audit and resource quality management platform designed for modern academic institutions. It provides a secure, role-based, multi-stage workflow to evaluate, rate, audit, and shelf textbooks, reference materials, and e-learning resources.

---

## 🌟 Key Features

Bookly provides a comprehensive suite of features tailored for academic resource evaluation:

### 👤 Role-Based Access Control (RBAC)
- **Super Admin**: Complete administrative oversight. Can manage system settings, add/update users, toggle user accounts, unlock finalized assessments, and update assessment criteria weights.
- **Reviewer**: Submit detailed, criterion-based quality assessments, flag outdated or inaccurate content, and manage personal resource shelves.
- **Viewer**: Read-only access to published assessments, detailed analytics dashboards, and custom report exports.

### 📚 Resource Catalogue & Curation
- **Comprehensive Management**: Organize, search, and filter educational resources by Subject, Type, and Grade Level.
- **Shelves Curation [NEW]**: Users can curate custom public or private "Shelves" (`public/private`) to categorize resources, track curriculum reads, and bookmark books for reviews.
- **Smart Covers**: Automatic extraction of book cover images with fallback support for external Google Books covers.

### 📊 Advanced Quality Assessment Engine
- **5-Criterion Evaluation**: Evaluates resources across 5 key dimensions:
  1. **Accuracy**: Factuality and up-to-date information.
  2. **Relevance**: Alignment with modern curricula.
  3. **Readability**: Language appropriateness and clarity.
  4. **Engagement**: Student interest and interactive elements.
  5. **Pedagogical Value**: Exercises, learning outcomes, and teacher guides.
- **Weighted Analytics & Radar Charts**: Visualizes quality scores through dynamic Chart.js radar charts and weighted score calculations configurable by Admins.

### 🚩 Content Auditing & Flagging
- **Discrepancy Tracking**: Reviewers can flag specific parts of resources for factual errors, bias, or outdated information.
- **Resolution Workflow**: Collaborative comment system for flag discussions, with Super Admin resolution toggles.

### 📈 Reports & Data Exports
- **Interactive Dashboards**: High-fidelity dashboards reporting scores by subject, score distribution, and reviewer activity.
- **Multiple Formats**: Export clean, structured data in PDF or CSV formats using server-side document generation.

---

## 🛠️ Technology Stack

- **Core Framework**: Laravel 11.x (PHP 8.2+)
- **Database**: SQLite (Highly portable, transactional database engine)
- **User Interface**: Tailwind CSS v4.0 (for sleek modern utility styling) & Alpine.js (for reactive, lightweight interactions)
- **Data Visualisation**: Chart.js (Interactive radar, bar, and line graphs)
- **Document Generation**: `barryvdh/laravel-dompdf` for server-side PDF exports
- **Build Tool**: Vite.js

---

## 🚀 Getting Started & Local Installation

Follow these steps to set up and run Bookly locally:

### 1. Prerequisites
Ensure you have the following installed on your machine:
- PHP >= 8.2
- Composer
- Node.js & NPM
- SQLite3

### 2. Clone and Install Dependencies
Navigate to your project directory and run:
```bash
# Install PHP dependencies
composer install

# Install Javascript dependencies
npm install
```

### 3. Setup Environment Configuration
Copy the default environment configuration:
```bash
cp .env.example .env
```
Generate the application encryption key:
```bash
php artisan key:generate
```

### 4. Database Setup & Seeding
Initialize the SQLite database file:
```bash
# On Mac/Linux:
touch database/database.sqlite
```
Run migrations and populate the database with default roles, subjects, and sample resource/assessment data:
```bash
php artisan migrate:fresh --seed
```

### 5. Build Assets & Start Dev Server
Compile CSS and Javascript assets:
```bash
npm run dev
```
In a new terminal window, start the Laravel local development server:
```bash
php artisan serve
```
Visit the application in your browser at **[http://localhost:8000](http://localhost:8000)**.

---

## 🔐 Seeding Credentials (Password: `password`)

Use the following seeded accounts to log in and explore different user roles:

| Role | Email Address | Password |
| :--- | :--- | :--- |
| **Super Admin** | `admin@bookly.com` | `password` |
| **Reviewer** | `sarah@bookly.com` | `password` |
| **Reviewer** | `james@bookly.com` | `password` |
| **Reviewer** | `priya@bookly.com` | `password` |
| **Viewer** | `alex@bookly.com` | `password` |
| **Viewer** | `maria@bookly.com` | `password` |

---

## 🧑‍💻 Developer Contribution Guide: How to Push Code

Since you are a contributor to the **Bookly** (EduQual) repository, follow this step-by-step workflow to push your local code changes back to GitHub securely.

### 🔹 Option A: If you have direct Push access to the Main branch
If you are listed as a direct collaborator on the `amalkrishnanka/EduQual` repository and are allowed to push to the main branch directly:

1. **Check your status**:
   Ensure you are on the `main` branch:
   ```bash
   git branch
   ```
2. **Stage your changes**:
   Add all modified and new files (like the new Shelves tables and migrations):
   ```bash
   git add .
   ```
3. **Commit your changes**:
   Write a clear, descriptive commit message:
   ```bash
   git commit -m "feat: implement shelves curation, user profiles, and update README"
   ```
4. **Pull latest changes**:
   Always pull before pushing to avoid conflicts:
   ```bash
   git pull origin main --rebase
   ```
5. **Push to GitHub**:
   ```bash
   git push origin main
   ```

---

### 🔹 Option B: Recommended Contributing Workflow (Feature Branch & Pull Request)
Even as a contributor, pushing directly to `main` is often discouraged in production-grade repositories. Using a **Feature Branch** is the standard industry practice:

1. **Create and switch to a new branch**:
   Give it a name that represents your feature:
   ```bash
   git checkout -b feature/shelves-and-profiles
   ```
2. **Stage and commit your work**:
   ```bash
   git add .
   git commit -m "feat: add user profile management and resource shelves"
   ```
3. **Push the feature branch to GitHub**:
   ```bash
   git push origin feature/shelves-and-profiles
   ```
4. **Create a Pull Request (PR)**:
   - Go to [https://github.com/amalkrishnanka/EduQual](https://github.com/amalkrishnanka/EduQual) in your browser.
   - Click the green **"Compare & pull request"** button that pops up.
   - Describe your changes and click **"Create pull request"**.
   - Your teammates can review the code and merge it into `main`!

> 💡 **Need GitHub Authentication?**
> If Git asks you for a password when pushing, GitHub no longer accepts account passwords. You must use a **Personal Access Token (PAT)** or **SSH Key**:
> 1. Go to your **GitHub Settings** -> **Developer Settings** -> **Personal Access Tokens (Tokens classic)**.
> 2. Generate a token with the `repo` scope.
> 3. Use this token as your password when Git prompts you in the terminal.
