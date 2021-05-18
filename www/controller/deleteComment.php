<?php

require_once "../model/Comment.php";
require_once "../view/session.php";

SetSessionLevel(2);


// ---- FIELDS ---- 

$comment = new Comment();
$idComment = filter_input(INPUT_GET, 'idComment', FILTER_DEFAULT, FILTER_SANITIZE_NUMBER_INT);
$idSpot = filter_input(INPUT_GET, 'idSpot', FILTER_DEFAULT, FILTER_SANITIZE_NUMBER_INT);


// ---- DELETE COMMENT ----

$comment->DeleteComment($idComment);


// ---- RETURN PAGE ----

header('location: ../view/oneSpot.php?idSpot=' . $idSpot);
exit;
