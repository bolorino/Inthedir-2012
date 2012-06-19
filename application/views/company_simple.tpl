<div class="span-10">
    <h2>{$view_company.company_name}</h2>
    
    <div id="contact">
        {if !$logged}
            <p>
                <strong>{t}Managers and other companies:{/t}</strong> <br />
                {url type="anchor" url="access/login/" text="{t}Login or register{/t}" attr=""} 
                {t}to see contact data and to add your own information.{/t}
            </p>
            
            <p>
            	<img src="{$flags_path}{$view_company.country|lower}.png" alt="{$view_company.country|country_name:$lang}" /> 
                {$view_company.country|country_name:$lang}
            </p>
                
            {if $view_company.short_description}
                <p>
                    {$view_company.short_description|nl2br}
                </p>
            {/if}
        {else}
        	{if $view_company.image}
        		<img src="{$user_thumbnail}{$view_company.image}" alt="{$view_company.company_name}" /> <br />
        	{/if}
        	
            <h3>{t}Contact{/t}</h3>
            {if $view_company.email}
               	{mailto address=$view_company.email} <br />
            {/if}
            
            <img src="{$img_path}icons/postal_address.png" class="left" alt="Postal Address icon" width="16" height="16" />
            {if $view_company.address}
               	{$view_company.address} <br />
            {/if}
            {if $view_company.postal_code}
               	{$view_company.postal_code} - {$view_company.city} <br />
            {/if}
            <img src="{$flags_path}{$view_company.country|lower}.png" alt="{$view_company.country|country_name:$lang}" /> 
            {$view_company.country|country_name:$lang} <br /> 
			
            {if $view_company.phone}
               	<img src="{$img_path}icons/phone.png" alt="Phone icon" width="16" height="16" /> 
               	{$view_company.phone} <br />
            {/if}
            {if $view_company.mobile}
                <img src="{$img_path}icons/mobile.png" alt="Mobile icon" width="16" height="16" /> 
                {$view_company.mobile} <br />
            {/if}
                    
            {if $view_company.contact_person}
                <strong>{t}Contact Person{/t}:</strong> {$view_company.contact_person} <br />
            {/if}

            {if $view_company.website}
            	<img src="{$img_path}icons/website.png" alt="Website icon" width="16" height="16" /> 
                <a href="{$view_company.website}">{$view_company.website|remove_http}</a> <br />
            {/if}
    
            {if $view_company.short_description}
              	<hr class="space"/>
                {$view_company.short_description|nl2br}
            {/if}
        {/if}
    </div>
</div>

<div class="span-9 last">
	{if isset($company_menu) && $company_menu === TRUE}
        <div id="companytools">
        	{if !$inlist}
           		<a id="addcompany" name="{$view_company.id}" class="button add" href="#">{t}Add to your notebook{/t}</a>
        	{/if}
        	{url type="anchor" url="/company/{$view_company.normalized_name}" text="{t}View full profile{/t}" attr='class="button"'}
        </div>

        <br class="clear" />

        <div id="added_company"></div>
        <hr class="space" />
    {/if}
    
    {if $view_spectacles}
        <div id="simplelistshows"> 
            <h4>{t}Shows{/t}</h4>
            <ul> 
            {foreach $view_spectacles item=spectacle}
                <li>
    				<strong>{url type="anchor" url="/spectacle/{$spectacle.normalized_name}/" text="{$spectacle.spectacle_name}" attr=""}</strong>
        			<small>({$spectacle.premiere})</small>
        		</li>
            {/foreach}
            </ul>
        </div>
    {/if}
</div>