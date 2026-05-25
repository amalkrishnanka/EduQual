# EduQual — Educational Resource Quality Assessment System

EduQual is a full-stack web application built with Laravel 11, SQLite, Tailwind CSS v4, and Alpine.js. It allows educational institutions to assess the quality of textbooks, reference books, and e-books through a structured, multi-role workflow.

## Key Features

- **Role-Based Access Control**:
  - `Super Admin`: Full control over users, resources, and system settings.
  - `Reviewer`: Can submit structured assessments and raise inaccuracy flags.
  - `Viewer`: Read-only access to published assessments and generated reports.
- **Resource Catalogue**: Comprehensive management of educational resources with advanced search and filtering.
- **Assessment Engine**: 5-criterion scoring system (Accuracy, Relevance, Readability, Engagement, Pedagogical Value) with weighted averages and radar chart visualisations.
- **Content Flagging**: Workflow to flag inaccurate or outdated content and track its resolution.
- **Analytics & Reporting**: Interactive Chart.js dashboards, PDF report generation, and CSV data exports.

## Technology Stack

- **Backend**: Laravel 11 (PHP 8.2+), SQLite (default database engine)
- **Frontend**: Tailwind CSS v4, Alpine.js, Chart.js
- **Exports**: `barryvdh/laravel-dompdf` for PDF generation

## Getting Started

1. **Install dependencies**:
   ```bash
   composer install
   npm install
   ```

2. **Set up the environment**:
   Copy `.env.example` to `.env` (if not already done) and generate the app key:
   ```bash
   php artisan key:generate
   ```

3. **Run migrations and seed the database**:
   This will create the necessary tables and populate the database with default users, criteria, resources, and sample assessments.
   ```bash
   php artisan migrate:fresh --seed
   ```

4. **Compile frontend assets**:
   ```bash
   npm run build
   ```

5. **Start the application**:
   ```bash
   php artisan serve
   ```
   Visit `http://localhost:8000` in your browser.

## Default Users (Password: `password`)
- **Super Admin**: `admin@eduqual.com`
- **Reviewers**: `sarah@eduqual.com`, `james@eduqual.com`, `priya@eduqual.com`
- **Viewers**: `alex@eduqual.com`, `maria@eduqual.com`
