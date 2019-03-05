<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />

    <meta name="google-site-verification" content="aR2l54Przdb1etfZj0wNgacTxcg1D2Z6I2AoJ7y3RhU" />

    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="/s3/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="/s3/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/s3/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />

    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="/s3/assets/global/plugins/socicon/socicon.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->

    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="/s3/assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
    <link href="/s3/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="/s3/assets/layouts/layout3/css/layout.min.css" rel="stylesheet" type="text/css" />
    <link href="/s3/assets/layouts/layout3/css/custom.min.css" rel="stylesheet" type="text/css" />

    <link href="/s3/assets/pages/css/profile.min.css" rel="stylesheet" type="text/css" />


    <link href="/css/theme.css" rel="stylesheet" type="text/css" id="style_color" />

    <!-- PLAYER -->
    <link rel="stylesheet" href="/css/bar-ui.css" />
    <link rel="stylesheet" href="/css/player-button.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/soundmanager2/2.97a.20150601/script/soundmanager2-jsmin.js"></script>
    <script src="/js/bar-ui.js"></script>
    <script src="/js/player-button.js"></script>


    <!-- PODFLIX CUSTOM -->
    <link rel="stylesheet" href="/css/app.css" />


    <!-- CSRF Token -->
    <meta id="token" value="{{csrf_token()}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
        ]); ?>
    </script>

    <!-- Google Adsense page-level ads -->
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script>
      (adsbygoogle = window.adsbygoogle || []).push({
        google_ad_client: "ca-pub-000000000000",
        enable_page_level_ads: true
      });
    </script>

