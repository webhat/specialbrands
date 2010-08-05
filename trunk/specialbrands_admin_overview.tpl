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
		<!-- Items are places here in a list -->
		<ul>{$list}</ul>
	</div>
{/if}
