# Laravel Input Sanitizer Middleware

![Laravel](https://img.shields.io/badge/Laravel-10.x%20%7C%2011.x%20%7C%2012.x-orange)
![License](https://img.shields.io/github/license/fixer112/laravel-sanitizer)
![Packagist](https://img.shields.io/packagist/v/fixer112/laravel-sanitizer)

A Laravel middleware package that sanitizes all incoming request data by stripping out potentially malicious scripts, SQL keywords, and dangerous shell command inputs. It also blocks known bots and crawlers based on the `User-Agent`.

---

## ✨ Features

- Filters out common XSS/JS/HTML injections
- Removes SQL injection keywords
- Removes shell command patterns like `cmd`, `powershell`, `shutdown`
- Sanitizes all fields except `password` and `confirm_password`
- Blocks basic bot `User-Agent` patterns
- Lightweight and auto-runs on every request (if configured)

---

## 🚀 Installation

```bash
composer require fixer112/sanitizer
```

## ⚙️ Configuration
To publish the configuration file:

```bash
php artisan vendor:publish --tag=config --provider="Fixer112\Sanitizer\SanitizerServiceProvider"
```
This will create config/sanitizer.php with:

```php
return [
    'global' => true, // Automatically apply to all web and API routes
];
```
If global is true, the sanitizer middleware will be added to both the web and api middleware stacks automatically.

## 🛡️ What It Sanitizes
It removes the following:

- ```<script>, <iframe>, <style>, <svg>, etc.```

- onerror=, onclick=, javascript: URIs

- data:text/html;base64, patterns

- Dangerous SQL terms: select, update, drop, exec, etc.

- Shell/OS commands like cmd, powershell, shutdown, etc.

- Character patterns like &, |, ;, <, > that can trigger shell execution

## 🧪 Usage
No additional setup required if global => true in config.

If not, register the middleware manually in your Kernel.php:

```php
protected $middleware = [
    \Fixer112\Sanitizer\Middleware\Sanitizer::class,
];
```
Or add it only to certain routes:

```php
Route::middleware(['sanitizer'])->group(function () {
    // routes
});
```

## 🧼 Skipped Fields
By default, these fields are not sanitized:

password

confirm_password

You can customize this inside the package or fork it to your needs.

🤖 Bot Protection
Rejects requests with suspicious or missing User-Agent headers like:

- bot

- crawler

- spider

- curl

- httpclient

- scrapy