</head>
<body class="page-container-bg-solid">
<div class="page-wrapper">
    <!-- BEGIN HEADER -->
    <div class="page-wrapper-row">
        <div class="page-wrapper-top" style="background: #444d58; ">
            <!-- BEGIN HEADER -->
            <div class="page-header">
                <!-- BEGIN HEADER TOP -->
                <div class="page-header-top">
                    <div class="container">
                        <!-- BEGIN LOGO -->
                        <div class="page-logo">
                            <a href="/">
                                <img src="/image/logo.png" alt="logo" class="logo-default" style="margin: 13px 0 0; padding: 0;">
                            </a>
                        </div>
                        <!-- END LOGO -->
                        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                        <a href="javascript:;" class="menu-toggler"></a>
                        <!-- END RESPONSIVE MENU TOGGLER -->
                        <!-- BEGIN TOP NAVIGATION MENU -->
                        <div class="top-menu">
                            <ul class="nav navbar-nav pull-right">

                                <!-- BEGIN USER LOGIN DROPDOWN -->
                                @if (Auth::guest())
                                    <li><a href="{{ url('/login') }}">Entrar</a></li>
                                    <!--<li><a href="{{ url('/register') }}">Register</a></li>-->
                                @else
                                    <li class="dropdown dropdown-user dropdown-dark">
                                        <a href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="false">
                                            <span class="username">{{ Auth::user()->name }}</span>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-default">
                                            <li>
                                                <a href="/subscription_manager">
                                                    <i class="icon-rocket"></i> Podcasts Assinados
                                                </a>
                                            </li>
                                            <li class="divider"> </li>
                                            <li>
                                                <a href="{{ url('/logout') }}"
                                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                                    <i class="icon-key"></i> Logout
                                                </a>
                                                <form id="logout-form" action="{{ url('/logout') }}" method="POST"
                                                      style="display: none;">
                                                    {{ csrf_field() }}
                                                </form>
                                            </li>
                                        </ul>
                                    </li>
                            @endif
                            <!-- END USER LOGIN DROPDOWN -->
                            </ul>
                        </div>
                        <!-- END TOP NAVIGATION MENU -->
                    </div>
                </div>
                <!-- END HEADER TOP -->
                <!-- BEGIN HEADER MENU -->
                <div class="page-header-menu">
                    <div class="container">
                        <!-- BEGIN HEADER SEARCH BOX -->
                        <form class="search-form" action="/search" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Procurar por podcasts, temas, episódios, participantes" name="q">
                                <span class="input-group-btn">
                                            <a href="javascript:;" class="btn submit">
                                                <i class="icon-magnifier"></i>
                                            </a>
                                        </span>
                            </div>
                        </form>
                        <!-- END HEADER SEARCH BOX -->
                        <!-- BEGIN MEGA MENU -->
                        <!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
                        <!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the dropdown opening on mouse hover -->
                        <div class="hor-menu  ">
                            <ul class="nav navbar-nav">

                                <li>
                                    <a href="/"> Início </a>
                                </li>

                                <li>
                                    <a href="/trending"></i> Em Alta </a>
                                </li>

                                <li>
                                    <a href="/featured"> Lançamentos </a>
                                </li>

                                <li>
                                    <a href="/newbies"> Novos Programas </a>
                                </li>

                                <li>
                                    <a href="/drops"> Drops </a>
                                </li>

                                <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown ">
                                    <a href="javascript:;"> Categorias
                                        <span class="arrow"></span>
                                    </a>
                                    <ul class="dropdown-menu pull-left">

                                        <?php
                                        $catalogs = array(
                                                array('name' => 'Filmes','slug' => 'filmes'),
                                                array('name' => 'Jogos','slug' => 'jogos'),
                                                array('name' => 'Livros e HQs','slug' => 'livros-e-hqs'),
                                                array('name' => 'Tecnologia','slug' => 'tecnologia'),
                                                array('name' => 'Variedades','slug' => 'variedades'),
                                                array('name' => 'Entrevistas','slug' => 'entrevistas'),
                                                array('name' => 'Música','slug' => 'musica'),
                                                array('name' => 'Entretenimento','slug' => 'entretenimento'),
                                                array('name' => 'Esporte','slug' => 'esporte'),
                                                array('name' => 'Culturas e Tradições Religiosas','slug' => 'cultura-e-tradicoes-religiosas'),
                                                array('name' => 'História Geral','slug' => 'historia-geral'),
                                                array('name' => 'Podcasts de Rádios','slug' => 'podcasts-de-radios')
                                        );
                                        ?>

                                        @foreach($catalogs as $catalog)
                                            <?php $catalog = (object) $catalog; ?>
                                            <li aria-haspopup="true" class=" ">
                                                <a href="/episodes/{{$catalog->slug}}" class="nav-link">
                                                    {{$catalog->name}}
                                                </a>
                                            </li>
                                        @endforeach

                                    </ul>
                                </li>

                                @if (!Auth::guest())
                                    <li>
                                        <a href="/subscriptions"> Inscrições </a>
                                    </li>
                                    <li>
                                        <a href="/playlist"> Playlist </a>
                                    </li>
                                @endif

                                <li><a href="/feed/request/program/create/"><i class="fa fa-plus"></i> Podcast </a></li>





                            </ul>
                        </div>
                        <!-- END MEGA MENU -->
                    </div>
                </div>
                <!-- END HEADER MENU -->
            </div>
            <!-- END HEADER -->
        </div>
    </div>


    <div class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <div class="page-container">
                <div class="page-content-wrapper">
                    <div class="page-content">
                <div class="container">
                    <div class="row">
                    <div class="col-md-12">
                    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Podflix-Web-responsivo -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-0000000000000000"
     data-ad-slot="000000000"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
                     </div>
                     </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


    <div id="app" class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <!-- BEGIN CONTENT -->
            <div class="page-container">
                <!-- BEGIN PAGE CONTENT -->
                <div class="page-content-wrapper">
                    <div class="page-content">
                        @yield('content')
                    </div>
                </div>
                <!-- END PAGE CONTENT -->
            </div>
            <!-- END CONTENT -->
        </div>
    </div>




    <div class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <div class="page-container">
                <div class="page-content-wrapper">
                    <div class="page-content">
                <div class="container">
                    <div class="row">
                    <div class="col-md-12">
                    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Podflix-Web-responsivo -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-000000000"
     data-ad-slot="0000000"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
                     </div>
                     </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


    <div class="page-wrapper-row">
        <div class="page-wrapper-bottom">
            <!-- BEGIN FOOTER -->
            <!-- BEGIN PRE-FOOTER -->
            <div class="page-prefooter">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12 footer-block">
                            <h2>Podflix</h2>
                            <p>Encontre os Podcasts que adora. Conecte-se diretamente com seus programas favoritos. </p>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs12 footer-block">
                            <h2>Enviar</h2>
                            <a href="/feed/request/program/create">Adicionar Podcast</a>
                            
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12 footer-block">
                            <h2>Redes Sociais</h2>
                            <ul class="social-icons">
                                <li>
                                    <a href="https://facebook.com/" target="_blank" data-original-title="facebook" class="facebook"></a>
                                </li>
                                <li>
                                    <a href="https://twitter.com/" target="_blank" data-original-title="twitter" class="twitter"></a>
                                </li>
                            </ul>
                        </div>

                        <div class="col-md-3 col-sm-6 col-xs-12 footer-block">
                            <h2>Contato</h2>
                            <address class="margin-bottom-40"> Email:
                                <a href="mailto:">EMAIL</a>
                            </address>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END PRE-FOOTER -->
            <!-- BEGIN INNER FOOTER -->
            <div class="page-footer">
                <div class="container"> {{date("Y")}} © Podflix
                </div>
            </div>
            <div class="scroll-to-top" style="display: block;">
                <i class="icon-arrow-up"></i>
            </div>
            <!-- END INNER FOOTER -->
            <!-- END FOOTER -->
        </div>
    </div>


    <!-- END QUICK NAV -->
    <!--[if lt IE 9]>
    <script src="/s3/assets/global/plugins/respond.min.js"></script>
    <script src="/s3/assets/global/plugins/excanvas.min.js"></script>
    <script src="/s3/assets/global/plugins/ie8.fix.min.js"></script>
    <![endif]-->
    <!-- BEGIN CORE PLUGINS -->

    <script src="https://unpkg.com/vue/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/vue.resource/1.0.3/vue-resource.min.js"></script>

    <script src="/s3/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN PAGE LEVEL PLUGINS
    <script src="/s3/assets/global/plugins/moment.min.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/morris/morris.min.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/morris/raphael-min.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/amcharts/amcharts/amcharts.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/amcharts/amcharts/serial.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/amcharts/amcharts/pie.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/amcharts/amcharts/radar.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/amcharts/amcharts/themes/light.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/amcharts/amcharts/themes/patterns.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/amcharts/amcharts/themes/chalk.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/amcharts/ammap/ammap.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/amcharts/ammap/maps/js/worldLow.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/amcharts/amstockcharts/amstock.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/horizontal-timeline/horizontal-timeline.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js" type="text/javascript"></script>
    -->
    <script src="/s3/assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
    <!--<script src="/s3/assets/global/plugins/jqvmap/jqvmap/jquery.vmap.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js" type="text/javascript"></script>
    -->
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <script src="/s3/assets/global/scripts/app.min.js" type="text/javascript"></script>
    <!-- END THEME GLOBAL SCRIPTS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <!--
    <script src="/s3/assets/pages/scripts/dashboard.min.js" type="text/javascript"></script>
    -->
    <!-- END PAGE LEVEL SCRIPTS -->

    <!-- BEGIN PAGE PROFILE - PODCASTS LEVEL SCRIPTS -->
    <script src="/s3/assets/pages/scripts/profile.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->

    <!-- BEGIN FEED REQUEST WIZARD PAGE LEVEL PLUGINS -->
    <script src="/s3/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
    <script src="/s3/assets/global/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js" type="text/javascript"></script>
    <script src="/s3/assets/pages/scripts/form-wizard.min.js" type="text/javascript"></script>
    <!--<script src="/s3/assets/pages/scripts/form-validation.min.js" type="text/javascript"></script>-->
    <script src="/s3/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
    <script src="/s3/assets/pages/scripts/ui-blockui.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->


    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <script src="/s3/assets/layouts/layout3/scripts/layout.min.js" type="text/javascript"></script>
    <script src="/s3/assets/layouts/layout3/scripts/demo.min.js" type="text/javascript"></script>
    <script src="/s3/assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
    <script src="/s3/assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
    <!-- END THEME LAYOUT SCRIPTS -->
    
    <!-- VUE.JS -->
    <script src="/js/vue-app.js"></script>


    <!-- BEGIN ADSENSE -->
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
    <!-- END ADSENSE -->

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-41263900-1', 'auto');
        ga('send', 'pageview');
    </script>

    <!-- BEGIN FACEBOOK SHARE -->
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v2.8&appId=595756863837116";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
    <!-- END FACEBOOK SHARE -->

</body>
</html>
