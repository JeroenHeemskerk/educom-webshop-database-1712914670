<?php

function get404Title() {
    return "Page Not Found";
}

function show404Content() {
    echo '<h2>Oh oh... 404 Fout: Pagina niet gevonden.</h2>';
    echo '<p class="content">Het lijkt erop dat mijn website verstoppertje speelt met deze pagina. Terwijl ik snel tot 404 tel, bekijk anders <a href="index.php?page=contact">de contactpagina</a>? Die is niet verstopt, beloofd!</p>';
}