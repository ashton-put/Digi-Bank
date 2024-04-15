<!-- this is the nav for the protected folder, displays pages for users to access -->
<!-- this includes account summary, deposit, withdraw, wire, and logout -->
<!-- highlights the nav differently based on activePage -->

<nav class="protected_nav">
    <section>
        <ul>
            <li><a  class="<?php
                if($pathParts['filename'] == 'home') {
                    print 'activePage';
                }
                ?>" href="home.php">Home</a>
            </li>

            <li><a  class="<?php
                if($pathParts['filename'] == 'account') {
                    print 'activePage';
                }
                ?>" href="account.php">Account Summary</a>
            </li>

            <li><a  class="<?php
                if($pathParts['filename'] == 'deposit') {
                    print 'activePage';
                }
                ?>" href="deposit.php">Deposit</a>
            </li>

            <li><a  class="<?php
                if($pathParts['filename'] == 'withdraw') {
                    print 'activePage';
                }
                ?>" href="withdraw.php">Withdraw</a>
            </li>

            <li><a  class="<?php
                if($pathParts['filename'] == 'wire') {
                    print 'activePage';
                }
                ?>" href="wire.php">Wire</a>
            </li>

            <li class="logout"><a  class="<?php
                if($pathParts['filename'] == '../digibank') {
                    print 'activePage';
                }
                ?>" href="../digibank.php">Log Out</a>
            </li>
        </ul>

    </section>

</nav>
