{* Field Validation - see docs/template.txt documentation *}
{fv form='messages'}
{fv prepend='<span class="error">' append='</span>'}
{fv validate="subscribe_sub"}
{fv validate="subscribe_msg"}
{fv validate="subscribe_web"}
{fv validate="unsubscribe_sub"}
{fv validate="unsubscribe_msg"}
{fv validate="unsubscribe_web"}
{fv validate="confirm_sub"}
{fv validate="confirm_msg"}
{fv validate="activate_sub"}
{fv validate="activate_msg"}
{fv validate="update_sub"}
{fv validate="update_msg"}
{fv validate="notify_email"}
{fv validate="notify_subscribe"}
{fv validate="notify_unsubscribe"}
{fv validate="notify_update"}
{fv validate="notify_pending"}

{include file="inc/ui.tooltips.tpl"}


<form action="{$smarty.server.PHP_SELF}" method="post" class="json">

<fieldset>
<h3>{t}Notifications{/t}</h3>
{t}Administrators can be sent a notification of subscription list changes.{/t}

<div class="formSpacing">
<label for="notify_email">{t}Notification email(s):{/t}&nbsp;{fv message="notify_email"}</label>
<input type="text" name="notify_email" value="{$notify_email|escape}" size="30" maxlength="60" />&nbsp;<a href="#" class="tooltip" title="{t}notifications will be sent to the above address(es)... multiple addresses can be entered separated by commas{/t}"><img src="{$url.theme.shared}/images/icons/help-small.png" alt="tip" /></a>
<!--<span class="notes">{t}(notifications will be sent to the above address(es)... multiple addresses can be entered separated by commas){/t}</span>-->
</div>

<div class="formSpacing">
<label for="notify_subject">{t}Subject Prefix:{/t}&nbsp;{fv message="notify_subject"}</label>
<input type="text" name="notify_subject" value="{$notify_subject|escape}" size="30" maxlength="60" />&nbsp;<a href="#" class="tooltip" title="{t}the subject of notification mails will begin with this{/t}"><img src="{$url.theme.shared}/images/icons/help-small.png" alt="tip" /></a>
<!--<span class="notes">{t}(the subject of notification mails will begin with this){/t}</span>-->
</div>

<div class="formSpacing">
<label for="notify_subscribe">{t escape=no 1="$t_subscribe"}Notify on %1:{/t}&nbsp;{fv message="notify_subscribe"}</label>
<input type="radio" name="notify_subscribe" value="on"{if $notify_subscribe == 'on'} checked="checked"{/if} /> {t}on{/t}&nbsp;&nbsp;
<input type="radio" name="notify_subscribe" value="off"{if $notify_subscribe != 'on'} checked="checked"{/if} /> {t}off{/t}&nbsp;&nbsp;<a href="#" class="tooltip" title="{t}sent upon successful subscription{/t}"><img src="{$url.theme.shared}/images/icons/help-small.png" alt="tip" /></a>
<!--<span class="notes">{t}(sent upon successful subscription){/t}</span>-->
</div>

<div class="formSpacing">
<label for="notify_unsubscribe">{t escape=no 1="$t_unsubscribe"}Notify on %1:{/t}&nbsp;{fv message="notify_unsubscribe"}</label>
<input type="radio" name="notify_unsubscribe" value="on"{if $notify_unsubscribe == 'on'} checked="checked"{/if} /> {t}on{/t}&nbsp;&nbsp;
<input type="radio" name="notify_unsubscribe" value="off"{if $notify_unsubscribe != 'on'} checked="checked"{/if} /> {t}off{/t}&nbsp;&nbsp;<a href="#" class="tooltip" title="{t}sent upon successful unsubscription{/t}"><img src="{$url.theme.shared}/images/icons/help-small.png" alt="tip" /></a>
<!--<span class="notes">{t}(sent upon successful unsubscription){/t}</span>-->
</div>

<div class="formSpacing">
<label for="notify_update">{t escape=no 1="$t_update"}Notify on %1:{/t}&nbsp;{fv message="notify_update"}</label>
<input type="radio" name="notify_update" value="on"{if $notify_update == 'on'} checked="checked"{/if} /> {t}on{/t}&nbsp;&nbsp;
<input type="radio" name="notify_update" value="off"{if $notify_update != 'on'} checked="checked"{/if} /> {t}off{/t}&nbsp;&nbsp;<a href="#" class="tooltip" title="{t}sent upon subscriber update{/t}"><img src="{$url.theme.shared}/images/icons/help-small.png" alt="tip" /></a>
<!--<span class="notes">{t}(sent upon subscriber update){/t}</span>-->
</div>

<div class="formSpacing">
<label for="notify_pending">{t escape=no 1="$t_pending"}Notify on %1:{/t}&nbsp;{fv message="notify_pending"}</label>
<input type="radio" name="notify_pending" value="on"{if $notify_pending == 'on'} checked="checked"{/if} /> {t}on{/t}&nbsp;&nbsp;
<input type="radio" name="notify_pending" value="off"{if $notify_pending != 'on'} checked="checked"{/if} /> {t}off{/t}&nbsp;&nbsp;<a href="#" class="tooltip" title="{t}sent upon subscription attempt{/t}"><img src="{$url.theme.shared}/images/icons/help-small.png" alt="tip" /></a>
<!--<span class="notes">{t}(sent upon subscription attempt){/t}</span>-->
</div>

