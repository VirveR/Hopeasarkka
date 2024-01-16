<?php

    #luodaan session palauteaihio
    function palauteaihio() {
        $palaute = array();
        $palaute['asiakasnro'] = null;
        $palaute['huonenro'] = null;
        $palaute['varausnro'] = null;
        $palaute['tahdet'] = null;
        $palaute['viesti'] = '';
        $palaute['nimim'] = '';
        $_SESSION['palaute'] = $palaute;
    }

    #laitetaan palauteaihioon arvo
    function asetaarvo($kentta, $arvo) {
        $_SESSION['palaute'][$kentta] = $arvo;
    }

    #virheenkäsittely yms. viestit
    function stoppi($paikka, $teksti, $ok, $tyhjenna, $osoite) {
        echo '<script type="text/JavaScript">ilmoitus("' . $paikka . '", "' . $teksti . '", ' . $ok . ');</script>';
        if ($tyhjenna) {
            unset($_POST);
            echo '<script type="text/JavaScript">siivoa("' . $osoite . '");</script>';
        }
    }

    #perustulostusfunktio palautteille
    function tulostaPalaute($palaute) {
        echo '<div class="palautelaatikko">';
            for ($i = 0; $i < $palaute['tahdet']; $i++) { echo ' <b>&#11088</b> '; } echo '<br>';
            if ($palaute['viesti'] != null) { echo '<blockquote>"' . $palaute['viesti'] . '"</blockquote><br>'; }
            if ($palaute['nimim'] == null) { echo '-anonyymi, '; }
            else { echo '-' . $palaute['nimim'] . ', '; } echo $palaute['pvm'];
        echo '</div><br>';
    }

    #muotoilee tietokannasta tulevat päivämäärät nätimmäksi
    function muotoileAikavali($alkupv, $loppupv) {
        $aikavali = '';
        $pv1 = date_parse_from_format('Y-m-d', $alkupv)['day'] . '.';
        $pv2 = date_parse_from_format('Y-m-d', $loppupv)['day'] . '.';
        $kk1 = date_parse_from_format('Y-m-d', $alkupv)['month'] . '.';
        $kk2 = date_parse_from_format('Y-m-d', $loppupv)['month'] . '.';
        $vu1 = date_parse_from_format('Y-m-d', $alkupv)['year'];
        $vu2 = date_parse_from_format('Y-m-d', $loppupv)['year'];
        $aikavali = $pv1;
        if (!($kk1 == $kk2)) {
            $aikavali .= $kk1;
            if (!($vu1 == $vu2)) {
                $aikavali .= $vu1;
            }
        }
        $aikavali .= '-' . $pv2 . $kk2 . $vu2;

        return $aikavali;
    }

?>