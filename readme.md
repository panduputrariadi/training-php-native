# SIMPLE FRAMEWORK PHP NATIVE 

A simple framework for PHP development using native PHP features. It includes the following features:

- Routing
- Database migrations
- Query builder
- Middleware
- CLI tool
- RESTful API
- Validation
- Logger

## GITHUB REPOSITORY
[https://github.com/panduputrariadi/training-php-native](https://github.com/panduputrariadi/training-php-native.git)

you can clone the repository using the following command:
```BASH
git clone https://github.com/panduputrariadi/training-php-native.git
```
## Prerequisites

### For Windows Users

1. **Install PHP**:
   - Download from [windows.php.net/download](https://windows.php.net/download/)
   - Add PHP to your PATH environment variable
   - Verify installation: `php -v`

2. **Install Laragon** (includes PHP, MySQL, and web server):
   - Download from [laragon.org/download](https://laragon.org/download/)
   - Follow the installation wizard
   - Laragon includes PHP, MySQL, Apache/Nginx, and more

3. **Install Composer**:
   - Download from [getcomposer.org](https://getcomposer.org/download/)
   - Run the installer and follow instructions
   - Verify installation: `composer --version`

4. **Install MySQL** (if not using Laragon):
   - Download from [mysql.com/downloads](https://dev.mysql.com/downloads/)
   - Use MySQL Installer for Windows
   - Set up root password and remember credentials

### For macOS Users

1. **Install Homebrew** (package manager):
   ```bash
   /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
   ```

2. **Install PHP**:
   ```bash
   brew install php
   ```

3. **Install MySQL**:
   ```bash
   brew install mysql
   brew services start mysql
   ```

4. **Install Composer**:
   ```bash
   curl -sS https://getcomposer.org/installer | php
   mv composer.phar /usr/local/bin/composer
   chmod +x /usr/local/bin/composer
   ```

### Alternative for macOS: Use MAMP

1. Download MAMP from [mamp.info](https://www.mamp.info/en/downloads/)
2. Install and launch MAMP
3. MAMP includes PHP, MySQL, and Apache server
4. Add MAMP's PHP to your PATH:
   ```bash
   echo 'export PATH="/Applications/MAMP/bin/php/php[version]/bin:$PATH"' >> ~/.bash_profile
   source ~/.bash_profile
   ```

## Installation

1. Clone or download this project to your local machine
2. Navigate to the project directory
3. Install dependencies (if any):
   ```bash
   composer install
   ```

## Usage

Run the CLI tool using the following commands:

```bash
# Show help
php artisan --help
php artisan -h

# Start development server
php artisan serve

# Database migrations
php artisan migrate:up    # Run all migrations
php artisan migrate:down  # Rollback all migrations

# Code generation
php artisan make:migration    # Create new migration
php artisan make:model        # Create new model
php artisan make:queries      # Create model queries
php artisan make:controller   # Create new controller
```

## Development Server

The development server will start at http://localhost:2000

```bash
php artisan serve
```

Press `Ctrl+C` to stop the server.

## Project Structure

```
src/
  cli/
    make_migration.php
    make_model.php
    make_queries.php
    make_controller.php
  boostrapp/
    app.php
migrate
artisan
```

## Configuration

1. Update database configuration in `src/boostrapp/app.php`
2. Create your migrations using `php artisan make:migration`
3. Run migrations with `php artisan migrate:up`

## Troubleshooting

1. If you get "php not found" error, ensure PHP is in your PATH
2. For permission issues on macOS/Linux, try adding execute permission:
   ```bash
   chmod +x artisan
   ```
3. For database connection issues, verify your MySQL server is running

## Support

For issues with this CLI tool, check that:
- PHP version is 7.4 or higher
- Required extensions are enabled (pdo_mysql, mbstring, etc.)
- Database credentials are correct