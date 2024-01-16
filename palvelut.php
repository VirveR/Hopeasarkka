<?php   $title = 'Palvelut | Hotelli Hopeasärkkä'; //html - Title
        include('phpinclude/head.php');            //html - Head 
        include('phpinclude/header.php'); ?>    <!-- html - Header ja nav -->


    <!------- Sivun sisältö ------->
            
            <section class="row justify-content-center">
                <h1>Enemmän irti lomastasi</h1>
                <article class="col-md-10">
                    <p>Loremeaipsum dolor sit amet, consectetur adipiscing elit. Curabitur elementum 
                        massa vitae tortor sagittis ultricies. Phasellus pellentesque sem arcu, sed 
                        gravida nunc egestas et. Ut sagittis quam at hendrerit laoreet. Lorem ipsum dolor, sit amet 
                        consectetur adipisicing elit. Pariatur nobis tempora a debitis sapiente architecto dignissimos.
                    </p>
                </article>
            </section>
        
            <section class="row justify-content-center">
                <h2>Aloita päiväsi herkuttelemalla</h2>
                <article class="col-md-5">
                    <p>Pellentesque vel ligula dignissim, rutrum augue ac, porta enim. Ut semper fringilla 
                        neque, quis commodo purus mollis at. Nulla tincidunt ac ligula vel condimentum. 
                        Vestibulum metus odio, tincidunt a mauris ut, luctus tempus ligula. Aenean pulvinar 
                        consectetur urna, sit amet tristique libero dapibus in.
                    </p>
                </article>
                    
                <aside class="col-md-5">
                    <img class="img-fluid sisaltokuva" src="kuvat/aamupala.jpg" alt="ylhäältä kuvattu vaalea puinen pöytä, jolla on erikokoisia pyöreitä lautasia, joilla mm.leipää, leikkeleitä, muroja ja leivonnaisia">
                </aside>
            </section>

            <section class="row justify-content-center">
                <h2>Viihdy kotoisassa ravintolassamme</h2>
                <aside class="col-md-5">
                    <img class="img-fluid sisaltokuva" src="kuvat/ravintola.jpg" alt="tummasävyinen ravintola-aula, useita pieniä pyöreitä ja neliskulmaisia pöytiä, joiden ympärillä mustia tuoleja puunvärisillä jaloilla">
                </aside>
                <article class ="col-md-5">
                    <p>Pellentesque vel ligula dignissim, rutrum augue ac, porta enim. Ut semper fringilla 
                        neque, quis commodo purus mollis at. Nulla tincidunt ac ligula vel condimentum. 
                        Vestibulum metus odio, tincidunt a mauris ut, luctus tempus ligula. Aenean pulvinar 
                        consectetur urna, sit amet tristique libero dapibus in.
                    </p>

                    <h3>Palvelemme:</h3>
                    <ul>
                        <li>su-to 7-23</li>
                        <li>pe-la 7-04</li>
                    </ul>
                </article>
            </section>

            <section class="row justify-content-center">
                <h2>Tutustu nähtävyyksiin</h2>
                <article class ="col-md-5">
                    <p>Pellentesque vel ligula dignissim, rutrum augue ac, porta enim. Ut semper fringilla 
                        neque, quis commodo purus mollis at. Nulla tincidunt ac ligula vel condimentum. 
                        Vestibulum metus odio, tincidunt a mauris ut, luctus tempus ligula. Aenean pulvinar 
                        consectetur urna, sit amet tristique libero dapibus in.
                    </p>
                    <p>Pellentesque vel ligula dignissim, rutrum augue ac, porta enim. Ut semper fringilla 
                        neque, quis commodo purus mollis at. Nulla tincidunt ac ligula vel condimentum. 
                        Vestibulum metus odio, tincidunt a mauris ut, luctus tempus ligula. Aenean pulvinar 
                        consectetur urna, sit amet tristique libero dapibus in. Lorem ipsum dolor sit amet 
                        consectetur adipisicing elit. Consequatur perspiciatis aliquid iusto eum vel et 
                        numquam distinctio accusamus animi omnis. Enim odit tempore voluptatum accusantium 
                        facere sed unde possimus minus.
                    </p>
                </article>
                    
                <aside class="col-md-5">
                    <img class="img-fluid etusivunkuva" src="kuvat/nahtavyys.jpg" alt="vanha kivinen linna, jossa on kaksi pyöreää tornia, etualalla ruohikkoa ja taustalla puita ja sininen taivas">
                </aside>
            </section>
        
<!------- Footer ------->
<?php   include('phpinclude/footer.php'); ?>