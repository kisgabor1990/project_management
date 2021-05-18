<?php
if (isset($_SESSION['id'])) {
    header('Location: index.php');
    exit;
}

if (isset($_POST) && isset($_POST['email']) && isset($_POST['password'])) {
    $query = 'SELECT * FROM users WHERE email="' . $_POST['email'] . '" LIMIT 1';
    $result = mysqli_query($db, $query);

    if (($row = mysqli_fetch_assoc($result)) && password_verify($_POST['password'], $row['password'])) {
        $_SESSION['id'] = $row['id'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['permission'] = $row['permission'];
        header('Location: index.php');
        exit;
    }

?>
    <div class="alert alert-danger">
        Nem megfelelő email/jelszó páros!
    </div>
<?php
}
?>
<div class="row justify-content-center mt-5">
    <div class="col-2">
        <div class="card">
            <div class="card-header bg-success bg-gradient">
                <h5>Bejelentkezés</h5>
            </div>
            <div class="card-body">

                <form action="index.php?module=login" method="post">
                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="password">Jelszó</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
            </div>

            <div class="card-footer d-flex justify-content-between">
                <a href="index.php?module=password_recovery" class="btn btn-light">Elfelejtett jelszó</a>
                <button type="submit" class="btn btn-primary">Belépés</button>
            </div>
            </form>
        </div>
    </div>
</div>