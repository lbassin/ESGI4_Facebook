window.fbLoaded = function () {
    addFacebookLoginEvent();
};

function addFacebookLoginEvent() {
    $('#reAskPermissions').click(() => {
        FB.login(result => {
            window.location.href = redirectTo;
        }, {
            scope: window.fbData.scope,
            auth_type: 'rerequest'
        });
    })
}