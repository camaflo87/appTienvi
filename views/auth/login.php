<?php include __DIR__ . "/../templates/header.php"; ?>
<?php include __DIR__ . "/../templates/alertas.php"; ?>

<main class="login">
    <h2 class="login__heading">Login Administrador</h2>

    <p class="login__subtitulo">Administrador de Clientes y Creditos</p>

    <form action="/login" class="formulario" method="POST">
        <div class="formulario__campo">
            <label for="email" class="formulario__label">Email:</label>
            <input type="email" id="email" class="formulario__input" name="email" placeholder="Digita tu email" value="<?php echo $login->email; ?>">
        </div>

        <div class="formulario__campo">
            <label for="password" class="formulario__label">Password:</label>
            <input type="password" id="password" class="formulario__input" name="password" placeholder="Digita tu password">
        </div>

        <input type="submit" class="formulario__submit" value="Iniciar Sessión">
    </form>
</main>