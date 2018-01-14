window.fbLoaded = function () {
    addFacebookReAuthEvent();
};

function addFacebookReAuthEvent() {
    $('#reAskPermissions').click(() => {
        FB.login(result => {
            window.location.href = redirectTo;
        }, {
            scope: window.fbData.scope,
            auth_type: 'rerequest'
        });
    })
}