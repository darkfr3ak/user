<?php
/**
 * OZ\User login demo
 */
require 'config.php';

use OZ\User as User;

/* check current user */
$user = false;
if (User::check()) {
    /* redirect to user account */
    header('Location: account.php');
    exit();
}

/* default values */
$login = '';

/* login routine */
$login_error = array();
if (isset($_POST['enter'])) {
    $login = !empty($_POST['login']) ? $_POST['login'] : '';
    $password = !empty($_POST['password']) ? $_POST['password'] : '';

    $error_flag = false;

    if (empty($login)) {
        /* login is required */
        $login_error['login'] = 'Login is required';
        $error_flag = true;
    }

    if (empty($password)) {
        /* password is required */
        $login_error['password'] = 'Password is required';
        $error_flag = true;
    }

    /* all checks passed */
    if (!$error_flag) {
        if (User::login($login, $password)) {
            /* redirect to user account */
            header('Location: account.php');
            exit();
        } else {
            $login_error['general'] = 'Something wrong';
        }
    }
}
?>

<html>
    <head>
        <title>User class demo. Login</title>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css"/>
        <link rel='stylesheet' type='text/css' href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
        <link rel='stylesheet' type='text/css' href="bootstrap/css/style.css"/>
        <script src="http://code.jquery.com/jquery-3.1.1.min.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
    </head>
    <body>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">OZ\User demo</a>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="login.php">Login <span class="sr-only">(current)</span></a></li>
                        <li><a href="registration.php">Registration</a></li>
                        <li><a href="recover.php">Recover account</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <h1>Login</h1>
            <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
                <div class="panel panel-info" >
                    <div class="panel-heading">
                        <div class="panel-title">Sign In</div>
                        <div style="float:right; font-size: 80%; position: relative; top:-10px"><a href="recover.php">Forgot password?</a></div>
                    </div>
                    <div style="padding-top:30px" class="panel-body" >
                        <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                        <form id="loginform" class="form-horizontal" role="form" action="" method="post">

                            <div style="margin-bottom: 25px" class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                <input id="login" type="text" class="form-control" name="login" value="" placeholder="username">
                                <?php if (!empty($login_error['login'])) { ?>
                                    <br/>
                                    <div class="msg msg-danger msg-danger-text" role="alert"><?php echo $login_error['login']; ?></div>
                                <?php } ?>
                            </div>

                            <div style="margin-bottom: 25px" class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                <input id="password" type="password" class="form-control" name="password" placeholder="password">
                                <?php if (!empty($login_error['password'])) { ?>
                                    <br/>
                                    <div class="msg msg-danger msg-danger-text" role="alert"><?php echo $login_error['password']; ?></div>
                                <?php } ?>
                            </div>
                            <div style="margin-top:10px" class="form-group">
                                <!-- Button -->
                                <div class="col-sm-12 controls">
                                    <button name="enter" id="btn-login" class="btn btn-default">Login  </button>
                                </div>
                                <?php if (!empty($login_error['general'])) { ?>
                                    <br/><br/>
                                    <div class="msg msg-danger msg-danger-text" role="alert"><?php echo $login_error['general']; ?></div>
                                <?php } ?>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 control">
                                    <div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" >
                                        Don't have an account!
                                        <a href="registration.php">
                                            Sign Up Here
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>