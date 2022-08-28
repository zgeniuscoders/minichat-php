<?php

$errors = [];

if ($_POST) {
    if (empty($_POST["email"]) || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "cette adress email n'est  pas valide";
    }

    if (empty($_POST["name"]) || !preg_match("/^[a-zA-Z]+$/", $_POST["name"])) {
        $errors["name"] = "Alphanumerique uniquemengt(a-zA-Z)";
    }
    if (empty($_POST["password"]) || strlen($_POST["password"]) < 6) {
        $errors["password"] = "Le mot de pass doit contenir plus de 6 caracteres";
    } else if ($_POST["password"] != $_POST["password_confirm"]) {
        $errors["password_confirm"] = "Le mot de pass ne coprespond pas";
    }

    if (empty($_FILES["picture"]["name"])) {
        $errors["picture"] = "Cette est requis";
    } else {
        $fileName =  $_FILES["picture"]["name"];
        $ext = "." . strtolower(substr(strchr($fileName, "."), 1));
        $tmp = $_FILES["picture"]["tmp_name"];
        $uunam = md5(uniqid(rand(), true));
        $extAuth = [".jpg", ".png", ".jpeg", ".JPG", ".PNG", ".JPEG"];

        if ($_FILES["picture"]["error"] > 0) {
            $errors["picture"] = "la photo napu être uploader";
        }

        if (!in_array($ext, $extAuth)) {
            $errors["picture"] = "cette extension n'est pas autoriser";
        }
    }

    if(empty($errors))
    {
        require "./dbconnection.php";

        $hash = password_hash($_POST["password"],PASSWORD_BCRYPT);
        $file = $uunam . $ext;
        $to = "./img" . DIRECTORY_SEPARATOR . $file;

        $stmt = $pdo->prepare("INSERT INTO users(name,email,photo,password) VALUES(?,?,?,?)");
        $stmt->execute([
            $_POST["name"],
            $_POST["email"],
            $file,
            $hash
        ]);


        if($stmt)
        {
            move_uploaded_file($tmp,$to);

            $id = $pdo->lastInsertId();
            $stmt = $pdo->query("SELECT * FROM users WHERE id = $id");
            $user = $stmt->fetch();

            $_SESSION["auth"] = $user;

            header("Location: index.php");
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
    <link rel="stylesheet" href="./css/app.css">
</head>

<body>
    <div class="flex items-center justify-center h-screen">
        <form action="" method="post" class="bg-gray-100 shadow-md p-4 rounded" style="width: 450px;" enctype="multipart/form-data">
            <div class="my-2">
                <label for="email">Email</label>
                <input type="title" name="email" id="email" class="w-full border-solid border-2 border-gray-300 outline-none p-2 rounded">
                <?php if(isset($errors["email"])): ?>
                    <span class="text-red-400 mt-1 block"><?= $errors["email"] ?></span>
                <?php endif; ?>    
            </div>
            <div class="my-2">
                <label for="name">Pseudo</label>
                <input type="title" name="name" id="name" class="w-full border-solid border-2 border-gray-300 outline-none p-2 rounded">
                <?php if(isset($errors["name"])): ?>
                    <span class="text-red-400 mt-1 block"><?= $errors["name"] ?></span>
                <?php endif; ?> 
            </div>
            <div class="my-2">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" class="w-full border-solid border-2 border-gray-300 outline-none p-2 rounded">
                <?php if(isset($errors["password"])): ?>
                    <span class="text-red-400 mt-1 block"><?= $errors["password"] ?></span>
                <?php endif; ?> 
            </div>
            <div class="my-2">
                <label for="password_confirm">Confirmation du mot de passe</label>
                <input type="password" name="password_confirm" id="password_confirm" class="w-full border-solid border-2 border-gray-300 outline-none p-2 rounded">
                <?php if(isset($errors["password_confirm"])): ?>
                    <span class="text-red-400 mt-1 block"><?= $errors["password_confirm"] ?></span>
                <?php endif; ?> 
            </div>
            <div class="my-2">
                <label for="picture">Photo de profile</label>
                <input type="file" name="picture" id="picture" class="w-full border-solid border-2 border-gray-300 outline-none p-2 rounded">
                <?php if(isset($errors["picture"])): ?>
                    <span class="text-red-400 mt-1 block"><?= $errors["picture"] ?></span>
                <?php endif; ?> 
            </div>
            <button type="submit" class="bg-blue-600 text-white w-full p-2 hover:bg-blue-500 rounded">enregistrer</button>
        </form>
    </div>
</body>

</html>