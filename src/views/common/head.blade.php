        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <link rel="shortcut icon" href="{{ Config::get('photon::photon.package_assets_uri') }}/favicon.ico" />
        <link rel="apple-touch-icon" href="{{ Config::get('photon::photon.package_assets_uri') }}/iosicon.png" />
<!--    DEVELOPMENT LESS -->
        <link rel="stylesheet/less" href="{{ Config::get('photon::photon.package_assets_uri') }}/css/photon.less?<?=time()?>" media="all" />
        <link rel="stylesheet/less" href="{{ Config::get('photon::photon.package_assets_uri') }}/css/photon-responsive.less?<?=time()?>" media="all" />

        <link rel="stylesheet/less" href="{{ Config::get('photon::photon.package_assets_uri') }}/css/changes.css?<?=time()?>" media="all" />

<!--    PRODUCTION CSS -->
        <!-- <link rel="stylesheet" href="{{ Config::get('photon::photon.package_assets_uri') }}/css/css_compiled/photon-min.css?v1.1" media="all" /> -->
        <!-- <link rel="stylesheet" href="{{ Config::get('photon::photon.package_assets_uri') }}/css/css_compiled/photon-min-blessed1.css?v1.1" media="all" /> -->
        <!-- <link rel="stylesheet" href="{{ Config::get('photon::photon.package_assets_uri') }}/css/css_compiled/photon-responsive-min.css?v1.1" media="all" /> -->

<!--[if IE]>
        <link rel="stylesheet" type="text/css" href="{{ Config::get('photon::photon.package_assets_uri') }}/css/css_compiled/ie-only-min.css?v1.1" />

<![endif]-->

<!--[if lt IE 9]>
        <link rel="stylesheet" type="text/css" href="{{ Config::get('photon::photon.package_assets_uri') }}/css/css_compiled/ie8-only-min.css?v1.1" />
        <script type="text/javascript" src="{{ Config::get('photon::photon.package_assets_uri') }}/js/plugins/excanvas.js"></script>
        <script type="text/javascript" src="{{ Config::get('photon::photon.package_assets_uri') }}/js/plugins/html5shiv.js"></script>
        <script type="text/javascript" src="{{ Config::get('photon::photon.package_assets_uri') }}/js/plugins/respond.min.js"></script>
        <script type="text/javascript" src="{{ Config::get('photon::photon.package_assets_uri') }}/js/plugins/fixFontIcons.js"></script>
<![endif]-->

