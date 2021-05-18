<div class="container menu mt-3 mb-5">
    <a class="btn btn-outline-primary is-ajax nav-dashboard <?php echo 'dashboard' == $module ? 'active' : '' ?>" href="index.php?module=dashboard" role="button"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a class="btn btn-outline-primary is-ajax nav-projects <?php echo 'projects' == $module ? 'active' : '' ?>" href="index.php?module=projects" role="button"><i class="fas fa-database"></i> Projektek</a>
    <?php
    if (isset($_SESSION['id']) && ($_SESSION['permission'] == 'Admin' || $_SESSION['permission'] == 'BOSS')) {
    ?>
        <a class="btn btn-outline-primary is-ajax nav-users <?php echo 'users' == $module ? 'active' : '' ?>" href="index.php?module=users" role="button"><i class="fas fa-users"></i> Felhasználók</a>
    <?php } ?>
    <a class="btn btn-outline-primary is-ajax nav-folders <?php echo 'folders' == $module ? 'active' : '' ?>" href="index.php?module=folders" role="button"><i class="fas fa-folder"></i> Mappák</a>
    <a class="btn btn-outline-primary" href="index.php?module=logout" role="button"><i class="fas fa-sign-out-alt"></i> Kilépés</a>
</div>