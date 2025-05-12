<div id="myAgregarAbonos" class="modal">
    <div class="modal-content modal-content--abono">
        <div class="modal-title">Agregar Abono</div>
        <div class="modal-options--agregarAbono">

            <div class="formulario__campo">
                <label for="nombreUser" class="formulario__label">Nombre:</label>
                <input type="text" id="nombreUser" class="formulario__input modal--nombre" name="nombreUser" value="">
            </div>

            <div class="formulario__campo">
                <label for="deuda" class="formulario__label">Deuda:</label>
                <input type="text" id="deuda" class="formulario__input" name="deuda" value="" disabled>
            </div>

            <div class="formulario__campo">
                <label for="fechaAbono" class="formulario__label">Fecha:</label>

                <input type="date" id="fechaAbono" class="formulario__input" name="fechaAbono" min="<?php echo ($_SESSION['perfil'] === '2') ? '' : date('Y-m-d'); ?>" value="">
            </div>

            <div class="formulario__campo">
                <label for="abono" class="formulario__label">Abono:</label>
                <input type="number" id="abono" class="formulario__input" name="abono" value="">
            </div>

            <div class="modal-btnAcciones">
                <button class="modal-btn btn-yes" id="btnYesAgregar">Registrar</button>
                <button class="modal-btn btn-no" id="btnNoAgregar">Cancelar</button>
            </div>
        </div>
    </div>
</div>