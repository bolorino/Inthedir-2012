{* Extend master template *}
{extends file="master.tpl"}

{block name=content}
<div id="groupinfo">
    <div class="span-14">
        <h2>{$view_company.company_name}</h2>

        {if $view_company.image}
            <div class="groupimage"> 
                <a href="{$user_image_medium}{$view_company.image}" class="companythumb"><img src="{$user_image}{$view_company.image}" alt="{$view_company.company_name}" /></a>  
            </div>
            {block name=gallery_plugin}{/block}
        {/if}

        <ul class="share">
            <li><a title="Twitt" href="http://twitter.com/share" class="twitter-share-button">{t}Twitt{/t}</a></li> 
            <li><g:plusone></g:plusone></li>
            <li><fb:like href="{$fb_url}" send="false" layout="button_count" width="250" show_faces="false"></fb:like></li> 
        </ul>
    </div>

    <div id="contact" class="span-10 last">
        <h3>{t}Contact{/t}</h3>
        <div class="box">
        {if $cid == $ucid}
            {url type="anchor" url="dashboard/update_{$role}" text="{t}Update your info{/t}" attr="class='btn'"}
        {/if}
        
        {if $role == 'manager'}
            {if !$inlist}
                <a id="addcompany" name="{$view_company.id}" href="#" class="btn">{t}Add to your notebook{/t}</a>
                <div id="added_company"></div>
            {else}
                {t}Company in your notebook{/t}
            {/if}     
        {/if}

        {if $cid == $ucid OR $role == 'manager'}
            <hr class="space" />
        {/if}

        <img src="{$img_path}icons/postal_address.png" class="left" alt="Postal Address icon" width="16" height="16" />
        <p class="address">
            {if $view_company.address}
                {$view_company.address} <br />
            {/if}
            {if $view_company.postal_code}
                {$view_company.postal_code} - {$view_company.city} <br />
            {/if}
            {$view_company.country|country_name:$lang} <br /> 
        </p>

        {if $view_company.phone OR $view_company.mobile}
            <p>
                {if $view_company.phone}
                    <img src="{$img_path}icons/phone.png" alt="Phone icon" width="16" height="16" /> 
                {$view_company.phone} <br />
                {/if}
                {if $view_company.mobile}
                    <img src="{$img_path}icons/mobile.png" alt="Mobile icon" width="16" height="16" /> 
                    {$view_company.mobile} <br />
                {/if}
            </p>
        {/if}

        {if $view_company.contact_person}
            <p>
                <strong>{t}Contact Person{/t}:</strong> <br /> 
                {$view_company.contact_person}
            </p> 
        {/if}
        <p>
        {if $view_company.email}
            {mailto address=$view_company.email encode="javascript"} <br />
        {/if}

        {if $view_company.website}
            <img src="{$img_path}icons/website.png" alt="Website icon" width="16" height="16" /> 
            <a href="{$view_company.website}">{$view_company.website|remove_http}</a>
        {/if}
        </p>
        </div> {*contact .box*}
    </div>
    
</div>

<hr class="space" />

<div id="about" class="span-14">    
    <h3>{t name=$view_company.company_name}About %1{/t}</h3>
    <p>
        {if $view_company.founded}
            <strong>{t}Founded{/t}</strong> {$view_company.founded} <br />
        {/if}

        {$view_company.about|nl2br}
    </p>
</div>

<div id="single-sidebar" class="span-10 last">
    {if $view_spectacles} 
        <div id="listshows"> 
            <h3>{t name=$view_company.company_name}%1 Shows{/t}</h3>
            
            <ul class="company-shows">
            {foreach $view_spectacles item=spectacle}
                {cycle name="colmargin" assign="colclass" values="none, last"}
                <li class="{$colclass}">
                    <h3 class="titlelist">{url type="anchor" url="/spectacle/{$spectacle.normalized_name}/" text="{$spectacle.spectacle_name}" attr=""}</h3> 
                    <small>({$spectacle.premiere})</small> 
                    <br class="clear" />

                    {url type="anchor" url="spectacle/{$spectacle.normalized_name}" text="<img src=\"{$user_thumbnail}{$spectacle.Media[0].media}\" class=\"microthumb\" alt=\"{$spectacle.spectacle_name}\" />" attr=""}
                    
                    {$spectacle.short_description}
                </li>
            {/foreach}
            </ul>
        </div>
    {/if}

</div>

<hr class="space" />
{/block}

{block name=gallery_plugin}
    {literal}
		<script type="text/javascript">
			$(function() {
				$("a.companythumb").fancybox({ 
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