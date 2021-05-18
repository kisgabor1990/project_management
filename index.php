<?php

session_start();

$module = $_GET['module'] ?? 'dashboard';

include './includes/db_connection.php';
include './includes/constants.php';

if (
    in_array($module, AJAX_MODULES) &&
    is_file('modules/' . $module . '.php') &&
    isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'
) {
    include('modules/' . $module . '.php');
    mysqli_close($db);
    exit;
}

include './includes/header.php';
if (!isset($_SESSION['id'])) {
    if ($module == 'password_recovery') {
        include './modules/password_recovery.php';
    } else if ($module == 'new_password') {
        include './modules/new_password.php';
    } else {
        if (isset($_GET['success'])) {
?>
            <div class="alert alert-success text-center" role="alert">A jelszó megváltoztatása sikeres volt!</div>
    <?php
        }
        include './modules/login.php';
    }
} else {

    include './includes/navigation.php';

    ?>

    <div class="container" id="mainContent">
        <?php
        include('modules/' . (is_file('modules/' . $module . '.php') ? $module : '404') . '.php');
        ?>
    </div>

<?php
}
include './includes/modals.php';
include './includes/footer.php';
mysqli_close($db);
