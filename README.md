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

- Detects Joomla installations by checking for the Joomla manifest:
  - Displays the exact Joomla version from `administrator/manifests/files/joomla.xml`
  - Provides a direct link to the administrator area:

    https://example.test/administrator

- Detects Git repositories by checking for a `.git` file or directory and displays a **Git Repository** label.

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

The dashboard skips the following entries:

- `.`
- `..`
- `.git`
- `.github`

These entries are listed in the `$ignoreDirs` array. You can add other directory names to this array if you do not want them displayed.

---

## Joomla Detection

A project is identified as Joomla when the following file exists:

    administrator/manifests/files/joomla.xml

When detected, the dashboard displays:

- Joomla branding
- The exact Joomla version read from the manifest's `<version>` element
- A link to the administrator login page

    https://project.test/administrator

The version is displayed alongside the Joomla label above the Admin link.

---

## Git Repository Detection

A project is identified as a Git repository when a `.git` entry exists in its root directory:

    file_exists($host . '/.git')

This works for both standard Git repositories, where `.git` is a directory, and Git worktrees or submodules, where `.git` can be a file.

When detected, the dashboard displays a **Git Repository** label beneath the project name. Git detection is independent of Joomla detection, so a Joomla project that is also a Git repository displays both its Joomla version and the Git Repository label.

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
        '.',
        '..',
        '.git',
        '.github',
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

But if you are looking for something more complex you should take a look at https://github.com/LebToki/Laragon-Dashboard

## Credits
@drmenzelit for the change to include the specific Joomla version and the Git Repository label
