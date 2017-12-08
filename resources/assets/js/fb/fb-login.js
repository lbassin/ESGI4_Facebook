window.fbLoaded = function () {
    addFacebookLoginEvent();
};

function addFacebookLoginEvent() {
    $('#fbConnect').click(() => {
        FB.login(function (result) {
            if (result.status === 'connected') {
                window.location.href = window.URLs.dashboard;
            }
        }, {scope: window.fbData.scope})
    });
}
