<script type='text/javascript'>
	    
{if !empty({$lockedSchemas})}
    function check_locking(){

	var lockedSchemas = {$lockedSchemas};

	if(jQuery.inArray($('#idCombination').val(), lockedSchemas) !== -1){
	    $('#add_to_cart').after( '<p id="locked" style="background-color: #9B0000; color: #FFFFFF; display: inline-block; font-size: 10px; font-weight: bold; text-align: center; padding: 0 10px; text-shadow: none; text-transform: uppercase;">{l s='This product is locked. You cant add it to your cart' mod='subscriptionsmanager'}</p>' );
	    $('#add_to_cart').css('display', 'none'); 
	    $('#add_to_cart').attr('id', 'add_to_cart_hidden'); 
	}
	else{
	    $('#add_to_cart_hidden').css('display', 'block'); 
	    $('#add_to_cart_hidden').attr('id', 'add_to_cart'); 
	    $('#locked').remove();			
	}
    }
{/if}

	$(document).ready(function() {

	    {if !empty({$lockedSchemas})}check_locking();{/if}

	    $('#add_to_cart input').click(function() {
		setTimeout(function(){
			ajaxCart.refresh();
		}, 1000);
		setTimeout(function(){
			ajaxCart.refresh();
		}, 3000);
		setTimeout(function(){
			ajaxCart.refresh();
		}, 5000);
		setTimeout(function(){
			ajaxCart.refresh();
		}, 10000);
	    });

	    {if !empty({$lockedSchemas})}$('.attribute_list select').change(function(){			
		check_locking();			
	    });
	    $('.attribute_list input').change(function(){			
		check_locking();			
	    });{/if}

	});
    </script>