window.fbLoaded = function () {
    addFacebookLoginEvent();
};

function addFacebookLoginEvent() {
    $('#fbConnect').click(() => {
        FB.login(function (result) {
            console.log(result);
            if (result.status === 'connected') {
                console.log('ok');
                window.location.href = window.URLs.dashboard;
            }
        }, {scope: 'public_profile, email'})
    });
}
