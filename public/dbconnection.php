<?php

    try{

        if(session_status() === PHP_SESSION_NONE)
        {
            session_start();
        }

        $pdo = new PDO("mysql:dbname=minichat;host=127.0.0.1","root","",[
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ]);
    }catch(PDOException $e)
    {
        die($e->getMessage());
    }