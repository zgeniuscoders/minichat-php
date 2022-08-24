<?php

$errors = [];

if ($_POST) {
    extract($_POST);
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Cette address email est invalid";
    }

    if (empty($name) || !preg_match("/^[a-zA-Z]+$/", $name)) {
        $errors["name"] = "Alphanumerique uniquement (aA-zZ)";
    }

    if (strlen($password) < 6) {
        $errors["password"] = "Le mot de passe doit contenir plus des 6 caracteres";
    } else if ($password != $password_confirm) {
        $errors["password-confirm"] = "Le mot de passe ne correspond pas";
    }

    if (!empty($_FILES["profile-picture"])) {
        $maxSize = 20480;
        $fileName = $_FILES["profile-picture"]["name"];
        $fileSize = $_FILES["profile-picture"]["size"];
        $ext = "." . strtolower(substr(strrchr($fileName, "."), 1));
        $tmp = $_FILES["profile-picture"]["tmp_name"];
        $uuname =  md5(uniqid(rand(), true));
        $extAuthorize = [".jpg", ".png", ".JPG", ".PNG"];

        if ($_FILES["profile-picture"]["error"] > 0) {
            $errors["profile-picture"] = "la photo de profile na pu pas être uploader";
        }
        if (!in_array($ext, $extAuthorize)) {
            $errors["profile-picture"] = "Extension de cette fichier n'est pas autorisé";
        }
    }
}



?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/app.css" />
</head>

<body class="bg-gray-800 text-gray-100">
    <div class="flex h-screen items-center justify-center">
        <form action="" method="post" class="bg-gray-100 p-4 text-gray-700" style="width: 450px;" enctype="multipart/form-data">
            <div class="my-2">
                <label for="email" class="mb-2 block">Adresse email</label>
                <input type="title" name="email" id="email" class="border-solid border-2 w-full outline-none p-2 rounded">
                <?php if (isset($errors["email"])) : ?>
                    <span class="text-red-300"><?= $errors["email"] ? $errors["email"] : '' ?></span>
                <?php endif ?>
            </div>
            <div class="my-2">
                <label for="name" class="mb-2 block">Pseudo</label>
                <input type="title" name="name" id="name" class="border-solid border-2 w-full outline-none p-2 rounded">
                <?php if (isset($errors["name"])) : ?>
                    <span class="text-red-300"><?= $errors["name"] ? $errors["name"] : '' ?></span>
                <?php endif ?>
            </div>
            <div class="my-2">
                <label for="password" class="mb-2 block">Mot de passe</label>
                <input type="password" name="password" id="password" class="border-solid border-2 w-full outline-none p-2 rounded">
                <?php if (isset($errors["password"])) : ?>
                    <span class="text-red-300"><?= $errors["password"] ? $errors["password"] : '' ?></span>
                <?php endif ?>
            </div>
            <div class="my-2">
                <label for="password-confirm" class="mb-2 block">Confirmation du mot de passe</label>
                <input type="password" name="password_confirm" id="password-confirm" class="border-solid border-2 w-full outline-none p-2 rounded">
                <?php if (isset($errors["password-confirm"])) : ?>
                    <span class="text-red-300"><?= $errors["password-confirm"] ? $errors["password-confirm"] : '' ?></span>
                <?php endif ?>
            </div>
            <div class="my-2">
                <label for="profile-picture" class="mb-2 block">Photo du profil</label>
                <input type="file" name="profile-picture" id="profile-picture" class="border-solid border-2 w-full outline-none p-2 rounded">
                <?php if (isset($errors["profile-picture"])) : ?>
                    <span class="text-red-300"><?= $errors["profile-picture"] ? $errors["profile-picture"] : '' ?></span>
                <?php endif ?>
            </div>
            <button type="submit" class="block bg-blue-600 w-full text-gray-100 p-2 rounded">enregitrer</button>
        </form>
    </div>
</body>

</html>