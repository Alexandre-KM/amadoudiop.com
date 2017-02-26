<tr>
    <td id="subscriptions_filters" colspan="10">
	<form action="#" method="post" id="filter_subscriptions_form" class="defaultForm  form-horizontal">

	    <h3>{l s='Filters subscriptions' mod='subscriptionsmanager'}</h3>


	    {* 
	    <label>{l s='Filter by subscription name' mod='subscriptionsmanager'}</label>
	    <input type="text" name="name" placeholder="{l s='Filter by subscription name' mod='subscriptionsmanager'}" class="criteria_field"/>
	    *}



	    <div class="col-lg-3">
		<div class="panel">
		    <div class="panel-heading">
			{l s='Filter by customer' mod='subscriptionsmanager'}
		    </div>
		    <input type="text" name="customer_name" placeholder="{l s='Search by customer' mod='subscriptionsmanager'}" class="criteria_field" />
		</div>
	    </div>
	    <div class="col-lg-3">
		<div class="panel">
		    <div class="panel-heading">
			{l s='Start date' mod='subscriptionsmanager'}
		    </div>
		    <input type="text" name="start_date" placeholder="{l s='Start date' mod='subscriptionsmanager'}" class="datepicker" />			
		</div>
	    </div>
	    <div class="col-lg-3">
		<div class="panel">
		    <div class="panel-heading">
			{l s='End date' mod='subscriptionsmanager'}
		    </div>
		    <input type="text" name="end_date" placeholder="{l s='End date' mod='subscriptionsmanager'}" class="datepicker" />
		</div>
	    </div>
	    <div class="col-lg-3">
		<div class="panel">
		    <div class="panel-heading">
			{l s='Duration' mod='subscriptionsmanager'}
		    </div>
		    <select name="duration" class="criteria_select">
			<option value="">{l s='All durations' mod='subscriptionsmanager'}</option>
			<option value="0">{l s='Unlimited' mod='subscriptionsmanager'}</option>
			{foreach $periods as $val => $month}
			    <option value="{$val}">{$month}</option>
			{/foreach}
		    </select>
		</div>
	    </div>

	    <button type="button" class="btn btn-default pull-right reset_filters">
		<i class="icon-times-circle"></i> {l s='Reset all filters' mod='subscriptionsmanager'}
	    </button>

	</form>
    </td>
</tr>

