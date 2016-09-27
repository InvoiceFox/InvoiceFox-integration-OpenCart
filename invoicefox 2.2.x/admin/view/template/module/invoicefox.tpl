<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-slideshow" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-invoicefox" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="invoicefox_api_key"><?php echo $entry_api_key; ?></label>
            <div class="col-sm-10">
              <input id="invoicefox_api_key" type="text" name="invoicefox_api_key" value="<?php echo $invoicefox_api_key; ?>" placeholder="<?php echo $entry_api_key; ?>" id="input-name" class="form-control" />
              <?php if ($error_api_key) { ?>
              <div class="text-danger"><?php echo $error_api_key; ?></div>
              <?php } ?>
            </div>
          </div>
          
	  <div class="form-group">
            <label class="col-sm-2 control-label" for="invoicefox_api_domain"><?php  echo $entry_api_domain; ?></label>
            <div class="col-sm-10"><select id="invoicefox_api_domain" name="invoicefox_api_domain"  class="form-control">
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
	    </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="invoicefox_app_name"><?php  echo $entry_app_name; ?></label>
            <div class="col-sm-10"><input id="invoicefox_app_name" type="text" name="invoicefox_app_name" value="<?php echo $invoicefox_app_name; ?>"  class="form-control"/>
	    <?php if ($error_app_name) { ?>
              <span class="error"><?php echo $error_app_name; ?></span>
              <?php } ?>
	    </div>
          </div>

	  <div class="form-group">
            <label class="col-sm-2 control-label" for="invoicefox_document_to_make"><?php  echo $entry_document_to_make; ?></label>
            <div class="col-sm-10"><select id="invoicefox_document_to_make" name="invoicefox_document_to_make"  class="form-control">
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
	    </div>
          </div>
	
	  <div class="form-group">
            <label class="col-sm-2 control-label" for="invoicefox_proforma_days_valid"><?php  echo $entry_proforma_days_valid; ?></label>
            <div class="col-sm-10"><input id="invoicefox_proforma_days_valid" type="text" name="invoicefox_proforma_days_valid" value="<?php echo $invoicefox_proforma_days_valid; ?>"  class="form-control"/>
	    <?php if ($error_proforma_days_valid) { ?>
              <span class="text-danger"><?php echo $error_proforma_days_valid; ?></span>
              <?php } ?>
	    </div>
          </div>
	  
	  <div class="form-group">
            <label class="col-sm-2 control-label" for="invoicefox_customer_general_payment_period"><?php  echo $entry_customer_general_payment_period; ?></label>
            <div class="col-sm-10"><input id="invoicefox_customer_general_payment_period" type="text" name="invoicefox_customer_general_payment_period" value="<?php echo $invoicefox_customer_general_payment_period; ?>"  class="form-control"/>
	    <?php if ($error_customer_general_payment_period) { ?>
              <span class="text-danger"><?php echo $error_customer_general_payment_period; ?></span>
              <?php } ?>
	    </div>
          </div>
	  <!--
	  <div class="form-group">
            <label class="col-sm-2 control-label" for="invoicefox_add_post_content_in_item_descr"><?php  echo $entry_add_post_content_in_item_descr; ?></label>
            <div class="col-sm-10"><select id="invoicefox_add_post_content_in_item_descr" name="invoicefox_add_post_content_in_item_descr"  class="form-control">
                  <?php if($invoicefox_add_post_content_in_item_descr=='true') :?>
			<option selected="selected" value="true">true</option>
		  <?php else : ?>
			<option value="true">true</option>
		  <?php endif;?>

		  <?php if($invoicefox_add_post_content_in_item_descr=='false') :?>
			<option selected="selected" value="false">false</option>
		  <?php else : ?>
			<option value="false">false</option>
		  <?php endif;?>

		  
                </select>
	    </div>
          </div>
	  -->
	  <div class="form-group">
            <label class="col-sm-2 control-label" for="invoicefox_add_post_content_in_item_descr"><?php  echo $entry_display_product_option_label; ?></label>
            <div class="col-sm-10"><select id="invoicefox_add_post_content_in_item_descr" name="invoicefox_add_post_content_in_item_descr"  class="form-control">
                  <?php if($invoicefox_add_post_content_in_item_descr=='1') :?>
			<option selected="selected" value="1">true</option>
		  <?php else : ?>
			<option value="1">true</option>
		  <?php endif;?>

		  <?php if($invoicefox_add_post_content_in_item_descr=='0') :?>
			<option selected="selected" value="0">false</option>
		  <?php else : ?>
			<option value="0">false</option>
		  <?php endif;?>

		  
                </select>
	    </div>
          </div>
	  
	  <div class="form-group">
            <label class="col-sm-2 control-label" for="invoicefox_partial_sum_label"><?php  echo $entry_partial_sum_label; ?></label>
            <div class="col-sm-10"><input id="invoicefox_partial_sum_label" type="text" name="invoicefox_partial_sum_label" value="<?php echo $invoicefox_partial_sum_label; ?>"  class="form-control"/>
	    <?php if ($error_partial_sum_label) { ?>
              <span class="text-danger"><?php echo $error_partial_sum_label; ?></span>
              <?php } ?>
	    </div>
          </div>
	  
	  <div class="form-group">
            <label class="col-sm-2 control-label" for="invoicefox_round_calculated_taxrate_to"><?php  echo $entry_round_calculated_taxrate_to; ?></label>
            <div class="col-sm-10"><input id="invoicefox_round_calculated_taxrate_to" type="text"  name="invoicefox_round_calculated_taxrate_to" value="<?php echo $invoicefox_round_calculated_taxrate_to; ?>"  class="form-control" />
	    </div>
          </div>

	  <div class="form-group">
            <label class="col-sm-2 control-label" for="invoicefox_round_calculated_netprice_to"><?php  echo $entry_round_calculated_netprice_to; ?></label>
            <div class="col-sm-10"><input id="invoicefox_round_calculated_netprice_to" type="text" name="invoicefox_round_calculated_netprice_to" value="<?php echo $invoicefox_round_calculated_netprice_to; ?>"  class="form-control"/>
	    </div>
          </div>
	  
	  <div class="form-group">
            <label class="col-sm-2 control-label" for="invoicefox_from_warehouse_id"><?php  echo $entry_from_warehouse_id; ?></label>
            <div class="col-sm-10"><input id="invoicefox_from_warehouse_id" type="text" name="invoicefox_from_warehouse_id" value="<?php echo $invoicefox_from_warehouse_id; ?>"  class="form-control"/>
	    </div>
          </div>

	  <div class="form-group">
            <label class="col-sm-2 control-label" for="invoicefox_tax_rate_on_shipping"><?php  echo $entry_tax_rate_on_shipping; ?></label>
            <div class="col-sm-10"><input id="invoicefox_tax_rate_on_shipping" type="text" name="invoicefox_tax_rate_on_shipping" value="<?php echo $invoicefox_tax_rate_on_shipping; ?>"  class="form-control" />
	    </div>
          </div>

	  <div class="form-group">
            <label class="col-sm-2 control-label" for="invoicefox_use_shop_document_numbers"><?php  echo $entry_use_shop_document_numbers; ?></label>
            <div class="col-sm-10"><select id="invoicefox_use_shop_document_numbers" name="invoicefox_use_shop_document_numbers"  class="form-control">
                  <?php if($invoicefox_use_shop_document_numbers=='true') :?>
			<option selected="selected" value="true">true</option>
		  <?php else : ?>
			<option value="true">true</option>
		  <?php endif;?>

		  <?php if($invoicefox_use_shop_document_numbers=='false') :?>
			<option selected="selected" value="false">false</option>
		  <?php else : ?>
			<option value="false">false</option>
		  <?php endif;?>

		  
                </select>
	    </div>
          </div>

	   <div class="form-group">
            <label class="col-sm-2 control-label" for="invoicefox_create_invfox_document_on_status"><?php  echo $entry_create_invfox_document_on_status; ?></label>
            <div class="col-sm-10"><select id="invoicefox_create_invfox_document_on_status" name="invoicefox_create_invfox_document_on_status"  class="form-control">
                  <?php foreach($order_statuses as $order_statuse) :?>
		  <?php if($invoicefox_create_invfox_document_on_status==$order_statuse['name']) :?>
			<option selected="selected" value="<?php echo $order_statuse['name'];?>"><?php echo $order_statuse['name'];?></option>
		  <?php else : ?>
			<option value="<?php echo $order_statuse['name'];?>"><?php echo $order_statuse['name'];?></option>
		  <?php endif;?>
		  <?php endforeach;?>

		  
                </select>
	    </div>
          </div>

	   <div class="form-group">
            <label class="col-sm-2 control-label" for="invoicefox_close_invfox_document_on_status"><?php  echo $entry_close_invfox_document_on_status; ?></label>
            <div class="col-sm-10"><select id="invoicefox_close_invfox_document_on_status"  name="invoicefox_close_invfox_document_on_status"  class="form-control">
                  <?php foreach($order_statuses as $order_statuse) :?>
		  <?php if($invoicefox_close_invfox_document_on_status==$order_statuse['name']) :?>
			<option selected="selected" value="<?php echo $order_statuse['name'];?>"><?php echo $order_statuse['name'];?></option>
		  <?php else : ?>
			<option value="<?php echo $order_statuse['name'];?>"><?php echo $order_statuse['name'];?></option>
		  <?php endif;?>
		  <?php endforeach;?>

		  
                </select>
	    </div>
          </div>


	  <div class="form-group">
            <label class="col-sm-2 control-label" for="invoicefox_tax_id"><?php  echo $entry_tax_id; ?></label>
            <div class="col-sm-10"><input id="invoicefox_tax_id" type="text" name="invoicefox_tax_id" value="<?php echo $invoicefox_tax_id; ?>"  class="form-control"/>
	    <?php if ($error_tax_id) { ?>
              <span class="error"><?php echo $error_tax_id; ?></span>
              <?php } ?>
	    </div>
          </div>

	  <div class="form-group">
            <label class="col-sm-2 control-label" for="invoicefox_tax_name"><?php  echo $entry_tax_name; ?></label>
            <div class="col-sm-10"><input id="invoicefox_tax_name" type="text" name="invoicefox_tax_name" value="<?php echo $invoicefox_tax_name; ?>"  class="form-control"/>
	    <?php if ($error_tax_name) { ?>
              <span class="error"><?php echo $error_tax_name; ?></span>
              <?php } ?>
	    </div>
          </div>

	  <div class="form-group">
            <label class="col-sm-2 control-label" for="invoicefox_tax_location"><?php  echo $entry_tax_location; ?></label>
            <div class="col-sm-10"><input id="invoicefox_tax_location" type="text" name="invoicefox_tax_location" value="<?php echo $invoicefox_tax_location; ?>"  class="form-control"/>
	    <?php if ($error_tax_location) { ?>
              <span class="error"><?php echo $error_tax_location; ?></span>
              <?php } ?>
	    </div>
          </div>

	  <div class="form-group">
            <label class="col-sm-2 control-label" for="invoicefox_tax_register"><?php  echo $entry_tax_register; ?></label>
            <div class="col-sm-10"><input id="invoicefox_tax_register" type="text" name="invoicefox_tax_register" value="<?php echo $invoicefox_tax_register; ?>"  class="form-control"/>
	    <?php if ($error_tax_register) { ?>
              <span class="error"><?php echo $error_tax_register; ?></span>
              <?php } ?>
	    </div>
          </div>

	   <div class="form-group">
            <label class="col-sm-2 control-label" for="invoicefox_fiscalize"><?php  echo $entry_fiscalize; ?></label>
            <div class="col-sm-10"><select id="invoicefox_fiscalize" name="invoicefox_fiscalize"  class="form-control">
                  <?php if($invoicefox_fiscalize=='1') :?>
			<option selected="selected" value="1">true</option>
		  <?php else : ?>
			<option value="1">true</option>
		  <?php endif;?>

		  <?php if($invoicefox_fiscalize==='0') :?>
			<option selected="selected" value="0">false</option>
		  <?php else : ?>
			<option value="0">false</option>
		  <?php endif;?>

		  
                </select>
	    </div>
          </div>
	 


	 

        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>