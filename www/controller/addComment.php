<?php

require_once "../model/Comment.php";


// ---- FIELDS ---- 

$comment = new Comment();

$idUser = 1; //! NOT SUPPOSE TO TAKE IT LIKE THAT 
$idSpot = filter_input(INPUT_POST, 'addComment_IdSpot', FILTER_DEFAULT, FILTER_SANITIZE_NUMBER_INT);
$content = filter_input(INPUT_POST, 'addComment_Content', FILTER_DEFAULT, FILTER_SANITIZE_STRING);

$error = null;
$myComment = null;


// ---- FILTRE DATA ----

if (empty($content))
    $error = 'The content of the comment is empty. ';

if ($error != null) {
    header("location: ../view/oneSpot.php?idSpot=" . $idSpot . "&error=" . $error);
    exit;
}


// ---- ADD COMMENT ----

$myComment = $comment->AddComment($idUser, $idSpot, $content);


// ---- RETURN ----

if (!empty($myComment)) {
    header("location: ../view/oneSpot.php?idSpot=" . $idSpot);
    exit;
}
