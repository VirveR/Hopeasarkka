<?php   $title = 'Muokkaa varausta | Hotelli Hopeasärkkä';       //html - Title
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
if (isset($_POST['id'])){
    $palnro = filter_var($_POST['id'],FILTER_SANITIZE_SPECIAL_CHARS);
}
elseif (isset($_GET['id'])){
    $palnro = filter_var($_GET['id'],FILTER_SANITIZE_SPECIAL_CHARS);
}         

$sql = "SELECT * FROM hop_palaute WHERE palautenro = ? LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute([$palnro]);
$rivi = $stmt->fetch();
$pdo->connection = null;

?>

<div class="row justify-content-center">
    <h1>Palautearkisto</h1>
    <article class="col-md-10 keskitys">
        <form class="row" action="" method="post">
            <div class="col-md-12 keskitys">
                <br>
                <div class="container row table-responsive">
                    <div class="col-md-1"></div>
                    <table class="col-md-10 admin">
                            <tr>
                                    <th> Palautenumero  </th>
                                    <th> Asiakas       </th>
                                    <th> Huone       </th>
                                    <th> Pvm        </th>
                                    <th> Tähdet     </th>
                                    <th> Viesti       </th>
                                    <th> Nimi/merkki         </th>
                                    <th> Muokkaa       </th>
                                    <th> Poista         </th>

                            </tr>
                    <?php

                    echo    '<tr>
                                    <td><input class="varauksenMuokkaus" style="text" value="'.$rivi['palautenro'].'" name="palautenro"></td>
                                    <td><input class="varauksenMuokkaus" style="text" value="'.$rivi['asiakasnro'].'" name="asiakasnro"></td>
                                    <td><input class="varauksenMuokkaus" style="text" value="'.$rivi['huonenro'].'" name="huonenro"></td>
                                    <td><input class="varauksenMuokkaus" style="text" value="'.$rivi['pvm'].'" name="pvm"></td>
                                    <td><input class="varauksenMuokkaus" style="text" value="'.$rivi['tahdet'].'" name="tahdet"></td>    
                                    <td><input class="varauksenMuokkaus" style="text" value="'.$rivi['viesti'].'" name="viesti"></td>  
                                    <td><input class="varauksenMuokkaus" style="text" value="'.$rivi['nimim'].'" name="nimim"></td>                                     
                                    <form class="keskita" action="" method="post">
                                    <td>
                                        <input type="hidden" name="id" value="'.$rivi['palautenro'].'">
                                        <input type="submit" name="tallenna" value="Tallenna">
                                    </td>
                                    <td>
                                        <input type="hidden" name="id" value="'.$rivi['palautenro'].'">
                                        <input type="submit" name="poista" value="Poista palaute">
                                    </td>
                                    </form>                        
                                    </td>
                            </tr>
                    </table>
                    <div class="col-md-1"></div>
                </div>
                <div><a href="admin_hae_palautteita.php" class="btn btn-secondary" style="margin-top:30px; margin-bottom:10px; color:white;">Takaisin</a></div>
            </div>
        </form>
    </article>
</div>';

if (isset($_POST['tallenna'])){
    $palautenro = trim(filter_var($_POST['palautenro'],FILTER_SANITIZE_SPECIAL_CHARS));
    $asiakasnro = trim(filter_var($_POST['asiakasnro'],FILTER_SANITIZE_SPECIAL_CHARS));
    $huonenro = trim(filter_var($_POST['huonenro'],FILTER_SANITIZE_SPECIAL_CHARS));
    $tahdet = trim(filter_var($_POST['tahdet'],FILTER_SANITIZE_SPECIAL_CHARS));
    $viesti = trim(filter_var($_POST['viesti'],FILTER_SANITIZE_SPECIAL_CHARS));
    $nimim = trim(filter_var($_POST['nimim'],FILTER_SANITIZE_SPECIAL_CHARS));
    $pvm = trim(filter_var($_POST['pvm'],FILTER_SANITIZE_SPECIAL_CHARS));
    $sql = "UPDATE hop_palaute
            SET palautenro = ?, asiakasnro = ?, huonenro = ?, tahdet = ?, viesti = ?, nimim = ?, pvm = ?
            WHERE palautenro = $palautenro";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$palautenro, $asiakasnro, $huonenro, $tahdet, $viesti, $nimim, $pvm]);
    $pdo->connection = null;
    ?>
        <script>
            var idNumero = <?php echo json_encode($palautenro); ?>;
            window.location.href = "muokkaa_palautetta.php?id=" + idNumero;
        </script>
    <?php
}
if (isset($_POST['poista'])){
    echo'
        <div class="virheTietojenMuokkaamisessa">
            <form action="" method="post">
                <p>Haluatko varmasti poistaa palautteen: '.$rivi['palautenro'].'</p>
                <input type="hidden" name="poistettavannro" value="'.$rivi['palautenro'].'">
                <input type="hidden" name="id" value="'.$rivi['palautenro'].'">
                <p><input type="submit" name="poistapalaute" value="Kyllä"> <input type="submit" name="poistu" value="Peruuta"></p>
            </form>
        </div>';
}

if (isset($_POST['poistapalaute'])){
    $poistettava = filter_var($_POST['poistettavannro'],FILTER_SANITIZE_SPECIAL_CHARS);
    $sql = "DELETE FROM hop_palaute
            WHERE palautenro = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$poistettava]);
    $pdo->connection = null;
    ?>
    <script>
        window.location.href = "admin_hae_palautteita.php";
    </script>
    <?php
}
elseif (isset($_POST['poistu'])){
    ?>
    <script>
        var idNumero = <?php echo json_encode($palautenro); ?>;
        window.location.href = "muokkaa_palautetta.php?id=" + idNumero;
    </script>
    <?php
}

include('../phpinclude/footer.php'); ?>