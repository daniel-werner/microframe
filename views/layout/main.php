<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- CSS -->
        <link rel="stylesheet" href="/assets/css/main.css">

        <title>Microframe</title>
    </head>
    <body>
        <header>
            <nav>
                <div class="topnav" id="myTopnav">
                    <a href="/" class="<?php active('/');?>">Welcome</a>
                    <a href="/contact" class="<?php active('/contact');?>">Contact</a>
                    <a href="/about" class="<?php active('/about');?>">About</a>
                    <a href="javascript:void(0);" class="icon" onclick="burgerMenu()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                        </svg>
                    </a>
                </div>
            </nav>
        </header>

        <!-- content -->
        <div>
            <?php
                $this->render();
            ?>
        </div>

        <!-- footer -->
        <footer class="footer">
            <p>&copy; <?php year(); ?> Microframe</p>
        </footer>

        <!-- Javascript -->
        <script src="/assets/js/main.js"></script>
    </body>
</html>
