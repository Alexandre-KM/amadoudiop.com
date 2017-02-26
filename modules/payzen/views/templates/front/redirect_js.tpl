{*
 * PayZen V2-Payment Module version 1.8.0 for PrestaShop 1.5-1.7. Support contact : support@payzen.eu.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 *
 * @category  payment
 * @package   payzen
 * @author    Lyra Network (http://www.lyra-network.com/)
 * @copyright 2014-2016 Lyra Network and contributors
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *}

<script type="text/javascript">
  {literal}
    $(document).ready(function() {
      $('#payzen_form').submit(function() {
        // disable submit button
        $('button[type=submit], input[type=submit]').prop('disabled', true);
        return true;
      });

      var redirectUrl = window.location.href;
      var sep = (redirectUrl.indexOf('?') >= 0) ? '&' : '?';
      $.ajax({
        type: 'POST',
        headers: {'Cache-Control': 'no-cache'},
        url: redirectUrl + sep + 'rand=' + new Date().getTime(),
        async: true,
        cache: false,
        dataType: 'json',
        data: {
          checkCart: true,
          ajax: true
        },
        success: function(jsonData) {
          if (!jsonData.success) {
            window.location.href = jsonData.redirect;
          } else {
            $('#payzen_content').show();
            $('#payzen_form').submit();
          }
        }
      });
    });
  {/literal}
</script>
