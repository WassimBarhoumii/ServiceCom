# ServiceCom #

A modern service marketplace platform built with Laravel that connects service providers with service seekers.

## Overview ##

ServiceCom is a full-featured web application that enables users to post service listings, browse available services, and manage applications. The platform provides a comprehensive marketplace experience with advanced search, filtering, and user management capabilities.
## Key Features ##
### For Service Providers ###

+ Service Management: Create, edit, and delete service listings with detailed information
+ Application Tracking: Monitor applications received on posted services
+ Profile Customization: Manage profile with image uploads and professional details

### For Service Seekers ###

- Advanced Search: Filter services by keyword, location, category, and service type
- Service Applications: Apply to services with automatic email notifications 
- Bookmarking System: Save services for later review
- Application Dashboard: Track all submitted applications in one place

## Administrative Features ##

+ User Management: Oversee user accounts and permissions
+ Service Oversight: Edit and manage all service listings
+ Application Management: Monitor and manage service applications

## Technology Stack ##

+ Framework: Laravel 10.48.29
+ PHP: ^8.1 
+ Image Processing: Intervention Image ^3.11
+ HTTP Client: Guzzle ^7.2
+ Frontend: Blade templates with Bootstrap CSS
+ Database: MySQL/PostgreSQL/SQLite compatible

## Core Functionality ##
### Authentication System ###

- User registration and login
- Password reset with email verification
- Secure password hashing
- Profile management with image uploads

### Service Marketplace ###

+ Service creation with categories and types
+ Advanced filtering and search capabilities
+ Application submission with validation
+ Email notifications for service providers

### User Dashboard ###

- My Services: View and manage posted services
- My Applications: Track submitted applications
- Saved Services: Manage bookmarked services

## Installation ##

**Clone the repository**

    git clone https://github.com/WassimBarhoumii/ServiceCom.git
    
**Install dependencies**

    composer install  
    
**Configure environment**

    cp .env.example .env  
    php artisan key:generate  
    
**Run migrations**

    php artisan migrate  
    
**Start development server**

    php artisan serve
    
## Project Structure ##

+ Controllers: Handle HTTP requests and business logic
+ Models: Define database relationships and data structures
+ Views: Blade templates for frontend rendering
+ Middleware: Authentication and authorization guards
+ Mail: Email notification system

## Security Features ##

- CSRF protection on all forms
- Password hashing with Laravel's Hash facade
- Input validation on all user submissions
- Secure file upload handling
- Role-based access control for admin features

## License ##

This project is open-sourced software licensed under the MIT license. README.md

## Author ##

Wassim Barhoumi

## Notes ##

This README provides a comprehensive overview of the ServiceCom platform, covering its core features, technology stack, and functionality. The project implements a complete service marketplace with user authentication, service management, application tracking, and administrative oversight. The codebase follows Laravel best practices with MVC architecture, Eloquent ORM for database operations, and Blade templating for views. 
