<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minta KFT. Project Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>

    <div class="container-fluid header d-flex justify-content-start align-items-center">
        <h1 class="bg-light p-3 user-select-none">
            Minta KFT. Project Management
            <?php
            if (isset($_SESSION['name'])) echo ' - ' . $_SESSION['name'] . ' (' . $_SESSION['permission'] . ')';
            ?>
        </h1>
    </div>