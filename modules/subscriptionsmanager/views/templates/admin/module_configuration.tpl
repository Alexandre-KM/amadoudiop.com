
{if $errors}
    {$errors}
{/if}

<form action="{$form_action}" method="post" id="module_form" class="defaultForm form-horizontal">


    <div class="panel">
	<div class="panel-heading">
	    <i class="icon-cogs"></i> 
	    {l s='Module settings' mod='subscriptionsmanager'}
	</div>

	<div class="form-group ">

	    <label class="control-label col-lg-4"  for="NB_DAYS_STOP">{l s='Gap (in number of days) between the cancellation request of a subscription and the date of the next levy' mod='subscriptionsmanager'}</label>
	    <div class="col-lg-8">
		<input type="text" id="NB_DAYS_STOP" name="NB_DAYS_STOP" value="{$moduleConfig['NB_DAYS_STOP']}" />
		<p class="help-block">
		    {l s='if the client requests to cancel during the date range, cancellation shall take effect at the next levy' mod='subscriptionsmanager'}
		<br/><br/>

		    {l s='For example: If the gap is 10 days and if a subscription was purchased on January 20 with a monthly levy.' mod='subscriptionsmanager'}<br/>
		    {l s='The next two dates of levies will be February 20 and March 20.' mod='subscriptionsmanager'}<br/>
		    ------<br/>
		    {l s='If the customer wishes to cancel his subscription on January 11, then the cancellation date will be March 20.' mod='subscriptionsmanager'}<br/>
		    {l s='If the customer wishes to cancel his subscription on January 09, then the cancellation date will be February 20.' mod='subscriptionsmanager'}<br/>

		</p>
	    </div>
	</div>

	<div class="form-group ">
	    <label class="control-label col-lg-4"  for="NOTIFY_ENGAGEMENT">{l s='Notify customers of the minimum period of engagement' mod='subscriptionsmanager'}</label>
	    <div class="col-lg-8">

		<span class="switch prestashop-switch fixed-width-lg">
		    <input id="NOTIFY_ENGAGEMENT_on" type="radio" {if $moduleConfig['NOTIFY_ENGAGEMENT'] == 1}checked="checked"{/if} value="1" name="NOTIFY_ENGAGEMENT">
		    <label for="NOTIFY_ENGAGEMENT_on"> Oui </label>
		    <input id="NOTIFY_ENGAGEMENT_off" type="radio" {if $moduleConfig['NOTIFY_ENGAGEMENT'] == 0}checked="checked"{/if} value="0" name="NOTIFY_ENGAGEMENT">
		    <label for="NOTIFY_ENGAGEMENT_off"> Non </label>
		    <a class="slide-button btn"></a>
		</span>


		<p class="help-block">
		    {l s='The message will be displayed in the shopping tunnel before paying' mod='subscriptionsmanager'}
		</p>
	    </div>
	</div>





	<div class="form-group ">
	    <label class="control-label col-lg-4"  for="VSM_HOUR_CRON">{l s='hour of passage of the CRON' mod='subscriptionsmanager'}</label>
	    <div class="col-lg-8">
		<select name="VSM_HOUR_CRON" id="VSM_HOUR_CRON" class="form-control fixed-width-xl">
		    <option value="0" {if $moduleConfig['VSM_HOUR_CRON'] == 0}selected="selected"{/if}>0</option>
		    <option value="1" {if $moduleConfig['VSM_HOUR_CRON'] == 1}selected="selected"{/if}>1</option>
		    <option value="2" {if $moduleConfig['VSM_HOUR_CRON'] == 2}selected="selected"{/if}>2</option>
		    <option value="3" {if $moduleConfig['VSM_HOUR_CRON'] == 3}selected="selected"{/if}>3</option>
		    <option value="4" {if $moduleConfig['VSM_HOUR_CRON'] == 4}selected="selected"{/if}>4</option>
		    <option value="5" {if $moduleConfig['VSM_HOUR_CRON'] == 5}selected="selected"{/if}>5</option>
		    <option value="6" {if $moduleConfig['VSM_HOUR_CRON'] == 6}selected="selected"{/if}>6</option>
		    <option value="7" {if $moduleConfig['VSM_HOUR_CRON'] == 7}selected="selected"{/if}>7</option>
		    <option value="8" {if $moduleConfig['VSM_HOUR_CRON'] == 8}selected="selected"{/if}>8</option>
		    <option value="9" {if $moduleConfig['VSM_HOUR_CRON'] == 9}selected="selected"{/if}>9</option>
		    <option value="10" {if $moduleConfig['VSM_HOUR_CRON'] == 10}selected="selected"{/if}>10</option>
		    <option value="11" {if $moduleConfig['VSM_HOUR_CRON'] == 11}selected="selected"{/if}>11</option>
		    <option value="12" {if $moduleConfig['VSM_HOUR_CRON'] == 12}selected="selected"{/if}>12</option>
		    <option value="13" {if $moduleConfig['VSM_HOUR_CRON'] == 13}selected="selected"{/if}>13</option>
		    <option value="14" {if $moduleConfig['VSM_HOUR_CRON'] == 14}selected="selected"{/if}>14</option>
		    <option value="15" {if $moduleConfig['VSM_HOUR_CRON'] == 15}selected="selected"{/if}>15</option>
		    <option value="16" {if $moduleConfig['VSM_HOUR_CRON'] == 16}selected="selected"{/if}>16</option>
		    <option value="17" {if $moduleConfig['VSM_HOUR_CRON'] == 17}selected="selected"{/if}>17</option>
		    <option value="18" {if $moduleConfig['VSM_HOUR_CRON'] == 18}selected="selected"{/if}>18</option>
		    <option value="19" {if $moduleConfig['VSM_HOUR_CRON'] == 19}selected="selected"{/if}>19</option>
		    <option value="20" {if $moduleConfig['VSM_HOUR_CRON'] == 20}selected="selected"{/if}>20</option>
		    <option value="21" {if $moduleConfig['VSM_HOUR_CRON'] == 21}selected="selected"{/if}>21</option>
		    <option value="22" {if $moduleConfig['VSM_HOUR_CRON'] == 22}selected="selected"{/if}>22</option>
		    <option value="23" {if $moduleConfig['VSM_HOUR_CRON'] == 23}selected="selected"{/if}>23</option>
		    <option value="24" {if $moduleConfig['VSM_HOUR_CRON'] == 24}selected="selected"{/if}>24</option>

		</select>
	    </div>
	</div>
		    
		    
	<div class="panel-footer">
	    <button class="btn btn-default pull-right" name="saveConfig" id="module_form_submit_btn" value="1" type="submit">
		<i class="process-icon-save"></i> {l s='Save' mod='subscriptionsmanager'}
	    </button>	   
	</div>
	    

    </div>
    <div class="panel">
	<div class="panel-heading">
	    <i class="icon-clock-o"></i> 
	    {l s='CRON' mod='subscriptionsmanager'}
	</div>


	<div class="form-group ">
	    <div class="bootstrap">
		<div class="alert alert-message">
		    {l s='Copy into the CRON tab:' mod='subscriptionsmanager'}
		    <br/>
		    {$cron}
		    <br/>
		    <br/>
		    {l s='Warning: Without CRON, the module can not work properly' mod='subscriptionsmanager'}
		</div>
	    </div>
	</div>
	    
    </div>

</form>

