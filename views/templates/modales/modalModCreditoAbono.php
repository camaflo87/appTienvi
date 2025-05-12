<div id="myModalEli" class="modalEliminar">
    <div class="modalEliminar-content">
        <div class="modalEliminar-title">¿Estás seguro de realizar esta acción?</div>
        <div class="modalEliminar-options">
            <button class="modalEliminar-btn btn-yes" id="btnYes">SÍ</button>
            <button class="modalEliminar-btn btn-no" id="btnNo">NO</button>
        </div>
    </div>
</div>

<div id="myModalMod" class="modalModificar">
    <div class="modalModificar-content">
        <div class="modalModificar-title">Modificar Valor</div>

        <div class="modalModificar__consulta">
            <div class="modalModificar__campo">
                <label for="fecha" class="modalModificar__label">Fecha</label>
                <input type="text" id="fecha" class="modalModificar__input" name="fecha" value="" disabled>
            </div>

            <div class="modalModificar__campo">
                <label for="valor" class="modalModificar__label">Valor Actual</label>
                <input type="text" id="valor" class="modalModificar__input" name="valor" value="" disabled>
            </div>
        </div>

        <div class="modalModificar__campo">
            <label for="valorNuevo" class="modalModificar__label">Nuevo Valor</label>
            <input type="number" id="valorNuevo" class="modalModificar__input" name="valorNuevo" value="">
        </div>

        <div class="modalModificar-options">
            <button class="modalModificar-btn btn-yes" id="btnModificar">Modificar</button>
            <button class="modalModificar-btn btn-no" id="btnCancelar">Cancelar</button>
        </div>
    </div>
</div>