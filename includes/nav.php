<nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>                        
                    </button>
                    <a class="navbar-brand" href="index.php">Something Like Twitter</a>
                </div>
                <div class="collapse navbar-collapse" id="myNavbar">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="index.php">Home</a></li>
                        <?php
                        // widzi zalogowany użytkownik
                        if ($userID !== null) {
                            echo '<li class="dropdown">'
                            . '<a class="dropdown-toggle" data-toggle="dropdown" href="#">Page 1 <span class="caret"></span></a>'
                            . '<ul class="dropdown-menu">'
                            . '<li><a href="#">Page 1-1</a></li>'
                            . '<li><a href="#">Page 1-2</a></li>'
                            . '<li><a href="#">Page 1-3</a></li>'
                            . '</ul>'
                            . '</li>'
                            . '<li><a href="page_user.php?id=' . $userID . '">My Info</a></li>'
                            . '<li><a href="#">Page 3</a></li>';
                        }
                        ?>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <?php
                        // widzi niezalogowany użytkownik
                        if ($userID === null) {
                            echo '<li><a href="page_register.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>'
                            . '<li><a href="page_login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>';
                        } else {
                            // widzi zalogowany użytkownik
                            echo '<li><a href="page_logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
