{* Extend master template *}
{extends file="master.tpl"}

{block name=content}

{if $results_message}
    <h3 class="resultsheader">{$results_message}</h3>
{/if}

<div id="groupscontainer">
    {if ($spectacles)}
        <!-- begin results -->
        <ul class="groupslists"> 
        {foreach from=$spectacles item=spectacle}
            {cycle name="colmargin" assign="colclass" values="none, last"}
            <li class="spectacle-item {$colclass}">
                <div class="imagelist">
                    {if $spectacle.Media[0].media}
                        {url type="anchor" url="spectacle/{$spectacle.normalized_name}" text="<img src=\"{$user_thumbnail}{$spectacle.Media[0].media}\" alt=\"{$spectacle.spectacle_name}\" />" attr=""}
                    {else}
                        <img src="{$user_thumbnail}noimage.jpg" alt="No image" />
                    {/if}
                </div>
                <div class="infolist"> 
                    {$spectacle.short_description} <br />
                </div>
                <div class="title">
                    <strong>{url type="anchor" url="spectacle/{$spectacle.normalized_name}" text="{$spectacle.spectacle_name}" attr=""}</strong> 
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
        
        {if isset($scroll)}
        	{literal}
        	<script type="text/javascript">
            	jQuery.ias({
                	container : ".groupslists",
                	item: ".spectacle-item",
                	pagination: "#pagination",
                	next: "#next a.page",
                	loader: assets_path + 'js/images/ajax-loader.gif', 
                	onPageChange: function(pageNum, pageUrl, scrollOffset) { _gaq.push(['_trackEvent', 'spectaclespagination', 'nextpage', pageUrl, pageNum]); } 
            	});
            </script>
            {/literal}
        {/if}

    {/if}
    
</div> {* groupscontainer *}
{/block}

{block name=sidebar}
    <!-- sidebar -->
    <div id="itd-sidebar"><!-- itd-sidebar -->
        
    </div>
{/block}