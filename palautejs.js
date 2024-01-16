//näytetään piilotettu elementti
function avaaid(x) {
    document.getElementById(x).style.display = "block"; 
}

//piilotetaan elementti
function suljeid(x) { 
    document.getElementById(x).style.display = "none";
}

//vaihdetaan elementin sisältö
function vaihdaid(x, sisalto) {
    document.getElementById(x).innerHTML = sisalto;
}

function paivita() { 
    
}

//siivoustoimet lomakkeen lähettämisen jälkeen
function siivoa(osoite) {
    location.hash = osoite;
    location.href = location.href;
    window.history.replaceState(null, null, window.location.href );
}



