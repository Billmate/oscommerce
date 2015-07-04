<?php
/**
 *  Copyright 2015 Billmate AB. All rights reserved.
 *
 *  Redistribution and use in source and binary forms, with or without modification, are
 *  permitted provided that the following conditions are met:
 *
 *     1. Redistributions of source code must retain the above copyright notice, this list of
 *        conditions and the following disclaimer.
 *
 *     2. Redistributions in binary form must reproduce the above copyright notice, this list
 *        of conditions and the following disclaimer in the documentation and/or other materials
 *        provided with the distribution.
 *
 *  THIS SOFTWARE IS PROVIDED BY BILLMATE AB "AS IS" AND ANY EXPRESS OR IMPLIED
 *  WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND
 *  FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL BILLMATE AB OR
 *  CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 *  CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 *  SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 *  ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF
 *  ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 *  The views and conclusions contained in the software and documentation are those of the
 *  authors and should not be interpreted as representing official policies, either expressed
 *  or implied, of Billmate AB.
 *
 */

  // Translations in installer
  define('MODULE_PAYMENT_BILLMATE_ALLOWED_TITLE', 'L�mna det tomt!');
  define('MODULE_PAYMENT_BILLMATE_ALLOWED_DESC', '');
  define('MODULE_PAYMENT_BILLMATE_STATUS_TITLE', 'Aktivera Billmate Faktura');
  define('MODULE_PAYMENT_BILLMATE_STATUS_DESC', 'Vill du ta emot Billmate betalningar?');
  define('MODULE_PAYMENT_BILLMATE_ORDER_STATUS_ID_TITLE', 'St�ll Orderstatus');
  define('MODULE_PAYMENT_BILLMATE_ORDER_STATUS_ID_DESC', 'St�ll status f�r best�llningar som g�rs med denna betalningsmetod modul till detta v�rde');
  define('MODULE_PAYMENT_BILLMATE_EID_TITLE', 'Merchant ID');
  define('MODULE_PAYMENT_BILLMATE_EID_DESC', 'Merchant ID (eStore id) som ska anv�ndas f�r Billmate tj�nst (som tillhandah�lls av Billmate)');
  define('MODULE_PAYMENT_BILLMATE_SECRET_TITLE', 'delad hemlighet');
  define('MODULE_PAYMENT_BILLMATE_SECRET_DESC', 'Delad hemlighet att anv�nda med Billmate tj�nst (som tillhandah�lls av Billmate)');
  define('MODULE_PAYMENT_BILLMATE_ARTNO_TITLE', 'Produkt art nr attribut (id eller modell)');
  define('MODULE_PAYMENT_BILLMATE_ARTNO_DESC', 'Anv�nd f�ljande produkt attribut f�r artnr.');

  define('MODULE_PAYMENT_BILLMATE_PERSON_NUMBER','Personnummer / Organisationsnummer:');
  define('MODULE_PAYMENT_BILLMATE_EMAIL','Min e-postadress �r korrekt och f�r anv�ndas f�r fakturering.<br/>Jag bekr�ftar �ven <a style="text-decoration: underline !important;" id="terms" href="javascript:;">k&ouml;pvillkoren</a>  och accepterar betalningsansvaret.
    <script>
    if (!window.jQuery) {
      var script = document.createElement(\'script\');
      script.type = "text/javascript";
      script.src = "http://code.jquery.com/jquery-1.9.1.js";
      document.getElementsByTagName(\'head\')[0].appendChild(script);
    }
    </script>
    <script type="text/javascript">
      setTimeout(function(){
        jQuery(function(){
          $.getScript("https://billmate.se/billmate/base.js", function(){
            $("#terms").Terms("villkor",{invoicefee: 0});
          });
        });
      },1000);
    </script>');
  define('MODULE_PAYMENT_BILLMATE_ADDR_TITLE','');
  define('MODULE_PAYMENT_BILLMATE_CONDITIONS','');
  define('MODULE_PAYMENT_BILLMATE_ADDR_NOTICE','<br/>Observera: Din faktura- och leveransadress kommer att<br/>automatiskt uppdateras till din folkbokf�rda adress.');

  define('MODULE_PAYMENT_BILLMATE_ORDER_LIMIT_TITLE', 'kreditgr�ns');
  define('MODULE_PAYMENT_BILLMATE_MIN_ORDER_LIMIT_TITLE', 'Minsta Orderv�rde');

  define('MODULE_PAYMENT_BILLMATE_ORDER_LIMIT_DESC', 'Visa endast denna betalning alternativ f�r best�llningar med f�rre �n v�rdet nedan.');
  define('MODULE_PAYMENT_BILLMATE_MIN_ORDER_LIMIT_DESC', 'Visa endast denna betalning alternativ f�r best�llningar med st�rre �n v�rdet nedan.');
  define('MODULE_PAYMENT_BILLMATE_ORDER_TOTAL_IGNORE_TITLE', 'Ignorera tabell');
  define('MODULE_PAYMENT_BILLMATE_ORDER_TOTAL_IGNORE_DESC', 'Ignorera dessa poster fr�n den totala ordersumman listan n�r de sammanst�ller fakturaunderlag');
  define('MODULE_PAYMENT_BILLMATE_ZONE_TITLE', 'Betalning Zone');
  define('MODULE_PAYMENT_BILLMATE_ZONE_DESC', 'Om en zon �r markerad, endast aktivera denna betalningsmetod f�r den zonen.');
  define('MODULE_PAYMENT_BILLMATE_TAX_CLASS_TITLE', 'Tax Class');
  define('MODULE_PAYMENT_BILLMATE_TAX_CLASS_DESC', 'Anv�nd f�ljande skatteklass p� betalning laddning.');
  define('MODULE_PAYMENT_BILLMATE_SORT_ORDER_TITLE', 'Sortera ordningen p� displayen.');
  define('MODULE_PAYMENT_BILLMATE_SORT_ORDER_DESC', 'Sortera ordningen p� displayen. L�gst visas f�rst.');
  define('MODULE_PAYMENT_BILLMATE_LIVEMODE_TITLE', 'Live Server');
  define('MODULE_PAYMENT_BILLMATE_LIVEMODE_DESC', 'Vill du anv�nda Billmate LIVE server (true) eller BETA-servern (falskt)?');
  define('MODULE_PAYMENT_BILLMATE_TEXT_TESTMODE_TITLE', '(TESTL�GE)');
  define('MODULE_PAYMENT_BILLMATE_TEXT_TESTMODE_DESC', 'Vill du aktivera testl�get? Vi kommer inte att betala f�r de fakturor som skapas med testpersonerna eller f�retag, och vi kommer inte att samla in n�gra avgifter ocks�.');

  define('MODULE_PAYMENT_BILLMATE_TEXT_TITLE', 'Faktura - Betala 14 dagar efter leverans');
  define('MODULE_PAYMENT_BILLMATE_FRONTEND_TEXT_TITLE', 'Faktura - Betala 14 dagar efter leverans');
  define('MODULE_PAYMENT_BILLMATE_TEXT_DESCRIPTION', 'Svensk faktura fr�n Billmate');
  define('MODULE_PAYMENT_BILLMATE_TEXT_CONFIRM_DESCRIPTION', 'www.billmate.se');

  define('MODULE_PAYMENT_BILLMATE_ADDRESS_WRONG', 'K�p mot faktura kan bara g�ras till den adress som �r angiven i folkbokf�ringen. Vill du genomf�ra k�pet med adressen:');
  define('MODULE_PAYMENT_BILLMATE_CORRECT_ADDRESS', 'Din bokf�ringsadress:');
  define('MODULE_PAYMENT_BILLMATE_CORRECT_ADDRESS_OPTION', 'Klicka p� Ja f�r att forts�tta med den nya adressen, Nej f�r att v�lja ett annat betalningss�tt');
  define('MODULE_PAYMENT_BILLMATE_YES', 'Ja, genomf�r k�p med denna adress');
  define('MODULE_PAYMENT_BILLMATE_NO', 'Nej jag vill ange ett annat personnummer eller byta betals�tt');
  define('MODULE_PAYMENT_BILLMATE_VAT','Moms');
  define('MODULE_PAYMENT_BILLMATE_EXTRA_FEE',' - Fakturaavgift p� %s tillkommer p� ordern.');

  define('MODULE_PAYMENT_BILLMATE_CHOOSEALTERNATIVES', 'V&auml;lj alternativ adress nedan');
  define('MODULE_PAYMENT_BILLMATE_ERRORINVOICE', 'Billmate - misslyckades');
