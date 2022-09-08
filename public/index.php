<?php

session_start();

include "./dbconnection.php";

if (!isset($_SESSION["auth"])) {
    header("Location: login.php");
}

$stmt = $pdo->prepare("SELECT id,name,photo FROM users WHERE id != :id");
$stmt->execute([
    "id" => $_SESSION["auth"]->id
]);

$users = $stmt->fetchAll();


if (isset($_GET["id"])) {
    $stmt = $pdo->prepare("SELECT id,name,photo FROM users WHERE id = :id");
    $stmt->execute([
        "id" => $_GET["id"]
    ]);

    $userMessage = $stmt->fetch();

    if ($_POST) {
        $senderId = $_SESSION["auth"]->id;
        $receiverId = $_POST["receiverId"];
        $msg = $_POST["message"];


        $stmt = $pdo->prepare("INSERT INTO messages(sender,receiver,message) VALUES(?,?,?)");
        $stmt->execute([
            $senderId,
            $receiverId,
            $msg
        ]);
    }

    $stmt = $pdo->prepare("SELECT * FROM messages 
                            WHERE (sender = :authId AND receiver = :receiver) 
                            OR (sender = :receiver AND receiver = :authId) ");
    $stmt->execute([
        "authId" => $_SESSION["auth"]->id,
        "receiver" => $_GET["id"]
    ]);

    $messages = $stmt->fetchAll();

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/app.css">
</head>

<body>


    <div class="flex flex-row h-screen antialiased text-gray-800">

        <!-- affichages des utilisateur -->
        <div class="flex flex-row w-96 flex-shrink-0 bg-gray-100 p-4">
            <div class="flex flex-col w-full h-full pl-4 pr-4 py-4 -mr-4">
                <div class="flex flex-row items-center">
                    <div class="flex flex-row items-center">
                        <div class="text-xl font-semibold">Messages</div>
                        <div class="flex items-center justify-center ml-2 text-xs h-5 w-5 text-white bg-red-500 rounded-full font-medium">5</div>
                    </div>
                </div>
                <div class="mt-5">
                    <ul class="flex flex-row items-center justify-between">
                        <li>
                            <a href="#" class="flex items-center pb-3 text-xs font-semibold relative text-indigo-800">
                                <span>Tout les conversations</span>
                                <span class="absolute left-0 bottom-0 h-1 w-6 bg-indigo-800 rounded-full"></span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="mt-5">
                    <div class="text-xs text-gray-400 font-semibold uppercase">Groupes</div>
                </div>

                <div class="mt-5">
                    <div class="text-xs text-gray-400 font-semibold uppercase">Amis</div>
                </div>
                <div class="h-full overflow-hidden relative pt-2">

                    <!-- utilisateurs  -->

                    <div class="flex flex-col divide-y h-full overflow-y-auto -mx-4">
                        <?php foreach ($users as $user) : ?>
                            <div class="flex flex-row items-center p-4 relative">
                                <div class="absolute text-xs text-gray-500 right-0 top-0 mr-4 mt-3">2 hours ago</div>
                                <img class="flex items-center justify-center h-10 w-10 rounded-full flex-shrink-0" src="./img/<?= $user->photo ?>" />
                                <div class="flex flex-col flex-grow ml-3">
                                    <a class="text-sm font-medium" href="index.php?id=<?= $user->id ?>"><?= $user->name ?></a>
                                    <div class="text-xs truncate w-40">Good after noon! how can i help you?</div>
                                </div>
                                <div class="flex-shrink-0 ml-2 self-end mb-1">
                                    <span class="flex items-center justify-center h-5 w-5 bg-red-500 text-white text-xs rounded-full">3</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="absolute bottom-0 right-0 mr-2">
                        <button class="flex items-center justify-center shadow-sm h-10 w-10 bg-red-500 text-white rounded-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- affichages des messages  -->
        <?php if (isset($_GET["id"])) : ?>
            <div class="flex flex-col h-full w-full bg-white px-4 py-6">
                <div class="flex flex-row items-center py-4 px-6 rounded-2xl shadow">
                    <img class="flex items-center justify-center h-10 w-10 rounded-full" src="./img/<?= $userMessage->photo ?>" />
                    <div class="flex flex-col ml-3">
                        <div class="font-semibold text-sm"><?= $userMessage->name ?></div>
                        <div class="text-xs text-gray-500">En ligne</div>
                    </div>
                </div>
                <div class="h-full overflow-hidden py-4">
                    <div class="h-full overflow-y-auto">
                        <div class="grid grid-cols-12 gap-y-2">
                            <?php foreach($messages as $message): ?>

                            <?php if($message->sender == $_SESSION["auth"]->id): ?>
                                <div class="col-start-1 col-end-8 p-3 rounded-lg">
                                    <div class="flex flex-row items-center">
                                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-indigo-500 flex-shrink-0">
                                            A
                                        </div>
                                        <div class="relative ml-3 text-sm bg-white py-2 px-4 shadow rounded-xl">
                                            <div><?= $message->message ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="col-start-6 col-end-13 p-3 rounded-lg">
                                <div class="flex items-center justify-start flex-row-reverse">
                                    <div class="flex items-center justify-center h-10 w-10 rounded-full bg-indigo-500 flex-shrink-0">
                                        A
                                    </div>
                                    <div class="relative mr-3 text-sm bg-indigo-100 py-2 px-4 shadow rounded-xl">
                                        <div><?= $message->message ?></div>
                                    </div>
                                </div>
                            </div>
                            <?php endif ?>
                          
                            
                           <?php endforeach ?>
                        </div>
                    </div>
                </div>
                <div class="flex flex-row items-center">


                    <form action="" method="post" class="flex flex-row items-center w-full border rounded-3xl h-12 px-2">
                        <button class="flex items-center justify-center h-10 w-10 text-gray-400 ml-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                            </svg>
                        </button>
                        <div class="w-full">
                            <input type="hidden" name="receiverId" value="<?= $userMessage->id ?>">
                            <input type="text" class="border border-transparent w-full focus:outline-none text-sm h-10 flex items-center" placeholder="Type your message...." name="message">
                        </div>
                        <div class="flex flex-row">
                            <button class="flex items-center justify-center h-10 w-8 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                </svg>
                            </button>
                            <button class="flex items-center justify-center h-10 w-8 text-gray-400 ml-1 mr-2" type="submit">
                                <svg class="w-5 h-5 transform rotate-90 -mr-px" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        <?php endif ?>


</body>

</html>