<?php
if (isset($_SESSION['id'])) {
    header('Location: index.php');
    exit;
}

include './includes/PHPMailer/Exception.php';
include './includes/PHPMailer/PHPMailer.php';
include './includes/PHPMailer/SMTP.php';
include './includes/functions.php';

if (isset($_POST) && isset($_POST['email'])) {
    $query = 'SELECT * FROM users WHERE email="' . $_POST['email'] . '" LIMIT 1';
    $result = mysqli_query($db, $query);
    if ($result->num_rows == 1) {
        $user = mysqli_fetch_assoc($result);

        $reset_token = sha1(uniqid(more_entropy: true));
        mysqli_query($db, 'UPDATE users SET reset_token="' . $reset_token . '" WHERE id=' . $user['id'] . ' LIMIT 1');

        sendEmail($user['email'], $user['name'], $reset_token);

?>
        <div class="alert alert-success text-center">
            Az email-t kiküldtük! Ellenőrizze postaládáját!
        </div>
    <?php

    } else {
    ?>
        <div class="alert alert-danger text-center">
            Nincs ilyen email cím!
        </div>
<?php

    }
}
?>

<div class="row justify-content-center mt-5">
    <div class="col-2">
        <div class="card">
            <div class="card-header bg-success bg-gradient">
                <h5>Új jelszó igénylése</h5>
            </div>
            <form action="index.php?module=password_recovery" method="post">
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Küldés</button>
                </div>
            </form>
        </div>
    </div>
</div>