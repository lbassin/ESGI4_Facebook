@extends('layouts/app')

@section('title', 'Politique de confidentialité')

@section('header_scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/animejs/2.2.0/anime.min.js"></script>
@endsection

@section('content')
    <div id="home" style="height: 100px;">
        <nav>
            <div class="logo">
                Wawat
            </div>
            <a href="#">Pricing</a>
            <a href="#">Docs</a>
            <a href="#">Support</a>
            <a class="primary fb-login-open" href="#">Get started</a>
        </nav>
    </div>

    <div id="policy">
        <h1>Politique de confidentialité</h1>
        <h2>Généralités</h2>
        <div class="policy-text">
            <p>
                Wawat attache une grande importance à la protection et au respect de votre vie privée. C'est pour cela que
                nous nous engageons à protéger votre confidentialité ainsi qu'à sécuriser vos données en notre possession.
            </p>
            <p>
                La présente politique vise à vous informer de nos pratiques concernant la collecte, l'utilisation et le
                partage des informations que vous êtes amenés à nous fournir par l'utilisation de notre application
                accessible via le site internet https://wawat.io/.
            </p>
        </div>
        <h2>Les informations que nous recueillons</h2>
        <div class="policy-text">
            <p>
                Au cours de votre utilisation de l'application Wawat, des données nécessaires au bon fonctionnement de la
                solution proposée sont conservées sur nos serveurs. Vous trouverez ci-dessous les différentes données
                susceptibles d'être enregistrées.
            </p>
            <h3>Manuellement</h3>
            <div>
                <p>
                    En utilisant notre plateforme, vous pourrez être amenés à nous transmettre des informations dont
                    certaines sont de nature à vous identifier (données personnelles).
                </p>
                <p>
                    La collecte de telles informations s'effectue exclusivement via différents formulaires présents sur
                    l'application. Voici les différentes informations susceptibles de vous être demandées lors de
                    l'utilisation de nos services :
                </p>
                <ul>
                    <li>Nom et prénom</li>
                    <li>Adresse email</li>
                </ul>
                <p>
                    Ces informations sont nécessaires dans le cadre d'une demande d'aide à notre équipe via la partie
                    "Support" de l'application.
                </p>
            </div>
            <h3>Automatique</h3>
            <div>
                <p>
                    Pour accéder à l'application Wawat, il est obligatoire de s'identifier à l'aide d'un compte Facebook. De
                    ce fait, toutes les informations sont récupérées depuis Facebook chaque fois que cela est nécessaire.
                    Nous n'enregistrons aucune donnée personnelle sur nos serveurs.
                </p>
                <p>
                    Toutes les photos utilisées sont enregistrées au sein de l'application Facebook et sont donc soumises
                    aux politiques de confidentialité de Facebook. Aucune copie des médias envoyés sur l'application n'est
                    conservée.
                </p>
                <p>
                    Il est possible que des informations telles que votre adresse IP, les heures de connexion ainsi que les
                    différentes pages consultées soient enregistrées. Ces données ont pour buts d'établir des statistiques
                    sur l'utilisation de nos services et d'aider à l'amélioration de ces derniers.
                </p>
            </div>
            <h3>Durée de conservation</h3>
            <div>
                <p>
                    A l'exception d'une demande précise et écrite de votre part, vos données sont conservées pendant 5 ans
                    sur notre plateforme après votre dernière utilisation de nos services.
                </p>
                <p>
                    Il est possible de réduire cette durée dans certaines conditions détaillées ci-après dans la rubrique
                    "Vos droits".
                </p>
            </div>
        </div>
        <h2>Utilisation des données</h2>
        <div class="policy-text">
            <p>
                Wawat s'engage à ne pas vendre ou distribuer vos données à un organisme tiers.
            </p>
            <p>
                Nous rappelons que les services proposés sont liés à Facebook à l'aide d'outils mis à disposition par ce
                dernier. Toute action réalisée sur notre solution est donc susceptible d'être connue et analysée par
                Facebook.
            </p>
        </div>
        <h2>Protection des données</h2>
        <div class="policy-text">
            <p>
                Dans un but de protection de votre vie privée, nous avons mis en place plusieurs systèmes afin de garantir
                la protection de vos données. Nos serveurs sont configurés et maintenus à jour afin de pallier aux
                potentielles attaques informatiques que nos services pourraient subir.
            </p>
            <p>
                Tous les services proposés par Wawat sont accessibles à l'aide d'un protocole sécurisé (HTTPS) permettant de
                chiffrer chaque échange effectué entre l'utilisateur et nos serveurs.
            </p>
        </div>
        <h2>Qu'arrive-t-il aux données supprimées ?</h2>
        <div class="policy-text">
            <p>
                Lors de la suppression de donnée enregistrée sur nos serveurs (non stocké sur Facebook), ces informations
                sont marquées comme "archivées" et ne sont plus disponibles.
            </p>
            <p>
                L'archivage de données ne signifie pas la suppression de ces dernières. Ces informations seront conservées
                afin de pouvoir les restaurer. La restauration est possible dans plusieurs situations telles qu'une demande
                de l'utilisateur via le support de l'application ou dans le cas d'un litige.
            </p>
        </div>
        <h2>Vos droits</h2>
        <div class="policy-text">
            <p>
                Lorsque cela est prévu par la législation applicable, vous avez le droit de nous demander une copie de vos
                données personnelles que nous détenons. Avant de répondre à votre demande, nous sommes susceptibles de vous
                demander de nous confirmer votre identité.
            </p>
            <p>
                Nous nous efforcerons de donner suite à votre demande dans un délai raisonnable, au maximum dans les délais
                fixés par la loi.
            </p>
        </div>
        <h2>Cookies</h2>
        <div class="policy-text">
            <p>
                Les cookies sont des petits fichiers textes qui servent à stocker des informations sur les navigateurs web
                de l'utilisateur. Les cookies sont notamment utilisés pour stocker et recevoir des identifiants et d'autres
                informations permettant d'utiliser les fonctionnalités proposées par nos services. Ces derniers peuvent
                aussi être utilisés dans le but d'établir des statistiques sur l'utilisation de l'application par
                l'utilisateur.
            </p>
            <p>
                Tous les services proposés par Wawat utilisent différents cookies afin d'offrir une éxperience optimale lors
                de l'utilisation de notre plateforme.
            </p>
            <p>
                Vous êtes libre de configurer votre navigateur afin de refuser l'utilisation de cookies. Il est important de
                signaler que le refus d'utilisation des cookies entrainera une utilisation limitée de nos services.
            </p>
            <p>
                Il vous est possible de consulter les informations fournies par la CNIL afin de vous indiquer comment
                configurer l'utilisation des cookies sur votre navigateur :
                https://www.cnil.fr/fr/cookies-les-outils-pour-les-maitriser
            </p>
        </div>
        <h2>Mise à jour de notre politique de confidentialité</h2>
        <div class="policy-text">
            <p>
                Nous pouvons modifier la présente politique à tout moment et sans préavis. Pensez à la consulter
                régulièrement.
            </p>
            <p>
                Votre utilisation continue de la plateforme vaut comme acceptation de ces modifications
            </p>
        </div>
        <h2>Contact</h2>
        <div class="policy-text">
            <p>
                Pour toute question relative à la présente politique de confidentialité ou pour toute demande relative à vos
                données personnelles, vous pouvez nous contacter en ligne à l'aide du formulaire de contact.
            </p>
        </div>
    </div>

    @include('home.login-modal')

    <script>
        $(document).ready(function () {
            let logo = $('#home').find('.logo');
            logo.hide();

            logo.each(function () {
                $(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='letter'>$&</span>"));
            });

            setTimeout(function () {
                logo.show();
                anime.timeline({
                    loop: false
                }).add({
                    targets: '#home .logo .letter',
                    opacity: [0, 1],
                    scale: [0, 1],
                    duration: 1500,
                    elasticity: 600,
                    delay: function delay(el, i) {
                        return 90 * (i + 1);
                    }
                });
            }, 1500);
        });

        $('.fb-login-close').click(closeModal);
        $('.fb-login-open').click(openModal);

        function openModal(){
            $('.login-modal').fadeIn();
            $('.login-modal-overlay').fadeIn();
        }

        function closeModal(){
            $('.login-modal').fadeOut();
            $('.login-modal-overlay').fadeOut();
        }
    </script>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/fb-login.js') }}"></script>
@endsection

