// Vote

function SendRate() {
    let rate = 0;
    let ratingStars = document.getElementsByName("rating");
    let idSpot = document.getElementById("mySpotId");
    let idUser = document.getElementById("myUserId");

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
            idSpot: idSpot.value,
            idUser: idUser.value,
        },
        success: function (result) {
            document.getElementById("containerStar").innerHTML = result;
        },
    });
}

// Like

function AddLike() {
    let liked = document.getElementById("myUserLiked");
    let idSpot = document.getElementById("mySpotId");

    $.ajax({
        url: "../controller/addLike.php",
        type: "POST",
        data: {
            liked: liked.value,
            idSpot: idSpot.value,
        },
        success: function (result) {
            let containerLike = document.getElementById("containerLike");
            let valueLike = parseInt(containerLike.innerText);
            let heartIcon = ' <i class="bi bi-heart-fill" style="font-size: 0.9rem;"></i>';

            if (liked.value == "0") {
                valueLike++;
                containerLike.innerHTML = valueLike + heartIcon;
                containerLike.style = "color : #ff5454 !important";

                liked.value = "1";
            } else if (liked.value == "1") {
                valueLike--;
                containerLike.innerHTML = valueLike + heartIcon;
                containerLike.style = "color : #6c757d !important";

                liked.value = "0";
            }
        },
    });
}
