<?php
/**
 * OZ\User account demo
 */
require 'config.php';

use OZ\User as User;

/* check current user */
$user = false;
if (User::check()) {
    $user = User::getByID($_SESSION['user']['id']);
    $groups = User::getGroups();
    $userList = User::getList();
    $members = 0;
    foreach ($userList as $value) {
        if (intval($value['group']) == 3) {
            $members = $members + 1;
        }
    }

    if (!User::hasGroup($user['id'], 1)) {
        header('Location: login.php');
        exit();
    }
} else {
    /* redirect to user account */
    header('Location: login.php');
    exit();
}

/* user data routine */
$data_error = array();
$data_update = false;
if (isset($_POST['update_data'])) {
    $userdata['id'] = !empty($_POST['id']) ? $_POST['id'] : '';
    $userdata['name'] = !empty($_POST['name']) ? $_POST['name'] : '';
    $userdata['mail'] = !empty($_POST['mail']) ? $_POST['mail'] : '';
    $userdata['group'] = !empty($_POST['group']) ? $_POST['group'] : '';
    if (User::update($userdata['id'], $userdata)) {
        $data_update['msg'] = "User edited successfully";
    } else {
        $data_error['general'] = implode('<br/>', User::getError());
    }
}
if (isset($_POST['delete_user'])) {
    $userdata['id'] = !empty($_POST['id']) ? $_POST['id'] : '';
    if (User::delete($userdata['id'])) {
        $data_update['msg'] = "User deleted successfully";
    } else {
        $data_error['general'] = implode('<br/>', User::getError());
    }
}

if (isset($_POST['add_user'])) {
    $login = !empty($_POST['login']) ? $_POST['login'] : '';
    $password = !empty($_POST['password']) ? $_POST['password'] : '';
    $password_key = !empty($_POST['password_key']) ? $_POST['password_key'] : '';

    $error_flag = false;

    if (empty($login)) {
        /* login is required */
        $registration_error['login'] = 'Login is required';
        $error_flag = true;
    } else if (User::loginExists($login)) {
        /* login already exists */
        $registration_error['login'] = 'Login exists';
        $error_flag = true;
    }

    if (empty($password)) {
        /* password is required */
        $registration_error['password'] = 'Password is required';
        $error_flag = true;
    } else if ($password != $password_key) {
        /* check password key */
        $registration_error['password_key'] = 'Passwords do not match';
        $error_flag = true;
    }

    /* all checks passed */
    if (!$error_flag) {
        $user_data = array(
            'login' => $login,
            'pass' => $password,
            'group' => 3,
            'name' => !empty($_POST['name']) ? $_POST['name'] : '',
            'mail' => !empty($_POST['mail']) ? $_POST['mail'] : ''
        );
        $userID = User::add($user_data);
        if (!empty($userID)) {
            /* registration done */
            /* login user and redirect to account */
            $data_update['msg'] = "User added successfully";
        } else {
            $data_error['general'] = implode('<br/>', User::getError());
        }
    }
}
?>
<html>
    <head>
        <title>User class demo. Admin</title>
        <link rel='stylesheet' type='text/css' href="bootstrap/css/bootstrap.min.css"/>
        <link rel='stylesheet' type='text/css' href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
        <link rel='stylesheet' type='text/css' href="bootstrap/css/style.css"/>
        <link rel='stylesheet' type='text/css' href="bootstrap/css/admin.css"/>
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
                    <a class="navbar-brand" href="account.php">OZ\User demo</a>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li><a href="account.php"><?php echo $user['name']; ?></a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <h1>Admin</h1>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="offer offer-info">
                        <div class="shape">
                            <div class="shape-text">
                                <span class="glyphicon  glyphicon-home"></span>
                            </div>
                        </div>
                        <div class="offer-content">
                            <h3 class="lead">
                                Users : <label class="label label-info"> <?php echo count($userList); ?></label>
                            </h3>
                            <h3 class="lead">
                                Members : <label class="label label-info"> <?php echo $members; ?></label>
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">

                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">

                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">

                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title pull-left">User list</h3>
                            <button class="btn btn-default pull-right" data-toggle="modal" data-target="#addUserModal"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add user</button>
                            <div class="clearfix"></div>
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Name</th>
                                    <th>E-Mail</th>
                                    <th>Group</th>
                                    <th><em class="fa fa-cog"></em></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for ($index = 0; $index < count($userList); $index++) {
                                    ?>
                                <form action="" method="post">
                                    <tr>
                                        <td><input type="hidden" name="id" id="id" value="<?php echo $userList[$index]['id'] ?>"/><?php echo $userList[$index]['id'] ?></td>
                                        <td><?php echo $userList[$index]['login'] ?></td>
                                        <td><input type="text" class="form-control" name="name" id="name" value="<?php echo $userList[$index]['name']; ?>"/></td>
                                        <td><input type="email" class="form-control" name="mail" id="mail" value="<?php echo $userList[$index]['mail'] ?>"/></td>
                                        <td>
                                            <select name="group" id="group" class="form-control">
                                                <?php
                                                foreach ($groups as $key => $value) {
                                                    if ($userList[$index]['group'] == $key) {
                                                        echo "<option value='" . $key . "' selected>" . $value . "</option>";
                                                    } else {
                                                        echo "<option value='" . $key . "'>" . $value . "</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td>
                                            <button type="submit" name='update_data' id="update_data" class="btn btn-default"aria-label="Left Align">
                                                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                            </button>
                                            <button type="submit" name='delete_user' id="delete_user" class="btn btn-default"aria-label="Left Align">
                                                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                            </button>
                                        </td>
                                    </tr>
                                </form>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                        <?php if (!empty($data_error['general'])) { ?>
                            <br/><br/>
                            <div class="msg msg-danger msg-danger-text" role="alert"> <span class="glyphicon glyphicon-exclamation-sign"></span> <?php echo $data_error['general']; ?></div>
                        <?php } ?>
                        <?php if (!empty($data_update)) { ?>
                            <br/><br/>
                            <div class="msg msg-success msg-success-text" role="alert"> <span class="glyphicon glyphicon glyphicon-ok"></span> <?php echo $data_update['msg']; ?></div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">User list</h3>
                        </div>
                        <div class="panel-body">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <button type="button" class="close"
                                data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">
                            Add new user
                        </h4>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <form role="form" id="adduserForm" action="" method="POST">
                            <div class="form-group">
                                <label for="login">Username</label>
                                <input type="text" class="form-control" id="login" name="login" placeholder="Login-Name"/>
                            </div>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Name"/>
                            </div>
                            <div class="form-group">
                                <label for="mail">Email address</label>
                                <input type="email" class="form-control" id="mail" name="mail" placeholder="Enter email"/>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password"/>
                            </div>
                            <div class="form-group">
                                <label for="passwordagain">Re-enter Password</label>
                                <input type="password" class="form-control" id="password_key" name="password_key" placeholder="Re-enter Password"/>
                            </div>
                            <button type="submit" class="btn btn btn-default" name='add_user' id="add_user">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>