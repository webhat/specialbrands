{capture name=path}{l s='eFide CreditModule' mod='specialbrands'}{/capture}    |<?php
{include file=$tpl_dir./breadcrumb.tpl}     
{if $confirm}
	<p class="success">{$confirm}</p>
{else}
<form method="post" action="{$request_uri}">
	<input type="text" name="c" value=""/>
	<input type="text" name="fn" value=""/>
	<input type="text" name="ln" value=""/>
	<input type="text" name="mail" value=""/>
	<input type="hidden" name="account_id" value=""/>
	<input type="hidden" name="resource_id" value=""/>
	<input type="text" name="enable" value=""/>
	<input type="text" name="reset" value=""/>
	<input type="button" name="submit" value="{l s='Send' mod='specialbrands'}"/>
</form>
{/if}
