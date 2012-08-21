{* Extend master template *}
{extends file="master.tpl"}

{block name=content}
<div id="spectacleinfo" class="single-contentleft">
	<h2>{$view_spectacle.spectacle_name}</h2>
    
	{if $main_image}
		<div class="groupimage"> 
			<img src="{$user_image}{$main_image.media}" alt="{$view_spectacle.spectacle_name}" />
		</div> 
	{/if}

    <ul class="share">
        <li><a title="Twitt" href="http://twitter.com/share" class="twitter-share-button">{t}Twitt{/t}</a></li> 
        <li><g:plusone></g:plusone></li>
        <li><fb:like href="{$fb_url}" send="false" layout="button_count" width="250" show_faces="false"></fb:like></li> 
    </ul>

    <br class="clear" />

	<div id="sinopsis">
		<h3>{t}Synopsis{/t}</h3>
		<p>
			{$view_spectacle.sinopsis|nl2br|pb2b}
		</p>
	</div>
	
	{if $view_spectacle_videos}
		{foreach from=$view_spectacle_videos item=video}
    		<div class="spectacle_video">
    			{$video.embed}
        	</div>
        {/foreach}
	{/if}
	
	{if $view_spectacle.credit_titles}
    	<div id="credits">
    		<h3>{t}Credit Titles{/t}</h3>
    		<p>
    			{$view_spectacle.credit_titles|nl2br|pb2b}
    		</p>
    	</div>
    {/if}
    	
    {if $role == 'manager' OR $view_spectacle.company_id == $ucid}	
    	{if $view_spectacle.sheet}
    		<div id="sheet">
    			<h3>{t}Sheet{/t}</h3>
    			<p>
    				{$view_spectacle.sheet|nl2br|pb2b}
    			</p>
    		</div>
    	{/if}
	{/if}

    <hr class="space" />

</div>
{/block}

{block name=sidebar}
    <!-- sidebar -->
    <div id="itd-single-sidebar">
    	<div class="box">
            {if $view_spectacle.company_id == $ucid OR $role == 'manager' && $manager_status == 'approved'}
                {if $role == 'manager'}
                    {if !$inlist}
                        <a id="addspectacle" name="{$view_spectacle.id}" href="#" class="btn">{t}Add to your notebook{/t}</a>
                        <div id="added_spectacle"></div>
                    {else}
                        <span class="small">{t}Show in your notebook{/t}</span>
                    {/if}
                {else}
                    {url type="anchor" url="dashboard/spectacle/{$view_spectacle.id}" text="{t}Update{/t}" attr='class="btn"'}
                {/if}
                <hr class="space" />
            {/if}
            <p>
    		{t}A show from{/t}
    		</p> 
    		<h3>{url type="anchor" url="company/{$view_company.normalized_name}" text="{$view_company.company_name}" attr=""}</h3>
    		
    		{if $spectacle_offers}
    			<h3>{t}Current offers to hire this show{/t}</h3>
    			{foreach $spectacle_offers as $offer}
    				<img src="{$img_path}icons/market/calendar.png" width="23" height="25" class="left" /> 
    				
                    <strong>{url type="anchor" url="market/detail/{$offer.id}" text="{$offer.location}" attr="title='{t}Details of this offer{/t}'"}</strong> <br />

        			{if $offer.to_date}
        				{if $offer.time_scope == 'month'}
        					{$offer.from_date|date_format:"%B %Y"} 
        				{else}
        					{t}Entre el{/t} {$offer.from_date|date_format:"%d de %B de %Y"} 
        					{t}y el{/t} {$offer.to_date|date_format:"%d de %B de %Y"}
        				{/if}
        			{else}
        				{$offer.from_date|date_format:"%d de %B de %Y"}
        			{/if}
        			<hr class="space" />
    			{/foreach}
    		{/if}
    		
    	</div>
    	
    	{if $view_spectacle_images}
        	<div id="photos">
        		<h3>{t name=$view_spectacle.spectacle_name}Images from “%1”{/t}</h3>

        		{foreach from=$view_spectacle_images item=image}
        			<div class="showthumbnail">
        			<a href="{$user_image_medium}{$image.media}" class="showthumb" rel="showimages" title="{$view_spectacle.spectacle_name} from {$view_spectacle.Company.company_name}">
        				<img src="{$user_thumbnail_square}{$image.media}"  />
        			</a> 
        			{if $role == 'manager'}
        				<br />
        				<small><a href="{$user_image_high}{$image.media}" title="{t}Click to download full size image{/t}">{t}Full size{/t}</a></small>
        			{/if}
        			</div> 
        		{/foreach}
        	</div>
        	<br class="clear" />
        	{block name=gallery_plugin}{/block}
    	{/if}
    	
        {if $view_company_spectacles}
        <br class="clear" />
        <div id="listshows"> 
            <h2>{t name=$view_company.company_name}%1 Shows{/t}</h2>
            <ul class="company-shows">
            {foreach $view_company_spectacles item=spectacle}
                {cycle name="colmargin" assign="colclass" values="none, last"}
                <li class="{$colclass}">
                    <h3 class="titlelist">{url type="anchor" url="/spectacle/{$spectacle.normalized_name}/" text="{$spectacle.spectacle_name}" attr=""}</h3> 
                    <small>({$spectacle.premiere})</small> 
                    <br class="clear" />

                    {url type="anchor" url="spectacle/{$spectacle.normalized_name}" text="<img src=\"{$user_thumbnail}{$spectacle.Media[0].media}\" alt=\"{$spectacle.spectacle_name}\" />" attr=""}
                    <hr class="space" />
                    {$spectacle.short_description}
                </li>
            {/foreach}
            </ul>
        </div>
        {/if}
    	
    </div> <!-- itd-single-sidebar -->
{/block}

{block name=gallery_plugin}
    {literal}
		<script type="text/javascript">
			$(function() {
				$("a.showthumb").fancybox({ 
					'titlePosition' :	'over',
					'padding'		:	0, 
					'transitionIn'	:	'elastic',
					'transitionOut'	:	'fade',
					'speedIn'		:	300, 
					'speedOut'		:	300, 
					'overlayShow'	:	true
				});
			});
		</script>
    {/literal}
{/block}
