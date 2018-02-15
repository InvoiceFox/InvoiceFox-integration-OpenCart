<?php

class InvfoxAPI {

  var $api;

  function InvfoxAPI($apitoken, $apidomain, $debugMode=false) {
    $this->api = new StrpcAPI($apitoken, $apidomain, $debugMode);
  }

  function setDebugHook($hook) {
    $this->api->debugHook = $hook;
  }

  function assurePartner($data) {
    $res = $this->api->call('partner', 'assure', $data);
    if ($res->isErr()) {
      echo 'error' . $res->getErr();
    }
    return $res;
  }

  function createInvoice($header, $body) {
    $res = $this->api->call('invoice-sent', 'insert-smart-2', $header);
    if ($res->isErr()) {
      echo 'error' . $res->getErr();
    } else {
      foreach ($body as $bl) {
	$resD = $res->getData();
	//print_r($resD);
	$bl['id_invoice_sent'] = $resD[0]['id'];
	$res2 = $this->api->call('invoice-sent-b', 'insert-into', $bl);
	if ($res2->isErr()) {
	  echo 'error' . $res->getErr();
	} 
      }
    }
    return $res;
  }

  /*
  * finalizes the invoice
  * 
  * $header['id']=$invId; // invoice Id
  * $header['fiscalize'] = 1;  // should fiscalize or not / cash invoice or not (send to FURS / Tax Office)
  * $header['id_location'] = 1; // id of location 
  * $header['op-tax-id'] = "12345678"; // personal tax number of the issuer
  * $header['op-name'] = "Andrej"; // name or label of the issuer
  *
  * 
  * returns: [[{"docnum":"P1-B1-42","eor":"443d18e9-0f0a-48a6-a27d-7fcea373ef88"}]]
  */

  function finalizeInvoice($header)
  {
	$res = $this->api->call('invoice-sent', 'finalize-invoice', $header);
	if ($res->isErr()) {
		echo 'error' . $res->getErr();
	} else {
		$resD = $res->getData();
		return $resD;
	}
  }
  
  /*
  * gets fiscal info of invoice (EOR, ZOI, QR code)
  * id // id of invoice 
  *
  * returns: [[{"id":80,"tax_id":"10217177","operator_tax_id":"12345678","invoice_amount":1440.0,"location_id":"P1","register_id":"B1","zoi_code":"ad3d87a26aab4a6d5a81c8cfae4b2bac","zoi_code_dec":"230275924372432379612582134529131228076","bar_code":"230275924372432379612582134529131228076102171771512240025088","eor_code":"443d18e9-0f0a-48a6-a27d-7fcea373ef88","date_time":"2015-12-24T00:25:08","operator_name":"PRODAJALEC1"}]]
  */
  
  function getFiscalInfo($id) {
	$res = $this->api->call('invoice-sent', 'get-fiscal-info', array('id' => $id));
	if ($res->isErr()) {
		echo 'error' . $res->getErr();
		return false;
	} else {
		$resD = $res->getData();
		return $resD;
	}
  }

  function createProFormaInvoice($header, $body) {
    $res = $this->api->call('preinvoice', 'insert-smart', $header);
    //		print_r($res);
    if ($res->isErr()) {
      echo 'error' . $res->getErr();
    } else {
      foreach ($body as $bl) {
	$resD = $res->getData();
	//print_r($resD);
	$bl['id_preinvoice'] = $resD[0]['id'];
	$res2 = $this->api->call('preinvoice-b', 'insert-into', $bl);
	if ($res2->isErr()) {
	  echo 'error' . $res->getErr();
	} 
      }
    }
    return $res;
  }

  function createInventorySale($header, $body) {
    $res = $this->api->call('transfer', 'insert-smart', $header);
    //		print_r($res);
    if ($res->isErr()) {
      echo 'error' . $res->getErr();
    } else {
      foreach ($body as $bl) {
	$resD = $res->getData();
	//print_r($resD);
	$bl['id_transfer'] = $resD[0]['id'];
	$res2 = $this->api->call('transfer-b', 'insert-into', $bl);
	if ($res2->isErr()) {
	  echo 'error' . $res->getErr();
	} 
      }
    }
    return $res;
  }

  function downloadPDF($id, $path, $res='invoice-sent', $hstyle='') {
    // $res - invoice-sent / preinvoice / transfer
    echo $id;
    $opts = array(
		  'http'=>array(
				'method'=>"GET",
				'header'=>"Authorization: Basic ".base64_encode($this->api->apitoken.':x')."\r\n" 
				)
		  );
    $context = stream_context_create($opts);
    $data = file_get_contents("https://{$this->api->domain}/API-pdf?id=$id&res={$res}&format=PDF&doctitle=Invoice%20No.&lang=si&hstyle={$hstyle}", false, $context);
		
    if ($data === false) {
      echo 'error downloading PDF';
    } else {
      $file = $path."/".$id.".pdf";
      file_put_contents($file, $data);
    }
  }

  function markInvoicePaid($id, $payment_method=1) {
    $res = $this->api->call('invoice-sent-p', 'mark-paid', array('id_invoice_sent_ext' => $id, 
							       'date_of' => date("Y-m-d"), 'amount' => 0, 'id_payment_method' => $payment_method, 'id_invoice_sent' => 0));
	
	if ($res->isErr()) {
      echo 'error' . $res->getErr();
    }
  }

  function checkInvtItems($items, $warehouse, $date) {
    $skv = "";
    foreach ($items as $item) {
      $skv .= $item['code'].";".$item['qty']."|";
    }
    $res2 = $this->api->call('item', 'check-items', array("just-for-items" => $skv, "warehouse" => $warehouse, "date" => $date));
    if ($res2->isErr()) {
      echo 'error' . $res->getErr();
    } 
    return $res2->getData();
    // TODO -- return what is not on inventory OR item missing OR if all is OK
  }


  function _toUSDate($d) {
    if (strpos($d, "-") > 0) {
      $da = explode(" ", $d);
      $d1 = explode("-", $da[0]);
      return $d1[1]."/".$d1[2]."/".$d1[0];
    } else {
      return $d;
    }
  }

  function _toSIDate($d) {
    if (strpos($d, "-") > 0) {
      $da = explode(" ", $d);
      $d1 = explode("-", $da[0]);
      return $d1[2].".".$d1[1].".".$d1[0];
    } else {
      return $d;
    }
  }

  function printInvoice($id, $res='invoice-sent',$hstyle='basicVER3') { //basicVER3UPN
    // $res - invoice-sent / preinvoice / transfer
    $opts = array(
		  'http'=>array(
				'method'=>"GET",
				'header'=>"Authorization: Basic ".base64_encode($this->api->apitoken.':x')."\r\n" 
				)
		  );
    $context = stream_context_create($opts); //Predračun%20št. Račun%20št. //inv-template basic modern elegant basicVER3  basicVER3UPN modernVER3 elegantVER3
	$data = file_get_contents("http://{$this->api->domain}/API-pdf?id=0&extid={$id}&res={$res}&format=PDF&doctitle=".urlencode('')."&lang=si&hstyle=$hstyle", false, $context);
    if ($data === false) { 
      echo 'error downloading PDF';
    } else {
      
	  header('Content-type: application/pdf');
	  header('Content-Disposition: inline; filename="invoice.pdf"');
      header('Content-Transfer-Encoding: binary');
      header('Accept-Ranges: bytes');
	  echo $data;
    }
  }



}

/*
CREATE TABLE IF NOT EXISTS `oc_invoicefox` (
  `id` int(11) unsigned NOT NULL,
  `invoicefox_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` enum('active','deleted') NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
 
*/
?>
