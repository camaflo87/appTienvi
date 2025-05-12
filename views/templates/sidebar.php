<aside class="sidebar">
    <nav class="sidebar__nav">
        <a href="/administracion" class="<?php echo ($link === 'admin') ? 'admin__activo' : ''; ?>">Administrador</a>
        <a href="/registro">Nuevo Cliente</a>
        <?php
        if ($_SESSION['perfil'] === '2') { ?>
            <a href="/administracion_consultas?cat=personas" class="<?php echo ($link === 'persona') ? 'admin__activo' : ''; ?>">Consultar Cliente</a>
            <a href="/administracion_consultas?cat=bloquear" class="<?php echo ($link === 'userBloq') ? 'admin__activo' : ''; ?>">Adm. Bloqueos</a>
            <a href="/administracion_consultas?cat=creditos" class="<?php echo ($link === 'credito') ? 'admin__activo' : ''; ?>">Consultar Credito</a>
            <a href="/administracion_consultas?cat=consultaPago" class="<?php echo ($link === 'cstPago') ? 'admin__activo' : ''; ?>">Consultar Pagos</a>
            <a href="/creditos_informes" class="<?php echo ($link === 'informe') ? 'admin__activo' : ''; ?>">Informe Creditos</a><?php }
                                                                                                                                    ?>
        <a href="/administracion_consultas?cat=agrCredito" class="<?php echo ($link === 'agrcredito') ? 'admin__activo' : ''; ?>">Agregar Credito</a>
        <a href="/administracion_consultas?cat=regPago" class="<?php echo ($link === 'agrPago') ? 'admin__activo' : ''; ?>">Agregar Pago</a>
    </nav>
</aside>