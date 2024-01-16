<?php   $title = 'Hae palautteita | Hotelli Hopeasärkkä';       //html - Title
        include('admin_head.php');                     //html - Head 
        include('admin_header.php');                   //html - Header ja nav
        $_GLOBALS['kuka'] = 3; //1=asiakas 2=huone 3=admin
        if (!$_SESSION['admin_kirjautunut']){
            echo'
            <script>
                window.location.href = "index.php" 
            </script>'; //Jos käyttäjä ei ole kirjautunut, tälle sivulle ei pääse
        }
        include_once('../../.asetukset.php');

        if (isset($_GET['viesti'])){
            echo    '<p class="uloskirjautuminen">'.$_GET['viesti'].'</p>';
        }
        elseif (isset($_GET['virhe'])){
            echo    '<p class="virheTietojenMuokkaamisessa">'.$_GET['virhe'].'</p>';
        }
?>

<!-- Tämä sivu on osa työntekijöille tarkoitettua kokonaisuutta. Sillä pystyy hakemaan palautteita valittujen rajausten perusteella
        Antin tekemältä sivupohjalta muokkasi Virve, php-osan teki Virve. -->


<!------- Sivun sisältö ------->
    <div class="container justify-content-center">
        <h1>Palautearkisto</h1>
        <article class="row container justify-content-center" style="border:1px solid black; padding:20px; margin:10px;">
            <div class="col-lg-4">
                <p><b>Valitse haluamasi rajaukset ja paina Hae. Ei rajauksia näyttää kaikki palautteet.</b></p>
                <form action="" method="post">
                    <p><input type="submit" name="hae" value="Hae"></p>
            </div>
            <div class="col-lg-5">
                    <table>
                        <tr><td style="padding: 7px;">Alkaen pvm:</td><td><input type="date" name="pvalku"></td></tr>
                        <tr><td style="padding: 7px;">Päättyen pvm:</td><td><input type="date" name="pvloppu"></td></tr>
                        <tr><td style="padding: 7px;">Huoneennumero:</td><td><input type="text" name="huonenro"></td></tr>
                        <tr><td style="padding: 7px;">Asiakasnumero:</td><td><input type="text" name="asiakasnro"></td></tr>
                        <tr><td style="padding: 7px;">Hakusana:</td><td><input type="text" name="sanahaku"></td></tr>
                    </table>
            </div>
            <div class="col-lg-3">
                    <input type="checkbox" name="tahti5" value ="5">
                    <label for="haetahti5"><b>&#11088 &#11088 &#11088 &#11088 &#11088</b></label><br>
                    <input type="checkbox" name="tahti4" value ="4">                        
                    <label for="haetahti4"><b>&#11088 &#11088 &#11088 &#11088</b></label><br>
                    <input type="checkbox" name="tahti3" value ="3">
                    <label for="haetahti3"><b>&#11088 &#11088 &#11088</b></label><br>
                    <input type="checkbox" name="tahti2" value ="2">
                    <label for="haetahti2"><b>&#11088 &#11088</b></label><br>
                    <input type="checkbox" name="tahti1" value ="1">
                    <label for="haetahti1"><b>&#11088</b></label><br>
                </form>
            </div>
        </article>
        <div class="row container justify-content-center" style="padding:20px; margin:5px;">

            <?php

                if (isset($_POST['hae'])) {
                    
                    #aletaan rakentamaan prepared statementiä
                    $rajaukset = array();
                    $sqlpalat = array();
                    $r = 0; $s = 0;

                    if (!(empty($_POST['pvalku']))) {
                        $rajaukset[$r] = trim(filter_var($_POST['pvalku'],FILTER_SANITIZE_SPECIAL_CHARS));
                        $sqlpalat[$s] = '(pvm >= ?)';
                        $r++; $s++;
                    }
                    if (!(empty($_POST['pvloppu']))) {
                        $rajaukset[$r] = trim(filter_var($_POST['pvloppu'],FILTER_SANITIZE_SPECIAL_CHARS));
                        $sqlpalat[$s] = '(pvm <= ?)';
                        $r++; $s++;
                    }

                    #huone- ja asiakasnumerorajaukset
                    if (!(empty($_POST['huonenro']))) {
                        $rajaukset[$r] = trim(filter_var($_POST['huonenro'],FILTER_SANITIZE_SPECIAL_CHARS));
                        $sqlpalat[$s] = '(huonenro = ?)';
                        $r++; $s++;
                    }
                    if (!(empty($_POST['asiakasnro']))) {
                        $rajaukset[$r] = trim(filter_var($_POST['asiakasnro'],FILTER_SANITIZE_SPECIAL_CHARS));
                        $sqlpalat[$s] = '(asiakasnro = ?)';
                        $r++; $s++;
                    } 

                    #sanahaku
                    if (!(empty($_POST['sanahaku']))) {
                        $rajaukset[$r] = '%' . trim(filter_var($_POST['sanahaku'],FILTER_SANITIZE_SPECIAL_CHARS)) . '%';
                        $sqlpalat[$s] = '(viesti LIKE ?)';
                        $r++; $s++;
                    } 

                    #tähtirajaukset
                    $tahtijoukko = array();
                    $j = 0;
                    for ($i = 5; $i > 0; $i--) {                            
                        if (isset($_POST['tahti' . $i])) {
                            $tahtijoukko[$j] = trim(filter_var($_POST['tahti' . $i],FILTER_SANITIZE_SPECIAL_CHARS));
                            $j++;
                        }
                    }
                    if (count($tahtijoukko) > 0) {
                        $sqlpalat[$s] = '(tahdet IN (';
                        for ($i = 0; $i < count($tahtijoukko); $i++) {
                            $rajaukset[$r] = $tahtijoukko[$i];
                            $r++;
                            $sqlpalat[$s] .= '?, ';
                        }
                        $sqlpalat[$s] .= '10))';
                    }
                
                    #kootaan prepared statement
                    $sql = "SELECT palautenro, varausnro, asiakasnro, huonenro, tahdet, viesti, nimim, pvm 
                    FROM hop_palaute WHERE "; 

                    for ($i = 0; $i < count($sqlpalat); $i++) {
                        $sql .= $sqlpalat[$i] . ' AND ';
                    }

                    $sql .= '1 = 1 ORDER BY palautenro DESC;';

                    #echo $sql;

                    #suoritetaan haku
                    $henk = $pdo->prepare($sql);
                    $henk->execute($rajaukset);
                    $rivit = $henk->fetchAll();
                    $pdo->connection = null;

                    include('tulostaPalautteet.php');
                }

            ?>

        </div>
    </div>

<?php include('../phpinclude/footer.php'); ?>