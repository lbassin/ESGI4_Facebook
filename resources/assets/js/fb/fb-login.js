window.fbLoaded = function () {
    addFacebookLoginEvent();
};

function addFacebookLoginEvent() {
    $('#fbConnect').click(() => {
        FB.login(function (result) {

            if (result.status === 'connected') {
                laert('connected');
            }

        }, {scope: 'public_profile, email'})
    });
}
