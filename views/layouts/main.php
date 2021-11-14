<?php

use app\core\Application;

/**
 * @var $this app\core\View
 */
?>


<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title><?php echo $this->title ?></title>
    <meta name="keywords" content="<?=$this->keywords ?>">
</head>

<body>


    <nav class="navbar navbar-expand-lg navbar-primary bg-gray">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">PHP MVC</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <?php if (Application::isGuest()) : ?>
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="/contact">İletişim</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/login">Giriş</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/register">Kayıt</a>
                        </li>
                    </ul>
                <?php else : ?>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/">Anasayfa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/profile">Profile</a>
                        </li>
                        <li class="nav-item">

                            <a class="nav-link active" aria-current="page" href="/logout">Hoşgeldiniz <?php echo Application::$app->user->getDisplayname() ?> Çıkış</a>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php if (Application::$app->session->getFlash('success')) : ?>
            <div class="alert alert-success">
                <?php echo Application::$app->session->getFlash('success'); ?>
            </div>
        <?php endif; ?>
        {{content}}
    </div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
</body>

</html>