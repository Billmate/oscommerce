<?php
/**
 *  Copyright 2010 BILLMATECARDPAY AB. All rights reserved.
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
 *  THIS SOFTWARE IS PROVIDED BY BILLMATECARDPAY AB "AS IS" AND ANY EXPRESS OR IMPLIED
 *  WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND
 *  FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL BILLMATECARDPAY AB OR
 *  CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 *  CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 *  SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 *  ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF
 *  ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 *  The views and conclusions contained in the software and documentation are those of the
 *  authors and should not be interpreted as representing official policies, either expressed
 *  or implied, of BILLMATECARDPAY AB.
 *
 */

  // Translations in installer
  define('MODULE_PAYMENT_BILLMATECARDPAY_ALLOWED_TITLE', 'L�mna det tomt!');
  define('MODULE_PAYMENT_BILLMATECARDPAY_ALLOWED_DESC', '');
  define('MODULE_PAYMENT_BILLMATECARDPAY_STATUS_TITLE', 'Aktivera Billmate Kort');
  define('MODULE_PAYMENT_BILLMATECARDPAY_STATUS_DESC', 'Vill du ta emot Billmate Korts betalningar?');
  define('MODULE_PAYMENT_BILLMATECARDPAY_ORDER_STATUS_ID_TITLE', 'St�ll Orderstatus');
  define('MODULE_PAYMENT_BILLMATECARDPAY_ORDER_STATUS_ID_DESC', 'St�ll status f�r best�llningar som g�rs med denna betalningsmetod modul till detta v�rde');
  define('MODULE_PAYMENT_BILLMATECARDPAY_EID_TITLE', 'Merchant ID');
  define('MODULE_PAYMENT_BILLMATECARDPAY_EID_DESC', 'Merchant ID (eStore id) som ska anv�ndas f�r Billmate Kort tj�nsten (tillhandah�lls av Billmate Kort)');
  define('MODULE_PAYMENT_BILLMATECARDPAY_SECRET_TITLE', 'delad hemlighet');
  define('MODULE_PAYMENT_BILLMATECARDPAY_SECRET_DESC', 'Delad hemlighet att anv�nda med Billmate Kort tj�nst (som tillhandah�lls av Billmate Kort)');
  define('MODULE_PAYMENT_BILLMATECARDPAY_ARTNO_TITLE', 'Produkt art nr attribut (id eller modell)');
  define('MODULE_PAYMENT_BILLMATECARDPAY_ARTNO_DESC', 'Anv�nd f�ljande produkt attribut f�r artnr.');
  define('MODULE_PAYMENT_BILLMATECARDPAY_ORDER_LIMIT_TITLE', 'kreditgr�ns');
  define('MODULE_PAYMENT_BILLMATECARDPAY_MIN_ORDER_LIMIT_TITLE', 'Minsta orderv�rde');
  define('MODULE_PAYMENT_BILLMATECARDPAY_ORDER_LIMIT_DESC', 'Visa endast denna betalning alternativ f�r best�llningar med f�rre �n v�rdet nedan.');
  define('MODULE_PAYMENT_BILLMATECARDPAY__MIN_ORDER_LIMIT_DESC', 'Visa endast denna betalning alternativ f�r best�llningar med st�rre �n v�rdet nedan.');
  define('MODULE_PAYMENT_BILLMATECARDPAY_ORDER_TOTAL_IGNORE_TITLE', 'Ignorera tabell');
  define('MODULE_PAYMENT_BILLMATECARDPAY_ORDER_TOTAL_IGNORE_DESC', 'Ignorera dessa poster fr�n den totala ordersumman listan n�r de sammanst�ller fakturaunderlag');
  define('MODULE_PAYMENT_BILLMATECARDPAY_ZONE_TITLE', 'Betalning Zone');
  define('MODULE_PAYMENT_BILLMATECARDPAY_ZONE_DESC', 'Om en zon �r markerad, endast aktivera denna betalningsmetod f�r den zonen.');
  define('MODULE_PAYMENT_BILLMATECARDPAY_TAX_CLASS_TITLE', 'Tax Class');
  define('MODULE_PAYMENT_BILLMATECARDPAY_TAX_CLASS_DESC', 'Anv�nd f�ljande skatteklass p� betalning laddning.');
  define('MODULE_PAYMENT_BILLMATECARDPAY_SORT_ORDER_TITLE', 'Sortera ordningen p� displayen.');
  define('MODULE_PAYMENT_BILLMATECARDPAY_SORT_ORDER_DESC', 'Sortera ordningen p� displayen. L�gst visas f�rst.');
  define('MODULE_PAYMENT_BILLMATECARDPAY_LIVEMODE_TITLE', 'Live Server');
  define('MODULE_PAYMENT_BILLMATECARDPAY_LIVEMODE_DESC', 'Vill du anv�nda Billmate Kort LIVE server (true) eller BETA-servern (falskt)?');
  define('MODULE_PAYMENT_BILLMATECARDPAY_TESTMODE_TITLE', '(Test-l�ge)');
  define('MODULE_PAYMENT_BILLMATECARDPAY_TESTMODE_DESC', 'Vill du aktivera testl�get? Vi kommer inte att betala f�r de fakturor som skapas med testpersonerna eller f�retag, och vi kommer inte att samla in n�gra avgifter ocks�.');
	define('MODULE_PAYMENT_BILLMATECARDPAY_VAT','Moms');

  define('MODULE_PAYMENT_BILLMATECARDPAY_TEXT_TITLE', 'Billmate Kort');
  define('MODULE_PAYMENT_BILLMATECARDPAY_LANG_TESTMODE', '(TESTL�GE)');
  define('MODULE_PAYMENT_BILLMATECARDPAY_TEXT_DESCRIPTION', 'Credit Kortk�p fr�n Billmate Kort');
  define('MODULE_PAYMENT_BILLMATECARDPAY_TEXT_CONFIRM_DESCRIPTION', 'www.billmate.se');
  
