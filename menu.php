<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#!">Menu principal</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item <?=$opcion == null ? 'active' : '' ?>">
                <a class="nav-link" href="/index.php">Inicio</a>
            </li>
            <!-- 
                El operador ? es un operador que se traduce en un if else:
                if($opcion == 'Marcas')
                    'active'
                else{
                    ''
                }
                -->
            <li class="nav-item <?=$opcion == 'marcas' ? 'active' : '' ?>">
            
                <a class="nav-link" href="/index.php?opcion=marcas">Marcas</a>
            </li>

            <li class="nav-item <?=$opcion == 'rubros' ? 'active' : '' ?>">
                <a class="nav-link" href="/index.php?opcion=rubros">Rubros</a>
            </li>

            <li class="nav-item <?=$opcion == 'productos' ? 'active' : '' ?>">
                <a class="nav-link" href="/index.php?opcion=productos">Productos</a>
            </li>
        </ul>
    </div>
</nav>