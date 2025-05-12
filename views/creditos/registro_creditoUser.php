<main class="creditos">

    <h2 class="admin__heading"><?php echo $titulo ?></h2>

    <section class="creditos__informacion">
        <div class="creditos__bloque">
            <p><strong>Cliente: </strong><?php echo $nombre; ?></p>
            <p><strong>Deuda: $</strong><?php echo ($deuda > 0) ? $deuda : 0; ?></p>
            <?php
            if ($deuda < 0) { ?>
                <p><strong>A favor: </strong><?php echo ($deuda * (-1)); ?></p>
            <?php  }
            ?>
        </div>
    </section>

    <p class="principal__subtitulo">Registro de Compras</p>

    <section class="creditos__registros">
        <?php
        foreach ($registros as $registro) {
            if ($deuda === 0) { ?>
                <p class="creditos__sinResultado">SIN REGISTRO</p>
            <?php } else { ?>
                <div class="creditos__bloque">
                    <p class="creditos__consulta"><?php echo "Fecha: $registro->fecha - Deuda: $$registro->credito"; ?></p>
                    <div class="creditos__acciones">
                        <button class="creditos__btnModificar" data-id="<?php echo $registro->id; ?>" data-fecha="<?php echo $registro->fecha; ?>" data-idPersona="<?php echo $registro->id_persona; ?>" data-credito="<?php echo $registro->credito; ?>">Modificar</button>
                        <button class="creditos__btnEliminar" data-id="<?php echo $registro->id; ?>" data-fecha="<?php echo $registro->fecha; ?>" data-credito="<?php echo $registro->credito; ?>">Eliminar</button>
                    </div>
                </div>
        <?php }
        }
        ?>
    </section>
    <?php
    if ($deuda !== 0) { ?>
        <div class="consultas__datos">
            <p class="consultas__cant">Registros: <?php echo $cant; ?></p>
        </div>
    <?php } ?>
    <a href="administracion_consultas?cat=creditos" class="formulario__cancelar">Cancelar</a>

</main>

<?php
include __DIR__ . "/../templates/modales/modalModCreditoAbono.php";
?>