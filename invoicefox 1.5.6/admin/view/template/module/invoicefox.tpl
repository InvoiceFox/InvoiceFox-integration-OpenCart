<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
         <table class="form">

	 <tr>
            <td><?php echo $entry_api_key; ?></td>
            <td><input type="text" name="invoicefox_api_key" value="<?php echo $invoicefox_api_key; ?>" />
	    <?php if ($error_api_key) { ?>
              <span class="error"><?php echo $error_api_key; ?></span>
              <?php } ?>
	    </td>
          </tr>

	  <tr>
            <td><?php echo $entry_api_domain; ?></td>
            <td><select name="invoicefox_api_domain">
                  <?php if($invoicefox_api_domain=='www.invoicefox.com') :?>
			<option selected="selected" value="www.invoicefox.com">www.invoicefox.com</option>
		  <?php else : ?>
			<option value="www.invoicefox.com">www.invoicefox.com</option>
		  <?php endif;?>

		  <?php if($invoicefox_api_domain=='www.invoicefox.co.uk') :?>
			<option selected="selected" value="www.invoicefox.co.uk">www.invoicefox.co.uk</option>
		  <?php else : ?>
			<option value="www.invoicefox.co.uk">www.invoicefox.co.uk</option>
		  <?php endif;?>

		  <?php if($invoicefox_api_domain=='www.invoicefox.com.au') :?>
			<option selected="selected" value="www.invoicefox.com.au">www.invoicefox.com.au</option>
		  <?php else : ?>
			<option value="www.invoicefox.com.au">www.invoicefox.com.au</option>
		  <?php endif;?>

		  <?php if($invoicefox_api_domain=='www.cebelca.biz') :?>
			<option selected="selected" value="www.cebelca.biz">www.cebelca.biz</option>
		  <?php else : ?>
			<option value="www.cebelca.biz">www.cebelca.biz</option>
		  <?php endif;?>

		  <?php if($invoicefox_api_domain=='ww2.cebelca.biz') :?>
			<option selected="selected" value="ww2.cebelca.biz">ww2.cebelca.biz</option>
		  <?php else : ?>
			<option value="ww2.cebelca.biz">ww2.cebelca.biz</option>
		  <?php endif;?>

		  <?php if($invoicefox_api_domain=='test.cebelca.biz') :?>
			<option selected="selected" value="test.cebelca.biz">test.cebelca.biz</option>
		  <?php else : ?>
			<option value="test.cebelca.biz">test.cebelca.biz</option>
		  <?php endif;?>	 

		  <?php if($invoicefox_api_domain=='www.abelie.biz') :?>
			<option selected="selected" value="www.abelie.biz">www.abelie.biz</option>
		  <?php else : ?>
			<option value="www.abelie.biz">www.abelie.biz</option>
		  <?php endif;?>

                </select>
	    </td>
          </tr>

          <tr>
            <td><?php echo $entry_app_name; ?></td>
            <td><input type="text" name="invoicefox_app_name" value="<?php echo $invoicefox_app_name; ?>" />
	    <?php if ($error_app_name) { ?>
              <span class="error"><?php echo $error_app_name; ?></span>
              <?php } ?>
	    </td>
          </tr>

	  <tr>
            <td><?php echo $entry_document_to_make; ?></td>
            <td><select name="invoicefox_document_to_make">
                  <?php if($invoicefox_document_to_make=='invoice') :?>
			<option selected="selected" value="invoice">invoice</option>
		  <?php else : ?>
			<option value="invoice">invoice</option>
		  <?php endif;?>

		  <?php if($invoicefox_document_to_make=='proforma') :?>
			<option selected="selected" value="proforma">proforma</option>
		  <?php else : ?>
			<option value="proforma">proforma</option>
		  <?php endif;?>
		  
		  <?php if($invoicefox_document_to_make=='inventory') :?>
			<option selected="selected" value="inventory">inventory</option>
		  <?php else : ?>
			<option value="inventory">inventory</option>
		  <?php endif;?>

		  
                </select>
	    </td>
          </tr>
	
	  <tr>
            <td><?php echo $entry_proforma_days_valid; ?></td>
            <td><input type="text" name="invoicefox_proforma_days_valid" value="<?php echo $invoicefox_proforma_days_valid; ?>" />
	    <?php if ($error_proforma_days_valid) { ?>
              <span class="error"><?php echo $error_proforma_days_valid; ?></span>
              <?php } ?>
	    </td>
          </tr>
	  
	  <tr>
            <td><?php echo $entry_customer_general_payment_period; ?></td>
            <td><input type="text" name="invoicefox_customer_general_payment_period" value="<?php echo $invoicefox_customer_general_payment_period; ?>" />
	    <?php if ($error_customer_general_payment_period) { ?>
              <span class="error"><?php echo $error_customer_general_payment_period; ?></span>
              <?php } ?>
	    </td>
          </tr>
	  <!--
	  <tr>
            <td><?php echo $entry_add_post_content_in_item_descr; ?></td>
            <td><select name="invoicefox_add_post_content_in_item_descr">
                  <?php if($invoicefox_add_post_content_in_item_descr==='1') :?>
			<option selected="selected" value="1">true</option>
		  <?php else : ?>
			<option value="1">true</option>
		  <?php endif;?>

		  <?php if($invoicefox_add_post_content_in_item_descr==='0') :?>
			<option selected="selected" value="0">false</option>
		  <?php else : ?>
			<option value="0">false</option>
		  <?php endif;?>

		  
                </select>
	    </td>
          </tr>
	  -->
	  <tr>
            <td><?php echo $entry_display_partial_sum_label; ?></td>
            <td><select name="invoicefox_display_partial_sum_label">
                  <?php if($invoicefox_display_partial_sum_label==='1') :?>
			<option selected="selected" value="1">true</option>
		  <?php else : ?>
			<option value="1">true</option>
		  <?php endif;?>

		  <?php if($invoicefox_display_partial_sum_label==='0') :?>
			<option selected="selected" value="0">false</option>
		  <?php else : ?>
			<option value="0">false</option>
		  <?php endif;?>

		  
                </select>
	    </td>
          </tr>
	  
	  <tr>
            <td><?php echo $entry_partial_sum_label; ?></td>
            <td><input type="text" name="invoicefox_partial_sum_label" value="<?php echo $invoicefox_partial_sum_label; ?>" />
	    <?php if ($error_partial_sum_label) { ?>
              <span class="error"><?php echo $error_partial_sum_label; ?></span>
              <?php } ?>
	    </td>
          </tr>

	  <tr>
            <td><?php echo $entry_display_product_option_label; ?></td>
            <td><select name="invoicefox_display_product_option_label">
                  <?php if($invoicefox_display_product_option_label==='1') :?>
			<option selected="selected" value="1">true</option>
		  <?php else : ?>
			<option value="1">true</option>
		  <?php endif;?>

		  <?php if($invoicefox_display_product_option_label==='0') :?>
			<option selected="selected" value="0">false</option>
		  <?php else : ?>
			<option value="0">false</option>
		  <?php endif;?>

		  
                </select>
	    </td>
          </tr>
	  
	  
	  <tr>
            <td><?php echo $entry_round_calculated_taxrate_to; ?></td>
            <td><input type="text" name="invoicefox_round_calculated_taxrate_to" value="<?php echo $invoicefox_round_calculated_taxrate_to; ?>" />
	    </td>
          </tr>

	  <tr>
            <td><?php echo $entry_round_calculated_netprice_to; ?></td>
            <td><input type="text" name="invoicefox_round_calculated_netprice_to" value="<?php echo $invoicefox_round_calculated_netprice_to; ?>" />
	    </td>
          </tr>
	  
	  <tr>
            <td><?php echo $entry_from_warehouse_id; ?></td>
            <td><input type="text" name="invoicefox_from_warehouse_id" value="<?php echo $invoicefox_from_warehouse_id; ?>" />
	    </td>
          </tr>

	  <tr>
            <td><?php echo $entry_tax_rate_on_shipping; ?></td>
            <td><input type="text" name="invoicefox_tax_rate_on_shipping" value="<?php echo $invoicefox_tax_rate_on_shipping; ?>" />
	    </td>
          </tr>

	  <tr>
            <td><?php echo $entry_use_shop_document_numbers; ?></td>
            <td><select name="invoicefox_use_shop_document_numbers">
                  <?php if($invoicefox_use_shop_document_numbers==='1') :?>
			<option selected="selected" value="1">true</option>
		  <?php else : ?>
			<option value="1">true</option>
		  <?php endif;?>

		  <?php if($invoicefox_use_shop_document_numbers==='0') :?>
			<option selected="selected" value="0">false</option>
		  <?php else : ?>
			<option value="0">false</option>
		  <?php endif;?>

		  
                </select>
	    </td>
          </tr>

	   <tr>
            <td><?php echo $entry_create_invfox_document_on_status; ?></td>
            <td><select name="invoicefox_create_invfox_document_on_status">
                  <?php foreach($order_statuses as $order_statuse) :?>
		  <?php if($invoicefox_create_invfox_document_on_status==$order_statuse['name']) :?>
			<option selected="selected" value="<?php echo $order_statuse['name'];?>"><?php echo $order_statuse['name'];?></option>
		  <?php else : ?>
			<option value="<?php echo $order_statuse['name'];?>"><?php echo $order_statuse['name'];?></option>
		  <?php endif;?>
		  <?php endforeach;?>

		  
                </select>
	    </td>
          </tr>

	  <tr>
            <td><?php echo $entry_close_invfox_document_on_status; ?></td>
            <td><select name="invoicefox_close_invfox_document_on_status">
                  <?php foreach($order_statuses as $order_statuse) :?>
		  <?php if($invoicefox_close_invfox_document_on_status==$order_statuse['name']) :?>
			<option selected="selected" value="<?php echo $order_statuse['name'];?>"><?php echo $order_statuse['name'];?></option>
		  <?php else : ?>
			<option value="<?php echo $order_statuse['name'];?>"><?php echo $order_statuse['name'];?></option>
		  <?php endif;?>
		  <?php endforeach;?>

		  
                </select>
	    </td>
          </tr>
	
	  <tr>
            <td><?php echo $entry_tax_id; ?></td>
            <td><input type="text" name="invoicefox_tax_id" value="<?php echo $invoicefox_tax_id; ?>" />
	    <?php if ($error_tax_id) { ?>
              <span class="error"><?php echo $error_tax_id; ?></span>
              <?php } ?>
	    </td>
          </tr>

	  <tr>
            <td><?php echo $entry_tax_name; ?></td>
            <td><input type="text" name="invoicefox_tax_name" value="<?php echo $invoicefox_tax_name; ?>" />
	    <?php if ($error_tax_name) { ?>
              <span class="error"><?php echo $error_tax_name; ?></span>
              <?php } ?>
	    </td>
          </tr>

	  <tr>
            <td><?php echo $entry_tax_location; ?></td>
            <td><input type="text" name="invoicefox_tax_location" value="<?php echo $invoicefox_tax_location; ?>" />
	    <?php if ($error_tax_location) { ?>
              <span class="error"><?php echo $error_tax_location; ?></span>
              <?php } ?>
	    </td>
          </tr>

	  <tr>
            <td><?php echo $entry_tax_register; ?></td>
            <td><input type="text" name="invoicefox_tax_register" value="<?php echo $invoicefox_tax_register; ?>" />
	    <?php if ($error_tax_register) { ?>
              <span class="error"><?php echo $error_tax_register; ?></span>
              <?php } ?>
	    </td>
          </tr>

	  <tr>
            <td><?php echo $entry_fiscalize; ?></td>
            <td><select name="invoicefox_fiscalize">
                  <?php if($invoicefox_fiscalize=='1') :?>
			<option selected="selected" value="1">true</option>
		  <?php else : ?>
			<option value="1">true</option>
		  <?php endif;?>

		  <?php if($invoicefox_fiscalize=='0') :?>
			<option selected="selected" value="0">false</option>
		  <?php else : ?>
			<option value="0">false</option>
		  <?php endif;?>

		  
                </select>
	    </td>
          </tr>


        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--

//--></script> 
<?php echo $footer; ?>