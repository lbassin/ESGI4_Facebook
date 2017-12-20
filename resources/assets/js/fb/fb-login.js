window.fbLoaded = function () {
    addFacebookLoginEvent();
};

function addFacebookLoginEvent() {
    $('.spin').click(() => {
        var target = $(this).attr("id");
        if ($(".spin").hasClass("done")) {
            // Do nothing
        } else {
            $(".spin").addClass("processing");
            FB.login(function (result) {
                if (result.status === 'connected') {
                    setTimeout(function () {
                        $(".spin").removeClass("processing");
                        $(".spin").addClass("done");
                        setTimeout(function () {
                            window.location.href = window.URLs.dashboard;
                        }, 500);
                    }, 1500);
                } else {
                    $(".spin").removeClass("processing");
                    $(".spin").addClass("spin");
                }
            }, {
                scope: window.fbData.scope
            });
        }
    });
}