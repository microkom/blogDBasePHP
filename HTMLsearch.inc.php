<?php


print '
<section class="search">
                <form action="principal.php" method="post">
                    <input type="search" name="datosBusqueda" placeholder="Búsqueda">
                    
                    <input id="allWords" type="radio" name="tipoBusqueda" value="all">
                    <label for="allWords">Todas las palabras</label>&emsp;
                    
                    <input id="someWords" type="radio" name="tipoBusqueda" value="any" checked>
                    <label for="someWords">Algunas de las palabras</label>&emsp;
                    
                    <input type="submit" name="buscar" value="Buscar">
                </form>
            </section>
';
?>