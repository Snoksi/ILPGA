
// Pop up
$(document).ready(function () {
    $(".btnPop").click(function (e) {
        e.preventDefault();
        $(".pop").fadeIn(300);
        positionPopup();
    });

    $(".pop > span").click(function () {
        $(".pop").fadeOut(300);
    });
});