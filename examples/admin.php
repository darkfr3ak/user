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
        $data_update = true;
    } else {
        $data_error['general'] = implode('<br/>', User::getError());
    }
}
?>

<html>
    <head>
        <title>User class demo. Admin</title>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css"/>
        <script src="http://code.jquery.com/jquery-3.1.1.min.js"></script>
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
                        <li><a href="#"><?php echo $user['name']; ?></a></li>
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
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">User list</div>
                        <div class="panel-body">
                            <?php
                            $userList = User::getList();
                            ?>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Name</th>
                                        <th>E-Mail</th>
                                        <th>Group</th>
                                        <th></th>
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
                                            <td><button type="submit" name='update_data' id="update_data" class="btn btn-primary">Update</button> | Update</td>
                                        </tr>
                                    </form>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                            <?php if (!empty($data_error['general'])) { ?>
                                <br/><br/>
                                <div class="alert alert-danger" role="alert"><?php echo $data_error['general']; ?></div>
                            <?php } ?>
                            <?php if (!empty($data_update)) { ?>
                                <br/><br/>
                                <div class="alert alert-success" role="alert">Data saved</div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>