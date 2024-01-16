<?php   $title = 'Kahden hengen huone | Hotelli Hopeasärkkä';       //html - Title
        include('phpinclude/head.php');                             //html - Head 
        include('phpinclude/header.php');                           //html - Header ja nav
        $_GLOBALS['kuka'] = 2; //1=asiakas 2=huone 3=admin
        $_GLOBALS['numero'] = 2; //huonenro
?>


    <!------- Sivun sisältö ------->
        <div class="container justify-content-center">
            <!-- rivi 1 --> 
            <h1>Kahden hengen huone</h1>
            <div class="row container-fluid justify-content-center">
                <!-- sarake 1.1 -->
                <article class="col-lg-6 osio-oik">
                    <h2 class="osio">Hemmottele puolisoasi!</h2>
                    <p>Loremeaipsum dolor sit amet, consectetur adipiscing elit. Curabitur elementum massa vitae tortor sagittis ultricies.                            
                        Phasellus pellentesque sem arcu, sed gravida nunc egestas et. Ut sagittis quam at hendrerit laoreet. Ut sagittis quam at hendrerit laoreet. 
                        Fusce iaculis laoreet lacus. Ut sagittis quam at hendrerit laoreet. Fusce iaculis laoreet lacus.
                    </p>
                    <h3 class="osio">Huoneesta löydät:</h3>
                    <ul class="osio">
                        <li>Ilmainen wifi</li>
                        <li>Hiustenkuivaaja</li>
                        <li>Baarikaappi</li>
                        <li>Vedenkeitin</li>
                    </ul>           
                </article>

                <!-- sarake 1.2 -->
                <aside class="col-lg-6">
                    <img class="img-fluid sisaltokuva" src="kuvat/kaksio.jpeg" alt="huoneen sisätila, missä harmaapäätyinen parisänky valkoisilla lakanoilla, tapeteissa on kasvikuvioita, sängyn edessä pieni vaalea sohva, jolla tyynyjä, kaksi apupöytää ja senkki, jonka päällä on muutamita tavaroita">
                </aside>
            </div>

            <!-- rivi 2 -->
            <div class="row container-fluid justify-content-center">

                <!-- sarake 2.1 -->
                <div class="col-lg-8">

                    <!-- rivi 2.1.1 -->
                    <article class="row osio-oik" id="kalenterin-ankkuri">
                        <h2 class="osio">Varaa huone tästä:</h2>

                        <!-- sarake 2.1.1.1 -->
                        <div class="col-md-6">
                            <?php
                                include('phpinclude/varauslomake.php');                                    
                            ?>
                        </div>

                        <!-- sarake 2.1.1.2 -->
                        <div class="col-md-6">
                            <p class="kalenterinHaku"></p>
                        </div>
                    </article>

                    <!-- rivi 2.1.2 -->
                    <article class="row osio-oik">
                        <h2 class="osio">Yövyitkö meillä?</h2>
                        <p id="palp">Anna varaustunnus, niin pääset antamaan palautetta!</p>
                        <p class="palauteviesti"></p>
                        <div id="palauteosio" class="col-md-12">
                            <?php
                                include('palaute/annapalaute.php');
                            ?>
                        </div>
                    </article>
                </div>

                <!-- sarake 2.2 -->
                <div class="col-lg-4">
                    <aside id="palautteetHuone">
                        <?php  
                            include('palaute/naytapalaute.php');
                        ?>
                    </aside>
                </div>
            </div> 
        </div>

        <!--Varauskalenterin haku ja päivitys kuukautta vaihtaessa-->
        <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
        <script type="text/javascript">var huoneNro = "<?= $_GLOBALS['numero'] ?>";</script>
        <script type="text/javascript" src="phpinclude/kalenteri.js"></script>     

    <!------- Footer ------->
<?php   include('phpinclude/footer.php'); ?>