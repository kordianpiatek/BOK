document.addEventListener("DOMContentLoaded", function () {

    $(".addOpen").click(function () {

        $.ajax({
            type: "POST",
            url: "../api/AssertConversation.php",
            data: {
                id: $(this).val(),
                supportId: $(this).attr('data-button')
            },

            success: function(data) {
                if (data == "refresh"){
                    window.location.reload();
                }
            }
        });
    });

});