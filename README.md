# Laragon Development Server Dashboard

A simple single-file PHP dashboard for Laragon that displays information about your local development environment and provides quick links to your local projects.

Designed to replace the default Laragon landing page with a cleaner, more useful overview of your development server specifically for Joomla developers.

<img width="929" height="548" alt="image" src="https://github.com/user-attachments/assets/3279bafa-ae9c-4ed9-8165-9110a739ae51" />


## Features

- Displays server information:
  - Web server software
  - PHP version
  - MySQL server version
  - Document root
  - Current host name
  - Laragon version

- Automatically detects local projects in the Laragon `www` directory.

- Provides quick links to local sites using the `.test` domain convention:

    https://example.test

- Detects Joomla installations and provides a direct link to the administrator area:

    https://example.test/administrator

- Includes embedded SVG icons.

- Fully self-contained:
  - No external CSS files
  - No JavaScript dependencies
  - No database required
  - No installation process

---

## Installation

1. Download `index.php`.

2. Replace the existing Laragon document root index file:

    C:\laragon\www\index.php

3. Start Laragon.

4. Open:

    https://localhost

or:

    http://localhost

Your local development dashboard will be displayed.

---

## Project Detection

The dashboard scans the Laragon `www` directory and checks each folder.

Ignored directories include:

- `logs`
- `access-logs`
- `vendor`
- `assets`
- `favicon_io`

Additional folders can be added to the ignore list inside the PHP file.

---

## Joomla Detection

A project is identified as Joomla when the following file exists:

    administrator/manifests/files/joomla.xml

When detected, the dashboard displays:

- Joomla branding
- A link to the administrator login page

    https://project.test/administrator

---

## Customisation

The dashboard is intentionally kept as a single file so it is easy to modify.

Common customisations include:

### Change colours

Edit the CSS:

    .overviewcard {
        background: #00adef;
    }

    .overviewcard_sites {
        background: #023e8a;
    }

### Add ignored folders

Update the ignore list:

    $ignoreDirs = [
        'logs',
        'vendor'
    ];

### Add additional framework detection

The application detection section uses a simple PHP `switch` statement, making it easy to add support for other platforms such as:

- WordPress
- Drupal
- Laravel
- Symfony
- Custom applications

---

## Why a Single File?

This dashboard deliberately avoids a traditional project structure.

Keeping everything in one PHP file means:

- Copy it anywhere
- No dependencies
- Easy backup
- Easy sharing between machines
- Works immediately after a Laragon installation

Sometimes simple is better.

