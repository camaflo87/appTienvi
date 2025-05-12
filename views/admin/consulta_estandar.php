<div class="admin__informacion">

    <h2 class="admin__heading"><?php echo $titulo ?></h2>

    <?php include_once __DIR__ . '/../templates/consulta.php'; ?>

    <?php

    if ($modal = "btnAgregarCredito") {
        include __DIR__ . '/../templates/modales/modalAgregarCredito.php';
    }

    if ($modal = "btnAgregarPago") {
        include __DIR__ . '/../templates/modales/modalAgregarAbonos.php';
    }

    ?>

</div>