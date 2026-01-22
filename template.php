<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>La Buvette</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
<div class="top-bar"></div>

<header>
    <div class="header__logo">🍺 La Buvette</div>
    <div class="header__icon">👤</div>
    <?php $menu->affiche(); ?>
</header>


<section class="main">
    <?php echo $contenu; ?>
</section>

<div class="bottom-bar"></div>

<footer>
    <p>© 2025 - Mohamed-Amine Ayman et Chadi | laBuvette</p>
</footer>
</body>
</html>