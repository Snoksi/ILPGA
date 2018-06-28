
// Pop up
$(document).ready(function () {
    $(".btnPop").click(function (e) {
        e.preventDefault();
        $(".pop, .pop-background").fadeIn(300);
    });

    $(".pop .popClose").click(function () {
        $(".pop, .pop-background").fadeOut(300);
    });

    $("#createFolder").on('submit', function (e) {
        console.log('lol');
        e.preventDefault();

        var name = $("#createFolder input[name='name']").val();

        $.ajax({
            url: "/api/tests/create_folder/"+name+"/",
            type: "POST"
        }).done(function() {
            location.reload();
        });
    });
});