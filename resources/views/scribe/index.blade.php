<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>ElePHPant.me API</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "https://www.elephpant.me";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.11.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.11.0.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-elephpants" class="tocify-header">
                <li class="tocify-item level-1" data-unique="elephpants">
                    <a href="#elephpants">Elephpants</a>
                </li>
                                    <ul id="tocify-subheader-elephpants" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="elephpants-GETapi-elephpants">
                                <a href="#elephpants-GETapi-elephpants">List all elephpants</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="elephpants-GETapi-elephpants--id-">
                                <a href="#elephpants-GETapi-elephpants--id-">Get a single elePHPant</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-herds" class="tocify-header">
                <li class="tocify-item level-1" data-unique="herds">
                    <a href="#herds">Herds</a>
                </li>
                                    <ul id="tocify-subheader-herds" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="herds-GETapi-herd--username-">
                                <a href="#herds-GETapi-herd--username-">Get a collector's herd</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: September 21, 2026</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<p>A free, public JSON API for the PHP community.</p>
<aside>
    <strong>Base URL</strong>: <code>https://www.elephpant.me</code>
</aside>
<p>Everything here is public, read-only and needs no API key. Herds marked private by their
owner are excluded from every endpoint.</p>
<p>The machine-readable spec lives at <a href="/openapi.yaml">/openapi.yaml</a>. Country codes are
ISO 3166-1 alpha-3 (<code>DNK</code>, <code>GBR</code>, <code>BRA</code>).</p>
<p>Be kind to the herd: cache what you fetch rather than re-reading the whole catalogue.</p>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>This API is not authenticated.</p>

        <h1 id="elephpants">Elephpants</h1>

    <p>The elePHPant species catalogue.</p>

                                <h2 id="elephpants-GETapi-elephpants">List all elephpants</h2>

<p>
</p>

<p>Returns a paginated list of all elephpant species, ordered by year and name.</p>

<span id="example-requests-GETapi-elephpants">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://www.elephpant.me/api/elephpants?page=1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://www.elephpant.me/api/elephpants"
);