<div class="formSpacing"></div>

<div class="buttons">
<button type="submit" value="{t}Update{/t}" class="positive"><img src="{$url.theme.shared}/images/icons/tick.png" alt="update"/>{t}Update{/t}</button>
<img src="{$url.theme.shared}images/loader.gif" alt="loading..." class="hidden" name="loading" />
</div>

<br class="clear" />

<div class="output">{if $output}{$output}{/if}</div>

</fieldset>



<fieldset>

<hr class="hr" />
<h3>{t}Subscription Email{/t}</h3>
{t}Customize the messages sent to your users when they subscribe.{/t}
<br class="clear" />
<br class="clear">

<input type="checkbox" name="subscribe_email" {if $subscribe_email}checked {/if}/>&nbsp;{t}(Check to Enable){/t}
<div>
<label for="subscribe_sub"><strong class="required">{t}Subject:{/t}&nbsp;&nbsp;</strong>{fv message="subscribe_sub"}</label>
<input type="text" name="subscribe_sub" value="{$subscribe_sub|escape}" size="50" maxlength="200" />
</div>

<div>
<label for="subscribe_msg"><strong class="required" style="vertical-align: top;">{t}Message:{/t}</strong>{fv message="subscribe_msg"}</label>
<textarea name="subscribe_msg" cols="70" rows="10">{$subscribe_msg|escape}</textarea>
</div>

<p>&nbsp;</p>
<h3>{t}Subscription Message Displayed on Website{/t}</h3>
{t}Customize the messages displayed to your users on your website when they subscribe.{/t}

<div>
<label for="subscribe_web"><strong class="required" style="vertical-align: top;">{t}Message:{/t}</strong>{fv message="subscribe_web"}</label>
<textarea name="subscribe_web" cols="70" rows="5">{$subscribe_web|escape}</textarea>
<!--<div class="notes">{t escape='no'}(displayed on webpage){/t}</div>-->
</div>

<div class="buttons" style="padding-left: 64px;">
<button type="submit" value="{t}Update{/t}" class="positive"><img src="{$url.theme.shared}/images/icons/tick.png" alt="update"/>{t}Update{/t}</button>
<button type="submit" name="restore[subscribe]" value="{t}Restore to Defaults{/t}" class="positive"><img src="{$url.theme.shared}/images/icons/reload-small.png" alt="restore defaults"/>{t}Restore to Defaults{/t}</button>
<img src="{$url.theme.shared}images/loader.gif" alt="loading..." class="hidden" name="loading" />
</div>

<br class="clear">

<div class="output">{if $output}{$output}{/if}</div>

</fieldset>

<br class="clear" />

<fieldset>
<hr class="hr" />
<h3>{t}Unsubscribe Email{/t}</h3>
{t}Customize the messages sent to your users when they unsubscribe.{/t}
<br class="clear">
<br class="clear">

<input type="checkbox" name="unsubscribe_email" {if $unsubscribe_email}checked {/if}/>&nbsp;{t}(Check to Enable){/t}
<div>
<label for="unsubscribe_sub"><strong class="required">{t}Subject:{/t}&nbsp;&nbsp;</strong>{fv message="unsubscribe_sub"}</label>
<input type="text" name="unsubscribe_sub" value="{$unsubscribe_sub|escape}" size="50" maxlength="200" />
</div>

<div>
<label for="unsubscribe_msg"><strong class="required" style="vertical-align: top;">{t}Message:{/t}</strong>{fv message="unsubscribe_msg"}</label>
<textarea name="unsubscribe_msg" cols="70" rows="10">{$unsubscribe_msg|escape}</textarea>
</div>

<p>&nbsp;</p>
<h3>{t}Unsubscribe Message Displayed on Website{/t}</h3>
{t}Customize the messages displayed to your users on your website when they unsubscribe.{/t}

<div>
<label for="unsubscribe_web"><strong class="required" style="vertical-align: top;">{t}Message:{/t}</strong>{fv message="unsubscribe_web"}</label>
<textarea name="unsubscribe_web" cols="70" rows="5">{$unsubscribe_web|escape}</textarea>
<!--<div class="notes">{t escape='no'}(displayed on webpage){/t}</div>-->
</div>

<div class="buttons" style="padding-left: 64px;">
<button type="submit" value="{t}Update{/t}" class="positive"><img src="{$url.theme.shared}/images/icons/tick.png" alt="update"/>{t}Update{/t}</button>
<button type="submit" name="restore[subscribe]" value="{t}Restore to Defaults{/t}" class="positive"><img src="{$url.theme.shared}/images/icons/reload-small.png" alt="restore defaults"/>{t}Restore to Defaults{/t}</button>
<img src="{$url.theme.shared}images/loader.gif" alt="loading..." class="hidden" name="loading" />
</div>

