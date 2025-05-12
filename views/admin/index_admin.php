<div class="admin__informacion">

    <h2 class="admin__heading"><?php echo $titulo ?></h2>

    <div class="admin__campos">

        <div class="admin__datosInfo">

            <div class="admin__campo">
                <label for="cantClientes" class="admin__label">Cant. Clientes:</label>
                <input type="number" id="cantClientes" class="admin__input" name="cantClientes" disabled value="<?php echo $cant; ?>">
            </div>

            <div class="admin__campo">
                <label for="saldo" class="admin__label">Total a favor del cliente:</label>
                <input type="number" id="saldo" class="admin__input" name="saldo" disabled value="<?php echo $saldo; ?>">
            </div>

            <div class="admin__campo">
                <label for="credito" class="admin__label">Credito Actual:</label>
                <div class="masked-input-container">
                    <input type="number" id="credito" class="admin__input admin__input--mask" name="credito" value="<?php echo $total; ?>">
                    <div class="mask" id="maskCredito"></div>
                </div>
            </div>

            <div class="admin__campo">
                <label for="credito_dia" class="admin__label">Credito hoy <?php echo date('Y-m-d'); ?></label>
                <div class="masked-input-container">
                    <input type="number" id="credito_dia" class="admin__input admin__input--maskdia" name="credito" disabled value="<?php echo ($deudaHoy) ? $deudaHoy : '0'; ?>">
                    <div class="mask" id="maskCreditodia"></div>
                </div>
            </div>
        </div>

        <div class="admin__datosConsulta">
            <div class="admin__campo">
                <label for="deudores" class="admin__label">Top 10 Deudores:</label>
                <div class="admin__contorno">
                    <?php
                    if (!$topDeudores) { ?>
                        <p class="admin__lista admin__lista--registro"><?php echo "SIN REGISTRO"; ?></p>

                        <?php } else {
                        $contador = 1;
                        foreach ($topDeudores as $top) {
                        ?>
                            <p class="admin__lista"><?php echo $contador . " - " . recortarTexto($top->nombre) . " " . recortarTexto($top->apellido) . " Saldo: " . $top->deuda; ?></p>
                    <?php
                            $contador += 1;
                        }
                    }
                    ?>
                </div>
            </div>

            <div class="admin__campo admin__campo--altura">
                <label for="saldo" class="admin__label">Clientes saldo a favor:</label>
                <div class="admin__contorno">
                    <?php
                    if (!$Afavor) { ?>
                        <p class="admin__lista admin__lista--registro"><?php echo "SIN REGISTRO"; ?></p>

                        <?php } else {
                        $contador = 1;
                        foreach ($Afavor as $abonos) { ?>
                            <p class="admin__lista"><?php echo $contador . " - " . $abonos['nombre'] . " a favor: " . $abonos['abono']; ?></p>
                    <?php
                            $contador += 1;
                        }
                    }
                    ?>
                </div>
            </div>



            <div class="admin__campo admin__campo--altura">
                <label for="morosos" class="admin__label">Clientes Morosos (+30 dias):</label>
                <div class="admin__contorno">
                    <?php
                    if (!$morosos) { ?>
                        <p class="admin__lista admin__lista--registro"><?php echo "SIN REGISTRO"; ?></p>

                        <?php } else {
                        $contador = 1;
                        foreach ($morosos as $dato) { ?>
                            <p class="admin__lista"><?php echo $contador . " - " . $dato['nombre'] . " en mora " . $dato['mora'] . " días"; ?></p>
                    <?php
                            $contador += 1;
                        }
                    }
                    ?>
                </div>
            </div>

            <div class="admin__campo admin__campo--altura">
                <label for="bloqueados" class="admin__label">Clientes Bloqueados:</label>
                <div class="admin__contorno">
                    <?php
                    if (!$bloqueados) { ?>
                        <p class="admin__lista admin__lista--registro"><?php echo "SIN REGISTRO"; ?></p>

                        <?php } else {
                        $contador = 1;
                        foreach ($bloqueados as $dato) { ?>
                            <p class="admin__lista"><?php echo $contador . " - " . $dato['nombre'] . " saldo " . $dato['saldo']; ?></p>
                    <?php
                            $contador += 1;
                        }
                    }
                    ?>
                </div>
            </div>

            <div class="admin__administradores">
                <label for="morosos" class="admin__label">Administradores:</label>
                <div class="admin__contorno">
                    <?php
                    if (!$administradores) { ?>
                        <p class="admin__lista admin__lista--registro"><?php echo "SIN REGISTRO"; ?></p>

                        <?php } else {
                        $contador = 1;
                        foreach ($administradores as $dato) { ?>
                            <p class="admin__lista"><?php echo $contador . " - " . $dato['usuario'] . " perfil " . $dato['perfil']; ?></p>
                    <?php
                            $contador += 1;
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

</div>