<?php

    $error = "";

    if($_POST)
    {
        require "./dbconnection.php";

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email =?");
        $stmt->execute([$_POST["email"]]);

        $user = $stmt->fetch();

        if($user && password_verify($_POST["password"],$user->password))
        {
            $_SESSION["auth"] = $user;
            header("Location: index.php");
        }else{
            $error = "Adress email ou mot de pass incorect";
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
            </div>

            <div class="my-2">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" class="w-full border-solid border-2 border-gray-300 outline-none p-2 rounded">
            </div>

            <?php if($error): ?>
                <span class="text-red-400 mt-1 block"><?= $error ?></span>
            <?php endif ?>    

            <button type="submit" class="bg-blue-600 text-white w-full p-2 hover:bg-blue-500 rounded">enregistrer</button>
        </form>
    </div>
</body>

</html>