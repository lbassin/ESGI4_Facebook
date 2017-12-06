window.fbAsyncInit = function () {
    FB.init({
        appId: '149733959103073',
        cookie: true,
        xfbml: true,
        version: 'v2.10'
    });

    window.fbLoaded();
};

(function (d, s, id) {
    let js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {
        return;
    }
    js = d.createElement(s);
    js.id = id;
    js.src = "https://connect.facebook.net/fr_FR/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
