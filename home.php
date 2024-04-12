<?php 

function getHomeTitle() {
    return "Home";
}

function showHomeContent() {
    echo '
    <h2>Welkom</h2>
    <p class="content">Van harte welkom op de formulierensite! Op deze website kun je een formulier invullen. Ga naar <a target="_blank" href="index.php?page=contact">onze contactpagina</a> om je formulier in te vullen!</p>

    <p class="content"><strong>Meer features zijn onderweg!</strong> Maar voor nu, geniet van de <b>rust</b> op deze website. </p>'; 
}
