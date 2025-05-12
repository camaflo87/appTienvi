<div class="header">
    <h1 class="header__title">Administrador TienVi</h1>

    <?php
    if (isset($admin)) { ?>
        <nav class="header__nav">
            <a href="<?php echo $link; ?>" class="header__link"><?php echo $admin; ?></a>
        </nav>
        <?php } else {

        if (!empty($_SESSION)) { ?>
            <nav>
                <form method="POST" action="/logout" class="formulario">
                    <input type="submit" value="Cerrar Sesión" class="formulario__logout">
                </form>
            </nav>
    <?php
        }
    }
    ?>
</div>