<main class="informe">
    <h2 class="admin__heading"><?php echo $titulo ?></h2>

    <button id="generarPDF" class="informe_btnPdf">Generar PDF</button>
    <p><small>Nota: El PDF se generará con los datos actuales de la base de datos.</small></p>

    <div class="informe__campo">
        <label for="deuda" class="informe__label">Credito Total:</label>
        <input type="text" id="deuda" class="informe__input" name="deuda" value="<?php echo "$" . $deudaGeneral; ?>">
    </div>

    <section id="registro_deudores" class="informe__deudores">
        <?php
        foreach ($deudores as $itemReg) { ?>
            <div class="informe__bloque">
                <p class="informe__nombre"><?php echo recortarTexto($itemReg->nombre) . " " . recortarTexto($itemReg->apellido); ?></p>
                <p class="informe__valorDeuda"><?php echo "$ $itemReg->deuda"; ?></p>
            </div>
        <?php }
        ?>
    </section>

    <p class="consultas__cant">Registros: <?php echo $cant; ?></p>


</main>