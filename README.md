# Charity Site

## Description

This project is a non-profit eCommerce platform designed to support people in need. Unlike traditional online stores, when a customer makes a purchase, the product is not delivered to them but sent directly to a charitable organization in Lyon. The producer ships the item, and the organization is responsible for distributing it to those in need.

## Tech Stack

- Symfony 7.1.0
- Doctrine
- Sass
- JavaScript
- PHP CS Fixer

## Naming Conventions

- **Sass**: Follow the `BEM` methodology for CSS structuring.
- **JavaScript**: Use `CamelCase` for variables and function names.
- **PHP**: Ensure the code follows the rules set by `PHP CS Fixer`.
- **File Names**:
  - **SCSS/CSS**: Use `snake_case`.  
  - **PHP/JavaScript**: Use `PascalCase` for files containing classes or modules.  

## Requirements

- PHP 8.0 or higher
- Composer
- **Live Sass Compiler** plugin for Sass auto-compilation

## Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/200habib/charity-site.git
    cd charity_site
    ```

2. Install Composer dependencies:
    ```bash
    composer install
    ```

3. Run the development server:
    ```bash
    symfony serve:start
    ```

## Using PHP CS Fixer

To format the code according to the defined rules, run:
```bash
vendor/bin/php-cs-fixer fix
