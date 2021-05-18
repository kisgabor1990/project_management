<?php

$action = $_GET['action'] ?? 'list';
if ($action == 'list') :

    $query = 'SELECT * FROM folders ORDER BY name';
    $result = mysqli_query($db, $query);

?>
    <div class="d-flex bd-highlight my-5 user-select-none">
        <h1 class="flex-grow-1"><i class="far fa-folder"></i> Mappák</h1>
        <div>
            <?php
            if (isset($_SESSION['id']) && $_SESSION['permission'] != 'Dolgozó') {
            ?>
                <a href="index.php?module=folders&action=new" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Új mappa
                </a>
            <?php } ?>
        </div>
    </div>
    <?php
    if (isset($_GET['saved'])) :
    ?>
        <div class="alert alert-success" role="alert">A mentés sikeres volt!</div>
    <?php
    endif;
    ?>
    <table class="table table-striped table-hover">
        <thead>
            <tr class="user-select-none">
                <th scope="col" class="w-100">Név</th>
                <th scope="col" class="text-end">Műveletek</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) :

                $url = 'index.php?module=folders&id=' . $row['id'];
            ?>
                <tr class="row-<?php echo $row['id']; ?>">
                    <td><?php echo $row['name']; ?></td>
                    <td class="text-end">
                        <?php
                        if (isset($_SESSION['id']) && $_SESSION['permission'] != 'Dolgozó') {
                        ?>
                            <div class="btn-group" role="group">
                                <a href="<?php echo $url; ?>&action=edit" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Szerkesztés">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" data-href="<?php echo $url; ?>&action=delete" data-id="<?php echo $row['id']; ?>" data-header="mappa" class="btn btn-danger delete" data-bs-toggle="tooltip" data-bs-placement="top" title="Törlés">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        <?php } ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php

elseif (($action == 'new' || ($action == 'edit' && isset($_GET['id']))) && (isset($_SESSION['id']) && $_SESSION['permission'] != 'Dolgozó')) :
    if ($action == 'edit') {
        $query = 'SELECT * FROM folders WHERE id = ' . $_GET['id'] . ' LIMIT 1';
        $result = mysqli_query($db, $query);
        $row = mysqli_fetch_assoc($result);
    }
?>
    <div class="col-6 ">

        <div class="d-flex bd-highlight mb-5">
            <h1 class="flex-grow-1 user-select-none"><?php echo $action == 'new' ? '<i class="fas fa-plus"></i> Új mappa' : '<i class="fas fa-edit"></i> Szerkesztés'; ?></h1>
        </div>
        <form action="index.php?module=folders&action=store" method="post" class="needs-validation" novalidate>
            <input type="hidden" name="id" value="<?php echo $_GET['id'] ?? ''; ?>">
            <div class="row">
                <div class="col-6 pe-0">
                    <div class="form-group mb-3">
                        <label for="name">Név</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($row['name'] ?? '', ENT_QUOTES); ?>" required>
                        <div class="invalid-feedback">
                            Név megadása kötelező!
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 d-flex justify-content-between mt-5">
                <button type="submit" class="btn btn-success"><?php echo $action == 'new' ? 'Mentés' : 'Szerkesztés'; ?></button>
                <a href="index.php?module=folders" class="btn btn-warning is-ajax">Vissza</a>
            </div>

        </form>
    </div>

    <?php
elseif ($action == 'store') :


    $valid = true;
    $errors = [];

    if ($_POST['name'] == '') {
        $valid = false;
        $errors[] = 'A név megadása kötelező!';
    }

    if ($valid) {
        $command = isset($_POST['id']) && $_POST['id'] != '' ? 'UPDATE' : 'INSERT INTO';
        $query = $command . ' folders SET
                name = "' . $_POST['name'] . '"';
        if (isset($_POST['id']) && $_POST['id'] != '') $query .= ' WHERE id=' . $_POST['id'];
        if (!mysqli_query($db, $query)) {
            echo mysqli_error($db);
        } else {
            header('Location: index.php?module=folders&saved');
        }
    } else {
    ?>
        <div class="col-6 offset-3">

            <div class="alert alert-danger">
                Hibás vagy hiányzó adatok!
                <ul class="m-0">
                    <?php
                    foreach ($errors as $error) {
                        echo '<li>' . $error . '</li>';
                    }
                    ?>
                </ul>
            </div>
            <a class="btn btn-primary" href="#" role="button" onclick="window.history.go(-1); return false;">Vissza</a>
        </div>
    <?php
    }
    ?>

<?php
elseif ($action == 'delete' && (isset($_SESSION['id']) && $_SESSION['permission'] != 'Dolgozó')) :
    $query = 'DELETE FROM folders WHERE id=' . $_GET['id'] . ' LIMIT 1';
    mysqli_query($db, $query);
    echo 'A mappa törlése sikeres!';

else :
    echo '404';

endif;
