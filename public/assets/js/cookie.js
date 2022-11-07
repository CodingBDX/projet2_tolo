var like_anime = document.getElementById("like-anime");

like_anime.addEventListener("click", function (event) {
    event.preventDefault(); // stops modal from being shown
    document.cookie = "like_anime=John Doe";

    console.log("you push the sky");
});
