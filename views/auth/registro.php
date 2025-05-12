<main class="auth">
    <h2 class="auth__heading">Crear Persona</h2>

    <p class="auth__subtitulo">Administrador para la creación de Clientes</p>

    <?php
    include_once __DIR__ . '/../templates/alertas.php';
    ?>

    <form action="/registro" class="formulario" method="POST">
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
            <input type="number" id="movil" class="formulario__input" name="movil" placeholder="Digita el número celular" value="<?php echo $persona->movil; ?>">
        </div>

        <div class="formulario__btnAcciones">
            <input type="submit" class="formulario__submit" value="Registrar">
            <a href="/administracion" class="formulario__cancelar">Cancelar</a>
        </div>
    </form>
</main>