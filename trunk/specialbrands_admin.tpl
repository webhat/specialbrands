{capture name=path}{l s='eFide CreditModule' mod='specialbrands'}{/capture}    |<?php
{include file=$tpl_dir./breadcrumb.tpl}     
<h2>{l s='eFide Administration' mod='specialbrands'}</h2>
<p>{l s='This is the form for administering your user's eFide Account.' mod='specialbrands'}</p>
{if $error}
	<p class="error">{$error}</p>
{/if}
{if $confirm}
	<p class="success">{$confirm}</p>
{else}
<script type="text/javascript">{$script}</script>
<div class="block-center">
	<form method="post" action="{$request_uri}">
		<p id="c">
			<input type="text" name="c" value="{$c}"/><label>{l s='Company' mod='specialbrands'}</label>
		</p>
		<p id="fn">
			<input type="text" name="fn" value="{$fn}"/><label>{l s='Firstname' mod='specialbrands'}</label>
		</p>
		<p id="ln">
			<input type="text" name="ln" value="{$ln}"/><label>{l s='Lastname' mod='specialbrands'}</label>
		</p>
		<p id="mail">
			<input type="text" name="mail" value="{$mail}"/><label>{l s='Mail' mod='specialbrands'}</label>
		</p>
		<p class="checkbox" id="enable">
			<input type="checkbox" name="enable" value="{$enable}"/><label>{l s='Enable' mod='specialbrands'}</label>
		</p>
		<p class="checkbox" id="reset">
			<input type="checkbox" name="reset" value="{$reset}"/><label>{l s='Reset' mod='specialbrands'}</label>
		</p>
		<p class="submit">
			<input type="hidden" name="account_id" value="{$account_id}"/>
			<input type="hidden" name="resource_id" value="{$resource_id}"/>
			<input type="submit" class="button_large" name="submit" value="{l s='Send' mod='specialbrands'}"/>
		</p>
	</form>
</div>
{/if}
