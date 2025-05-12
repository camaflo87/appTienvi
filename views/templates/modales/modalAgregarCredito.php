<div id="myAgregarCreditos" class="modal">
    <div class="modal-content">
        <div class="modal-title">Agregar Credito</div>
        <div class="modal-options--agregarCredito">

            <div class="formulario__campo">
                <label for="nombre" class="formulario__label">Nombre:</label>
                <input type="text" id="nombre" class="formulario__input modal--nombre" name="nombre" value="" disabled>
            </div>

            <div class="formulario__campo">
                <label for="fecha" class="formulario__label">Fecha:</label>
                <input type="date" id="fecha" class="formulario__input" name="fecha" min="<?php echo ($_SESSION['perfil'] === '2') ? '' : date('Y-m-d'); ?>" value="">
            </div>

            <div class="formulario__campo">
                <label for="valor" class="formulario__label">Valor:</label>
                <input type="number" id="valor" class="formulario__input" name="valor" value="">
            </div>

            <div class="modal-btnAcciones">
                <button class="modal-btn btn-yes" id="btnYes">Registrar</button>
                <button class="modal-btn btn-no" id="btnNo">Cancelar</button>
            </div>
        </div>
    </div>
</div>