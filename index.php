<?php
/**
 * Application: Laragon | Server Index Page
 * Description: Displays server information and local applications.
 */

mysqli_report(MYSQLI_REPORT_OFF);

$serverSoftware = htmlspecialchars($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown', ENT_QUOTES, 'UTF-8');
$documentRoot   = htmlspecialchars($_SERVER['DOCUMENT_ROOT'] ?? '', ENT_QUOTES, 'UTF-8');
$hostName       = htmlspecialchars($_SERVER['HTTP_HOST'] ?? '', ENT_QUOTES, 'UTF-8');

$mysqlVersion = 'MySQL not running!';

$laragonConfig = @parse_ini_file('../usr/laragon.ini');

$password = $laragonConfig['MySQLRootPassword'] ?? '';

$link = mysqli_connect('localhost', 'root', $password);

if (!$link) {
    $link = mysqli_connect('localhost', 'root', '');
}

if ($link) {
    $mysqlVersion = mysqli_get_server_info($link);
    mysqli_close($link);
}

$ignoreDirs = [
    '.',
    '..',
    '.git',
    '.github',
];

$folders = array_filter(glob('*'), 'is_dir');

$baseUrl = 'https';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>My Development Server</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: #fff;
            font: 18px ui-rounded,
                'Hiragino Maru Gothic ProN',
                Quicksand,
                Comfortaa,
                Manjari,
                'Arial Rounded MT',
                'Arial Rounded MT Bold',
                Calibri,
                source-sans-pro,
                sans-serif;
        }

        a {
            color: #fff;
            text-decoration: none;
            text-transform: uppercase;
        }

        .grid-container {
            display: grid;
            grid-template:
                "header" 50px
                "main" 1fr
                "footer" 50px
                / 1fr;
            height: 100vh;
        }

        .header,
        .footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
            background: #0b162c;
        }

        .header {
            grid-area: header;
        }

        .footer {
            grid-area: footer;
        }

        .main {
            grid-area: main;
            background: #e5e5e5;
            overflow: auto;
        }

        .main-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(265px, 1fr));
            gap: 20px;
            margin: 10px;
        }

        .wrapper {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(265px, 1fr));
            gap: 10px;
            margin: 10px;
        }

        .overviewcard,
        .overviewcard_sites {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            border-radius: 5px;
            line-height: 1;
            min-height: 61px;
        }

        .overviewcard {
            background: #00adef;
        }

        .overviewcard_sites {
            background: #023e8a;
        }

        .overviewcard_icon {
            display: flex;
            align-items: center;
            margin-right: 10px;
        }

        .overviewcard_title {
            flex: 1;
            /* text-align: center; */
        }
        .overviewcard_title a {
            display: flex;
            align-items: center;
            width: 100%;
            height: 100%;
            text-align: center;
        }
        .overviewcard_info {
            text-align: right;
        }

        .admin-label {
            font-size: 8px;
            color: #ccc;
        }

        .git-label {
            margin-block: 6px 0;
            color: #ccc;
            font-size: .65em;
        }

        .footer {
            color: #fff;
        }

        small {
            font-size: .75em;
        }
    </style>
</head>

<body>

<div class="grid-container">

    <header class="header">
        <div>
            My Development Server
        </div>
    </header>

    <main class="main">

        <div class="main-overview">

            <div class="overviewcard">
                <div class="overviewcard_title"></div>
                <div class="overviewcard_info">
                    <?= $serverSoftware ?>
                </div>
            </div>

            <div class="overviewcard">
                <div class="overviewcard_title">
                    PHP
                </div>
                <div class="overviewcard_info">
                    <?= phpversion() ?>
                </div>
            </div>

            <div class="overviewcard">
                <div class="overviewcard_title">
                    MySQL
                </div>
                <div class="overviewcard_info">
                    <?= htmlspecialchars($mysqlVersion, ENT_QUOTES, 'UTF-8') ?>
                </div>
            </div>

            <div class="overviewcard">
                <div class="overviewcard_title">
                    Document Root
                </div>
                <div class="overviewcard_info">
                    <?= $documentRoot ?><br>
                    <small><?= $hostName ?></small>
                </div>
            </div>

            <div class="overviewcard">
                <div class="overviewcard_title">
                    Laragon
                </div>
                <div class="overviewcard_info">
                    Full 6.0.220916
                </div>
            </div>

        </div>


        <div class="wrapper">

