<div class="consultas">

    <div class="consultas__campos">
        <label for="buscar" class="consultas__label">Buscar:</label>
        <input type="text" id="buscar" class="consultas__buscar" name="buscar" placeholder="Digita el nombre a buscar">
    </div>

    <section class="consultas__registros">

        <?php foreach ($personas as $persona) { ?>
            <div class="consultas__bloque">
                <p class="consultas__usuario"><?php echo $persona->nombre . " " . $persona->apellido; ?></p>
                <div class="consultas__acciones">
                    <?php
                    if ((str_contains($pagina, '/modificar')) || (str_contains($pagina, '/creditos_individuales')) || (str_contains($pagina, '/abonos_individuales'))) { ?>
                        <a href="<?php echo $pagina; ?>?id=<?php echo $persona->id; ?>" class="consultas__varias">Consultar</a>
                    <?php } else { ?>
                        <div class="consultas__btnNodal <?php echo ($persona->perfil === 'Inhabilitado') ? 'consultas__btnNodal--bloqueo' : ''; ?>" data-id="<?php echo $persona->id; ?>" data-modal="<?php echo $modal; ?>"><?php echo ($texto) ? $texto : $persona->perfil; ?></div>
                    <?php }
                    ?>
                </div>
            </div>
        <?php }
        ?>

    </section>

    <div class="consultas__datos">
        <?php echo $paginacion; ?>
    </div>

    <!-- <a href="/administracion" class="formulario__cancelar">Cancelar</a> -->
</div>