<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="{$lang}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="robots" content="index,follow" />
    {if !$header_description}
    	{if $lang == "es"}
    		<meta name="description" content="Inthedir: Directorio internacional de Artes Escénicas. Compañías y festivales de teatro, danza, circo y títeres." />
    	{else}
        	<meta name="description" content="Inthedir: Performing Arts Directory. Make your theatre, circus, puppets or dance company easy to find, contact and discover." />
        {/if}
    {else}
        <meta name="description" content="{$header_description}" />
    {/if}

    {if $lang == "es"}
    	<meta name="keywords" content="teatro, danza, artes escénicas, circo, gestión, títeres, escena, espectáculo, festivales, comedia, drama, artistas, cultura, gestión cultural" />
    {else}
    	<meta name="keywords" content="performing arts, theatre, dance, circus, management, puppets, theater, scene, performance, shows, arts, comedy, drama, artists" />
    {/if}

    <title>{$title}</title>
    
    <meta name="image" content="{$img_path}inthedir.png" />
    
    <link rel="shortcut icon" type="image/png" href="{$static_content}images/itd-favicon.png" />

    {if $print}
    	<link rel="stylesheet" href="{$static_content}assets/css/blueprint/print.css" type="text/css" media="print" />
    {/if}

    {if $local === TRUE}
    	<link rel="stylesheet" href="{$static_content}assets/css/inthedir.css" type="text/css" media="screen, projection" />
    {else}
    	<link rel="stylesheet" href="{$static_content}assets/css/inthedir-min.css?v=36817" type="text/css" media="screen, projection" />
    {/if}
    
    <!--[if lt IE 8]>
        <link rel="stylesheet" href="{$static_content}assets/css/blueprint/ie.css" type="text/css" media="screen, projection" />
    <![endif]-->
    
    {if $include_js}
    	<script type="text/javascript" src="{$web_root}assets/js/js_lang.php"></script>
    	{* JS Translations *}
    	<script type="text/javascript"> 
    		var delete_item = "";
            var cancel = "";
            var primary_image = ""; 
            var set_primary_image = ""; 
            var set_primary_image_title = "";
    	</script>
    	
        <script src="http://www.google.com/jsapi" type="text/javascript"></script>
        
        <script type="text/javascript">
        google.load("jquery", "1.4.4");
        google.load("jqueryui", "1.8.16");
        </script>
        
        <!-- UI Tools: Tabs, Tooltip, Scrollable and Overlay (4.05 Kb) -->
		<script type="text/javascript" src="http://cdn.jquerytools.org/1.2.5/all/jquery.tools.min.js"></script>
		
        {* Put livequery, global and global-dashboard together *}
        <script src="{$web_root}assets/js/itd.js" type="text/javascript"></script>
    {/if}
    
    {if $gallery_plugin}
    	<link rel="stylesheet" href="{$web_root}assets/js/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
    	<script src="{$web_root}assets/js/fancybox/jquery.fancybox.pack.js" type="text/javascript"></script>
    {/if}
    
    {if isset($chosen)}
    	<link rel="stylesheet" href="{$static_content}assets/css/chosen-big-min.css" type="text/css" media="screen, projection" /> 
		<script type="text/javascript" src="{$web_root}assets/js/chosen.jquery.min.js"></script>
	{/if}
	
    {if isset($scroll)}
    	<script src="{$static_content}assets/js/jquery.ias.min.js" type="text/javascript"></script>
    {/if}
    
    {block name=extrajs}{/block}

    {* Analytics *}

    {* Google+ *}