<?php

foreach ($folders as $host) {

    if (in_array(basename($host), $ignoreDirs, true)) {
        continue;
    }

    $adminLink = '';
    $appName = '';
    $avatar = '';
    $isGitRepo = file_exists($host . '/.git');

    $siteUrl = $baseUrl . '://' . $host . '.test';

    /*
     * Application detection
     */
    switch (true) {

        /*
         * Joomla detection
         *
         * Checking for the Joomla manifest avoids false positives
         * from folders that simply happen to contain "administrator".
         */
        case file_exists($host . '/administrator/manifests/files/joomla.xml'):

            $appName = 'Joomla';

            $manifest = simplexml_load_file($host . '/administrator/manifests/files/joomla.xml');
            $version = $manifest !== false ? (string) $manifest->version : '';
            $versionLabel = $version !== '' ? ' ' . htmlspecialchars($version, ENT_QUOTES, 'UTF-8') : '';

             $avatar = '<svg width="20" height="20" viewBox="-1 0 258 258" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid">
                            <path d="M255.667 35.561c0-18.607-15.084-33.691-33.691-33.691-16.99 0-31.035 12.578-33.349 28.929l-.341-.18c-27.553-12.427-50.762 9.584-50.762 9.584l-61.391 61.692 24.257 23.36 49.413-48.546c23.074-23.075 35.638-7.457 35.638-7.457 17.37 14.674.598 32.044.598 32.044l24.857 23.959c20.298-21.923 21.475-41.056 15.305-56.276 16.612-2.08 29.466-16.243 29.466-33.418" fill="#f9ae41"/>
                            <path d="M226.514 190.507c.115-.215.18-.34.18-.34 12.428-27.554-9.584-50.763-9.584-50.763l-61.693-61.393-23.359 24.259 48.546 49.412c23.074 23.075 7.456 35.64 7.456 35.64-14.673 17.37-32.043.598-32.043.598l-23.959 24.856c21.922 20.298 41.056 21.475 56.275 15.305 2.08 16.612 16.244 29.467 33.419 29.467 18.607 0 33.69-15.084 33.69-33.691 0-16.99-12.577-31.035-28.928-33.35" fill="#ee4035"/>
                            <path d="m155.942 133.04-49.413 48.545c-23.075 23.076-35.639 7.459-35.639 7.459C53.52 174.369 70.291 157 70.291 157l-24.855-23.96c-20.298 21.922-21.476 41.055-15.307 56.275C13.517 191.396.663 205.558.663 222.734c0 18.607 15.084 33.69 33.692 33.69 16.99.001 31.035-12.576 33.349-28.927.216.115.341.179.341.179 27.554 12.43 50.762-9.584 50.762-9.584l61.392-61.693z" fill="#4f91cd"/>
                            <path d="M75.727 106.613c-23.075-23.075-7.459-35.64-7.459-35.64 14.676-17.37 32.045-.598 32.045-.598l23.959-24.855C102.35 25.222 83.217 24.045 67.997 30.214 65.917 13.601 51.755.747 34.577.747 15.973.747.89 15.831.888 34.438c0 16.991 12.577 31.036 28.929 33.35-.115.216-.179.342-.179.342-12.43 27.552 9.583 50.76 9.583 50.76l61.694 61.393 23.359-24.257z" fill="#7ac043"/>
                        </svg>';

            $adminLink = '<a href="' . $siteUrl . '/administrator" target="_blank" rel="noopener"><small class="admin-label">' . $appName . $versionLabel . '</small><br>Admin</a>';

            break;


        default:
            /*
             * Unknown application 
             */
           $avatar = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="20" height="20"><path d="M7.969 0.043C5.914 0.156 4.23 0.371 3.598 0.594c-1.031 0.363 -1.945 1.094 -2.57 2.051C0.57 3.344 0.391 3.922 0.199 5.316 0.047 6.438 0.02 6.871 0.02 8.359c0 1.742 0.066 2.566 0.316 3.906 0.152 0.828 0.313 1.262 0.66 1.805 0.531 0.816 1.27 1.469 2.148 1.891 0.637 0.309 1.273 0.438 2.832 0.582 0.375 0.035 0.766 0.074 0.863 0.09l0.18 0.027 0.586 0.879c0.746 1.113 1.352 1.93 1.574 2.117 0.285 0.246 0.484 0.324 0.82 0.324 0.535 -0.004 0.766 -0.172 1.387 -1 0.285 -0.383 1.363 -1.953 1.598 -2.328 0.004 -0.004 0.313 -0.035 0.688 -0.066 0.977 -0.09 2.078 -0.242 2.441 -0.336 0.391 -0.102 1.004 -0.395 1.41 -0.668 0.395 -0.266 1.035 -0.887 1.313 -1.273 0.473 -0.66 0.691 -1.227 0.867 -2.277 0.398 -2.402 0.395 -4.965 -0.016 -7.363 -0.137 -0.793 -0.223 -1.094 -0.457 -1.563 -0.238 -0.473 -0.465 -0.789 -0.887 -1.223 -0.684 -0.703 -1.434 -1.164 -2.242 -1.375C15.402 0.324 13.746 0.141 11.875 0.039c-0.832 -0.043 -3.07 -0.043 -3.906 0.004m2.535 2.695c0.277 0.141 0.473 0.371 0.559 0.66 0.039 0.141 0.051 0.871 0.051 3.477v3.301l-0.109 0.223c-0.199 0.402 -0.563 0.633 -1.004 0.633s-0.805 -0.23 -1.004 -0.633l-0.109 -0.223V6.875c0 -2.605 0.012 -3.336 0.051 -3.477 0.137 -0.465 0.566 -0.781 1.063 -0.781 0.215 0 0.305 0.023 0.504 0.121m0.098 9.605c0.219 0.047 0.34 0.152 0.449 0.367 0.086 0.172 0.094 0.238 0.094 0.703 0 0.629 -0.043 0.793 -0.242 0.973 -0.195 0.176 -0.328 0.203 -0.961 0.203 -0.586 -0.004 -0.789 -0.07 -0.953 -0.313 -0.113 -0.172 -0.172 -0.613 -0.137 -1.086 0.039 -0.516 0.195 -0.77 0.527 -0.848 0.184 -0.043 1.039 -0.043 1.223 0" fill="#ee4035"/></svg>';
            break;
    }


    echo '
    <div class="overviewcard_sites">

        <div class="overviewcard_icon">
            ' . $avatar . '
        </div>

        <div class="overviewcard_title">
            <a href="' . htmlspecialchars($siteUrl, ENT_QUOTES, 'UTF-8') . '">
                ' . htmlspecialchars($host, ENT_QUOTES, 'UTF-8') . '
            </a>
            ' . ($isGitRepo ? '<p class="git-label">Git Repository</p>' : '') . '
        </div>

        <div class="overviewcard_info">
            ' . $adminLink . '
        </div>

    </div>';

}

?>

        </div>

    </main>


    <footer class="footer">
        <div>
           <?php echo date('l jS F Y, H:i:s'); ?>
        </div>

        <div>
            Made with ☕ by <a href="https://brian.teeman.net" target="_blank" rel="noopener">Brian Teeman</a>
        </div>
    </footer>

</div>

</body>

</html>
