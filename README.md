##Cebelca.biz modul za OpenCart 1.5.x

###Kaj omogoča

Povezava vašega OpenCart sistema s Cebelca.biz omogoča:
 - administracijski vmesnik v OpenCartu
 - prenos računov in strank
 - tiskanje CebelcaUPN računov kar preko OpenCart sistema
 - tiskanje Cebelca računov kar preko OpenCart sistema
 - avtomatsko dodajanje računov pod “Plačane” na Cebelci kar preko OpenCart-a

###Namestitev

 - za delovanje mora biti na OpenCartu naložen vQmod
 - naložite datoteke na vaš strežnik
 - v OpenCartu pod “Razširitve” in “Moduli” omogočite “InvoiceFox”
 - sledite navodilom in vpišite vaše podatke*
 - dodajte novo tabelo v MySQL bazo**

###Dodajanje tabele
Da lahko nekatere funkcije delujejo, morate dodati novo tabelo v MySQL bazo
(cPanel > phpMyAdmin > kliknete na bazo > zavihek “SQL”).
Ukaz je naslednji.

  CREATE TABLE IF NOT EXISTS `oc_invoicefox` (
    `id` int(11) unsigned NOT NULL,
    `invoicefox_id` int(11) NOT NULL,
    `order_id` int(11) NOT NULL,
    `status` enum('active','deleted') NOT NULL
  ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

###Vprašanja

Za vprašanja se lahko obrnete na
podpora@cebelca.biz ali matej@koren.eu

###Kje plugin deluje

http://www.cebelca.biz , http://www.invoicefox.com , http://www.invoicefox.co.nz


###Avtorji

originalni modula: REFAKTOR d.o.o. / Cebelca.biz

nadgradnja modula: KULER d.o.o. / Koren.eu


