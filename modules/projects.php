<?php

$action = $_GET['action'] ?? 'list';
if ($action == 'list') :

    $query = 'SELECT * FROM projects ORDER BY name';
    $result = mysqli_query($db, $query);

?>
    <div class="d-flex bd-highlight my-5 user-select-none">
        <h1 class="flex-grow-1"><i class="fas fa-users"></i> Projektek</h1>
        <div>
            <?php
            if (isset($_SESSION['id']) && $_SESSION['permission'] != 'Dolgozó') {
            ?>
                <a href="index.php?module=projects&action=new" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Új projekt
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
                <th scope="col">#</th>
                <th scope="col" style="width: 70%;">Projekt neve</th>
                <th scope="col">Tervezett kezdés</th>
                <th scope="col" class="text-end">Műveletek</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) :

                $url = 'index.php?module=projects&id=' . $row['id'];
            ?>
                <tr class="row-<?php echo $row['id']; ?>">
                    <td>#<?php echo $row['id'] ?>.</td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo date('Y. m. d.', strtotime($row['start_date'])); ?></td>
                    <td class="text-end">

                        <div class="btn-group" role="group">
                            <a href="<?php echo $url; ?>&action=show" class="btn btn-primary is-ajax" data-bs-toggle="tooltip" data-bs-placement="top" title="Adatlap">
                                <i class="fas fa-eye"></i>
                            </a>
                            <?php
                            if (isset($_SESSION['id']) && $_SESSION['permission'] != 'Dolgozó') {
                            ?>
                                <a href="<?php echo $url; ?>&action=edit" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Szerkesztés">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" data-href="<?php echo $url; ?>&action=delete" data-id="<?php echo $row['id']; ?>" data-header="projekt" class="btn btn-danger delete" data-bs-toggle="tooltip" data-bs-placement="top" title="Törlés">
                                    <i class="fas fa-trash"></i>
                                </a>
                            <?php } ?>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php

elseif ($action == 'show' && isset($_GET['id'])) :
    $query = 'SELECT * FROM projects WHERE id = ' . $_GET['id'];
    $result = mysqli_query($db, $query);
    $project = mysqli_fetch_assoc($result);
