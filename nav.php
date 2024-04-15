<!-- this is the nav for the main folder, displays about page and login/signup stuff -->
<!-- highlights the nav differently based on activePage -->
<nav class="main_nav">
    <section>
        <ul>
            <li><a  class="<?php
                if($pathParts['filename'] == 'digibank') {
                    print 'activePage';
                }
                ?>" href="digibank.php">Home</a>
            </li>

            <li><a  class="<?php
                if($pathParts['filename'] == 'about') {
                    print 'activePage';
                }
                ?>" href="about.php">About</a>
            </li>

            <li class="login"><a  class="<?php
                if($pathParts['filename'] == 'login') {
                    print 'activePage';
                }
                ?>" href="login.php">Login</a>
            </li>

            <li class="signup"><a  class="<?php
                if($pathParts['filename'] == 'signup') {
                    print 'activePage';
                }
                ?>" href="signup.php">Sign-Up</a>
            </li>
        </ul>

    </section>

</nav>
