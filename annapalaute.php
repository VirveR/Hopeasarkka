<?php include_once('../.asetukset.php'); include_once('palautefunktiot.php');
echo '<script type="text/javascript" src="palaute/palautejs.js"></script>';

# TÄMÄ ON PALAUTETTAVA VERSIO, ÄLÄ MUOKKAA!

# Tämä tiedosto ottaa vastaan uuden tai muokatun palautteen,
# tarkistaa saadut tiedot ja tallentaa palautteen tietokantaan
# Virve Rajasärkkä 2023

    $kuka = $_GLOBALS['kuka'];
    $jatka = false;
    echo '<span class="naytapalaute"></span>';

    # jos tullaan huonesivulta, ruvetaan tekemään uutta palautetta
    if ($kuka == 2) {
        echo '<div id="palalku"><form method="post" action="#palautelomake">
            <label>Varaustunnus*</label><br>
            <input type="text" name="varausnro" id="varausnro"><br>
            <input type="submit" name="avaapal" value="Etsi" class="varausnappi">
        </form></div>';

        # etsitään saatu varaustunnus
        if (isset($_POST['avaapal'])) {
            if ($_POST['varausnro'] == null) {
                $paikka = ".palauteviesti"; $teksti = 'Anna varaustunnus!'; $ok = false;
                $tyhjenna = true; $osoite = '#palauteosio';
                stoppi($paikka, $teksti, $ok, $tyhjenna, $osoite);
            }
            else {
                $varausnro = trim($_POST['varausnro']); 
                $varausnro = filter_var($varausnro, FILTER_SANITIZE_STRING);
                $_GLOBALS['palaute']['varausnro'] = $varausnro;
                $sql = "SELECT * FROM hop_varaus WHERE varausnro = ?;";
                $etsivar = $pdo->prepare($sql);
                $etsivar->execute([$varausnro]);
                $rivi = $etsivar->fetch();
                $pdo->connection = null;
        
                # jos varausta ei löydy, virhe
                if ($rivi == null) {
                    $paikka = ".palauteviesti"; $teksti = 'Varausta ei löydy!'; $ok = false;
                    $tyhjenna = true; $osoite = '#palauteosio';
                    stoppi($paikka, $teksti, $ok, $tyhjenna, $osoite);
                }

                # jos varaus on olemassa, tutkitaan, joko siitä on palaute
                else {
                    $sql = "SELECT * FROM hop_palaute WHERE varausnro = ?;";
                    $etsipal = $pdo->prepare($sql);
                    $etsipal->execute([$varausnro]);
                    $rivi2 = $etsipal->fetchAll();
                    $pdo->connection = null;

                    # jos varauksella on jo palaute, ei anneta antaa uutta
                    if (count($rivi2) > 0) {
                        $paikka = ".palauteviesti"; $teksti = 'Olet jo antanut palautetta tästä varauksesta!'; $ok = false;
                        $tyhjenna = true; $osoite = '#palauteosio';
                        stoppi($paikka, $teksti, $ok, $tyhjenna, $osoite);
                    }

                    # jos kaikki on kunnossa, jatketaan
                    else {
                        $jatka = true;
                        unset($_POST);
                        palauteaihio();
                        $_SESSION['palaute']['varausnro'] = $rivi['varausnro'];
                        $_SESSION['palaute']['asiakasnro'] = $rivi['asiakasnro'];
                        $_SESSION['palaute']['huonenro'] = $rivi['huonenro'];
                        $aikavali = muotoileAikavali($rivi['alkupv'], $rivi['loppupv']);
                        $uusipalp = 'Varaustunnus ' . $varausnro . ', huone ' . $rivi['huonenro'] . '<br>' . $aikavali;
                        echo '<script type="text/JavaScript"> suljeid("palalku");</script>';
                        echo '<script>vaihdaid("palp", "' . $uusipalp . '"); </script>';
                    }
                }
            }
        }
    }

    # omatietosivulta tullaan olemassaolevan palautteen kanssa
    else if ($kuka == 1) {
        if (isset($_POST['muokkaapal'])) {
            $jatka = true;
            unset($_POST);
            $palautenro = $_SESSION['palaute']['palautenro'];

            $sql = "SELECT tahdet, viesti, nimim FROM hop_palaute WHERE palautenro = ?;";
            $etsipal = $pdo->prepare($sql);
            $etsipal->execute([$palautenro]);
            $rivi = $etsipal->fetch();
            $pdo->connection = null;

            $_SESSION['palaute']['tahdet'] = $rivi['tahdet'];
            $_SESSION['palaute']['viesti'] = $rivi['viesti'];
            $_SESSION['palaute']['nimim'] = $rivi['nimim'];
        }
    }

    if ($jatka) {
        $tahdet = $_SESSION['palaute']['tahdet'];
        $viesti = $_SESSION['palaute']['viesti'];
        $nimim = $_SESSION['palaute']['nimim'];

        # tulostetaan palautelomake
        echo '<form method="post" id="palautelomake">
            <div class="tahdet">';
                for ($i = 1; $i < 6; $i++) {
                    if ((isset($tahdet)) && ($i <= $tahdet)) {
                            echo '<input class="tahti" type="checkbox" id="tahti' . $i . '" name="tahti' . $i . '" checked>';
                            echo '<label class="tahti" for="tahti' . $i . '"></label>';
                    }
                    else {
                        echo '<input class="tahti" type="checkbox" id="tahti' . $i . '" name="tahti' . $i . '">';
                        echo '<label class="tahti" for="tahti' . $i . '"></label>';
                    }
                }
            echo '</div>';
            echo '<label>Viesti:</label><br>';
            echo '<textarea name="viesti">' . $viesti . '</textarea><br>';
            echo '<label>Nimi tai nimimerkki:</label><br>';
            echo '<input type="text" name="nimim" value="' . $nimim . '"><br>';
            echo '<input type="submit" name="lisaapal" value="Lähetä" class="varausnappi">     ';
            echo '<input type="submit" name="peruutapal" value="Peruuta" class="varausnappi">';
        echo '</form>';
    }
    
        if (isset($_POST['lisaapal'])) {
            # lasketaan tähdet
            $tahdet = 0;
            for ($i = 1; $i < 6; $i++) {                
                $tahti = 'tahti' . $i;
                if (isset($_POST[$tahti])) {
                    $tahdet++;
                }
            }

            if ($tahdet == 0) {
                $paikka = ".palauteviesti"; $teksti = 'Anna edes yksi tähti...'; $ok = false;
                $tyhjenna = true; $osoite = '#palauteosio';
                stoppi($paikka, $teksti, $ok, $tyhjenna, $osoite);
            }

            #jos pakolliset tiedot kunnossa, jatketaan
            else {
                #viesti ei ole pakollinen kenttä
                #sanitoidaan viesti
                if (isset($_POST['viesti'])) { 
                    $viesti = trim($_POST['viesti']); 
                    $viesti = filter_var($viesti, FILTER_SANITIZE_STRING);
                }
                else {
                    $viesti = null;
                }

                #nimi/merkki ei myöskään ole pakollinen kenttä
                #sanitoidaan nimi/merkki
                if (isset($_POST['nimim'])) {
                    $nimim = trim($_POST['nimim']);
                    $nimim = filter_var($nimim, FILTER_SANITIZE_STRING);
                }
                else {
                    $nimim = null;
                }

                #päivämäärä tulee automaattisesti
                $pvm = date('y-m-d');
                    
                #jos kaikki oli kunnossa, tallennetaan palaute tietokantaan
                #huonesivulta tullessa uutena
                if ($kuka == 2) {
                    $varausnro = $_SESSION['palaute']['varausnro'];
                    $asiakasnro = $_SESSION['palaute']['asiakasnro'];
                    $huonenro = $_SESSION['palaute']['huonenro'];
                    $sql = "INSERT INTO hop_palaute (varausnro, asiakasnro, huonenro, tahdet, viesti, nimim, pvm) VALUES (?, ?, ?, ?, ?, ?, ?);";
                    $lisaa = $pdo->prepare($sql);
                    $lisaa->execute([$varausnro, $asiakasnro, $huonenro, $tahdet, $viesti, $nimim, $pvm]);
                    $pdo->connection = null;
                    
                    $paikka = ".ilmoitusikkuna"; $teksti = 'Kiitos palautteesta!'; $ok = true; 
                    $tyhjenna = true; $osoite = '';
                    stoppi($paikka, $teksti, $ok, $tyhjenna, $osoite);         
                }

                #asiakkaan omatietosivulta tullessa muokattuna
                else if ($kuka == 1) {
                    $palautenro = $_SESSION['palaute']['palautenro'];
                    $sql = "UPDATE hop_palaute SET tahdet = ?, viesti = ?, nimim = ?, pvm = ? WHERE palautenro = ?;";
                    $muokkaa = $pdo->prepare($sql);
                    $muokkaa->execute([$tahdet, $viesti, $nimim, $pvm, $palautenro]);
                    $pdo->connection = null;

                    echo '<script type="text/JavaScript">paivita();</script>';
                    $paikka = ".palauteviesti"; $teksti = 'Palaute muokattu!'; $ok = true; $tyhjenna = true; $osoite = '#palauteosio';
                    stoppi($paikka, $teksti, $ok, $tyhjenna, $osoite); 
                }
            }
        }
        #jos peruutetaan, sitten peruutetaan
        else if (isset($_POST['peruutapal'])) {
            unset($_POST);
            unset($_SESSION['palaute']);
            $paikka = ''; $teksti = ''; $ok = true; $tyhjenna = true; $osoite = '';
            stoppi($paikka, $teksti, $ok, $tyhjenna, $osoite); 
        }
?>