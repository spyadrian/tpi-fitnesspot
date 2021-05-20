/**
 * Author       :   Adrian Spycher
 * Project      :   FitnesSpot
 * Filename     :   likesVotesManager.js
 * Description  :   It's where all the functioning of the likes and the votes are.
 */

// ---- Vote ----

/**
 * @summary     Send a rate to the database, acording to the actual data.
 */
function SendRate() {
    // geting the data
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

    // call ajax to set the rate in the database
    $.ajax({
        url: "../controller/sendRate.php",
        type: "POST",
        data: {
            rate: rate,
            idSpot: idSpot.value,
            idUser: idUser.value,
        },
        success: function (result) {
            // refresh view
            document.getElementById("containerStar").innerHTML = result;
        },
    });
}

// ---- Like ----

/**
 * @summary     Add a like to the database, acording to the actual data.
 */
function AddLike() {
    // getting data
    let liked = document.getElementById("myUserLiked");
    let idSpot = document.getElementById("mySpotId");

    // call ajax to add a like to the database
    $.ajax({
        url: "../controller/addLike.php",
        type: "POST",
        data: {
            liked: liked.value,
            idSpot: idSpot.value,
        },
        success: function (result) {
            // refresh view
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
