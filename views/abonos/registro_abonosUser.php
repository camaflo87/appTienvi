<main class="abonos">

    <h2 class="admin__heading"><?php echo $titulo ?></h2>

    <section class="abonos__informacion">
        <div class="abonos__bloque">
            <p><strong>Cliente: </strong><?php echo $nombre; ?></p>
            <p><strong>Abonos: $</strong><?php echo $total; ?></p>
        </div>
    </section>

    <p class="principal__subtitulo">Registro de Abonos</p>

    <section class="abonos__registros">
        <?php
        foreach ($abonos as $registro) { ?>
            <div class="abonos__bloque">
                <p><?php echo "Fecha: $registro->fecha - Deuda: $$registro->abono"; ?></p>
                <div class="abonos__acciones">
                    <button class="abonos__btnModificar" data-id="<?php echo $registro->id; ?>" data-fecha="<?php echo $registro->fecha; ?>" data-idPersona="<?php echo $registro->id_persona; ?>" data-abono="<?php echo $registro->abono; ?>">Modificar</button>
                    <button class="abonos__btnEliminar" data-id="<?php echo $registro->id; ?>" data-fecha="<?php echo $registro->fecha; ?>" data-abono="<?php echo $registro->abono; ?>">Eliminar</button>
                </div>
            </div>
        <?php }
        ?>
    </section>

    <div class="consultas__datos">
        <p class="consultas__cant">Registros: <?php echo $cant; ?></p>
    </div>

    <a href="/administracion_consultas?cat=consultaPago" class="formulario__cancelar">Cancelar</a>

</main>

<?php
include __DIR__ . "/../templates/modales/modalModCreditoAbono.php";
?>