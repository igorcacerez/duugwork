<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
    <title><?= SITE_NOME; ?></title>

    <!-- SEO Meta  ================================================== -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="distribution" content="global" />
    <meta name="revisit-after" content="2 Days" />
    <meta name="robots" content="ALL" />
    <meta name="language" content="pt-br" />

    <!-- Responsivo  ================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Autoload ===================================================== -->
    <?php $this->view("autoload/css"); ?>
</head>
<body>

    <div class="container">
        <div class="row text-center">
            <h1><?= $ola; ?></h1>
            <p>Bem-vindo ao DuugWork. <br> Vamos te ajudar a desenvolver o que quiser!</p>
        </div>
    </div>

    <!-- Autoload JS ================================================== -->
    <?php $this->view("autoload/js"); ?>
</body>
</html>