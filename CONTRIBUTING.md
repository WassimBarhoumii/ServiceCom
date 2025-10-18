# Contributing to ServiceCom #

Thank you for your interest in contributing to ServiceCom! This document provides guidelines and instructions 
for contributing to our Laravel-based service marketplace platform.

## Table of Contents ##

## Code of Conduct ##

We are committed to providing a welcoming and inclusive environment for all contributors. Please be respectful, constructive, and professional in all interactions.

## Getting Started ##
### Prerequisites ###

Before contributing, ensure you have:

    PHP: 8.1 or higher
    Composer: Latest version for dependency management
    Laravel: Familiarity with Laravel 10.x framework
    Database: MySQL, PostgreSQL, or SQLite
    Git: For version control

Fork and Clone

    Fork the repository on GitHub
    
    Clone your fork locally: 
    git clone https://github.com/YOUR_USERNAME/ServiceCom.git  
    
    cd ServiceCom

Development Setup

Follow these steps to set up your development environment:

Install PHP dependencies

    composer install  
  
Copy environment file  
    
    cp .env.example .env  
  
Generate application key  

    php artisan key:generate  
  
Configure your database in .env file  

      DB_CONNECTION=mysql  
      DB_HOST=127.0.0.1  
      DB_PORT=3306  
      DB_DATABASE=servicecom  
      DB_USERNAME=root  
      DB_PASSWORD=  
  
Run migrations  

    php artisan migrate  
  
(Optional) Seed the database  

    php artisan db:seed  
  
Start development server  

    php artisan serve

Project Structure

Understanding the codebase structure is essential for effective contributions:
Controllers

    app/Http/Controllers/AccountController.php: Handles user authentication, profile management, and service CRUD operations AccountController.php:61-119
    app/Http/Controllers/ServicesController.php: Manages service browsing, applications, and bookmarking ServicesController.php:151-180
    app/Http/Controllers/HomeController.php: Homepage and featured services display HomeController.php:1-34
    app/Http/Controllers/admin/*: Administrative functionality for user and service management

Models

Key Eloquent models define the data structure:

    User: User accounts and authentication
    Service: Service listings
    ServiceApplication: Application submissions
    SavedService: Bookmarked services
    Category: Service categories
    ServiceType: Service type classifications

Views

Blade templates located in resources/views/:

    front/: User-facing pages
    admin/: Administrative interface
    email/: Email templates

Mail

Email notifications in app/Mail/:

    ResetPasswordEmail.php: Password reset functionality ResetPasswordEmail.php:1-54
    ServiceNotificationEmail: Application notifications

Contribution Workflow
1. Create a Branch

Always create a new branch for your work:

git checkout -b feature/your-feature-name  
# or  
git checkout -b fix/bug-description

Use descriptive branch names:

    feature/add-service-ratings
    fix/application-duplicate-prevention
    docs/update-installation-guide

2. Make Your Changes

    Write clean, readable code following Laravel conventions
    Add comments for complex logic
    Update documentation if needed

3. Commit Your Changes

Write clear, descriptive commit messages:

git add .  
git commit -m "Add service rating system with star display"

Follow commit message conventions:

    Use present tense ("Add feature" not "Added feature")
    Be specific and descriptive
    Reference issue numbers when applicable

4. Push and Create Pull Request

git push origin feature/your-feature-name

Then create a Pull Request on GitHub with:

    Clear title describing the change
    Detailed description of what was changed and why
    Screenshots for UI changes
    Reference to related issues

Coding Standards
Laravel Best Practices

    Follow PSR-12 coding standards
    Use Laravel's built-in features (Eloquent, Blade, Validation)
    Implement proper error handling and validation AccountController.php:104-111
    Use dependency injection where appropriate

Security

    Always validate user input
    Use Laravel's CSRF protection
    Hash passwords with Hash::make() AccountController.php:508-510
    Sanitize file uploads
    Use parameterized queries (Eloquent handles this)

Database

    Create migrations for all database changes
    Use Eloquent relationships properly
    Add indexes for frequently queried columns
    Write seeders for test data

Code Style

// Good: Descriptive variable names  
$serviceApplication = ServiceApplication::find($id);  
  
// Good: Proper validation  
$validator = Validator::make($request->all(), [  
    'email' => 'required|email|exists:users,email'  
]);  
  
// Good: Clear method names  
public function processForgotPassword(Request $request)


Testing Guidelines

While adding tests:

    Write unit tests for models and business logic
    Create feature tests for controller actions
    Test validation rules thoroughly
    Test authentication and authorization

# Run tests  
php artisan test

Pull Request Process

    Ensure your code works: Test locally before submitting
    Update documentation: If you changed functionality, update relevant docs
    Follow the template: Use the PR template if provided
    Be responsive: Address review comments promptly
    Keep PRs focused: One feature/fix per PR

PR Checklist

    Code follows project coding standards
    All tests pass
    Documentation updated (if needed)
    No merge conflicts
    Commit messages are clear
    Screenshots included (for UI changes)

Areas for Contribution

We welcome contributions in these areas:
Features

    Service ratings and reviews system
    Advanced search filters
    User messaging system
    Service recommendations
    Mobile responsive improvements

Bug Fixes

    Check the Issues page for reported bugs
    Look for issues labeled good first issue or help wanted

Documentation

    Improve code comments
    Add API documentation
    Create user guides
    Translate documentation

Testing

    Increase test coverage
    Add integration tests
    Create test fixtures

Performance

    Optimize database queries
    Implement caching strategies
    Reduce page load times

Questions?

If you have questions about contributing:

    Open an issue with the question label
    Review existing documentation and wiki pages
    Check closed issues for similar questions

License

By contributing to ServiceCom, you agree that your contributions will be licensed under the MIT License.

Thank you for contributing to ServiceCom! Your efforts help make this platform better for everyone.
Notes

This CONTRIBUTING.md file provides comprehensive guidance for contributors to the ServiceCom project. It covers the complete contribution 
workflow from setup to pull request submission, emphasizing Laravel best practices and security considerations. The guide references specific controller 
methods like processForgotPassword() and applyService() to help contributors understand the codebase structure. Contributors should familiarize 
themselves with the MVC architecture, Eloquent ORM patterns, and Laravel's validation system before making significant changes. 