</head>
<body>
	<div id="header">
		<div id="headercontent">
			<div id="logo">
				<a href="{$web_root}{if $lang != 'e'}{$lang}{/if}">
					<img src="{$img_path}inthedir.png" alt="Inthedir Logo" class="logo" width="233" height="59" />
				</a> 
				<h1>{t}Inthedir - International Performing Arts Directory{/t}</h1>
				<h4><span class="itdcolor">In</span>ternational <span class="itdcolor">The</span>atre <span class="itdcolor">Dir</span>ectory</h4>
			</div>
			<div id="topaccess">
				{if $logged}
					{if $first_name}
						{t name=$first_name}Hi %1{/t}:
					{/if} 
					{url type="anchor" url="account/" text="{t}Dashboard{/t}" attr="title='{t}Access your account{/t}'"}
					<img src="{$img_path}icons/user-16.png" alt="user" width="16" height="16" /> 
					
					{if $role == 'company' && $company} 
						| {url type="anchor" url="company/{$normalized_name}" text="{t}Profile{/t}" attr="title='{t}View your public profile{/t}'"} 
						<img src="{$img_path}icons/profile.png" alt="profile" width="16" height="16" /> 
					{/if}
					| {url type="anchor" url="access/logout" text="{t}Logout{/t}" attr=""}
					<img src="{$img_path}icons/logout.png" alt="logout" width="16" height="16" /> 
				{else}
					{url type="anchor" url="access/login" text="{t}Login{/t}" attr=""}
				{/if} 
				{$lang_selector} 
			</div>
			
			<br class="clear" />

		</div> {* headercontent *}
		
		<div id="menus">
			<ul class="menu-items">
				<li>
					{url type="anchor" url="" text="{t}Home{/t}" attr=""}
				</li>
				<li {if $place== 'market'} class="current"{/if}>
					{url type="anchor" url="/market/All" text="{t}Market{/t}" attr="title='{t}Theatre Market{/t}'"}
				</li>

				<li {if $place== 'companies'} class="current"{/if}>
					{url type="anchor" url="/companies/All" text="{t}Companies{/t}" attr=""}
				</li>
				<li {if $place== 'shows'} class="current"{/if}>
					{url type="anchor" url="/shows/All" text="{t}Shows{/t}" attr=""}
				</li>
				<li {if $place== 'festivals'} class="current"{/if}>
					{url type="anchor" url="/festivals/All" text="{t}Festivals{/t}" attr=""}
				</li>

            	<li class="search">
            		{form_open("/search/", $top_form_attributes)}
            			{form_input($q)} {form_submit($top_form_submit)}
            		{form_close()}
            	</li>
			</ul>
		</div> {* menus *}
	</div> {* header *}
	
	<div class="container">
		<div id="itd-content">
			{if $categories}
				<div id="categories">
					{if $category_tagline} 
						<span class="cattag">{$category_tagline}</span>
					{/if}
					<ul class="category-items horizontal">
						{foreach from=$categories item=menu}
							<li 
							{if is_array($category_normalized_name)}
								{if in_array($menu.normalized_name, $category_normalized_name)}
									class="current"
								{/if}
							{elseif $category_normalized_name == $menu.normalized_name}
								class="current"
							{/if}
							>
								{url type="anchor"
	    						url="{$place}/{$menu.normalized_name}/{$subcategory}"
	    						text="{$menu.cat_name}" attr=""}
	    					</li> 
						{/foreach}

						<li {if $category_normalized_name == 'All'} class="current"{/if}>
							{url type="anchor" 
							url="{$place}/All/{$subcategory}" text="{t}All{/t}" 
							attr="title='{t}Performing Arts Companies{/t}'"}
						</li>
					</ul>
				</div> {* Categories *} 
			{/if}
			
			{if $subcategories}
				<div id="subcategories">
					{if $subcategory_tagline} 
						<span class="cattag">{$subcategory_tagline}</span>
					{/if}
					<ul class="category-items horizontal">
						{foreach from=$subcategories item=menu}
						<li {if $subcategory== $menu.normalized_name} class="current"{/if}>
							{if is_array($category_normalized_name)}
								{url
	    						type="anchor" url="{$place}/{$category_normalized_name[0]}/{$menu.normalized_name}/"
	    						text="{$menu.cat_name}" attr=""}
							{else}
								{url
	    						type="anchor" url="{$place}/{$category_normalized_name}/{$menu.normalized_name}/"
	    						text="{$menu.cat_name}" attr=""}
							{/if}
							
						</li> 
						{/foreach}

						<li {if $subcategory== 'All'} class="current"{/if}>
							{if is_array($category_normalized_name)}
								{url
	    						type="anchor" url="{$place}/{$category_normalized_name[0]}/All"
	    						text="{t}All{/t}" attr="title='{t}Performing Arts Shows{/t}'"}
							{else}
								{url
	    						type="anchor" url="{$place}/{$category_normalized_name}/All"
	    						text="{t}All{/t}" attr="title='{t}Performing Arts Shows{/t}'"}
							{/if}
							
						</li>

					</ul>
				</div>
			{/if} {* Subcategories *} 
			
			<br class="clear" /> 

			{block name=content}{/block} 

			{block name=sidebar}{/block}

			{block name=extracontent}{/block}
		</div>
		<!-- itd-content -->
	</div>
	<!--  container -->

	<div class="clear"></div>

	<div id="footer">
		<div class="wide">
			<div class="block">
				<h4>{t}About{/t}</h4>
				<ul>
					<li>{url type="anchor" url="/page/about" text="{t}About{/t}" attr="title='{t}About in the Dir{/t}'"}</li>
					<li>{url type="anchor" url="/page/contact" text="{t}Contact us{/t}" attr="title='{t}Contact us{/t}'"}</li>
				</ul>
			</div>

			<div class="block">
				<h4>{t}Follow{/t}</h4>

			</div>

			<div class="block">
				<ul>
					<li><small>&copy; 2010-{$smarty.now|date_format:"%Y"} <a href="http://www.bolorino.net/">José Bolorino</a></small></li>
				</ul>
			</div>
			

		</div>
		
			{*<small>{$elapsed_time}</small>*}
		
	</div>
	<noscript>
		<div id="nojavascript">
			<p>
				<span>{t}WARNING:{/t} </span> {t}We have detected that you currently have Javascript disabled.{/t} 
				{t}Inthedir requires the use of Javascript.{/t} 
				{t}For the best possible viewing experience we highly recommend that you enable Javascript via your browser's options.{/t}
			</p>
		</div>
	</noscript>
	
	{block name=footerjs}{/block}

	{if isset($message)}
        {literal}
            <script type="text/javascript">
                var $div = $('#message');
                var height = $div.height();
                $div.hide().css({ height : 0 });
                (function() {
                    $div.show().animate({ height : height }, { duration: 500 });

                    $div.click(function () {
                        if ( $div.is(':visible') ) {
                            $div.animate({ height: 0 }, { duration: 500, complete: function () {
                            $div.hide();
                            }});
                        }
                    });
                })();
            </script>
        {/literal}
    {/if}
	
</body>
</html>