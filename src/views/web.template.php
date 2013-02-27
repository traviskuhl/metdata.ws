<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>metadata.ws</title>
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.8.1/build/cssreset/cssreset-min.css">
        <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed' rel='stylesheet' type='text/css'>
        <link href="//netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome.css" rel="stylesheet">
        <script src="http://yui.yahooapis.com/3.8.1/build/yui/yui-min.js"></script>
        <style type="text/css">
            header{
                background: #eee;
                box-shadow: inset 0 -2px 10px #ccc;
                padding: 10px;
                z-index: 10px;
            }
            header h1 {
                position: absolute;
                top: -99em;
                left: -99em;
            }
            header form input {
                border: none;
                background: transparent;
                font-family: arial;
                padding: 5px 10px;
                width: 70%;
                color: #777;
                outline: none;
                position: relative;
                margin-left: 20px;
            }
            header form:before {
                font-family: "FontAwesome";
                content: '\f002';
                position: absolute;
                top: 14px;
                left: 14px;
                color: #777;
            }
            header form input:focus {
                color: #333;
            }
            header nav {
                display: block;
                float: right;
                margin: 5px 20px 0 0;
            }
            header nav a {
                display: inline-block;
                font-size: 16px;
                color: #999;
                margin-left: 10px;
            }
            header nav a:hover {
                color: #333;
            }
            header nav a em {
                display: inline-block;
                text-indent: -999em;
            }
            header nav a:hover em {
                text-indent: 0;

            }
            article {
                padding: 30px;
            }
            article a {
                display: block;
                color: #333;
                text-decoration: none;
                padding-bottom: 10px;
                font-family: arial;
            }
            article a:hover {
                color: #ff8400;
            }
            article pre.prettyprint {
                border: none;
                overflow: auto;
            }
            i.icon-refresh {
                -webkit-transition: all .8s ease-out;
                -moz-transition: all .8s ease-out;
                transition: all .8s ease-out;
                opacity:0;
                top: -10%;
                left: 50%;
                position: fixed;
                margin: -25px 0 0 -25px;
                font-size: 50px;
                color: #eee;
                text-shadow: #ccc -1px -1px;
            }
            body.loading i.icon-refresh {
                top: 50%;
                left: 50%;
                opacity:1;
            }
        </style>
    </head>
    <body>
        <header>
            <h1><a href="/">&lt;meta&gt;data.ws</a></h1>
            <nav>
                <a href="https://github.com/traviskuhl/metdata.ws" class="icon-github-alt"><em>Github</em></a>
                <a href="https://github.com/traviskuhl/metdata.ws" class="icon-book"><em>Docs</em></a>
                <a href="https://twitter.com/traviskuhl" class="icon-twitter"><em>@traviskuhl</em></a>
            </nav>
            <form method="GET" action="<?php echo URI; ?>">
                <input type="test" placeholder="URL" value="<% url %>">
            </form>

        </header>
        <article></article>
        <i class="icon-refresh icon-spin"></i>

        <script src="http://s3.amazonaws.com/prod.kuhl.co/google-code-prettify/run_prettify.js?autoload=true"></script>
        <script src="http://s3.amazonaws.com/prod.kuhl.co/google-code-prettify/prettify.js"></script>

        <script type="text/javascript">
            YUI().use("node", "io", "querystring-stringify-simple", "json-parse", "history", function(Y){
                var $ = Y.Node.one, history = new Y.History(), input = $("header form input");
                var out = false, search = function(){
                    var value = input.get('value');
                    history.addValue('page', value, {
                        'title': "<meta>data.ws",
                        'url': '<?php echo URI; ?>?url='+value
                    });
                    if (value.indexOf('.') !== -1) {
                        $("body").addClass('loading');
                        Y.io('<?php echo URI; ?>api/page.json', {
                            'method': 'GET',
                            'data': {"url": value, '.pretty': true},
                            'on': {
                                'complete': function(id, o) {
                                    var json = Y.JSON.parse(o.responseText);
                                    $("body").removeClass('loading');
                                    $("article").setHTML("<a href='/api/page.json?url="+json.response.url+"'><i class=' icon-caret-right'></i> <?php echo URI; ?>api/page.json?url="+json.response.url+"</a><pre class='prettyprint'>"+o.responseText+"</pre>");
                                    prettyPrint();
                                }
                            }
                        });
                    }
                };
                input.on('keypress', function(){
                    clearTimeout(out); out = window.setTimeout(function(){ search() }, 500);
                });
                input.on('change', function(){
                    clearTimeout(out); out = window.setTimeout(function(){ search() }, 500);
                });
                if (input.get('value')) {
                    search();
                }
                else if (document.referrer) {
                    input.set('value', document.referrer);
                    search();
                }
            });
        </script>
    </body>
</html>