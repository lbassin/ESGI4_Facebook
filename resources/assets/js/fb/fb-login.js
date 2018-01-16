window.fbLoaded = function () {
    addFacebookLoginEvent();
};

function addFacebookLoginEvent() {
    $('.login-fb').click(() => {
        FB.login(function (result) {
            if (result.status === 'connected') {
                window.location.href = window.URLs.dashboard;
            } else {
                alert('Error');
            }
        }, {
            scope: window.fbData.scope
        });
    });
}