<br class="clear">

<div class="output">{if $output}{$output}{/if}</div>

</fieldset>

<br class="clear" />

<fieldset>
<hr class="hr" />
<h3>{t}Subscription Email Confirmation{/t}</h3>
{t}Customize the messages sent to your users when their subscription is confirmed.{/t}
<br class="clear">

<div>
<label for="confirm_sub"><strong class="required">{t}Subject:{/t}&nbsp;&nbsp;</strong>{fv message="confirm_sub"}</label>
<input type="text" name="confirm_sub" value="{$confirm_sub|escape}" size="50" maxlength="200" />
</div>

<div>
<label for="confirm_msg"><strong class="required" style="vertical-align: top;">{t}Message:{/t}</strong>{fv message="confirm_msg"}</label>
<textarea name="confirm_msg" cols="70" rows="10">{$confirm_msg|escape}</textarea>
<div class="notes" style="padding-left: 64px;">{t escape='no' 1='<tt>' 2='</tt>'}(Use %1[[url]]%2 for the confirm link at least once){/t}</div>
</div>

<div class="buttons" style="padding-left: 64px;">
<button type="submit" value="{t}Update{/t}" class="positive"><img src="{$url.theme.shared}/images/icons/tick.png" alt="update"/>{t}Update{/t}</button>
<button type="submit" name="restore[subscribe]" value="{t}Restore to Defaults{/t}" class="positive"><img src="{$url.theme.shared}/images/icons/reload-small.png" alt="restore defaults"/>{t}Restore to Defaults{/t}</button>
<img src="{$url.theme.shared}images/loader.gif" alt="loading..." class="hidden" name="loading" />
</div>

<br class="clear">

<div class="output">{if $output}{$output}{/if}</div>

</fieldset>

<br class="clear" />

<fieldset>
<hr class="hr" />
<h3>{t}Account Access Email{/t}</h3>
{t}Customize the messages sent to your users when they request to update their records.{/t}
<br class="clear" />

<div>
<label for="activate_sub"><strong class="required">{t}Subject:{/t}&nbsp;&nbsp;</strong>{fv message="activate_sub"}</label>
<input type="text" name="activate_sub" value="{$activate_sub|escape}" size="50" maxlength="200" />
</div>

<div>
<label for="activate_msg"><strong class="required" style="vertical-align: top;">{t}Message:{/t}</strong>{fv message="activate_msg"}</label>
<textarea name="activate_msg" cols="70" rows="10">{$activate_msg|escape}</textarea>
<div class="notes" style="padding-left: 64px;">{t escape='no' 1='<tt>' 2='</tt>'}(Use %1[[url]]%2 for the confirm link at least once){/t}</div>
</div>

<div class="buttons" style="padding-left: 64px;">
<button type="submit" value="{t}Update{/t}" class="positive"><img src="{$url.theme.shared}/images/icons/tick.png" alt="update"/>{t}Update{/t}</button>
<button type="submit" name="restore[subscribe]" value="{t}Restore to Defaults{/t}" class="positive"><img src="{$url.theme.shared}/images/icons/reload-small.png" alt="restore defaults"/>{t}Restore to Defaults{/t}</button>
<img src="{$url.theme.shared}images/loader.gif" alt="loading..." class="hidden" name="loading" />
</div>

<br class="clear">

<div class="output">{if $output}{$output}{/if}</div>

</fieldset>

<br class="clear" />

<fieldset>
<hr class="hr" />
<h3>{t}Update Validation Email{/t}</h3>
{t}Customize the messages sent to your users when they update their records.{/t}
<br class="clear" />

<div>
<label for="update_sub"><strong class="required">{t}Subject:{/t}&nbsp;&nbsp;</strong>{fv message="update_sub"}</label>
<input type="text" name="update_sub" value="{$update_sub|escape}" size="50" maxlength="200" />
</div>

<div>
<label for="update_msg"><strong class="required" style="vertical-align: top;">{t}Message:{/t}</strong>{fv message="update_msg"}</label>
<textarea name="update_msg" cols="70" rows="10">{$update_msg|escape}</textarea>
<div class="notes" style="padding-left: 64px;">{t escape='no' 1='<tt>' 2='</tt>'}(Use %1[[url]]%2 for the confirm link at least once){/t}</div>
</div>

<div class="buttons" style="padding-left: 64px;">
<button type="submit" value="{t}Update{/t}" class="positive"><img src="{$url.theme.shared}/images/icons/tick.png" alt="update"/>{t}Update{/t}</button>
<button type="submit" name="restore[subscribe]" value="{t}Restore to Defaults{/t}" class="positive"><img src="{$url.theme.shared}/images/icons/reload-small.png" alt="restore defaults"/>{t}Restore to Defaults{/t}</button>
<img src="{$url.theme.shared}images/loader.gif" alt="loading..." class="hidden" name="loading" />
</div>

<br class="clear">

<div class="output">{if $output}{$output}{/if}</div>

</fieldset>

</form>