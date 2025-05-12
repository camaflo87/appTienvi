<?php include __DIR__ . "/../templates/header.php"; ?>

<?php
include __DIR__ . "/../templates/alertas.php";
?>

<main class="mensaje">

    <picture class="mensaje__picture">
        <source srcset="/build/img/registro.webp" type="image/webp">
        <source srcset="/build/img/registro.avif" type="image/avif">
        <img class="mensaje__img" src="/build/img/registro.png" type="image/png" alt="registro exitoso">
    </picture>

    <a href="/administracion" class="mensaje__cancelar">Volver Administración</a>
</main>