?>

    <div class="row justify-content-between mt-5">
        <div class="col-6">
            <div class="card">
                <div class="card-header user-select-none">
                    <h2>
                        <span>#<?php echo $project['id']; ?></span>
                        <?php echo $project['name']; ?>
                    </h2>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td class="fw-bold user-select-none" style="width: 40%;">Tervezett kezdés:</td>
                            <td><?php echo date('Y. m. d.', strtotime($project['start_date'])); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold user-select-none">Tervezett befejezés:</td>
                            <td><?php echo date('Y. m. d.', strtotime($project['finish_date'])); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold user-select-none">Tervezett költségvetés:</td>
                            <td><?php echo $project['budget']; ?> Ft.</td>
                        </tr>
                        <tr>
                            <td colspan="2" class="py-3"><?php echo $project['description']; ?></td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer text-center">
                    <a class="btn btn-primary btn-sm is-ajax" href="index.php?module=projects" role="button">Vissza</a>
                </div>
            </div>
        </div>

        <div class="col-3">
            <div class="card">
                <div class="card-header user-select-none">
                    <h4>
                        Órák hozzáadása
                    </h4>
                </div>
                <form action="index.php?module=projects&action=store" method="post">
                    <input type="hidden" name="project_id" value="<?php echo $project['id'] ?>">
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['id'] ?>">
                    <div class="card-body">
                        <div class="row mb-3">
                            <label for="date" class="col-sm-4 col-form-label">Dátum</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="time" class="col-sm-4 col-form-label">Idő</label>
                            <div class="col-sm-8">
                                <input type="time" class="form-control" id="time" name="time" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description">Leírás</label>
                            <textarea class="form-control" name="description" id="description" rows="3" required></textarea>
                        </div>

                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-danger btn-sm">Mentés</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row my-5">
        <div class="col">
            <div class="card">
                <div class="card-header user-select-none">
                    <h4>
                        Elvégzett munkák
                    </h4>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <td class="fw-bold user-select-none">Dolgozó</td>
                                <td class="fw-bold user-select-none">Dátum</td>
                                <td class="fw-bold user-select-none">Idő</td>
                                <td class="fw-bold user-select-none">Megjegyzés</td>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $where = ' timetrackings.project_id = ' . $_GET['id'] . ' ';
                            if ($_SESSION['permission'] == 'Dolgozó') {
                                $where .= ' AND timetrackings.user_id = ' . $_SESSION['id'] . ' ';
                            }
                            $query = 'SELECT timetrackings.*, users.name FROM timetrackings
                                        LEFT JOIN users on timetrackings.user_id = users.id
                                        WHERE ' . $where . '
                                        ORDER BY date DESC';
                            $result = mysqli_query($db, $query);

                            while ($timetracking = mysqli_fetch_assoc($result)) {
                            ?>
                                <tr class="row-<?php echo $timetracking['id']; ?>">
                                    <td><?php echo $timetracking['name'] ?></td>
                                    <td><?php echo date('Y. m. d.', strtotime($timetracking['date'])) ?></td>
                                    <td><?php echo date('H:i', strtotime($timetracking['time'])) ?></td>
                                    <td><?php echo $timetracking['description'] ?></td>

                                    <?php
                                    if (isset($_SESSION['id']) && $_SESSION['permission'] == 'BOSS') {
                                    ?>
                                        <td>
                                            <a href="#" data-href="index.php?module=projects&id=<?php echo $timetracking['id'] ?>&action=deletework" data-id="<?php echo $timetracking['id']; ?>" data-header="munka" class="btn btn-danger delete" data-bs-toggle="tooltip" data-bs-placement="top" title="Törlés">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    <?php } ?>

                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php


elseif (($action == 'new' || ($action == 'edit' && isset($_GET['id']))) && (isset($_SESSION['id']) && $_SESSION['permission'] != 'Dolgozó')) :
    if ($action == 'edit') {
        $query = 'SELECT * FROM projects WHERE id = ' . $_GET['id'] . ' LIMIT 1';
        $result = mysqli_query($db, $query);
        $row = mysqli_fetch_assoc($result);
    }
?>
    <div class="col-6">

        <div class="d-flex bd-highlight mb-5">
            <h1 class="flex-grow-1 user-select-none"><?php echo $action == 'new' ? '<i class="fas fa-plus"></i> Új projekt' : '<i class="fas fa-edit"></i> Szerkesztés'; ?></h1>
        </div>
        <form action="index.php?module=projects&action=store" method="post" class="needs-validation" novalidate>
            <input type="hidden" name="id" value="<?php echo $_GET['id'] ?? ''; ?>">
            <div class="row">
                <div class="col">
                    <div class="form-group mb-3">
                        <label for="name">Cím</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($row['name'] ?? '', ENT_QUOTES); ?>" required>
                        <div class="invalid-feedback">
                            Cím megadása kötelező!
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group mb-3">
                        <label for="start_date">Tervezett kezdés</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo htmlspecialchars($row['start_date'] ?? '', ENT_QUOTES); ?>" required>
                        <div class="invalid-feedback">
                            Tervezett kezdés megadása kötelező!
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group mb-3">
                        <label for="finish_date">Tervezett befejezés</label>
                        <input type="date" class="form-control" id="finish_date" name="finish_date" value="<?php echo htmlspecialchars($row['finish_date'] ?? '', ENT_QUOTES); ?>" required>
                        <div class="invalid-feedback">
                            Tervezett befejezés megadása kötelező!
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group mb-3">
                        <label for="budget">Tervezett költségvetés</label>
                        <input type="number" class="form-control" id="budget" name="budget" value="<?php echo htmlspecialchars($row['budget'] ?? '', ENT_QUOTES); ?>" required>
                        <div class="invalid-feedback">
                            Tervezet költségvetés megadása kötelező!
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group mb-3">
                        <label for="description">Leírás</label>
                        <textarea class="form-control textarea-summernote" name="description" id="description" rows="10"><?php echo htmlspecialchars($row['description'] ?? '', ENT_QUOTES); ?></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="select">
                        <label id="folder_label" for="folder">Mappa</label>
                        <div class="input-group mb-3">
                            <select id="folder" name="folder" class="form-control form-select">
                                <?php
                                $query = 'SELECT * FROM folders ORDER BY name';
                                $result = mysqli_query($db, $query);
                                while ($folder = mysqli_fetch_assoc($result)) {
                                ?>
                                    <option value="<?php echo $folder['name'] ?>" <?php echo (isset($row['folder']) && $row['folder'] == $folder['name']) ? 'selected' : '' ?>><?php echo $folder['name'] ?></option>
                                <?php
                                }


                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col d-flex justify-content-between my-5">
                <button type="submit" class="btn btn-success"><?php echo $action == 'new' ? 'Mentés' : 'Szerkesztés'; ?></button>
                <a href="index.php?module=projects" class="btn btn-warning is-ajax">Vissza</a>
            </div>

        </form>
    </div>

    <?php
elseif ($action == 'store') :

    if (isset($_POST['project_id']) && isset($_POST['user_id'])) {
        $query = 'INSERT INTO timetrackings SET
                    project_id = ' . $_POST['project_id'] . ',
                    user_id = ' . $_POST['user_id'] . ',
                    date = "' . $_POST['date'] . '",
                    time = "' . $_POST['time'] . '",
                    description = "' . $_POST['description'] . '"';

        if (!mysqli_query($db, $query)) {
            echo mysqli_error($db);
        } else {
            header('Location: index.php?module=projects&id=' . $_POST['project_id'] . '&action=show');
        }
    }

    if (isset($_POST['name']) && isset($_POST['start_date'])) {

        $valid = true;
        $errors = [];

        if ($_POST['name'] == '') {
            $valid = false;
            $errors[] = 'A cím megadása kötelező!';
        }
        if ($_POST['start_date'] == '') {
            $valid = false;
            $errors[] = 'A tervezett kezdés megadása kötelező!';
        }
        if ($_POST['finish_date'] == '') {
            $valid = false;
            $errors[] = 'A tervezett befejezés megadása kötelező!';
        }
        if ($_POST['budget'] == '') {
            $valid = false;
            $errors[] = 'A tervezett költségvetés megadása kötelező!';
        }
        if ($_POST['description'] == '') {
            $valid = false;
            $errors[] = 'A leírás megadása kötelező!';
        }

        if ($valid) {
            $command = isset($_POST['id']) && $_POST['id'] != '' ? 'UPDATE' : 'INSERT INTO';
            $query = $command . ' projects SET
            name = "' . $_POST['name'] . '",
            start_date = "' . $_POST['start_date'] . '",
            finish_date = "' . $_POST['finish_date'] . '",
            budget = "' . $_POST['budget'] . '",
            description = "' . $_POST['description'] . '",
            folder = "' . $_POST['folder'] . '"';
            if (isset($_POST['id']) && $_POST['id'] != '') $query .= ' WHERE id=' . $_POST['id'];
            if (!mysqli_query($db, $query)) {
                echo mysqli_error($db);
            } else {
                header('Location: index.php?module=projects&saved');
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
    }
    ?>

<?php
elseif ($action == 'delete' && (isset($_SESSION['id']) && $_SESSION['permission'] != 'Dolgozó')) :
    $query = 'DELETE FROM projects WHERE id=' . $_GET['id'] . ' LIMIT 1';
    mysqli_query($db, $query);
    echo 'A projekt törlése sikeres!';

elseif ($action == 'deletework' && (isset($_SESSION['id']) && $_SESSION['permission'] == 'BOSS')) :
    $query = 'DELETE FROM timetrackings WHERE id=' . $_GET['id'] . ' LIMIT 1';
    mysqli_query($db, $query);
    echo 'A munka törlése sikeres!';

else :
    echo '404';

endif;
