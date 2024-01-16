<?php 
include_once('../.asetukset.php'); include_once('palautefunktiot.php');
echo '<script type="text/javascript" src="palaute/palautejs.js"></script>';

# TÄMÄ ON PALAUTETTAVA VERSIO, ÄLÄ MUOKKAA!

# Tämä tiedosto noutaa palautteet tietokannasta joko huonenumeron tai asiakasnumeron perusteella.
# Huoneversio on käytössä huonesivuilla ja asiakasversio asiakkaan omatietosivulla.
# Asiakkaalla mahdollisuus poistaa tai muokata palautetta (jälkimmäinen tapahtuu anna_palaute.php:n puolella).
# Virve Rajasärkkä 2023

    $kuka = $_GLOBALS['kuka'];
    
    # huonesivujen versio
    if ($kuka == 2) {
        
        $huonenro = $_GLOBALS['numero'];
        $sql = "SELECT * FROM hop_palaute WHERE huonenro = ? ORDER BY palautenro DESC;";
        $naytastmt = $pdo->prepare($sql);
        $naytastmt->execute([$huonenro]);
        $naytarivit = $naytastmt->fetchAll();
        $pdo->connection = null;

        # jos huone ei vielä ole saanut palautetta
        if ($naytarivit == null) {
            echo '<div class="palautelaatikko">Olisitko sinä ensimmäinen, joka jättää palautetta?</div>';
        }

        # jos on, tulostetaan ne (ei kuitenkaan ihan sivun täydeltä...)
        else {
            for ($i = 0; (($i < count($naytarivit)) && ($i < 8)); $i++) {
                tulostaPalaute($naytarivit[$i]);
            }
        }
    }
    

    # omatietosivun versio
    else if ($kuka == 1) {
        $asiakasnro = $_SESSION['asiakasnro'];
        
        # etsitään ko henkilön jättämät palautteet
        $sql = "SELECT palautenro, hop_palaute.varausnro, hop_palaute.huonenro, alkupv, loppupv, tahdet, viesti, nimim, pvm 
            FROM hop_palaute INNER JOIN hop_varaus ON hop_palaute.varausnro = hop_varaus.varausnro
            WHERE hop_palaute.asiakasnro = ? ORDER BY palautenro DESC;";
        $naytahenk = $pdo->prepare($sql);
        $naytahenk->execute([$asiakasnro]);
        $naytarivit = $naytahenk->fetchAll();
        $pdo->connection = null;

        # jos henkilö ei ole jättänyt palautetta
        if ($naytarivit == null) {
            echo '<p class="palautelaatikko">Et ole vielä jättänyt palautetta.</p>';
        }

        # muutoin tulostetaan palautteet
        else {
            foreach ($naytarivit as $rivi) {
                $aikavali = muotoileAikavali($rivi['alkupv'], $rivi['loppupv']);
                $info = 'Varaustunnus ' . $rivi['varausnro'] . '<br>' . $aikavali . ', huone ' . $rivi['huonenro'];
                echo '<div class="row"><div class="col-md-12">';
                echo $info;
                echo '</div></div>';
                echo '<div class="row"><div class="col-md-8">';
                    tulostaPalaute($rivi);
                echo '</div><div class="col-md-2">';
                    # muokkaa
                    echo '<form method="post" action="#palautelomake">';
                        echo '<input type="hidden" name="palnro" value="' . $rivi['palautenro'] . '">';
                        echo '<input type="submit" name="muokkaapal" value="&#9998" class="kyna">';
                    echo '</form>';
                echo '</div><div class="col-md-2">';
                    # poista
                    echo '<form class="poistapalaute" method="post">';
                        echo '<input type="hidden" name="palnro" value="' . $rivi['palautenro'] . '">';
                        echo '<input type="submit" name="poistapal" value="&#10006" class="roskis">';
                    echo '</form>';
                echo '</div></div>';
            }

            # muokataan palautetta
            if (isset($_POST['muokkaapal'])) {
                palauteaihio();
                $_SESSION['palaute']['palautenro'] = $_POST['palnro'];
            }

            # poistetaan palaute
            else if (isset($_POST['poistapal'])) {
                $palautenro = $_POST['palnro'];
                $sql = "DELETE FROM hop_palaute WHERE palautenro = ?;";
                $poista = $pdo->prepare($sql);
                $poista->execute([$palautenro]);
                $pdo->connection = null;
                
                echo '<script type="text/JavaScript">paivita();</script>';
                $paikka = ".palauteviesti"; $teksti = 'Palaute poistettu!'; $ok = true;
                $tyhjenna = true; $osoite = '#palauteosio';
                stoppi($paikka, $teksti, $ok, $tyhjenna, $osoite);
            }
        }
    }    
    
?>