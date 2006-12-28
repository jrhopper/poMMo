{if $datePicker}{capture name=head}
{* used to inject content into the HTML <head> *}
{include file="`$config.app.path`themes/shared/datepicker/datepicker.tpl"}
{/capture}{/if}
{include file="inc/tpl/user.header.tpl"}

<h2>{t}Subscriber Update{/t}</h2>

<p><a href="{$config.site_url}"><img src="{$url.theme.shared}images/icons/back.png" alt="back icon" class="navimage" /> {t website=$config.site_name}Return to %1{/t}</a></p>

{include file="inc/tpl/messages.tpl"}
 	
{include file="subscribe/form.update.tpl"}

<h3>{t}Unsubscribe{/t}</h3>

<form method="post" action="">

<div class="buttons">

<input type="hidden" name="Email" value="{$Email}" />

<button type="submit" name="unsubscribe" value="true">
<img src="{$url.theme.shared}images/icons/nok.png" alt="not ok icon" /> {t}Click to unsubscribe{/t} {$Email}
</button>

</div>

</form>

{include file="inc/tpl/user.footer.tpl"}