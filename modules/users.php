<?php

if (isset($_SESSION['id']) && ($_SESSION['permission'] != 'Admin' && $_SESSION['permission'] != 'BOSS')) {
    header('Location: index.php');
}

$action = $_GET['action'] ?? 'list';
if ($action == 'list') :

    $query = 'SELECT * FROM users ORDER BY name';
    $result = mysqli_query($db, $query);

?>
    <div class="d-flex bd-highlight my-5 user-select-none">
        <h1 class="flex-grow-1"><i class="fas fa-users"></i> Felhasználók</h1>
        <div>
            <a href="index.php?module=users&action=new" class="btn btn-primary">
                <i class="fas fa-plus"></i> Új felhasználó
            </a>
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
                <th scope="col">Név</th>
                <th scope="col">Adószám</th>
                <th scope="col">Születési idő</th>
                <th scope="col">Születési hely</th>
                <th scope="col">Jogosultság</th>
                <th scope="col" class="text-end">Műveletek</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) :

                $url = 'index.php?module=users&id=' . $row['id'];
            ?>
                <tr class="row-<?php echo $row['id']; ?>">
                    <td>#<?php echo $row['id'] ?>.</td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['tax_id']; ?></td>
                    <td><?php echo date('Y. m. d.', strtotime($row['birth_date'])); ?></td>
                    <td><?php echo $row['birth_city']; ?></td>
                    <td><?php echo $row['permission']; ?></td>
                    <td class="text-end">

                        <div class="btn-group" role="group">
                            <a href="<?php echo $url; ?>&action=show" class="btn btn-primary is-ajax" data-bs-toggle="tooltip" data-bs-placement="top" title="Adatlap">
                                <i class="fas fa-eye"></i>
                            </a>
                            <?php
                            
                            if ($row['id'] != 1 || $_SESSION['id'] == 1) {
                            ?>
                                <a href="<?php echo $url; ?>&action=edit" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Szerkesztés">
                                    <i class="fas fa-edit"></i>
                                </a>
                            <?php
                            }
                            if ($row['id'] != 1) {
                            ?>
                                <a href="#" data-href="<?php echo $url; ?>&action=delete" data-id="<?php echo $row['id']; ?>" data-header="felhasználó" class="btn btn-danger delete" data-bs-toggle="tooltip" data-bs-placement="top" title="Törlés">
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
    $query = 'SELECT * FROM users WHERE id = ' . $_GET['id'];
    $result = mysqli_query($db, $query);
    $user = mysqli_fetch_assoc($result);
?>

    <div class="row justify-content-center mt-5">
        <div class="col-5">
            <div class="card">
                <div class="card-header user-select-none">
                    <h2>
                        <span>#<?php echo $user['id']; ?></span>
                        <?php echo $user['name']; ?>
                    </h2>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td class="fw-bold user-select-none" style="width: 40%;">Email:</td>
                            <td><?php echo $user['email']; ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold user-select-none">Születési idő:</td>
                            <td><?php echo date('Y. m. d.', strtotime($user['birth_date'])); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold user-select-none">Születési hely:</td>
                            <td><?php echo $user['birth_city']; ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold user-select-none">Adóazonosító jele:</td>
                            <td><?php echo $user['tax_id']; ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold user-select-none">Anyja neve:</td>
                            <td><?php echo $user['mothers_name']; ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold user-select-none">Jogosultsága:</td>
                            <td><?php echo $user['permission']; ?></td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer text-center">
                    <a class="btn btn-primary btn-sm is-ajax" href="index.php?module=users" role="button">Vissza</a>
                </div>
            </div>
        </div>
    </div>

<?php


elseif ($action == 'new' || ($action == 'edit' && isset($_GET['id']))) :
    if ($action == 'edit') {
        $query = 'SELECT * FROM users WHERE id = ' . $_GET['id'] . ' LIMIT 1';
        $result = mysqli_query($db, $query);
        $row = mysqli_fetch_assoc($result);
    }
?>
    <div class="col-6">

        <div class="d-flex bd-highlight mb-5">
            <h1 class="flex-grow-1 user-select-none"><?php echo $action == 'new' ? '<i class="fas fa-plus"></i> Új felhasználó' : '<i class="fas fa-edit"></i> Szerkesztés'; ?></h1>
        </div>
        <form action="index.php?module=users&action=store" method="post" class="needs-validation" novalidate>
            <input type="hidden" name="id" value="<?php echo $_GET['id'] ?? ''; ?>">
            <div class="row">
                <div class="col-6">
                    <div class="form-group mb-3">
                        <label for="name">Név</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($row['name'] ?? '', ENT_QUOTES); ?>" required>
                        <div class="invalid-feedback">
                            Név megadása kötelező!
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group mb-3">
                        <label for="email">E-mail cím</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($row['email'] ?? '', ENT_QUOTES); ?>" required>
                        <div class="invalid-feedback">
                            Érvényes e-mail cím megadása kötelező!
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group mb-3">
                        <label for="password">Jelszó</label>
                        <input type="password" class="form-control" id="password" name="password" <?php echo (isset($_GET['action']) && $_GET['action'] == 'new') ? 'required' : '' ?>>
                        <div class="invalid-feedback">
                            Jelszó megadása kötelező!
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group mb-3">
                        <label for="password2">Jelszó ismét</label>
                        <input type="password" class="form-control" id="password2" name="password2" <?php echo (isset($_GET['action']) && $_GET['action'] == 'new') ? 'required' : '' ?>>
                        <div class="invalid-feedback">
                            Jelszó ismételt megadása kötelező!
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group mb-3">
                        <label for="birth_date">Születési idő</label>
                        <input type="date" class="form-control" id="birth_date" name="birth_date" value="<?php echo htmlspecialchars($row['birth_date'] ?? '', ENT_QUOTES); ?>" required>
                        <div class="invalid-feedback">
                            Születési idő megadása kötelező!
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group mb-3">
                        <label for="birth_city">Születési hely</label>
                        <input type="text" class="form-control" id="birth_city" name="birth_city" value="<?php echo htmlspecialchars($row['birth_city'] ?? '', ENT_QUOTES); ?>" required>
                        <div class="invalid-feedback">
                            Születési hely megadása kötelező!
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group mb-3">
                        <label for="tax_id">Adóazonosító jele</label>
                        <input type="number" class="form-control" id="tax_id" name="tax_id" value="<?php echo htmlspecialchars($row['tax_id'] ?? '', ENT_QUOTES); ?>" required>
                        <div class="invalid-feedback">
                            Adóazonosító jel megadása kötelező!
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group mb-3">
                        <label for="mothers_name">Anyja neve</label>
                        <input type="text" class="form-control" id="mothers_name" name="mothers_name" value="<?php echo htmlspecialchars($row['mothers_name'] ?? '', ENT_QUOTES); ?>" required>
                        <div class="invalid-feedback">
                            Anyja neve megadása kötelező!
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="select">
                        <label id="permission_label" for="permission">Jogosultság</label>
                        <div class="input-group mb-3">
                            <select id="permission" name="permission" class="form-control form-select">
                                <?php
                                if ($_SESSION['id'] == 1 && isset($_GET['id']) && $_GET['id'] == 1) {
                                ?>
                                    <option value="BOSS" selected>BOSS</option>
                                <?php
                                } else {
                                ?>
                                    <option value="Dolgozó" <?php echo isset($row) && $row['permission'] == 'Dolgozó' ? 'selected' : ''; ?>>Dolgozó</option>
                                    <option value="Vezető" <?php echo isset($row) && $row['permission'] == 'Vezető' ? 'selected' : ''; ?>>Csoportvezető</option>
                                    <option value="Admin" <?php echo isset($row) && $row['permission'] == 'Admin' ? 'selected' : ''; ?>>Admin</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col d-flex justify-content-between my-5">
                <button type="submit" class="btn btn-success"><?php echo $action == 'new' ? 'Mentés' : 'Szerkesztés'; ?></button>
                <a href="index.php?module=users" class="btn btn-warning is-ajax">Vissza</a>
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
    if ($_POST['email'] == '') {
        $valid = false;
        $errors[] = 'Az email megadása kötelező!';
    }
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $valid = false;
        $errors[] = 'Az email formátuma nem megfelelő!';
    }
    if ($_POST['password'] == '' && $_POST['id'] == '') {
        $valid = false;
        $errors[] = 'A jelszó megadása kötelező!';
    }
    if ($_POST['password'] != $_POST['password2'] && ($_POST['id'] == '' || ($_POST['id'] != '' && $_POST['password'] != ''))) {
        $valid = false;
        $errors[] = 'A két jelszó nem egyezik!';
    }
    if ($_POST['birth_date'] == '') {
        $valid = false;
        $errors[] = 'A születési idő megadása kötelező!';
    }
    if ($_POST['birth_city'] == '') {
        $valid = false;
        $errors[] = 'A születési hely megadása kötelező!';
    }
    if ($_POST['tax_id'] == '') {
        $valid = false;
        $errors[] = 'Az adóazonosító jel megadása kötelező!';
    }
    if ($_POST['mothers_name'] == '') {
        $valid = false;
        $errors[] = 'Az anyja neve megadása kötelező!';
    }

    if ($valid) {
        $command = isset($_POST['id']) && $_POST['id'] != '' ? 'UPDATE' : 'INSERT INTO';
        $query = $command . ' users SET
                name = "' . $_POST['name'] . '",
                email = "' . $_POST['email'] . '",
                ' . ($_POST['password'] != '' ? 'password = "' . password_hash($_POST['password'], PASSWORD_BCRYPT) . '",' : '') . '
                birth_date = "' . $_POST['birth_date'] . '",
                birth_city = "' . $_POST['birth_city'] . '",
                tax_id = "' . $_POST['tax_id'] . '",
                mothers_name = "' . $_POST['mothers_name'] . '",
                permission = "' . $_POST['permission'] . '"';
        if (isset($_POST['id']) && $_POST['id'] != '') $query .= ' WHERE id=' . $_POST['id'];

        if (!mysqli_query($db, $query)) {
            echo mysqli_error($db);
        } else {
            header('Location: index.php?module=users&saved');
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
elseif ($action == 'delete') :
    if ($_GET['id'] == 1) {
        echo 'Nem törölheted a BOSS -t !!!';
    } else {
        $query = 'DELETE FROM users WHERE id=' . $_GET['id'] . ' LIMIT 1';
        mysqli_query($db, $query);
        echo 'A felhasználó törlése sikeres!';
    }

else :
    echo '404';

endif;
