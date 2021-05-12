// Vote

function SendRate() {
    let rate = 0;
    let ratingStars = document.getElementsByName("rating");
    let idSpot = document.getElementById("mySpotId").value;
    let idUser = document.getElementById("myUserId").value;

    for (const star of ratingStars) {
        if (star.checked) {
            rate = star.value;
            break;
        }
    }

    $.ajax({
        url: "../controller/sendRate.php",
        type: "POST",
        data: {
            rate: rate,
            idSpot: idSpot,
            idUser: idUser,
        },
        success: function (result) {
            //? maybe make that with an call ajax in order not to refresh the view
            if (result == "success") location.reload();
        },
    });
}

// Like

function AddLike() {
    let liked = document.getElementById("myUserLiked").value;
    let idSpot = document.getElementById("mySpotId").value;
    let idUser = document.getElementById("myUserId").value;

    $.ajax({
        url: "../controller/addLike.php",
        type: "POST",
        data: {
            liked: liked,
            idSpot: idSpot,
            idUser: idUser,
        },
        success: function (result) {
            console.log(result);
            //? maybe make that with an call ajax in order not to refresh the view
            if (result == "success") location.reload();
        },
    });
}
