<?php 

function getHomeTitle() {
    return "Home";
}

function showHomeContent() {
    echo '
    <h2>Welkom</h2>
    <p class="content">Van harte welkom op Florian&apos;s Rariteitenkabinet! Op deze website toon ik alle snuisterijen die ik heb gevonden, gemaakt of gekregen. <a href="index.php?page="shop">Voor schappelijke prijzen </a> stuur ik ze naar je op. <a href="index.php?page=register">Registreer je</a> en <a href="index.php?page=login">login</a> om een bestelling te plaatsen! Je mening delen? Ga naar <a target="_blank" href="index.php?page=contact">onze contactpagina</a> om je formulier in te vullen!</p>'; 
}
