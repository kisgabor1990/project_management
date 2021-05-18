<div class="row  mt-5">
    <div class="col-12 col-lg-3">
        <div class="card">
            <div class="card-header bg-warning user-select-none">Projektek száma</div>
            <div class="card-body">
                <?php
                $query = 'SELECT COUNT(id) AS db FROM projects';
                $result = mysqli_query($db, $query);
                $row = mysqli_fetch_assoc($result);
                echo $row['db'];
                ?> darab
            </div>
            <div class="card-footer text-end">
                <a href="index.php?module=projects" class="btn btn-secondary btn-sm is-ajax">Projektek</a>
            </div>
        </div>
    </div>

    <?php
    if (isset($_SESSION['id']) && ($_SESSION['permission'] == 'Admin' || $_SESSION['permission'] == 'BOSS')) {
    ?>

        <div class="col-12 col-lg-3">
            <div class="card">
                <div class="card-header bg-warning user-select-none">Felhasználók száma</div>
                <div class="card-body">
                    <?php
                    $query = 'SELECT COUNT(id) AS db FROM users';
                    $result = mysqli_query($db, $query);
                    $row = mysqli_fetch_assoc($result);
                    echo $row['db'];
                    ?> darab
                </div>
                <div class="card-footer text-end">
                    <a href="index.php?module=users" class="btn btn-secondary btn-sm is-ajax">Felhasználók</a>
                </div>
            </div>
        </div>

    <?php } ?>

    <div class="col-12 col-lg-3">
        <div class="card">
            <div class="card-header bg-warning user-select-none">Mappák száma</div>
            <div class="card-body">
                <?php
                $query = 'SELECT COUNT(id) AS db FROM folders';
                $result = mysqli_query($db, $query);
                $row = mysqli_fetch_assoc($result);
                echo $row['db'];
                ?> darab
            </div>
            <div class="card-footer text-end">
                <a href="index.php?module=folders" class="btn btn-secondary btn-sm is-ajax">Mappák</a>
            </div>
        </div>
    </div>
</div>