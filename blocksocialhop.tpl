<div id="block_social_hop_container">
	<h4 class="title_block">{l s='Follow us' mod='blocksocialhop'}</h4>
	<ul>
		{foreach from=$elements key=element_key item=element_item}
			{if {$element_item.url} != ''}<li style="background: url({$element_item.img}) no-repeat;"><a href="{$element_item.url|escape:html:'UTF-8'}">{$element_item.title|escape:html:'UTF-8'}</a></li>{/if}
		{/foreach}		
	</ul>
</div>
