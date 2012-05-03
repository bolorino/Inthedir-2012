{* Extend master template *}
{extends file="master.tpl"}

{block name=content}
<div id="groupscontainer">
    {if $alphabet}
        <div id="alphabet">
            <ul>
            {foreach $alphabet as $letter}
                <li{if $letter.letter == $current_letter} class="current"{/if}>
                    {url type="anchor" url="/companies/{$category_normalized_name}/{$letter.letter}" text="{$letter.letter}" attr=""}
                </li>
            {/foreach}
            <li class="showall">{url type="anchor" url="/companies/{$category_normalized_name}" text="{t}All{/t}" attr=""}</li>
            </ul>
        </div>
    {/if}

    {if $results_message}
        <h3 class="resultsheader">{$results_message}</h3>
    {/if}
    
    {if ($companies)}
        
        <!-- begin results -->
        <ul class="groupslists"> 
        {foreach from=$companies item=view_company}
            {cycle name="colmargin" assign="colclass" values="none, last"}
            <li class="company-item {$colclass}">
            	<div class="imagelist">
            		{if $view_company.image}
                        {url type="anchor" url="company/{$view_company.normalized_name}" text="<img src=\"{$user_thumbnail}{$view_company.image}\" alt=\"{$view_company.company_name}\" />" attr=""}
                	{else}
                		{url type="anchor" url="company/{$view_company.normalized_name}" text="<img src=\"{$user_thumbnail}noimage.jpg\" alt=\"{$view_company.company_name}\" />" attr=""}
                	{/if}
                </div>

                <div class="infolist"> 
                    <small>({$view_company.country|country_name:$lang})</small> 
                    <br />
                    {$view_company.short_description} <br />
                </div>
                <div class="title">
                    <h3>{url type="anchor" url="company/{$view_company.normalized_name}" text="{$view_company.company_name}" attr=""}</h3>
                </div>
            </li>
        {/foreach}
        </ul>
        <!-- end results -->
        
        <br class="clear" />
        
        {if $pagination}
            <div id="pagination">
                {$pagination}
            </div>
        {/if}
    {/if}
    
{if isset($scroll)}
	{literal}
	<script type="text/javascript">
    	jQuery.ias({
        	container : ".groupslists",
        	item: ".company-item",
        	pagination: "#pagination",
        	next: "#next a.page",
        	loader: assets_path + 'js/images/loader.gif', 
        	onPageChange: function(pageNum, pageUrl, scrollOffset) { _gaq.push(['_trackEvent', 'companiespagination', 'nextpage', pageUrl, pageNum]); } 
    	});
    </script>
    {/literal}
{/if}
</div> {* groupscontainer *}
{/block}