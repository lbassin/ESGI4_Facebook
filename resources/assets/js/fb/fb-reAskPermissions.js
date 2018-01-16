window.fbLoaded = function () {
    addFacebookReAuthEvent();
};

function addFacebookReAuthEvent() {
    $('#reAskPermissions').click(() => {
        FB.login(result => {
            if (result.status !== 'connected') {
                addError('Les permissions Facebook sont nécessaires');
                return;
            }

            window.location.href = redirectTo;
        }, {
            scope: window.fbData.scope,
            auth_type: 'rerequest'
        });
    })
}