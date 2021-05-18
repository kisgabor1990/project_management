<?php
if (isset($_SESSION['id'])) {
    header('Location: index.php');
    exit;
}

if (isset($_POST['password'])) {
    $query = 'UPDATE users 
                SET
                    password="' . password_hash($_POST['password'], PASSWORD_BCRYPT) . '",
                    reset_token=null 
                WHERE reset_token="' . $_POST['reset_token'] . '" LIMIT 1';
    $result = mysqli_query($db, $query);
    if ($affected_rows = mysqli_affected_rows($db)) {
        header('Location: index.php?success');
    }
}

?>
<div class="row justify-content-center mt-5">
    <div class="col-2">
        <form action="index.php?module=new_password" method="post">
            <input type="hidden" name="reset_token" value="<?php echo $_GET['token'] ?>">
            <div class="card">
                <div class="card-header">Új jelszó megadása</div>
                <div class="card-body">
                    <p>Kérjük adja meg az új jelszavát!</p>
                    <div class="form-group col-8 offset-2">
                        <label for="password">Jelszó</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-secondary">Mentés</button>
                </div>
            </div>
        </form>
    </div>
</div>