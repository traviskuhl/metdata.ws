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
        <script src="http://yui.yahooapis.com/3.8.1/build/yui/yui-min.js"></script>
        <style type="text/css">
            header{
                background: #eee;
                box-shadow: inset 0 -2px 10px #ccc;
                padding: 10px;
            }
            header h1 a {
                font-family: "Roboto Condensed";
                text-decoration: none;
                color: #888;
                text-shadow: #fafafa 1px 1px;
                float: right;
                margin-right: 10px;
            }
            header form input {
                border: none;
                border-left: solid 2px #ccc;
                background: transparent;
                font-family: arial;
                padding: 5px 10px;
                width: 70%;
                color: #777;
                outline: none;
            }
            header form input:focus {
                border-left: solid 2px #ff8400;
                color: #333;
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
        </style>
    </head>
    <body>
        <header>
            <h1><a href="/">&lt;meta&gt;data.ws</a></h1>
            <form method="GET" action="<?php echo URI; ?>">
                <input type="test" placeholder="URL" value="<% url %>">
            </form>
        </header>
        <article></article>
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
                        Y.io('<?php echo URI; ?>api/page.json', {
                            'method': 'GET',
                            'data': {"url": value, '.pretty': true},
                            'on': {
                                'complete': function(id, o) {
                                    var json = Y.JSON.parse(o.responseText);
                                    $("article").setHTML("<a href='/api/page.json?url="+json.response.url+"'><?php echo URI; ?>api/page.json?url="+json.response.url+"</a><pre class='prettyprint'>"+o.responseText+"</pre>");
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