const params = {
    "page": "1",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-elephpants">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Original Blue&quot;,
            &quot;description&quot;: &quot;First Blue&quot;,
            &quot;sponsor&quot;: &quot;Nexen / Alter Way&quot;,
            &quot;year&quot;: 2007,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/1-original-blue.jpg&quot;,
            &quot;owners&quot;: 2,
            &quot;url&quot;: &quot;https://www.elephpant.me/api/elephpants/1&quot;
        },
        {
            &quot;id&quot;: 5,
            &quot;name&quot;: &quot;Oracle&quot;,
            &quot;description&quot;: &quot;Oracle Blue&quot;,
            &quot;sponsor&quot;: &quot;Oracle Corporation&quot;,
            &quot;year&quot;: 2008,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/5-oracle.jpg&quot;,
            &quot;owners&quot;: 1,
            &quot;url&quot;: &quot;https://www.elephpant.me/api/elephpants/5&quot;
        },
        {
            &quot;id&quot;: 6,
            &quot;name&quot;: &quot;Zend Blue&quot;,
            &quot;description&quot;: &quot;ZendCon 2010&quot;,
            &quot;sponsor&quot;: &quot;Zend Technologies&quot;,
            &quot;year&quot;: 2010,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/6-zend-blue.jpg&quot;,
            &quot;owners&quot;: 2,
            &quot;url&quot;: &quot;https://www.elephpant.me/api/elephpants/6&quot;
        },
        {
            &quot;id&quot;: 2,
            &quot;name&quot;: &quot;Original Pink&quot;,
            &quot;description&quot;: &quot;First Pink&quot;,
            &quot;sponsor&quot;: &quot;Alter Way&quot;,
            &quot;year&quot;: 2011,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/2-original-pink.jpg&quot;,
            &quot;owners&quot;: 2,
            &quot;url&quot;: &quot;https://www.elephpant.me/api/elephpants/2&quot;
        },
        {
            &quot;id&quot;: 13,
            &quot;name&quot;: &quot;Zend Framework&quot;,
            &quot;description&quot;: &quot;Zend Green&quot;,
            &quot;sponsor&quot;: &quot;Zend Technologies&quot;,
            &quot;year&quot;: 2012,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/13-zend-framework.jpg&quot;,
            &quot;owners&quot;: 3,
            &quot;url&quot;: &quot;https://www.elephpant.me/api/elephpants/13&quot;
        },
        {
            &quot;id&quot;: 7,
            &quot;name&quot;: &quot;Chili&quot;,
            &quot;description&quot;: &quot;Zend Red, ZendCon 2013&quot;,
            &quot;sponsor&quot;: &quot;Zend Technologies&quot;,
            &quot;year&quot;: 2013,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/7-chili.jpg&quot;,
            &quot;owners&quot;: 0,
            &quot;url&quot;: &quot;https://www.elephpant.me/api/elephpants/7&quot;
        },
        {
            &quot;id&quot;: 14,
            &quot;name&quot;: &quot;Archie&quot;,
            &quot;description&quot;: &quot;php[architect]&quot;,
            &quot;sponsor&quot;: &quot;php[architect]&quot;,
            &quot;year&quot;: 2014,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/14-archie.jpg&quot;,
            &quot;owners&quot;: 2,
            &quot;url&quot;: &quot;https://www.elephpant.me/api/elephpants/14&quot;
        },
        {
            &quot;id&quot;: 18,
            &quot;name&quot;: &quot;Liona&quot;,
            &quot;description&quot;: &quot;Laravel&quot;,
            &quot;sponsor&quot;: &quot;Laravel Community&quot;,
            &quot;year&quot;: 2014,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/18-liona.jpg&quot;,
            &quot;owners&quot;: 0,
            &quot;url&quot;: &quot;https://www.elephpant.me/api/elephpants/18&quot;
        },
        {
            &quot;id&quot;: 19,
            &quot;name&quot;: &quot;MurPHPy&quot;,
            &quot;description&quot;: &quot;AmsterdamPHP&quot;,
            &quot;sponsor&quot;: &quot;AmsterdamPHP Community&quot;,
            &quot;year&quot;: 2014,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/19-murphpy.jpg&quot;,
            &quot;owners&quot;: 2,
            &quot;url&quot;: &quot;https://www.elephpant.me/api/elephpants/19&quot;
        },
        {
            &quot;id&quot;: 16,
            &quot;name&quot;: &quot;Sonny&quot;,
            &quot;description&quot;: &quot;Sunshine PHP Conference&quot;,
            &quot;sponsor&quot;: &quot;Sunshine PHP, Zend Technologies&quot;,
            &quot;year&quot;: 2014,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/16-sonny.jpg&quot;,
            &quot;owners&quot;: 0,
            &quot;url&quot;: &quot;https://www.elephpant.me/api/elephpants/16&quot;
        },
        {
            &quot;id&quot;: 15,
            &quot;name&quot;: &quot;Umoja&quot;,
            &quot;description&quot;: &quot;PHP Women&quot;,
            &quot;sponsor&quot;: &quot;PHP Women&quot;,
            &quot;year&quot;: 2014,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/15-umoja.jpg&quot;,
            &quot;owners&quot;: 2,
            &quot;url&quot;: &quot;https://www.elephpant.me/api/elephpants/15&quot;
        },
        {
            &quot;id&quot;: 8,
            &quot;name&quot;: &quot;Z-Ray&quot;,
            &quot;description&quot;: &quot;ZendCon 2014&quot;,
            &quot;sponsor&quot;: &quot;Zend Technologies&quot;,
            &quot;year&quot;: 2014,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/8-z-ray.jpg&quot;,
            &quot;owners&quot;: 0,
            &quot;url&quot;: &quot;https://www.elephpant.me/api/elephpants/8&quot;
        },
        {
            &quot;id&quot;: 3,
            &quot;name&quot;: &quot;Blue&quot;,
            &quot;description&quot;: &quot;OpenGoodies Blue&quot;,
            &quot;sponsor&quot;: &quot;OpenGoodies&quot;,
            &quot;year&quot;: 2015,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/3-blue.jpg&quot;,
            &quot;owners&quot;: 1,
            &quot;url&quot;: &quot;https://www.elephpant.me/api/elephpants/3&quot;
        },
        {
            &quot;id&quot;: 37,
            &quot;name&quot;: &quot;Molly&quot;,
            &quot;description&quot;: &quot;PHP Woolly Mammoth&quot;,
            &quot;sponsor&quot;: &quot;Peter Meth / Softer Software&quot;,
            &quot;year&quot;: 2015,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/37-molly.jpg&quot;,
            &quot;owners&quot;: 1,
            &quot;url&quot;: &quot;https://www.elephpant.me/api/elephpants/37&quot;
        },
        {
            &quot;id&quot;: 51,
            &quot;name&quot;: &quot;PHPCon Grandpa&quot;,
            &quot;description&quot;: &quot;PHPCon Poland 2015&quot;,
            &quot;sponsor&quot;: &quot;PHP Conference Poland&quot;,
            &quot;year&quot;: 2015,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/51-phpcon-grandpa.jpg&quot;,
            &quot;owners&quot;: 1,
            &quot;url&quot;: &quot;https://www.elephpant.me/api/elephpants/51&quot;
        },
        {
            &quot;id&quot;: 20,
            &quot;name&quot;: &quot;Phil Snow&quot;,
            &quot;description&quot;: &quot;ConFoo Web Techno Conference&quot;,
            &quot;sponsor&quot;: &quot;ConFoo&quot;,
            &quot;year&quot;: 2015,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/20-phil-snow.jpg&quot;,
            &quot;owners&quot;: 1,
            &quot;url&quot;: &quot;https://www.elephpant.me/api/elephpants/20&quot;
        },
        {
            &quot;id&quot;: 4,
            &quot;name&quot;: &quot;Pink&quot;,
            &quot;description&quot;: &quot;OpenGoodies Pink&quot;,
            &quot;sponsor&quot;: &quot;OpenGoodies&quot;,
            &quot;year&quot;: 2015,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/4-pink.jpg&quot;,
            &quot;owners&quot;: 1,
            &quot;url&quot;: &quot;https://www.elephpant.me/api/elephpants/4&quot;
        },
        {
            &quot;id&quot;: 21,
            &quot;name&quot;: &quot;Symfony 10 Years&quot;,
            &quot;description&quot;: &quot;Symfony Framework&quot;,
            &quot;sponsor&quot;: &quot;Sensio Labs&quot;,
            &quot;year&quot;: 2015,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/21-symfony-10-years.jpg&quot;,
            &quot;owners&quot;: 0,
            &quot;url&quot;: &quot;https://www.elephpant.me/api/elephpants/21&quot;
        },
        {
            &quot;id&quot;: 9,
            &quot;name&quot;: &quot;Zend PHP7&quot;,
            &quot;description&quot;: &quot;ZendCon 2015&quot;,
            &quot;sponsor&quot;: &quot;Zend Technologies&quot;,
            &quot;year&quot;: 2015,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/9-zend-php7.jpg&quot;,
            &quot;owners&quot;: 1,
            &quot;url&quot;: &quot;https://www.elephpant.me/api/elephpants/9&quot;
        },
        {
            &quot;id&quot;: 38,
            &quot;name&quot;: &quot;pHackyderm&quot;,
            &quot;description&quot;: &quot;HHVM&quot;,
            &quot;sponsor&quot;: &quot;Facebook&quot;,
            &quot;year&quot;: 2015,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/38-phackyderm.jpg&quot;,
            &quot;owners&quot;: 0,
            &quot;url&quot;: &quot;https://www.elephpant.me/api/elephpants/38&quot;
        }
    ],
    &quot;links&quot;: {
        &quot;first&quot;: &quot;https://www.elephpant.me/api/elephpants?page=1&quot;,
        &quot;last&quot;: &quot;https://www.elephpant.me/api/elephpants?page=5&quot;,
        &quot;prev&quot;: null,
        &quot;next&quot;: &quot;https://www.elephpant.me/api/elephpants?page=2&quot;
    },
    &quot;meta&quot;: {
        &quot;current_page&quot;: 1,
        &quot;from&quot;: 1,
        &quot;last_page&quot;: 5,
        &quot;links&quot;: [
            {
                &quot;url&quot;: null,
                &quot;label&quot;: &quot;&amp;laquo; Previous&quot;,
                &quot;page&quot;: null,
                &quot;active&quot;: false
            },
            {
                &quot;url&quot;: &quot;https://www.elephpant.me/api/elephpants?page=1&quot;,
                &quot;label&quot;: &quot;1&quot;,
                &quot;page&quot;: 1,
                &quot;active&quot;: true
            },
            {
                &quot;url&quot;: &quot;https://www.elephpant.me/api/elephpants?page=2&quot;,
                &quot;label&quot;: &quot;2&quot;,
                &quot;page&quot;: 2,
                &quot;active&quot;: false
            },
            {
                &quot;url&quot;: &quot;https://www.elephpant.me/api/elephpants?page=3&quot;,
                &quot;label&quot;: &quot;3&quot;,
                &quot;page&quot;: 3,
                &quot;active&quot;: false
            },
            {
                &quot;url&quot;: &quot;https://www.elephpant.me/api/elephpants?page=4&quot;,
                &quot;label&quot;: &quot;4&quot;,
                &quot;page&quot;: 4,
                &quot;active&quot;: false
            },
            {
                &quot;url&quot;: &quot;https://www.elephpant.me/api/elephpants?page=5&quot;,
                &quot;label&quot;: &quot;5&quot;,
                &quot;page&quot;: 5,
                &quot;active&quot;: false
            },
            {
                &quot;url&quot;: &quot;https://www.elephpant.me/api/elephpants?page=2&quot;,
                &quot;label&quot;: &quot;Next &amp;raquo;&quot;,
                &quot;page&quot;: 2,
                &quot;active&quot;: false
            }
        ],
        &quot;path&quot;: &quot;https://www.elephpant.me/api/elephpants&quot;,
        &quot;per_page&quot;: 20,
        &quot;to&quot;: 20,
        &quot;total&quot;: 89
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-elephpants" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-elephpants"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-elephpants"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-elephpants" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-elephpants">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-elephpants" data-method="GET"
      data-path="api/elephpants"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-elephpants', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-elephpants"
                    onclick="tryItOut('GETapi-elephpants');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-elephpants"
                    onclick="cancelTryOut('GETapi-elephpants');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-elephpants"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/elephpants</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-elephpants"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-elephpants"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="page"                data-endpoint="GETapi-elephpants"
               value="1"
               data-component="query">
    <br>
<p>Page number. Example: <code>1</code></p>
            </div>
                </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>data</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>

            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>owners</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Number of collectors that have at least one of this elePHPant in their herd.</p>
                    </div>
                                    </details>
        </div>
                        <h2 id="elephpants-GETapi-elephpants--id-">Get a single elePHPant</h2>

<p>
</p>



<span id="example-requests-GETapi-elephpants--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://www.elephpant.me/api/elephpants/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://www.elephpant.me/api/elephpants/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-elephpants--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Original Blue&quot;,
        &quot;description&quot;: &quot;First Blue&quot;,
        &quot;sponsor&quot;: &quot;Nexen / Alter Way&quot;,
        &quot;year&quot;: 2007,
        &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/1-original-blue.jpg&quot;,
        &quot;owners&quot;: 2,
        &quot;url&quot;: &quot;https://www.elephpant.me/api/elephpants/1&quot;
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-elephpants--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-elephpants--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-elephpants--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-elephpants--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-elephpants--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-elephpants--id-" data-method="GET"
      data-path="api/elephpants/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-elephpants--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-elephpants--id-"
                    onclick="tryItOut('GETapi-elephpants--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-elephpants--id-"
                    onclick="cancelTryOut('GETapi-elephpants--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-elephpants--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/elephpants/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-elephpants--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-elephpants--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-elephpants--id-"
               value="1"
               data-component="url">
    <br>
<p>elePHPant ID. Example: <code>1</code></p>
            </div>
                    </form>

                <h1 id="herds">Herds</h1>

    <p>A single collector's herd of elePHPants.</p>

                                <h2 id="herds-GETapi-herd--username-">Get a collector&#039;s herd</h2>

<p>
</p>

<p>Returns the full herd of a registered collector, including statistics and collected elePHPants. 403s if the herd is private.</p>

<span id="example-requests-GETapi-herd--username-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://www.elephpant.me/api/herd/john" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://www.elephpant.me/api/herd/john"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-herd--username-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;username&quot;: &quot;john&quot;,
    &quot;name&quot;: &quot;John Doe&quot;,
    &quot;avatar&quot;: &quot;https://api.microlink.io/?url=https://twitter.com/john&amp;embed=image.url&quot;,
    &quot;country&quot;: &quot;USA&quot;,
    &quot;x_handle&quot;: &quot;john&quot;,
    &quot;mastodon&quot;: &quot;@john@elephpant.me&quot;,
    &quot;bluesky&quot;: &quot;@john.bsky.social&quot;,
    &quot;herd_url&quot;: &quot;https://www.elephpant.me/herd/john&quot;,
    &quot;stats&quot;: {
        &quot;total&quot;: 44,
        &quot;unique&quot;: 15,
        &quot;spare&quot;: 29
    },
    &quot;elephpants&quot;: [
        {
            &quot;id&quot;: 6,
            &quot;name&quot;: &quot;Zend Blue&quot;,
            &quot;description&quot;: &quot;ZendCon 2010&quot;,
            &quot;sponsor&quot;: &quot;Zend Technologies&quot;,
            &quot;year&quot;: 2010,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/6-zend-blue.jpg&quot;,
            &quot;quantity&quot;: 4
        },
        {
            &quot;id&quot;: 2,
            &quot;name&quot;: &quot;Original Pink&quot;,
            &quot;description&quot;: &quot;First Pink&quot;,
            &quot;sponsor&quot;: &quot;Alter Way&quot;,
            &quot;year&quot;: 2011,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/2-original-pink.jpg&quot;,
            &quot;quantity&quot;: 3
        },
        {
            &quot;id&quot;: 13,
            &quot;name&quot;: &quot;Zend Framework&quot;,
            &quot;description&quot;: &quot;Zend Green&quot;,
            &quot;sponsor&quot;: &quot;Zend Technologies&quot;,
            &quot;year&quot;: 2012,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/13-zend-framework.jpg&quot;,
            &quot;quantity&quot;: 4
        },
        {
            &quot;id&quot;: 14,
            &quot;name&quot;: &quot;Archie&quot;,
            &quot;description&quot;: &quot;php[architect]&quot;,
            &quot;sponsor&quot;: &quot;php[architect]&quot;,
            &quot;year&quot;: 2014,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/14-archie.jpg&quot;,
            &quot;quantity&quot;: 2
        },
        {
            &quot;id&quot;: 19,
            &quot;name&quot;: &quot;MurPHPy&quot;,
            &quot;description&quot;: &quot;AmsterdamPHP&quot;,
            &quot;sponsor&quot;: &quot;AmsterdamPHP Community&quot;,
            &quot;year&quot;: 2014,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/19-murphpy.jpg&quot;,
            &quot;quantity&quot;: 4
        },
        {
            &quot;id&quot;: 4,
            &quot;name&quot;: &quot;Pink&quot;,
            &quot;description&quot;: &quot;OpenGoodies Pink&quot;,
            &quot;sponsor&quot;: &quot;OpenGoodies&quot;,
            &quot;year&quot;: 2015,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/4-pink.jpg&quot;,
            &quot;quantity&quot;: 2
        },
        {
            &quot;id&quot;: 12,
            &quot;name&quot;: &quot;Zoe&quot;,
            &quot;description&quot;: &quot;ZendCon 2018&quot;,
            &quot;sponsor&quot;: &quot;Rogue Wave Software&quot;,
            &quot;year&quot;: 2018,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/12-zoe.jpg&quot;,
            &quot;quantity&quot;: 2
        },
        {
            &quot;id&quot;: 33,
            &quot;name&quot;: &quot;Jorvik&quot;,
            &quot;description&quot;: &quot;PHP Yorkshire&quot;,
            &quot;sponsor&quot;: &quot;PHP Yorkshire Conference&quot;,
            &quot;year&quot;: 2018,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/33-jorvik.jpg&quot;,
            &quot;quantity&quot;: 4
        },
        {
            &quot;id&quot;: 58,
            &quot;name&quot;: &quot;Aida&quot;,
            &quot;description&quot;: &quot;phpday conference mascotte&quot;,
            &quot;sponsor&quot;: &quot;GrUSP&quot;,
            &quot;year&quot;: 2020,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/58-aida.jpg&quot;,
            &quot;quantity&quot;: 3
        },
        {
            &quot;id&quot;: 60,
            &quot;name&quot;: &quot;InPHPinity&quot;,
            &quot;description&quot;: &quot;PHP8 ElePHPant&quot;,
            &quot;sponsor&quot;: &quot;@faguo &amp; @Elroubio&quot;,
            &quot;year&quot;: 2020,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/60-inphpinity.jpg&quot;,
            &quot;quantity&quot;: 2
        },
        {
            &quot;id&quot;: 75,
            &quot;name&quot;: &quot;PHPClasses Pink&quot;,
            &quot;description&quot;: &quot;PHPClasses&quot;,
            &quot;sponsor&quot;: &quot;PHPClasses&quot;,
            &quot;year&quot;: 2020,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/75-phpclasses-pink.jpg&quot;,
            &quot;quantity&quot;: 2
        },
        {
            &quot;id&quot;: 76,
            &quot;name&quot;: &quot;Flexy&quot;,
            &quot;description&quot;: &quot;Dyflexis&quot;,
            &quot;sponsor&quot;: &quot;Dyflexis&quot;,
            &quot;year&quot;: 2023,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/76-flexy.jpg&quot;,
            &quot;quantity&quot;: 4
        },
        {
            &quot;id&quot;: 79,
            &quot;name&quot;: &quot;Ploi&quot;,
            &quot;description&quot;: &quot;Ploi&quot;,
            &quot;sponsor&quot;: &quot;Ploi&quot;,
            &quot;year&quot;: 2024,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/79-ploi.jpg&quot;,
            &quot;quantity&quot;: 2
        },
        {
            &quot;id&quot;: 87,
            &quot;name&quot;: &quot;Eric the Red&quot;,
            &quot;description&quot;: &quot;laraveldk.org&quot;,
            &quot;sponsor&quot;: &quot;Laravel Denmark&quot;,
            &quot;year&quot;: 2026,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/87-eric-the-red.jpg&quot;,
            &quot;quantity&quot;: 4
        },
        {
            &quot;id&quot;: 89,
            &quot;name&quot;: &quot;Sentaur (2nd Edition)&quot;,
            &quot;description&quot;: &quot;sentry.io&quot;,
            &quot;sponsor&quot;: &quot;Sentry&quot;,
            &quot;year&quot;: 2026,
            &quot;image_url&quot;: &quot;https://www.elephpant.me/storage/elephpants/89-sentaur-2nd-edition.jpg&quot;,
            &quot;quantity&quot;: 2
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-herd--username-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-herd--username-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-herd--username-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-herd--username-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-herd--username-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-herd--username-" data-method="GET"
      data-path="api/herd/{username}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-herd--username-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-herd--username-"
                    onclick="tryItOut('GETapi-herd--username-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-herd--username-"
                    onclick="cancelTryOut('GETapi-herd--username-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-herd--username-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/herd/{username}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-herd--username-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-herd--username-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>username</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="username"                data-endpoint="GETapi-herd--username-"
               value="john"
               data-component="url">
    <br>
<p>Collector username. Example: <code>john</code></p>
            </div>
                    </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>country</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>ISO 3166-1 alpha-3 country code.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>stats</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>

            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>total</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Total elePHPants held, including spares.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>unique</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Distinct elePHPant species held.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>spare</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Extra copies beyond one of each species held (total - unique).</p>
                    </div>
                                    </details>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>elephpants</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>

            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>quantity</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>How many of this elePHPant the collector owns.</p>
                    </div>
                                    </details>
        </div>
                

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                            </div>
            </div>
</div>
</body>
</html>
