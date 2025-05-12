<main class="modificar">
    <h2 class="modificar__heading">Información de usuarios</h2>

    <p class="modificar__subtitulo">Administrador para la consulta y actualización de usuarios</p>

    <?php
    include_once __DIR__ . '/../templates/alertas.php';
    ?>

    <form action="/modificar" class="formulario" method="POST" id="modificar">
        <input type="hidden" name="id" id="id_persona" value="<?php echo $persona->id; ?>">
        <div class="formulario__campo">
            <label for="nombre" class="formulario__label">Nombre:</label>
            <input type="text" id="nombre" class="formulario__input" name="nombre" placeholder="Digita el nombre" value="<?php echo $persona->nombre; ?>">
        </div>

        <div class="formulario__campo">
            <label for="apellido" class="formulario__label">Apellido:</label>
            <input type="text" id="apellido" class="formulario__input" name="apellido" placeholder="Digita el apellido" value="<?php echo $persona->apellido; ?>">
        </div>

        <div class="formulario__campo">
            <label for="email" class="formulario__label">Email:</label>
            <input type="email" id="email" class="formulario__input" name="email" placeholder="Digita el email" value="<?php echo $persona->email; ?>">
        </div>

        <div class="formulario__campo">
            <label for="movil" class="formulario__label">Celular:</label>
            <input type="number" id="movil" class="formulario__input" name="movil" placeholder="Digita el número celular" value="<?php echo (int)$persona->movil; ?>">
        </div>

        <fieldset id="selectorPerfil">
            <legend>Perfil del usuario</legend>
            <div class="formulario__perfil">
                <label for="perfil">Selecciona un perfil:</label>
                <select id="perfil" name="perfil">
                    <option value="0" <?php echo ($usuario->perfil === "0") ? 'selected' : ''; ?>>Persona</option>
                    <option value="1" <?php echo ($usuario->perfil === "1") ? 'selected' : ''; ?>>Usuario</option>
                    <option value="2" <?php echo ($usuario->perfil === "2") ? 'selected' : ''; ?>>Administrador</option>
                </select>
            </div>
        </fieldset>

        <fieldset id="selectorContraseña">
            <legend>Asignar contraseña</legend>
            <div class="formulario__password">
                <div class="formulario__campo">
                    <label for="password" class="formulario__label">Password:</label>
                    <input type="password" id="password" class="formulario__input" name="password" placeholder="<?php echo ($usuario->perfil === "0") ? 'Campo Bloqueado' : 'Digita tu password' ?>" <?php echo ($usuario->perfil === "0") ? 'disabled' : ''; ?> value="">
                </div>

                <div class="formulario__campo">
                    <label for="password_respaldo" class="formulario__label">Confirma Password:</label>
                    <input type="password" id="password_respaldo" class="formulario__input" name="password_respaldo" placeholder="<?php echo ($usuario->perfil === "0") ? 'Campo Bloqueado' : 'Repite tu password' ?>" <?php echo ($usuario->perfil === "0") ? 'disabled' : ''; ?> value="">
                </div>

            </div>
        </fieldset>

        <div class="formulario__btnAcciones" id="btnModAcciones">
            <input type="submit" class="formulario__submit" value="Modificar">
            <div class="formulario__eliminar" id="btnEliminar_user">Eliminar</div>
            <a href="/administracion_consultas?cat=personas" class="formulario__cancelar">Cancelar</a>
        </div>
    </form>
</main>

<?php include __DIR__ . '/../templates/modales/modalEliminarUser.php'; ?>