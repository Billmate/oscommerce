<?php
//include 'pclasses.php';

// instant backwards compatibility
if (!defined('PATH_SEPARATOR')) {
    if (OS_WINDOWS) {
        define('PATH_SEPARATOR', ';');
    } else {
        define('PATH_SEPARATOR', ':');
    }
}
require_once(dirname( __FILE__ )."/lib/xmlrpc.inc");
require_once(dirname( __FILE__ )."/lib/xmlrpcs.inc");
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'commonfunctions.php';
require_once dirname(__FILE__).'/BillMate.php';

$GLOBALS['_PEAR_default_error_options']  = E_USER_NOTICE;
$GLOBALS['_PEAR_destructor_object_list'] = array();
$GLOBALS['_PEAR_shutdown_funcs']         = array();
$GLOBALS['_PEAR_error_handler_stack']    = array();

/*
 * XMLRPC protocol and kreditor.php version
 */

global $XMLRPC_LIB;
$XMLRPC_LIB = "BPEAR";
global $PROTO_VSN;
$PROTO_VSN = "4.0";
global $CLIENT_VSN;
$CLIENT_VSN = BILLMATE_VERSION;//"PHP:OsCommerce:1.13";
global $KREDITOR_PORT;
global $KREDITOR_HOST;

$KREDITOR_PORT = 443;			

//Use Billmate LIVE server or BETA server
if ($livemode == true) {
	$KREDITOR_HOST = "api.billmate.se";
} else {
	$KREDITOR_HOST = "apitest.billmate.se";
}

/*
 * Mode: $shipmenttype
 */

global $KRED_NORMAL_SHIPMENT;
$KRED_NORMAL_SHIPMENT = 1;
global $NORMAL_SHIPMENT; // backwards compatibility
$NORMAL_SHIPMENT = 1;
global $KRED_EXPRESS_SHIPMENT;
$KRED_EXPRESS_SHIPMENT = 2;
global $EXPRESS_SHIPMENT; // backwards compatibility
$EXPRESS_SHIPMENT = 2;

/*
 * Flags: $flags
 */

global $KRED_AUTO_ACTIVATE;
$KRED_AUTO_ACTIVATE = 1;
global $AUTO_ACTIVATE; // backwards compatibility
$AUTO_ACTIVATE = 1;
global $KRED_TEST_MODE;
$KRED_TEST_MODE = 2;
global $TEST_MODE; // backwards compatibility
$TEST_MODE = 2;
global $KRED_MANUAL_AUTO_ACTIVATE;
$KRED_MANUAL_AUTO_ACTIVATE = 4;
global $KRED_PRE_PAY;
$KRED_PRE_PAY = 8;


/* 
 * Flags: $flags in mk_goods
 */

global $KRED_PRINT_1000;
$KRED_PRINT_1000 = 1;
global $KRED_PRINT_100;
$KRED_PRINT_100 = 2;
global $KRED_PRINT_10;
$KRED_PRINT_10 = 4;
global $KRED_IS_SHIPMENT;
$KRED_IS_SHIPMENT = 8;
global $KRED_IS_HANDLING;
$KRED_IS_HANDLING = 16;
global $KRED_INC_VAT;
$KRED_INC_VAT = 32;

/*
 * Flags monthly_cost
 */
global $KRED_ACTUAL_COST;
$KRED_ACTUAL_COST = 1;
global $KRED_LIMIT_COST;
$KRED_LIMIT_COST = 0;

/* 
 * Mode: $type in get_addresses
 */
global $GA_OLD;
$GA_OLD = 1;
global $GA_NEW;
$GA_NEW = 2;

/*
 * Flags: $flags in reserve_amount / activate_reservation / split
 */

//global $KRED_TEST_MODE; is defined above
//$KRED_TEST_MODE = 2;
global $KRED_SEND_BY_MAIL;
$KRED_SEND_BY_MAIL = 4;
global $KRED_SEND_BY_EMAIL;
$KRED_SEND_BY_EMAIL = 8;
global $KRED_DELAYED_PAY;
$KRED_DELAYED_PAY = 16;

// Billmate Mobile flags
    global $KRED_PRESERVE_RESERVATION;
	$KRED_PRESERVE_RESERVATION = 16;
    global $KRED_PHONE_TRANSACTION;
	$KRED_PHONE_TRANSACTION	= 512;
    global $KRED_SEND_PHONE_PIN;
	$KRED_SEND_PHONE_PIN = 1024;

/*
 * Pno_encodings
 */

global $KRED_SE_PNO;
$KRED_SE_PNO = 2;
global $KRED_NO_PNO;
$KRED_NO_PNO = 3;
global $KRED_FI_PNO;
$KRED_FI_PNO = 4;
global $KRED_DK_PNO;
$KRED_DK_PNO = 5;
global $KRED_DE_PNO;
$KRED_DE_PNO = 6;
global $KRED_NL_PNO;
$KRED_NL_PNO = 7;
global $KRED_CNO_PNO;
$KRED_CNO_PNO = 1000;

global $PNO_SE;
$PNO_SE   = 2; // Swedish
global $PNO_NO;
$PNO_NO   = 3; // Norwegian
global $PNO_FI;
$PNO_FI   = 4; // Finnish
global $PNO_DK;
$PNO_DK   = 5; // Danish
global $PNO_DE;
$PNO_DE = 6; // German
global $PNO_NL;
$PNO_NL = 7; // Dutch

/*
 * Mode: $type in update_charge_amount (Same as ?KD_GT_* in kdb.hrl)
 */

global $KRED_SHIPMENT;
$KRED_SHIPMENT = 1;
global $KRED_HANDLING;
$KRED_HANDLING = 2;



/*
 * Mode: $currency
 */

global $KRED_SEK; // Swedish krona
$KRED_SEK = 0;
global $KRED_NOK; // Norwegian krona
$KRED_NOK = 1;
global $KRED_EUR; // Euro
$KRED_EUR = 2;
global $KRED_DKK; // Danska kronor
$KRED_DKK = 3;


/*
 * Mode: $country
 */

global $KRED_ISO3166_AF;
$KRED_ISO3166_AF = 1;   // AFGHANISTAN
global $KRED_ISO3166_AX;
$KRED_ISO3166_AX = 2;   // ÅLAND ISLANDS
global $KRED_ISO3166_AL;
$KRED_ISO3166_AL = 3;   // ALBANIA
global $KRED_ISO3166_DZ;
$KRED_ISO3166_DZ = 4;   // ALGERIA
global $KRED_ISO3166_AS;
$KRED_ISO3166_AS = 5;   // AMERICAN SAMOA
global $KRED_ISO3166_AD;
$KRED_ISO3166_AD = 6;   // ANDORRA
global $KRED_ISO3166_AO;
$KRED_ISO3166_AO = 7;   // ANGOLA
global $KRED_ISO3166_AI;
$KRED_ISO3166_AI = 8;   // ANGUILLA
global $KRED_ISO3166_AQ;
$KRED_ISO3166_AQ = 9;   // ANTARCTICA
global $KRED_ISO3166_AG;
$KRED_ISO3166_AG = 10;  // ANTIGUA AND BARBUDA
global $KRED_ISO3166_AR;
$KRED_ISO3166_AR = 11;  // ARGENTINA
global $KRED_ISO3166_AM;
$KRED_ISO3166_AM = 12;  // ARMENIA
global $KRED_ISO3166_AW;
$KRED_ISO3166_AW = 13;  // ARUBA
global $KRED_ISO3166_AU;
$KRED_ISO3166_AU = 14;  // AUSTRALIA
global $KRED_ISO3166_AT;
$KRED_ISO3166_AT = 15;  // AUSTRIA
global $KRED_ISO3166_AZ;
$KRED_ISO3166_AZ = 16;  // AZERBAIJAN
global $KRED_ISO3166_BS;
$KRED_ISO3166_BS = 17;  // BAHAMAS
global $KRED_ISO3166_BH;
$KRED_ISO3166_BH = 18;  // BAHRAIN
global $KRED_ISO3166_BD;
$KRED_ISO3166_BD = 19;  // BANGLADESH
global $KRED_ISO3166_BB;
$KRED_ISO3166_BB = 20;  // BARBADOS
global $KRED_ISO3166_BY;
$KRED_ISO3166_BY = 21;  // BELARUS
global $KRED_ISO3166_BE;
$KRED_ISO3166_BE = 22;  // BELGIUM
global $KRED_ISO3166_BZ;
$KRED_ISO3166_BZ = 23;  // BELIZE
global $KRED_ISO3166_BJ;
$KRED_ISO3166_BJ = 24;  // BENIN
global $KRED_ISO3166_BM;
$KRED_ISO3166_BM = 25;  // BERMUDA
global $KRED_ISO3166_BT;
$KRED_ISO3166_BT = 26;  // BHUTAN
global $KRED_ISO3166_BO;
$KRED_ISO3166_BO = 27;  // BOLIVIA
global $KRED_ISO3166_BA;
$KRED_ISO3166_BA = 28;  // BOSNIA AND HERZEGOVINA
global $KRED_ISO3166_BW;
$KRED_ISO3166_BW = 29;  // BOTSWANA
global $KRED_ISO3166_BV;
$KRED_ISO3166_BV = 30;  // BOUVET ISLAND
global $KRED_ISO3166_BR;
$KRED_ISO3166_BR = 31;  // BRAZIL
global $KRED_ISO3166_IO;
$KRED_ISO3166_IO = 32;  // BRITISH INDIAN OCEAN TERRITORY
global $KRED_ISO3166_BN;
$KRED_ISO3166_BN = 33;  // BRUNEI DARUSSALAM
global $KRED_ISO3166_BG;
$KRED_ISO3166_BG = 34;  // BULGARIA
global $KRED_ISO3166_BF;
$KRED_ISO3166_BF = 35;  // BURKINA FASO
global $KRED_ISO3166_BI;
$KRED_ISO3166_BI = 36;  // BURUNDI
global $KRED_ISO3166_KH;
$KRED_ISO3166_KH = 37;  // CAMBODIA
global $KRED_ISO3166_CM;
$KRED_ISO3166_CM = 38;  // CAMEROON
global $KRED_ISO3166_CA;
$KRED_ISO3166_CA = 39;  // CANADA
global $KRED_ISO3166_CV;
$KRED_ISO3166_CV = 40;  // CAPE VERDE
global $KRED_ISO3166_KY;
$KRED_ISO3166_KY = 41;  // CAYMAN ISLANDS
global $KRED_ISO3166_CF;
$KRED_ISO3166_CF = 42;  // CENTRAL AFRICAN REPUBLIC
global $KRED_ISO3166_TD;
$KRED_ISO3166_TD = 43;  // CHAD
global $KRED_ISO3166_CL;
$KRED_ISO3166_CL = 44;  // CHILE
global $KRED_ISO3166_CN;
$KRED_ISO3166_CN = 45;  // CHINA
global $KRED_ISO3166_CX;
$KRED_ISO3166_CX = 46;  // CHRISTMAS ISLAND
global $KRED_ISO3166_CC;
$KRED_ISO3166_CC = 47;  // COCOS (KEELING) ISLANDS
global $KRED_ISO3166_CO;
$KRED_ISO3166_CO = 48;  // COLOMBIA
global $KRED_ISO3166_KM;
$KRED_ISO3166_KM = 49;  // COMOROS
global $KRED_ISO3166_CG;
$KRED_ISO3166_CG = 50;  // CONGO
global $KRED_ISO3166_CD;
$KRED_ISO3166_CD = 51;  // CONGO, THE DEMOCRATIC REPUBLIC OF THE
global $KRED_ISO3166_CK;
$KRED_ISO3166_CK = 52;  // COOK ISLANDS
global $KRED_ISO3166_CT;
$KRED_ISO3166_CR = 53;  //	 COSTA RICA
global $KRED_ISO3166_CI;
$KRED_ISO3166_CI = 54;  //	 COTE D'IVOIRE
global $KRED_ISO3166_HR;
$KRED_ISO3166_HR = 55;  //	 CROATIA
global $KRED_ISO3166_CU;
$KRED_ISO3166_CU = 56;  //	 CUBA
global $KRED_ISO3166_CY;
$KRED_ISO3166_CY = 57;  //	 CYPRUS
global $KRED_ISO3166_CZ;
$KRED_ISO3166_CZ = 58;  //	 CZECH REPUBLIC
global $KRED_ISO3166_DK;
$KRED_ISO3166_DK = 59;  //	 DENMARK
global $KRED_ISO3166_DJ;
$KRED_ISO3166_DJ = 60;  //	 DJIBOUTI
global $KRED_ISO3166_DM;
$KRED_ISO3166_DM = 61;  //	 DOMINICA
global $KRED_ISO3166_DO;
$KRED_ISO3166_DO = 62;  //	 DOMINICAN REPUBLIC
global $KRED_ISO3166_EC;
$KRED_ISO3166_EC = 63;  //	 ECUADOR					
global $KRED_ISO3166_EG;
$KRED_ISO3166_EG = 64;  //	 EGYPT
global $KRED_ISO3166_SV;
$KRED_ISO3166_SV = 65;  //	 EL SALVADOR
global $KRED_ISO3166_GQ;
$KRED_ISO3166_GQ = 66;  //	 EQUATORIAL GUINEA
global $KRED_ISO3166_ER;
$KRED_ISO3166_ER = 67;  //	 ERITREA
global $KRED_ISO3166_EE;
$KRED_ISO3166_EE = 68;  //	 ESTONIA
global $KRED_ISO3166_ET;
$KRED_ISO3166_ET = 69;  //	 ETHIOPIA
global $KRED_ISO3166_FK;
$KRED_ISO3166_FK = 70;  //	 FALKLAND ISLANDS (MALVINAS)
global $KRED_ISO3166_FO;
$KRED_ISO3166_FO = 71;  //	 FAROE ISLANDS
global $KRED_ISO3166_FJ;
$KRED_ISO3166_FJ = 72;  //	 FIJI
global $KRED_ISO3166_FI;
$KRED_ISO3166_FI = 73;  //	 FINLAND
global $KRED_ISO3166_FR;
$KRED_ISO3166_FR = 74;  //	 FRANCE
global $KRED_ISO3166_GF;
$KRED_ISO3166_GF = 75;  //	 FRENCH GUIANA
global $KRED_ISO3166_PF;
$KRED_ISO3166_PF = 76;  //	 FRENCH POLYNESIA
global $KRED_ISO3166_TF;
$KRED_ISO3166_TF = 77;  //	 FRENCH SOUTHERN TERRITORIES
global $KRED_ISO3166_GA;
$KRED_ISO3166_GA = 78;  //	 GABON
global $KRED_ISO3166_GA;
$KRED_ISO3166_GM = 79;  //	 GAMBIA
global $KRED_ISO3166_GE;
$KRED_ISO3166_GE = 80;  //	 GEORGIA
global $KRED_ISO3166_DE;
$KRED_ISO3166_DE = 81;  //	 GERMANY
global $KRED_ISO3166_GH;
$KRED_ISO3166_GH = 82;  //	 GHANA
global $KRED_ISO3166_GI;
$KRED_ISO3166_GI = 83;  //	 GIBRALTAR
global $KRED_ISO3166_GR;
$KRED_ISO3166_GR = 84;  //	 GREECE
global $KRED_ISO3166_GL;
$KRED_ISO3166_GL = 85;  //	 GREENLAND
global $KRED_ISO3166_GD;
$KRED_ISO3166_GD = 86;  //	 GRENADA
global $KRED_ISO3166_GP;
$KRED_ISO3166_GP = 87;  //	 GUADELOUPE
global $KRED_ISO3166_GU;
$KRED_ISO3166_GU = 88;  //	 GUAM
global $KRED_ISO3166_GT;
$KRED_ISO3166_GT = 89;  //	 GUATEMALA
global $KRED_ISO3166_GG;
$KRED_ISO3166_GG = 90;  //	 GUERNSEY
global $KRED_ISO3166_GN;
$KRED_ISO3166_GN = 91;  //	 GUINEA
global $KRED_ISO3166_GW;
$KRED_ISO3166_GW = 92;  //	 GUINEA-BISSAU
global $KRED_ISO3166_GY;
$KRED_ISO3166_GY = 93;  //	 GUYANA
global $KRED_ISO3166_HT;
$KRED_ISO3166_HT = 94;  //	 HAITI
global $KRED_ISO3166_HM;
$KRED_ISO3166_HM = 95;  //	 HEARD ISLAND AND MCDONALD ISLANDS
global $KRED_ISO3166_VA;
$KRED_ISO3166_VA = 96;  //	 HOLY SEE (VATICAN CITY STATE)
global $KRED_ISO3166_HN;
$KRED_ISO3166_HN = 97;  //	 HONDURAS
global $KRED_ISO3166_HK;
$KRED_ISO3166_HK = 98;  //	 HONG KONG
global $KRED_ISO3166_HU;
$KRED_ISO3166_HU = 99;  //	 HUNGARY
global $KRED_ISO3166_IS;
$KRED_ISO3166_IS = 100; //	 ICELAND
global $KRED_ISO3166_IN;
$KRED_ISO3166_IN = 101; //	 INDIA
global $KRED_ISO3166_ID;
$KRED_ISO3166_ID = 102; //	 INDONESIA
global $KRED_ISO3166_IR;
$KRED_ISO3166_IR = 103; //	 IRAN, ISLAMIC REPUBLIC OF
global $KRED_ISO3166_IQ;
$KRED_ISO3166_IQ = 104; //	 IRAQ
global $KRED_ISO3166_IE;
$KRED_ISO3166_IE = 105; //	 IRELAND
global $KRED_ISO3166_IM;
$KRED_ISO3166_IM = 106; //	 ISLE OF MAN
global $KRED_ISO3166_IL;
$KRED_ISO3166_IL = 107; //	 ISRAEL
global $KRED_ISO3166_IT;
$KRED_ISO3166_IT = 108; //	 ITALY
global $KRED_ISO3166_JM;
$KRED_ISO3166_JM = 109; //	 JAMAICA
global $KRED_ISO3166_JP;
$KRED_ISO3166_JP = 110; //	 JAPAN
global $KRED_ISO3166_JE;
$KRED_ISO3166_JE = 111; //	 JERSEY
global $KRED_ISO3166_JO;
$KRED_ISO3166_JO = 112; //	 JORDAN
global $KRED_ISO3166_KZ;
$KRED_ISO3166_KZ = 113; //	 KAZAKHSTAN
global $KRED_ISO3166_KE;
$KRED_ISO3166_KE =114; //	 KENYA
global $KRED_ISO3166_KI;
$KRED_ISO3166_KI =115; //	 KIRIBATI
global $KRED_ISO3166_KP;
$KRED_ISO3166_KP =116; //	 KOREA, DEMOCRATIC PEOPLE'S REPUBLIC OF
global $KRED_ISO3166_KR;
$KRED_ISO3166_KR =117; //	 KOREA, REPUBLIC OF
global $KRED_ISO3166_KW;
$KRED_ISO3166_KW = 118; //	 KUWAIT
global $KRED_ISO3166_KG;
$KRED_ISO3166_KG = 119; //	 KYRGYZSTAN
global $KRED_ISO3166_LA;
$KRED_ISO3166_LA = 120; //	 LAO PEOPLE'S DEMOCRATIC REPUBLIC
global $KRED_ISO3166_LV;
$KRED_ISO3166_LV = 121; //	 LATVIA
global $KRED_ISO3166_LB;
$KRED_ISO3166_LB = 122; //	 LEBANON
global $KRED_ISO3166_LS;
$KRED_ISO3166_LS = 123; //	 LESOTHO
global $KRED_ISO3166_LR;
$KRED_ISO3166_LR = 124; //	 LIBERIA
global $KRED_ISO3166_LY;
$KRED_ISO3166_LY = 125; //	 LIBYAN ARAB JAMAHIRIYA
global $KRED_ISO3166_LI;
$KRED_ISO3166_LI = 126; //	 LIECHTENSTEIN
global $KRED_ISO3166_LT;
$KRED_ISO3166_LT = 127; //	 LITHUANIA
global $KRED_ISO3166_LU;
$KRED_ISO3166_LU = 128; //	 LUXEMBOURG
global $KRED_ISO3166_MO;
$KRED_ISO3166_MO = 129; //	 MACAO
global $KRED_ISO3166_MK;
$KRED_ISO3166_MK = 130; //	 MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF
global $KRED_ISO3166_MG;
$KRED_ISO3166_MG = 131; //	 MADAGASCAR
global $KRED_ISO3166_MW;
$KRED_ISO3166_MW = 132; //	 MALAWI
global $KRED_ISO3166_MY;
$KRED_ISO3166_MY = 133; //	 MALAYSIA
global $KRED_ISO3166_MV;
$KRED_ISO3166_MV = 134; //	 MALDIVES
global $KRED_ISO3166_ML;
$KRED_ISO3166_ML = 135; //	 MALI
global $KRED_ISO3166_MT;
$KRED_ISO3166_MT = 136; //	 MALTA
global $KRED_ISO3166_MH;
$KRED_ISO3166_MH = 137; //	 MARSHALL ISLANDS
global $KRED_ISO3166_MQ;
$KRED_ISO3166_MQ = 138; //	 MARTINIQUE
global $KRED_ISO3166_MR;
$KRED_ISO3166_MR = 139; //	 MAURITANIA
global $KRED_ISO3166_MU;
$KRED_ISO3166_MU = 140; //	 MAURITIUS
global $KRED_ISO3166_YT;
$KRED_ISO3166_YT = 141; //	 MAYOTTE
global $KRED_ISO3166_MX;
$KRED_ISO3166_MX = 142; //	 MEXICO
global $KRED_ISO3166_FM;
$KRED_ISO3166_FM = 143; //	 MICRONESIA	FEDERATED STATES OF
global $KRED_ISO3166_MD;
$KRED_ISO3166_MD = 144; //	 MOLDOVA, REPUBLIC OF
global $KRED_ISO3166_MC;
$KRED_ISO3166_MC = 145; //	 MONACO
global $KRED_ISO3166_MN;
$KRED_ISO3166_MN = 146; //	 MONGOLIA
global $KRED_ISO3166_MS;
$KRED_ISO3166_MS = 147; //	 MONTSERRAT
global $KRED_ISO3166_MA;
$KRED_ISO3166_MA = 148; //	 MOROCCO
global $KRED_ISO3166_MZ;
$KRED_ISO3166_MZ = 149; //	 MOZAMBIQUE
global $KRED_ISO3166_MM;
$KRED_ISO3166_MM = 150; //	 MYANMAR
global $KRED_ISO3166_NA;
$KRED_ISO3166_NA = 151; //	 NAMIBIA
global $KRED_ISO3166_NR;
$KRED_ISO3166_NR = 152; //	 NAURU
global $KRED_ISO3166_NP;
$KRED_ISO3166_NP = 153; //	 NEPAL
global $KRED_ISO3166_NL;
$KRED_ISO3166_NL = 154; //	 NETHERLANDS
global $KRED_ISO3166_AN;
$KRED_ISO3166_AN = 155; //	 NETHERLANDS ANTILLES
global $KRED_ISO3166_NC;
$KRED_ISO3166_NC = 156; //	 NEW CALEDONIA
global $KRED_ISO3166_NZ;
$KRED_ISO3166_NZ = 157; //	 NEW ZEALAND
global $KRED_ISO3166_NI;
$KRED_ISO3166_NI = 158; //	 NICARAGUA
global $KRED_ISO3166_NE;
$KRED_ISO3166_NE = 159; //	 NIGER
global $KRED_ISO3166_NG;
$KRED_ISO3166_NG = 160; //	 NIGERIA
global $KRED_ISO3166_NU;
$KRED_ISO3166_NU = 161; //	 NIUE
global $KRED_ISO3166_NF;
$KRED_ISO3166_NF = 162; //	 NORFOLK ISLAND
global $KRED_ISO3166_MP;
$KRED_ISO3166_MP = 163; //	 NORTHERN MARIANA ISLANDS
global $KRED_ISO3166_NO;
$KRED_ISO3166_NO = 164; //	 NORWAY
global $KRED_ISO3166_OM;
$KRED_ISO3166_OM = 165; //	 OMAN
global $KRED_ISO3166_PK;
$KRED_ISO3166_PK = 166; //	 PAKISTAN
global $KRED_ISO3166_PW;
$KRED_ISO3166_PW = 167; //	 PALAU
global $KRED_ISO3166_PS;
$KRED_ISO3166_PS = 168; //	 PALESTINIAN TERRITORY OCCUPIED
global $KRED_ISO3166_PA;
$KRED_ISO3166_PA = 169; //	 PANAMA
global $KRED_ISO3166_PG;
$KRED_ISO3166_PG = 170; //	 PAPUA NEW GUINEA
global $KRED_ISO3166_PY;
$KRED_ISO3166_PY = 171; //	 PARAGUAY
global $KRED_ISO3166_PE;
$KRED_ISO3166_PE = 172; //	 PERU
global $KRED_ISO3166_PH;
$KRED_ISO3166_PH = 173; //	 PHILIPPINES
global $KRED_ISO3166_PN;
$KRED_ISO3166_PN = 174; //	 PITCAIRN
global $KRED_ISO3166_PL;
$KRED_ISO3166_PL = 175; //	 POLAND
global $KRED_ISO3166_PT;
$KRED_ISO3166_PT = 176; //	 PORTUGAL
global $KRED_ISO3166_PR;
$KRED_ISO3166_PR = 177; //	 PUERTO RICO
global $KRED_ISO3166_QA;
$KRED_ISO3166_QA = 178; //	 QATAR
global $KRED_ISO3166_RE;
$KRED_ISO3166_RE = 179; //	 REUNION
global $KRED_ISO3166_RO;
$KRED_ISO3166_RO = 180; //	 ROMANIA
global $KRED_ISO3166_RU;
$KRED_ISO3166_RU = 181; //	 RUSSIAN FEDERATION
global $KRED_ISO3166_RW;
$KRED_ISO3166_RW = 182; //	 RWANDA
global $KRED_ISO3166_SH;
$KRED_ISO3166_SH = 183; //	 SAINT HELENA
global $KRED_ISO3166_KN;
$KRED_ISO3166_KN = 184; //	 SAINT KITTS AND NEVIS
global $KRED_ISO3166_LC;
$KRED_ISO3166_LC = 185; //	 SAINT LUCIA
global $KRED_ISO3166_PM;
$KRED_ISO3166_PM = 186; //	 SAINT PIERRE AND MIQUELON
global $KRED_ISO3166_VC;
$KRED_ISO3166_VC = 187; //	 SAINT VINCENT AND THE GRENADINES
global $KRED_ISO3166_WS;
$KRED_ISO3166_WS = 188; //	 SAMOA
global $KRED_ISO3166_SM;
$KRED_ISO3166_SM = 189; //	 SAN MARINO
global $KRED_ISO3166_ST;
$KRED_ISO3166_ST = 190; //	 SAO TOME AND PRINCIPE
global $KRED_ISO3166_SA;
$KRED_ISO3166_SA = 191; //	 SAUDI ARABIA
global $KRED_ISO3166_SN;
$KRED_ISO3166_SN = 192; //	 SENEGAL
global $KRED_ISO3166_CS;
$KRED_ISO3166_CS = 193; //	 SERBIA AND MONTENEGRO
global $KRED_ISO3166_SC;
$KRED_ISO3166_SC = 194; //	 SEYCHELLES
global $KRED_ISO3166_SL;
$KRED_ISO3166_SL = 195; //	 SIERRA LEONE
global $KRED_ISO3166_SG;
$KRED_ISO3166_SG = 196; //	 SINGAPORE
global $KRED_ISO3166_SK;
$KRED_ISO3166_SK = 197; //	 SLOVAKIA
global $KRED_ISO3166_SI;
$KRED_ISO3166_SI = 198; //	 SLOVENIA
global $KRED_ISO3166_SB;
$KRED_ISO3166_SB = 199; //	 SOLOMON ISLANDS
global $KRED_ISO3166_SO;
$KRED_ISO3166_SO = 200; //	 SOMALIA
global $KRED_ISO3166_ZA;
$KRED_ISO3166_ZA = 201; //	 SOUTH AFRICA
global $KRED_ISO3166_GS;
$KRED_ISO3166_GS = 202; //	 SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS
global $KRED_ISO3166_ES;
$KRED_ISO3166_ES = 203; //	 SPAIN
global $KRED_ISO3166_LK;
$KRED_ISO3166_LK = 204; //	 SRI LANKA
global $KRED_ISO3166_SD;
$KRED_ISO3166_SD = 205; //	 SUDAN
global $KRED_ISO3166_SR;
$KRED_ISO3166_SR = 206; //	 SURINAME
global $KRED_ISO3166_SJ;
$KRED_ISO3166_SJ = 207; //	 SVALBARD AND JAN MAYEN
global $KRED_ISO3166_SZ;
$KRED_ISO3166_SZ = 208; //	 SWAZILAND
global $KRED_ISO3166_SE;
$KRED_ISO3166_SE = 209; //	 SWEDEN
global $KRED_ISO3166_CH;
$KRED_ISO3166_CH = 210; //	 SWITZERLAND
global $KRED_ISO3166_SY;
$KRED_ISO3166_SY = 211; //	 SYRIAN ARAB REPUBLIC
global $KRED_ISO3166_TW;
$KRED_ISO3166_TW = 212; //	 TAIWAN PROVINCE OF CHINA
global $KRED_ISO3166_TJ;
$KRED_ISO3166_TJ = 213; //	 TAJIKISTAN
global $KRED_ISO3166_TZ;
$KRED_ISO3166_TZ = 214; //	 TANZANIA, UNITED REPUBLIC OF
global $KRED_ISO3166_TH;
$KRED_ISO3166_TH = 215; //	 THAILAND
global $KRED_ISO3166_TL;
$KRED_ISO3166_TL = 216; //	 TIMOR-LESTE
global $KRED_ISO3166_TC;
$KRED_ISO3166_TG = 217; //	 TOGO
global $KRED_ISO3166_TK;
$KRED_ISO3166_TK = 218; //	 TOKELAU
global $KRED_ISO3166_TO;
$KRED_ISO3166_TO = 219; //	 TONGA
global $KRED_ISO3166_TT;
$KRED_ISO3166_TT = 220; //	 TRINIDAD AND TOBAGO
global $KRED_ISO3166_TN;
$KRED_ISO3166_TN = 221; //	 TUNISIA
global $KRED_ISO3166_TR;
$KRED_ISO3166_TR = 222; //	 TURKEY
global $KRED_ISO3166_TM;
$KRED_ISO3166_TM = 223; //	 TURKMENISTAN
global $KRED_ISO3166_TC;
$KRED_ISO3166_TC = 224; //	 TURKS AND CAICOS ISLANDS
global $KRED_ISO3166_TV;
$KRED_ISO3166_TV = 225; //	 TUVALU
global $KRED_ISO3166_UG;
$KRED_ISO3166_UG = 226; //	 UGANDA
global $KRED_ISO3166_UA;
$KRED_ISO3166_UA = 227; //	 UKRAINE
global $KRED_ISO3166_AE;
$KRED_ISO3166_AE = 228; //	 UNITED ARAB EMIRATES
global $KRED_ISO3166_GB;
$KRED_ISO3166_GB = 229; //	 UNITED KINGDOM
global $KRED_ISO3166_US;
$KRED_ISO3166_US = 230; //	 UNITED STATES
global $KRED_ISO3166_UM;
$KRED_ISO3166_UM = 231; //	 UNITED STATES MINOR OUTLYING ISLANDS
global $KRED_ISO3166_UY;
$KRED_ISO3166_UY = 232; //	 URUGUAY
global $KRED_ISO3166_UZ;
$KRED_ISO3166_UZ = 233; //	 UZBEKISTAN
global $KRED_ISO3166_VU;
$KRED_ISO3166_VU = 234; //	 VANUATU
global $KRED_ISO3166_VE;
$KRED_ISO3166_VE = 235; //	 VENEZUELA
global $KRED_ISO3166_VN;
$KRED_ISO3166_VN = 236; //	 VIET NAM
global $KRED_ISO3166_VG;
$KRED_ISO3166_VG = 237; //	 VIRGIN ISLANDS, BRITISH
global $KRED_ISO3166_VI;
$KRED_ISO3166_VI = 238; //	 VIRGIN ISLANDS, US
global $KRED_ISO3166_WF;
$KRED_ISO3166_WF = 239; //	 WALLIS AND FUTUNA
global $KRED_ISO3166_EH;
$KRED_ISO3166_EH = 240; //	 WESTERN SAHARA
global $KRED_ISO3166_YE;
$KRED_ISO3166_YE = 241; //	 YEMEN
global $KRED_ISO3166_ZM;
$KRED_ISO3166_ZM = 242; //	 ZAMBIA
global $KRED_ISO3166_ZW;
$KRED_ISO3166_ZW = 243; //	 ZIMBABWE



/*
 * Language code
 */

global $KRED_ISO639_AA;
$KRED_ISO639_AA = 1;    // Afar
global $KRED_ISO639_AB;
$KRED_ISO639_AB = 2;    // Abkhazian
global $KRED_ISO639_AE;
$KRED_ISO639_AE = 3;    // Avestan
global $KRED_ISO639_AF;
$KRED_ISO639_AF = 4;    // Afrikaans
global $KRED_ISO639_AM;
$KRED_ISO639_AM = 5;    // Amharic
global $KRED_ISO639_AR;
$KRED_ISO639_AR = 6;    // Arabic
global $KRED_ISO639_AS;
$KRED_ISO639_AS = 7;    // Assamese
global $KRED_ISO639_AY;
$KRED_ISO639_AY = 8;    // Aymara
global $KRED_ISO639_AZ;
$KRED_ISO639_AZ = 9;    // Azerbaijani
global $KRED_ISO639_BA;
$KRED_ISO639_BA = 10;    // Bashkir
global $KRED_ISO639_BE;
$KRED_ISO639_BE = 11;    // Byelorussian; Belarusian
global $KRED_ISO639_BG;
$KRED_ISO639_BG = 12;    // Bulgarian
global $KRED_ISO639_BH;
$KRED_ISO639_BH = 13;    // Bihari
global $KRED_ISO639_BI;
$KRED_ISO639_BI = 14;    // Bislama
global $KRED_ISO639_BN;
$KRED_ISO639_BN = 15;    // Bengali; Bangla
global $KRED_ISO639_BO;
$KRED_ISO639_BO = 16;    // Tibetan
global $KRED_ISO639_BR;
$KRED_ISO639_BR = 17;    // Breton
global $KRED_ISO639_BS;
$KRED_ISO639_BS = 18;    // Bosnian
global $KRED_ISO639_CA;
$KRED_ISO639_CA = 19;    // Catalan
global $KRED_ISO639_CE;
$KRED_ISO639_CE = 20;    // Chechen
global $KRED_ISO639_CH;
$KRED_ISO639_CH = 21;    // Chamorro
global $KRED_ISO639_CO;
$KRED_ISO639_CO = 22;    // Corsican
global $KRED_ISO639_CS;
$KRED_ISO639_CS = 23;    // Czech
global $KRED_ISO639_CU;
$KRED_ISO639_CU = 24;    // Church Slavic
global $KRED_ISO639_CV;
$KRED_ISO639_CV = 25;    // Chuvash
global $KRED_ISO639_CY;
$KRED_ISO639_CY = 26;    // Welsh
global $KRED_ISO639_DA;
$KRED_ISO639_DA = 27;    // Danish
global $KRED_ISO639_DE;
$KRED_ISO639_DE = 28;    // German
global $KRED_ISO639_DZ;
$KRED_ISO639_DZ = 29;    // Dzongkha; Bhutani
global $KRED_ISO639_EL;
$KRED_ISO639_EL = 30;    // Greek
global $KRED_ISO639_EN;
$KRED_ISO639_EN = 31;    // English
global $KRED_ISO639_EO;
$KRED_ISO639_EO = 32;    // Esperanto
global $KRED_ISO639_ES;
$KRED_ISO639_ES = 33;    // Spanish
global $KRED_ISO639_ET;
$KRED_ISO639_ET = 34;    // Estonian
global $KRED_ISO639_EU;
$KRED_ISO639_EU = 35;    // Basque
global $KRED_ISO639_FA;
$KRED_ISO639_FA = 36;    // Persian
global $KRED_ISO639_FI;
$KRED_ISO639_FI = 37;    // Finnish
global $KRED_ISO639_FJ;
$KRED_ISO639_FJ = 38;    // Fijian; Fiji
global $KRED_ISO639_FO;
$KRED_ISO639_FO = 39;    // Faroese
global $KRED_ISO639_FR;
$KRED_ISO639_FR = 40;    // French
global $KRED_ISO639_FY;
$KRED_ISO639_FY = 41;    // Frisian
global $KRED_ISO639_GA;
$KRED_ISO639_GA = 42;    // Irish
global $KRED_ISO639_GD;
$KRED_ISO639_GD = 43;    // Scots; Gaelic
global $KRED_ISO639_GL;
$KRED_ISO639_GL = 44;    // Gallegan; Galician
global $KRED_ISO639_GN;
$KRED_ISO639_GN = 45;    // Guarani
global $KRED_ISO639_GU;
$KRED_ISO639_GU = 46;    // Gujarati
global $KRED_ISO639_GV;
$KRED_ISO639_GV = 47;    // Manx
global $KRED_ISO639_HA;
$KRED_ISO639_HA = 48;    // Hausa (?)
global $KRED_ISO639_HE;
$KRED_ISO639_HE = 49;    // Hebrew (formerly iw)
global $KRED_ISO639_HI;
$KRED_ISO639_HI = 50;    // Hindi
global $KRED_ISO639_HO;
$KRED_ISO639_HO = 51;    // Hiri Motu
global $KRED_ISO639_HR;
$KRED_ISO639_HR = 52;    // Croatian
global $KRED_ISO639_HU;
$KRED_ISO639_HU = 53;    // Hungarian
global $KRED_ISO639_HY;
$KRED_ISO639_HY = 54;    // Armenian
global $KRED_ISO639_HZ;
$KRED_ISO639_HZ = 55;    // Herero
global $KRED_ISO639_IA;
$KRED_ISO639_IA = 56;    // Interlingua
global $KRED_ISO639_ID;
$KRED_ISO639_ID = 57;    // Indonesian (formerly in)
global $KRED_ISO639_IE;
$KRED_ISO639_IE = 58;    // Interlingue
global $KRED_ISO639_IK;
$KRED_ISO639_IK = 59;    // Inupiak
global $KRED_ISO639_IO;
$KRED_ISO639_IO = 60;    // Ido
global $KRED_ISO639_IS;
$KRED_ISO639_IS = 61;    // Icelandic
global $KRED_ISO639_IT;
$KRED_ISO639_IT = 62;    // Italian
global $KRED_ISO639_IU;
$KRED_ISO639_IU = 63;    // Inuktitut
global $KRED_ISO639_JA;
$KRED_ISO639_JA = 64;    // Japanese
global $KRED_ISO639_JV;
$KRED_ISO639_JV = 65;    // Javanese
global $KRED_ISO639_KA;
$KRED_ISO639_KA = 66;    // Georgian
global $KRED_ISO639_KI;
$KRED_ISO639_KI = 67;    // Kikuyu
global $KRED_ISO639_KJ;
$KRED_ISO639_KJ = 68;    // Kuanyama
global $KRED_ISO639_KK;
$KRED_ISO639_KK = 69;    // Kazakh
global $KRED_ISO639_KL;
$KRED_ISO639_KL = 70;    // Kalaallisut; Greenlandic
global $KRED_ISO639_KM;
$KRED_ISO639_KM = 71;    // Khmer; Cambodian
global $KRED_ISO639_KN;
$KRED_ISO639_KN = 72;    // Kannada
global $KRED_ISO639_KO;
$KRED_ISO639_KO = 73;    // Korean
global $KRED_ISO639_KS;
$KRED_ISO639_KS = 74;    // Kashmiri
global $KRED_ISO639_KU;
$KRED_ISO639_KU = 75;    // Kurdish
global $KRED_ISO639_KV;
$KRED_ISO639_KV = 76;    // Komi
global $KRED_ISO639_KW;
$KRED_ISO639_KW = 77;    // Cornish
global $KRED_ISO639_KY;
$KRED_ISO639_KY = 78;    // Kirghiz
global $KRED_ISO639_LA;
$KRED_ISO639_LA = 79;    // Latin
global $KRED_ISO639_LB;
$KRED_ISO639_LB = 80;    // Letzeburgesch
global $KRED_ISO639_LN;
$KRED_ISO639_LN = 81;    // Lingala
global $KRED_ISO639_LO;
$KRED_ISO639_LO = 82;    // Lao; Laotian
global $KRED_ISO639_LT;
$KRED_ISO639_LT = 83;    // Lithuanian
global $KRED_ISO639_LV;
$KRED_ISO639_LV = 84;    // Latvian; Lettish
global $KRED_ISO639_MG;
$KRED_ISO639_MG = 85;    // Malagasy
global $KRED_ISO639_MH;
$KRED_ISO639_MH = 86;    // Marshall
global $KRED_ISO639_MI;
$KRED_ISO639_MI = 87;    // Maori
global $KRED_ISO639_MK;
$KRED_ISO639_MK = 88;    // Macedonian
global $KRED_ISO639_ML;
$KRED_ISO639_ML = 89;    // Malayalam
global $KRED_ISO639_MN;
$KRED_ISO639_MN = 90;    // Mongolian
global $KRED_ISO639_MO;
$KRED_ISO639_MO = 91;    // Moldavian
global $KRED_ISO639_MR;
$KRED_ISO639_MR = 92;    // Marathi
global $KRED_ISO639_MS;
$KRED_ISO639_MS = 93;    // Malay
global $KRED_ISO639_MT;
$KRED_ISO639_MT = 94;    // Maltese
global $KRED_ISO639_MY;
$KRED_ISO639_MY = 95;    // Burmese
global $KRED_ISO639_NA;
$KRED_ISO639_NA = 96;    // Nauru
global $KRED_ISO639_NB;
$KRED_ISO639_NB = 97;    // Norwegian Bokmål
global $KRED_ISO639_ND;
$KRED_ISO639_ND = 98;    // Ndebele, North
global $KRED_ISO639_NE;
$KRED_ISO639_NE = 99;    // Nepali
global $KRED_ISO639_NG;
$KRED_ISO639_NG = 100;    // Ndonga
global $KRED_ISO639_NL;
$KRED_ISO639_NL = 101;    // Dutch
global $KRED_ISO639_NN;
$KRED_ISO639_NN = 102;    // Norwegian Nynorsk
global $KRED_ISO639_NO;
$KRED_ISO639_NO = 103;    // Norwegian
global $KRED_ISO639_NR;
$KRED_ISO639_NR = 104;    // Ndebele, South
global $KRED_ISO639_NV;
$KRED_ISO639_NV = 105;    // Navajo
global $KRED_ISO639_NY;
$KRED_ISO639_NY = 106;    // Chichewa; Nyanja
global $KRED_ISO639_OC;
$KRED_ISO639_OC = 107;    // Occitan; Provençal
global $KRED_ISO639_OM;
$KRED_ISO639_OM = 108;    // (Afan) Oromo
global $KRED_ISO639_OR;
$KRED_ISO639_OR = 109;    // Oriya
global $KRED_ISO639_OS;
$KRED_ISO639_OS = 110;    // Ossetian; Ossetic
global $KRED_ISO639_PA;
$KRED_ISO639_PA = 111;    // Panjabi; Punjabi
global $KRED_ISO639_PI;
$KRED_ISO639_PI = 112;    // Pali
global $KRED_ISO639_PL;
$KRED_ISO639_PL = 113;    // Polish
global $KRED_ISO639_PS;
$KRED_ISO639_PS = 114;    // Pashto, Pushto
global $KRED_ISO639_PT;
$KRED_ISO639_PT = 115;    // Portuguese
global $KRED_ISO639_QU;
$KRED_ISO639_QU = 116;    // Quechua
global $KRED_ISO639_RM;
$KRED_ISO639_RM = 117;    // Rhaeto-Romance
global $KRED_ISO639_RN;
$KRED_ISO639_RN = 118;    // Rundi; Kirundi
global $KRED_ISO639_RO;
$KRED_ISO639_RO = 119;    // Romanian
global $KRED_ISO639_RU;
$KRED_ISO639_RU = 120;    // Russian
global $KRED_ISO639_RW;
$KRED_ISO639_RW = 121;    // Kinyarwanda
global $KRED_ISO639_SA;
$KRED_ISO639_SA = 122;    // Sanskrit
global $KRED_ISO639_SC;
$KRED_ISO639_SC = 123;    // Sardinian
global $KRED_ISO639_SD;
$KRED_ISO639_SD = 124;    // Sindhi
global $KRED_ISO639_SE;
$KRED_ISO639_SE = 125;    // Northern Sami
global $KRED_ISO639_SG;
$KRED_ISO639_SG = 126;    // Sango; Sangro
global $KRED_ISO639_SI;
$KRED_ISO639_SI = 127;    // Sinhalese
global $KRED_ISO639_SK;
$KRED_ISO639_SK = 128;    // Slovak
global $KRED_ISO639_SL;
$KRED_ISO639_SL = 129;    // Slovenian
global $KRED_ISO639_SM;
$KRED_ISO639_SM = 130;    // Samoan
global $KRED_ISO639_SN;
$KRED_ISO639_SN = 131;    // Shona
global $KRED_ISO639_SO;
$KRED_ISO639_SO = 132;    // Somali
global $KRED_ISO639_SQ;
$KRED_ISO639_SQ = 133;    // Albanian
global $KRED_ISO639_SR;
$KRED_ISO639_SR = 134;    // Serbian
global $KRED_ISO639_SS;
$KRED_ISO639_SS = 135;    // Swati; Siswati
global $KRED_ISO639_ST;
$KRED_ISO639_ST = 136;    // Sesotho; Sotho, Southern
global $KRED_ISO639_SU;
$KRED_ISO639_SU = 137;    // Sundanese
global $KRED_ISO639_SV;
$KRED_ISO639_SV = 138;    // Swedish
global $KRED_ISO639_SW;
$KRED_ISO639_SW = 139;    // Swahili
global $KRED_ISO639_TA;
$KRED_ISO639_TA = 140;    // Tamil
global $KRED_ISO639_TE;
$KRED_ISO639_TE = 141;    // Telugu
global $KRED_ISO639_TG;
$KRED_ISO639_TG = 142;    // Tajik
global $KRED_ISO639_TH;
$KRED_ISO639_TH = 143;    // Thai
global $KRED_ISO639_TI;
$KRED_ISO639_TI = 144;    // Tigrinya
global $KRED_ISO639_TK;
$KRED_ISO639_TK = 145;    // Turkmen
global $KRED_ISO639_TL;
$KRED_ISO639_TL = 146;    // Tagalog
global $KRED_ISO639_TN;
$KRED_ISO639_TN = 147;    // Tswana; Setswana
global $KRED_ISO639_TO;
$KRED_ISO639_TO = 148;    // Tonga (?)
global $KRED_ISO639_TR;
$KRED_ISO639_TR = 149;    // Turkish
global $KRED_ISO639_TS;
$KRED_ISO639_TS = 150;    // Tsonga
global $KRED_ISO639_TT;
$KRED_ISO639_TT = 151;    // Tatar
global $KRED_ISO639_TW;
$KRED_ISO639_TW = 152;    // Twi
global $KRED_ISO639_TY;
$KRED_ISO639_TY = 153;    // Tahitian
global $KRED_ISO639_UG;
$KRED_ISO639_UG = 154;    // Uighur
global $KRED_ISO639_UK;
$KRED_ISO639_UK = 155;    // Ukrainian
global $KRED_ISO639_UR;
$KRED_ISO639_UR = 156;    // Urdu
global $KRED_ISO639_UZ;
$KRED_ISO639_UZ = 157;    // Uzbek
global $KRED_ISO639_VI;
$KRED_ISO639_VI = 158;    // Vietnamese
global $KRED_ISO639_VO;
$KRED_ISO639_VO = 159;    // Volapuk
global $KRED_ISO639_WA;
$KRED_ISO639_WA = 160;    // Walloon
global $KRED_ISO639_WO;
$KRED_ISO639_WO = 161;    // Wolof
global $KRED_ISO639_XH;
$KRED_ISO639_XH = 162;    // Xhosa
global $KRED_ISO639_YI;
$KRED_ISO639_YI = 163;    // Yiddish (formerly ji)
global $KRED_ISO639_YO;
$KRED_ISO639_YO = 164;    // Yoruba
global $KRED_ISO639_ZA;
$KRED_ISO639_ZA = 165;    // Zhuang
global $KRED_ISO639_ZH;
$KRED_ISO639_ZH = 166;    // Chinese
global $KRED_ISO639_ZU;
$KRED_ISO639_ZU = 167;    // Zulu

global $KRED_ANNUITY_PCLASS;
$KRED_ANNUITY_PCLASS  = 0;
global $KRED_DIVISOR_PCLASS;
$KRED_DIVISOR_PCLASS = 1;
   
/*
 * Select available kreditor host
 */
/*
 * Do an xmlrpc call to Kreditor.
 */

function kred_call($function, $paramList, &$result) {
	global $PROTO_VSN;
	global $CLIENT_VSN;
	global $XMLRPC_LIB;
	global $KREDITOR_PORT;
	global $KREDITOR_HOST;
  	$params = array($PROTO_VSN, $CLIENT_VSN);


	while(list(,$v)=each($paramList)) {
    	$params[] = $v;
    }

	switch ($XMLRPC_LIB) {
	case "BPEAR":

		$parameterArray = array(XML_RPC_encode($params));
		$message = new XML_RPC_Message($function, $parameterArray);
		$message->setSendEncoding("ISO-8859-1");
		$client = new XML_RPC_Client("/", $KREDITOR_HOST, $KREDITOR_PORT);
		//$client->setDebug(1);
		$response = $client->send($message);
		
		billmate_log_data(array($params), $paramList[0], $function);

		if (is_int($response) && ($response == 0)) {
			if ($client->errstring != "") {
				$result = $client->errstring;
			} else {
				$result = $client->errstr;
			}
			return -99;
		} else
			$faultCode = $response->faultCode();
		
		if ($faultCode != 0) {
			$result = utf8_encode( strip_tags(html_entity_decode( $response->faultString())));
			billmate_log_data(array($result), $paramList[0], $function);
			return $faultCode;
		} else {
			$result = XML_RPC_decode($response->value());
			return 0;
		}
		break;
	default:
		$result = "Unknown XMLRPC library: " . $XMLRPC_LIB;
		return -99;
	}  
}

/*
 * API: activate_invoice
 */

function activate_invoice($eid, $invno, $secret, &$result) {
	our_settype_integer($eid);
	our_settype_string($invno);
	our_settype_string($secret);
	$digestSecret = invoice_digest($eid, $invno, $secret);
	$paramList = array($eid, $invno, $digestSecret);
    return kred_call("activate_invoice", $paramList, $result);	
}

/*
 * API: activate_part
 */
function mk_artno($qty, $artno) {
	our_settype_integer($qty);
	our_settype_string($artno);
	return array("artno" => $artno, "qty" => $qty);
}

function activate_part($eid, $invno, $artnos, $secret, &$result) {
	our_settype_integer($eid);
	our_settype_string($invno);
	our_settype_string($secret);
	$digestSecret = activate_part_digest($eid, $invno, $artnos, $secret);
	$paramList = array($eid, $invno, $artnos, $digestSecret);
    return kred_call("activate_part", $paramList, $result);	
}

/*
 * API: invoice_part_amount
 */
function invoice_part_amount($eid, $invno, $artnos, $secret, &$result) {
	our_settype_integer($eid);
	our_settype_string($invno);
	our_settype_string($secret);
	our_settype_integer($amount);
	$digestSecret = activate_part_digest($eid, $invno, $artnos, $secret);
	$paramList = array($eid, $invno, $artnos, $digestSecret);
    return kred_call("invoice_part_amount", $paramList, $result);	
}

/*
 * API: send_invoice
 */

function send_invoice($eid, $invno, $secret, &$result) {
	our_settype_integer($eid);
	our_settype_string($invno);
	our_settype_string($secret);
	$digestSecret = invoice_digest($eid, $invno, $secret);
	$paramList = array($eid, $invno, $digestSecret);
    return kred_call("send_invoice", $paramList, $result);	
}

/*
 * API: email_invoice
 */

function email_invoice($eid, $invno, $secret, &$result) {
	our_settype_integer($eid);
	our_settype_string($invno);
	our_settype_string($secret);
	$digestSecret = invoice_digest($eid, $invno, $secret);
	$paramList = array($eid, $invno, $digestSecret);
    return kred_call("email_invoice", $paramList, $result);	
}

	// has account
      function has_account($eid, $pno, $secret, $pno_encoding, &$result)
    {
		our_settype_integer($eid);
		our_strip_string($pno);
		our_settype_string($secret);
		our_settype_integer($pno_encoding);
		
        $digest = invoice_digest($eid, $pno, $secret);
		
		return kred_call("has_account", array($eid, $pno, $digest, $pno_encoding), $result);	
    }
	
	// CHECK_RESERVATION
    function check_reservation($eid, $rno, $amount, $address, $secret, $pno, &$result) {
		
		our_settype_integer($eid);
		our_settype_string($rno);
		our_settype_integer($amount);
		our_settype_string($secret);
		our_settype_integer($pno_encoding);
		$digest = periodic_cost_digest($eid,$rno,$amount,$secret);
		
		return kred_call("check_reservation", array($eid, $rno, $amount, $address, $digest, $pno), $result);
		
		}

/*
 * API: mk_goods
 */

function mk_goods($qty, $artno, $title, $price, $vat, $discount) {
	our_settype_integer($qty);
	our_settype_string($artno);
	our_settype_string($title);
	our_settype_integer($price);
	settype($vat, "double");
	settype($discount, "double");
	return array("goods" => array("artno" => $artno,
								  "title" => $title,
								  "price" => $price,
								  "vat" => $vat,
								  "discount" => $discount),
				 "qty" => $qty);
}

function mk_goods_flags($qty, $artno, $title, $price, $vat, $discount, $flags){
	our_settype_integer($qty);
	our_settype_integer($flags);
	our_settype_string($artno);
	our_settype_string($title);
	our_settype_integer($price);
	settype($vat, "double");
	settype($discount, "double");
	return array("goods" => array("artno" => $artno,
								  "title" => $title,
								  "price" => $price,
								  "vat" => $vat,
								  "discount" => $discount,
								  "flags" => $flags),
				 "qty" => $qty);
}

/*
 * API: mk_address(for reserve_amount)
 */

function mk_address_se($fname, $lname, $street, $postno, $city) {
	return mk_address($fname, $lname, $street, $postno, $city, "se", "", "");
}

function mk_address_no($fname, $lname, $street, $postno, $city) {
	return mk_address($fname, $lname, $street, $postno, $city, "no", "", "");
}

function mk_address_dk($fname, $lname, $street, $postno, $city) {
	return mk_address($fname, $lname, $street, $postno, $city, "dk", "", "");
}

function mk_address_fi($fname, $lname, $street, $postno, $city) {
	return mk_address($fname, $lname, $street, $postno, $city, "fi", "", "");
}

function mk_address_de($fname, $lname, $street, $postno, $city, $housenumber) {
	return mk_address($fname, $lname, $street, $postno, $city, "de", $housenumber, "");
}

function mk_address_nl($fname, $lname, $street, $postno, $city, $housenumber, $houseextension) {
	return mk_address($fname, $lname, $street, $postno, $city, "nl", $housenumber, $houseextension);
}

function mk_address($fname, $lname, $street, $postno, $city, $country, $housenumber = "", $houseextension = "") {
	our_settype_string($fname);
	our_settype_string($lname);
	our_settype_string($street);
	our_settype_string($postno);
	our_settype_string($city);
	our_settype_string($country);
	our_settype_string($housenumber);
	our_settype_string($houseextension);
	return array("fname" => $fname,
				 "lname" => $lname,	
				 "street" => $street,
				 "zip" => $postno,
				 "city" => $city,
				 "country" => $country,
				 "house_number" => $housenumber,
				 "house_extension" => $houseextension);
				 
}

/*
 * API: mk_addr(for add_transaction)
 */
 
 function mk_addr_se($careof, $street, $postno, $city, $telno, $cellno, $email){
	global $KRED_ISO3166_SE;
	return mk_addr($careof, $street, $postno, $city, $KRED_ISO3166_SE, $telno,
				   $cellno, $email, "", "");
}

function mk_addr_no($careof, $street, $postno, $city, $telno, $cellno, $email){
	global $KRED_ISO3166_NO;
	return mk_addr($careof, $street, $postno, $city, $KRED_ISO3166_NO, $telno,
				   $cellno, $email, "", "");
}


function mk_addr_dk($careof, $street, $postno, $city, $telno, $cellno, $email){
	global $KRED_ISO3166_DK;
	return mk_addr($careof, $street, $postno, $city, $KRED_ISO3166_DK, $telno,
				   $cellno, $email, "", "");
}

function mk_addr_fi($careof, $street, $postno, $city, $telno, $cellno, $email){
	global $KRED_ISO3166_FI;
	return mk_addr($careof, $street, $postno, $city, $KRED_ISO3166_FI, $telno,
				   $cellno, $email, "", "");
}

function mk_addr_de($careof, $street, $postno, $city, $telno, $cellno, $email, $housenumber){
	global $KRED_ISO3166_DE;
	return mk_addr($careof, $street, $postno, $city, $KRED_ISO3166_DE, $telno,
				   $cellno, $email, $housenumber, "");
}

function mk_addr_nl($careof, $street, $postno, $city, $telno, $cellno, $email, $housenumber, $houseextension){
	global $KRED_ISO3166_NL;
	return mk_addr($careof, $street, $postno, $city, $KRED_ISO3166_NL, $telno,
				   $cellno, $email, $housenumber, $houseextension);
}

function mk_addr($careof, $street, $postno, $city, $country, $telno, $cellno,
				 $email, $housenumber = "", $houseextension = "") {
	our_settype_string($careof);
	our_settype_string($street);
	 
	if($country == 154)
		our_settype_string($postno);
	else
		our_settype_integer($postno);
		
	our_settype_string($city);
	our_settype_integer($country);
	our_settype_string($telno);
	our_settype_string($cellno);
	our_settype_string($email);
	our_settype_string($housenumber);
	our_settype_string($houseextension);
	return array("careof" => $careof,
				 "street" => $street,
				 "postno" => $postno,
				 "city" => $city,
				 "country" => $country,
				 "telno" => $telno,
				 "cellno" => $cellno,
				 "email" => $email,
				 "house_number" => $housenumber,
				 "house_extension" => $houseextension);
}
				 
function apr_annuity($sum, $months, $monthfee, $rate, $startfee, $curr, &$result)
{
$r_min; $r_max; $r_guess; $apr_sum; $precision; $period_cost; $resp;
$precision = 0.001;

		$r_guess = 50.00; // we guess rate is somewhere (below) this value (for faster iteration)
        $r_min = 0.0; //lower limit is 0%
        $r_max = 10000.0; // upper limit. The value we are looking for should be way lower
		
		$period_cost = total_monthly_cost($sum, $months, $monthfee, $rate, $startfee, $curr);
		
		$apr_sum = 0;
		
          for($i = 0; $i < 1000; $i++)
		  {
			$apr_sum = 0;
              
			  for($k = 1; $k < $months + 1; $k++)
				$apr_sum = $apr_sum + $period_cost / pow((1 + $r_guess / 100),((double)($k / 12)));
                            
			  if ($apr_sum + $precision > $sum && $apr_sum - $precision < $sum)			
			{
				$result = $r_guess * 100;					
			}
              elseif ($apr_sum > $sum)
                  $r_min = $r_guess;
              else
                  $r_max = $r_guess;
			  
              $r_guess = ($r_min + $r_max) / 2;
          }		  
}

// new function
function total_credit_purchase_cost($sum, $pc, $cur, &$result)
{
global $KRED_SEK, $KRED_NOK, $KRED_EUR, $KRED_DKK,$KRED_DIVISOR_PCLASS, $KRED_ANNUITY_PCLASS;
if (($cur == $KRED_NOK) && (get_type($pc) == $KRED_DIVISOR_PCLASS))
	$months = 12;
else
	$months = get_months($pc);


$monthfee = get_month_fee($pc);
$startfee = get_start_fee($pc);
$rate = get_rate($pc);

$result = $months * total_monthly_cost($sum, $months, $monthfee, $rate, $startfee, $cur);
}

/* Calculates the total monthly cost (annuity payments) i.e the cost per 
 ' month including all fees, the purchase sum and interest, taking account:
 '- that the startfee is paid at the first payment (with no interest) and 
 ' - the monthly fee is paid with every payment before any amortizing is done.
 ' This cost should be as close as possible as the real cost will be.*/
 function total_monthly_cost($sum, $months, $monthfee, $rate, $startfee, $cur)
 {
	$sum_j_to_N = 0;
		// reference: Avbetalningsplaner.pdf
		$K0 = $sum;
		$A = $monthfee;
		$S = $startfee;
		$R = (pow(1.0+$rate/10000, 1.0/12.0));
		$N = $months;
		if ($rate == 0)
			$T = (1 / $N)*($K0 + ($N * $A) + $S);
		else        
		{	
            // the total cost of fees plus the cost of paying off the monthly fee first and after that amortize 
			for ($j = 2; $j < $N+1; $j++)
            {
                $sum_j_to_N = $sum_j_to_N + pow($R, $N - $j) * $A;
            }
            $T = (($R - 1) / (pow($R, $N) - 1)) * (pow($R, $N) * $K0 + $sum_j_to_N + pow($R, $N - 1) * ($A + $S));
		}
        
		return round($T, 0);
 }

/*
 * API: add_transaction 
 */


function add_transaction_se($eid, $estoreUser, $secret, $estoreOrderNo,
							$goodsList, $shipmentfee, $shipmenttype,
							$handlingfee, $pno, $fname, $lname, $addr, $passwd,
							$clientIp, $newPasswd, $flags, $comment,
							$ready_date, $rand_string, $pclass, $ysalary,
							&$result) {
	global $KRED_SE_PNO, $KRED_SEK, $KRED_ISO3166_SE, $KRED_ISO639_SV;
	return add_transaction($eid, $estoreUser, $secret, $estoreOrderNo,
						   $goodsList, $shipmentfee, $shipmenttype,
						   $handlingfee, $pno, $fname, $lname, $addr, $passwd,
						   $clientIp, $newPasswd, $flags, $comment,
						   $ready_date, $rand_string, $KRED_SEK,
						   $KRED_ISO3166_SE, $KRED_ISO639_SV, $KRED_SE_PNO, $pclass,
						   $ysalary, $result);
}

function add_transaction_no($eid, $estoreUser, $secret, $estoreOrderNo,
							$goodsList, $shipmentfee, $shipmenttype,
							$handlingfee, $pno, $fname, $lname, $addr, $passwd,
							$clientIp, $newPasswd, $flags, $comment,
							$ready_date, $rand_string, $pclass, $ysalary,
							&$result) {
	global $KRED_NO_PNO, $KRED_NOK, $KRED_ISO3166_NO, $KRED_ISO639_NB;
	return add_transaction($eid, $estoreUser, $secret, $estoreOrderNo,
						   $goodsList, $shipmentfee, $shipmenttype,
						   $handlingfee, $pno, $fname, $lname, $addr, $passwd,
						   $clientIp, $newPasswd, $flags, $comment,
						   $ready_date, $rand_string, $KRED_NOK,
						   $KRED_ISO3166_NO, $KRED_ISO639_NB, $KRED_NO_PNO, $pclass,
						   $ysalary, $result);
}


function add_transaction_dk($eid, $estoreUser, $secret, $estoreOrderNo,
							$goodsList, $shipmentfee, $shipmenttype,
							$handlingfee, $pno, $fname, $lname, $addr, $passwd,
							$clientIp, $newPasswd, $flags, $comment,
							$ready_date, $rand_string, $pclass, $ysalary,
							&$result) {
	global $KRED_DK_PNO, $KRED_DKK, $KRED_ISO3166_DK, $KRED_ISO639_DA;
	return add_transaction($eid, $estoreUser, $secret, $estoreOrderNo,
						   $goodsList, $shipmentfee, $shipmenttype,
						   $handlingfee, $pno, $fname, $lname, $addr, $passwd,
						   $clientIp, $newPasswd, $flags, $comment,
						   $ready_date, $rand_string, $KRED_DKK,
						   $KRED_ISO3166_DK, $KRED_ISO639_DA, $KRED_DK_PNO, $pclass,
						   $ysalary, $result);
}

function add_transaction_fi($eid, $estoreUser, $secret, $estoreOrderNo,
							$goodsList, $shipmentfee, $shipmenttype,
							$handlingfee, $pno, $fname, $lname, $addr, $passwd,
							$clientIp, $newPasswd, $flags, $comment,
							$ready_date, $rand_string, $pclass, $ysalary,
							&$result) {
	global $KRED_FI_PNO, $KRED_EUR, $KRED_ISO3166_FI, $KRED_ISO639_FI;
	return add_transaction($eid, $estoreUser, $secret, $estoreOrderNo,
						   $goodsList, $shipmentfee, $shipmenttype,
						   $handlingfee, $pno, $fname, $lname, $addr, $passwd,
						   $clientIp, $newPasswd, $flags, $comment,
						   $ready_date, $rand_string, $KRED_EUR,
						   $KRED_ISO3166_FI, $KRED_ISO639_FI, $KRED_FI_PNO, $pclass,
						   $ysalary, $result);
}

function add_transaction_de($eid, $estoreUser, $secret, $estoreOrderNo,
							$goodsList, $shipmentfee, $shipmenttype,
							$handlingfee, $pno, $fname, $lname, $addr, $passwd,
							$clientIp, $newPasswd, $flags, $comment,
							$ready_date, $rand_string, $pclass, $ysalary,
							&$result) {
	global $KRED_DE_PNO, $KRED_EUR, $KRED_ISO3166_DE, $KRED_ISO639_DE;
	return add_transaction($eid, $estoreUser, $secret, $estoreOrderNo,
						   $goodsList, $shipmentfee, $shipmenttype,
						   $handlingfee, $pno, $fname, $lname, $addr, $passwd,
						   $clientIp, $newPasswd, $flags, $comment,
						   $ready_date, $rand_string, $KRED_EUR,
						   $KRED_ISO3166_DE, $KRED_ISO639_DE, $KRED_DE_PNO, $pclass,
						   $ysalary, $result);
}

function add_transaction_nl($eid, $estoreUser, $secret, $estoreOrderNo,
							$goodsList, $shipmentfee, $shipmenttype,
							$handlingfee, $pno, $fname, $lname, $addr, $passwd,
							$clientIp, $newPasswd, $flags, $comment,
							$ready_date, $rand_string, $pclass, $ysalary,
							&$result) {
	global $KRED_NL_PNO, $KRED_EUR, $KRED_ISO3166_NL, $KRED_ISO639_NL;
	return add_transaction($eid, $estoreUser, $secret, $estoreOrderNo,
						   $goodsList, $shipmentfee, $shipmenttype,
						   $handlingfee, $pno, $fname, $lname, $addr, $passwd,
						   $clientIp, $newPasswd, $flags, $comment,
						   $ready_date, $rand_string, $KRED_EUR,
						   $KRED_ISO3166_NL, $KRED_ISO639_NL, $KRED_NL_PNO, $pclass,
						   $ysalary, $result);
}

function add_transaction($eid, $estoreUser, $secret, $estoreOrderNo,$goodsList,
						 $shipmentfee, $shipmenttype, $handlingfee, $pno,
						 $fname, $lname, $addr, $passwd, $clientIp, $newPasswd,
						 $flags, $comment, $ready_date, $rand_string,
						 $currency, $country, $language, $pno_encoding,
						 $pclass, $ysalary, &$result) {
	our_settype_integer($eid);
	our_settype_string($estoreUser);
	our_settype_string($secret);
	our_settype_string($estoreOrderNo);
	our_settype_integer($shipmentfee);
	our_settype_integer($shipmenttype);
	our_settype_integer($handlingfee);
	our_strip_string($pno);
	our_settype_string($fname);
	our_settype_string($lname);
	our_settype_string($passwd);
	our_settype_string($clientIp);
	our_settype_string($newPasswd);
	our_settype_integer($flags);
	settype($comment, "string");
	our_settype_string($ready_date);
	our_settype_integer($currency);
	our_settype_integer($country);
	our_settype_integer($language);
	our_settype_integer($pno_encoding);
	our_settype_integer($pclass);
	our_settype_integer($ysalary);
	$digestSecret = add_transaction_digest($goodsList, $secret);
	$paramList =
		array($eid, $estoreUser, $digestSecret,
			  $estoreOrderNo, $goodsList, $shipmentfee, $shipmenttype,
			  $handlingfee, $pno, $fname, $lname, $addr, $passwd, $clientIp,
			  $newPasswd, $flags, $comment, $ready_date, $rand_string,
              $currency, $country, $language, $pno_encoding, $pclass, $ysalary);
    return kred_call("add_invoice", $paramList, $result);	
}
 
/*
 * API: invoice_address
 */

function invoice_address($eid, $invno, $secret, &$result) {
	our_settype_integer($eid);
	our_settype_string($invno);
	our_settype_string($secret);
	$digestSecret = invoice_digest($eid, $invno, $secret);
	$paramList = array($eid, $invno, $digestSecret);
	return kred_call("invoice_address", $paramList, $result);	
}

/*
 * API: invoice_amount
 */

function invoice_amount($eid, $invno, $secret, &$result) {
	our_settype_integer($eid);
	our_settype_string($invno);
	our_settype_string($secret);
	$digestSecret = invoice_digest($eid, $invno, $secret);
	$paramList = array($eid, $invno, $digestSecret);
	return kred_call("invoice_amount", $paramList, $result);	
}

/*
 * API: delete_invoice
 */

function delete_invoice($eid, $invno, $secret, &$result) {
	our_settype_integer($eid);
	our_settype_string($invno);
	our_settype_string($secret);
	$digestSecret = invoice_digest($eid, $invno, $secret);
	$paramList = array($eid, $invno, $digestSecret);
	return kred_call("delete_invoice", $paramList, $result);	
}

/*
 * API: return_invoice
 */

function return_invoice($eid, $invno, $secret, &$result) {
    our_settype_integer($eid);
    our_settype_string($invno);
    our_settype_string($secret);
    $digestSecret = invoice_digest($eid, $invno, $secret);
    $paramList = array($eid, $invno, $digestSecret);
    return kred_call("return_invoice", $paramList, $result);
}

/*
 * API: credit_invoice
 */

function credit_invoice($eid, $invno, $credno, $secret, &$result) {
    our_settype_integer($eid);
    our_settype_string($invno);
    our_settype_string($credno);
    our_settype_string($secret);
    $digestSecret = invoice_digest($eid, $invno, $secret);
    $paramList = array($eid, $invno, $credno, $digestSecret);
    return kred_call("credit_invoice", $paramList, $result);
}

/*
 * API: return_part
 */

function return_part($eid, $invno, $artnos, $secret, &$result) {
    our_settype_integer($eid);
    our_settype_string($invno);
    our_settype_string($secret);
    $digestSecret = activate_part_digest($eid, $invno, $artnos, $secret);
    $paramList = array($eid, $invno, $artnos, $digestSecret);
    return kred_call("return_part", $paramList, $result);
}

/*
 * API: credit_part
 */

function credit_part($eid, $invno, $artnos, $credno, $secret, &$result) {
    our_settype_integer($eid);
    our_settype_string($invno);
    our_settype_string($credno);
    our_settype_string($secret);
    $digestSecret = activate_part_digest($eid, $invno, $artnos, $secret);
    $paramList = array($eid, $invno, $artnos, $credno, $digestSecret);
    return kred_call("credit_part", $paramList, $result);
}

/*
 * API: return_amount
 */

function return_amount($eid, $invno, $amount, $vat, $secret, &$result) {
    return return_amount_flags($eid, $invno, $amount, $vat, $secret, 0, $result);
}

function return_amount_flags($eid, $invno, $amount, $vat, $secret, $flags, &$result) {
     our_settype_integer($eid);
     our_settype_string($invno);
     our_settype_integer($amount);
     settype($vat, "float");
     our_settype_string($secret);
     our_settype_integer($flags);
     $digestSecret = invoice_digest($eid, $invno, $secret);
     $paramList = array($eid, $invno, $amount, $vat, $digestSecret, $flags);
     return kred_call("return_amount", $paramList, $result);
}

/*
 * API: strerror
 */

function strerror($reason) {
    switch ($reason)
	{
		case "unknown_estore":
		case "invalid_estore_secret":
			return "Det har uppstått ett integrationsfel mellan butiken och Billmate (felkod: $reason). Kontakta Webbutiken för mer information eller välj ett annat sätt att betala.";
		case "estore_blacklisted":
		case "customer_blacklisted":
			return "Ett problem har uppstått i kommunikationen med Billmate, som sköter vår fakturering. Vänligen kontakta Billmate (http://www.billmate.se) för mer information eller välj ett annat sätt att betala.";
		case "bad_customer_password":
			return "Du har angivit ett felaktigt lösenord.";
		case "dead":
		case "no_such_person":
		case "pno":
		case "invalid_pno":
			return "Du har angivit ett felaktigt personnummer, var vänlig och kontrollera om du har råkat skriva fel.";
		case "under_aged":
		case "customer_not_18":
			return "Tyvärr måste man vara 18 år för att kunna få handla genom Billmate.";   
		case "estore_overrun":
		case "amount":
		case "customer_credit_overrun":
			return "Tyvärr kan inte Billmate, som sköter vår fakturering, godkänna ditt köp då du har överskridit din kreditgräns. Vänligen kontakta Billmates kundtjänst (http://www.billmate.se) för att tillsammans hitta en lösning eller välj ett annat sätt att betala.";
		case "blocked":
		case "customer_not_accepted":
		case "unpaid_bills":
			return "Tyvärr kan inte inte Billmate, som sköter vår fakturering, godkänna köpet efter kreditkontroll. Vänligen kontakta Billmates kundtjänst (http://www.billmate.se) för att tillsammans hitta en lösning eller välj ett annat sätt att betala.";
		case "bad_name":
			return "Du har angivit ett namn som inte stämmer överens med ditt personnummer. Var vänlig och kontrollera om du har råkat skriva fel eller välj ett annat sätt att betala.";
		case "foreign_addr":
		case "bad_addr":
		case "address":
			return "Du har angivit en adress som inte stämmer överens med ditt personnummer. Var vänlig och kontrollera om du har råkat skriva fel eller välj ett annat sätt att betala.";
		case "postno":
		case "bad_postno":
			return "Du har angivit ett felaktigt postnummer. Var vänlig och kontrollera om du har råkat skriva fel eller välj ett annat sätt att betala.";
		case "cellno":
			return "Du har angivit ett felaktigt mobilnummer. Var vänlig och kontrollera om du har råkat skriva fel eller välj ett annat sätt att betala."; 
		case "no_such_subscription":
			return "This error means that an operation such as freeze refered to a customer subscription that doesn't exist";
		case "not_unique_subscription_no":
			return "The estore must provide estore order numbers that are unique for that estore, if they don't this error is returned from the XML-RPC operations";;
		case "terminated":
			return "It's not possible to i.e freeze a terminated subscription";
		case "already_set":
			return "It's not possible to i.e freeze a frozen subscription";
		case "need_email_addr":
			return "This error may be returned when the subscription is created and (a) the subscription type requires auth by email and (b) the XML-RPC from the estore that tries to create the subscription doesn't have a valid email addr in the email field";
		default:
			return "Ett problem har uppstått i kommunikationen med Billmate, som sköter vår fakturering. Vänligen kontakta Billmate (http://www.billmate.se) för mer information eller välj ett annat sätt att betala. (Felkod: " . $reason . ")";
    }
}



// RETURN ERROR IN SWEDISH
function strerror_se($reason) {
    switch ($reason)
	{
		case "unknown_estore":
		case "invalid_estore_secret":
			return "Det har uppstått ett integrationsfel mellan butiken och Billmate (felkod: $reason). Kontakta Webbutiken för mer information eller välj ett annat sätt att betala.";
		case "estore_blacklisted":
		case "customer_blacklisted":
			return "Ett problem har uppstått i kommunikationen med Billmate, som sköter vår fakturering. Vänligen kontakta Billmate (http://www.billmate.se) för mer information eller välj ett annat sätt att betala.";
		case "bad_customer_password":
			return "Du har angivit ett felaktigt lösenord.";
		case "dead":
		case "no_such_person":
		case "pno":
		case "invalid_pno":
			return "Du har angivit ett felaktigt personnummer, var vänlig och kontrollera om du har råkat skriva fel.";
		case "under_aged":
		case "customer_not_18":
			return "Tyvärr måste man vara 18 år för att kunna få handla genom Billmate.";   
		case "estore_overrun":
		case "amount":
		case "customer_credit_overrun":
			return "Tyvärr kan inte Billmate, som sköter vår fakturering, godkänna ditt köp då du har överskridit din kreditgräns. Vänligen kontakta Billmates kundtjänst (http://www.billmate.se) för att tillsammans hitta en lösning eller välj ett annat sätt att betala.";
		case "blocked":
		case "customer_not_accepted":
		case "unpaid_bills":
			return "Tyvärr kan inte inte Billmate, som sköter vår fakturering, godkänna köpet efter kreditkontroll. Vänligen kontakta Billmates kundtjänst (http://www.billmate.se) för att tillsammans hitta en lösning eller välj ett annat sätt att betala.";
		case "bad_name":
			return "Du har angivit ett namn som inte stämmer överens med ditt personnummer. Var vänlig och kontrollera om du har råkat skriva fel eller välj ett annat sätt att betala.";
		case "foreign_addr":
		case "bad_addr":
		case "address":
			return "Du har angivit en adress som inte stämmer överens med ditt personnummer. Var vänlig och kontrollera om du har råkat skriva fel eller välj ett annat sätt att betala.";
		case "postno":
		case "bad_postno":
			return "Du har angivit ett felaktigt postnummer. Var vänlig och kontrollera om du har råkat skriva fel eller välj ett annat sätt att betala.";
		case "cellno":
			return "Du har angivit ett felaktigt mobilnummer. Var vänlig och kontrollera om du har råkat skriva fel eller välj ett annat sätt att betala."; 
		case "no_such_subscription":
			return "This error means that an operation such as freeze refered to a customer subscription that doesn't exist";
		case "not_unique_subscription_no":
			return "The estore must provide estore order numbers that are unique for that estore, if they don't this error is returned from the XML-RPC operations";;
		case "terminated":
			return "It's not possible to i.e freeze a terminated subscription";
		case "already_set":
			return "It's not possible to i.e freeze a frozen subscription";
		case "need_email_addr":
			return "This error may be returned when the subscription is created and (a) the subscription type requires auth by email and (b) the XML-RPC from the estore that tries to create the subscription doesn't have a valid email addr in the email field";
		default:
			return "Ett problem har uppstått i kommunikationen med Billmate, som sköter vår fakturering. Vänligen kontakta Billmate (http://www.billmate.se) för mer information eller välj ett annat sätt att betala. (Felkod: " . $reason . ")";
    }
}


// RETURN ERROR IN FINNISH
function strerror_fi($reason)
{
	switch ($reason)
    {
        case "unknown_estore":
        case "invalid_estore_secret":
            return "On syntynyt integrointivirhe kaupan ja Billmaten välillä (virhekoodi: $reason). Ota yhteyttä verkkokauppaan tai valitse toinen maksutapa.";
        case "estore_blacklisted":
        case "customer_blacklisted":
            return "Jotain on tapahtunut kommunikaatiossa laskupalvelun hoitajan, Billmaten, kanssa. Ole ystävällinen ja ota yhteyttä Billmatean (http://www.billmate.fi) tai valitse toinen maksutapa.";
        case "bad_customer_password":
            return "Olet syöttänyt väärän salasanan.";
        case "dead":
        case "no_such_person":
        case "pno":
        case "invalid_pno":
            return "Olet syöttäny väärän henkilötunnuksen. Ole hyvä ja tarkista tunnuksesi.";
        case "under_aged":
        case "customer_not_18":
            return "Sinun täytyy valitettavasti olla 18 vuotta, jotta voit ostaa Billmaten laskupalvelulla.";
        case "estore_overrun":
		case "amount":
        case "customer_credit_overrun":
            return "Billmate, joka hoitaa laskupalvelua, ei voi valitettavasti hyväksyä ostostasi, koska olet ylittänyt luottorajasi. Ole ystävällinen ja ota yhteyttä Billmaten asiakaspalveluun (http://www.billmate.fi) tai valitse toinen maksutapa.";
        case "blocked":
        case "customer_not_accepted":
		case "unpaid_bills":
            return "Billmate, joka hoitaa laskupalvelua, ei voi valitettavasti hyväksyä ostostasi luottotarkistuksen jälkeen. Ole ystävällinen ja ota yhteyttä Billmaten asiakaspalveluun (http://www.billmate.fi) tai valitse toinen maksutapa.";
        case "bad_name":
            return "Olet ilmoittanut nimen, joka ei täsmää henkilötunnukseesi. Ole ystävällinen ja tarkista jos olet kirjoittanut sen väärin tai valitse toinen maksutapa.";
        case "foreign_addr":
        case "bad_addr":
		case "address":
            return "Olet ilmoittanut osoitteen, joka ei täsmää henkilötunnukseesi. Ole ystävällinen ja tarkista, jos olet kirjoittanut sen väärin tai valitse toinen maksutapa.";
        case "postno":
        case "bad_postno":
            return "Olet ilmoittanut väärän postinumeron. Ole ystävällinen ja tarkista, jos olet sen kirjoittanut väärin tai valitse toinen maksutapa.";
		case "cellno":
			return "Olet syöttäny väärän matkapuhelinnumeron. Ole ystävällinen ja tarkista, jos olet sen kirjoittanut väärin tai valitse toinen maksutapa.";
        case "no_such_subscription":
            return "This error means that an operation such as freeze refered to a customer subscription that doesn´t exist";
        case "not_unique_subscription_no":
            return "The estore must provide estore order numbers that are unique for that estore, if they don´t this error is returned from the XML-RPC operations";;
        case "terminated":
            return "It´s not possible to i.e freeze a terminated subscription";
        case "already_set":
            return "It´s not possible to i.e freeze a frozen subscription";
        case "need_email_addr":
            return "This error may be returned when the subscription is created and (a) the subscription type requires auth by email and (b) the XML-RPC from the estore that tries to create the subscription doesn't have a valid email addr in the email field";
        default:
            return "Jotain on tapahtunut kommunikaatiossa laskupalvelun hoitajan, Billmaten, kanssa. Ole ystävällinen ja ota yhteyttä Billmatean (http://www.billmate.fi) tai valitse toinen maksutapa. (Virhekoodi: $reason)";
    }
}

// RETURN ERROR IN DANISH
function strerror_dk($reason)
{
	switch ($reason)
    {
        case "unknown_estore":
        case "invalid_estore_secret":
            return "Et problem har opstået (fejlkod: $reason), vær venlig at kontakte butikken for mere information eller vælg en anden betalingsmetode.";
        case "estore_blacklisted":
        case "customer_blacklisted":                  
            return "Et problem har opstået med kommunikationen til Billmate (http://www.billmate.dk) som håndterer vores fakturering. Var venlig at kontakte Billmate for mere information eller vælg en anden betalingsmetode.";
        case "bad_customer_password":
            return "Fejl i login, forkert password.";
        case "dead":
        case "no_such_person":
        case "pno":
        case "invalid_pno":
            return "Fejl i login, forkert personnummer. Vær venlig at kontrollere at du indtastet det korrekte personnummer.";
        case "under_aged":
        case "customer_not_18":
            return "For at handle gennem Billmate skal du være fyldt 18 år.";
        case "estore_overrun":
		case "amount":
        case "customer_credit_overrun":
            return "Billmate som ansvarer for vores fakturaer beklager meget at måtte meddele dem at din betaling ikke kan accepteres på grund af at den overskrider din kreditgrænse. Vær venlig at kontakte Billmate (http://www.billmate.dk) så at vi kan finde en løsning eller vælg en anden betalingsmetode.";
        case "blocked":
		case "customer_not_accepted":
        case "unpaid_bills":
            return "Billmate som ansvarer for vores fakturaer beklager meget at måtte meddele dem at din betaling ikke kan accepteres på grund af at du ikke er godkendt af vores kreditoplysning. Vær venlig at kontakte Billmate (http://www.billmate.dk) så at vi kan finde en løsning eller vælg en anden betalingsmetode.";
        case "bad_name":
            return "Navnet du har intastet stemmer ikke overens med dit personnummer. Vær venlig at kontrollere at du har intastet det korrekte navn og prøv igen.";
        case "foreign_addr":
        case "bad_addr":
		case "address":
            return "Den adresse som du har intastet stemmer ikke overnes med dit personnummer. Vær venlig at kontrollere at du har intastet den korrekte adresse og prøv igen.";
        case "postno":
        case "bad_postno":
            return "Du har intastet en postnummer som ikke existerer. Venlig at kontrollere at du har intastet det korrekte postnummer og prøv igen.";
		case "cellno":
			return "Fejl i login, forkert mobilnummer. Venlig at kontrollere at du har intastet det korrekte postnummer og prøv igen.";
        case "no_such_subscription":
            return "This error means that an operation such as freeze refered to a customer subscription that doesn´t exist";
        case "not_unique_subscription_no":
            return "The estore must provide estore order numbers that are unique for that estore, if they don´t this error is returned from the XML-RPC operations";;
        case "terminated":
            return "It´s not possible to i.e freeze a terminated subscription";
        case "already_set":
            return "It´s not possible to i.e freeze a frozen subscription";
        case "need_email_addr":
            return "This error may be returned when the subscription is created and (a) the subscription type requires auth by email and (b) the XML-RPC from the estore that tries to create the subscription doesn't have a valid email addr in the email field";
        default:
            return "Et problem har opstået med kommunikationen til Billmate (http://www.billmate.dk) som håndterer vores fakturering. Var venlig at kontakte Billmate for mere information eller vælg en anden betalingsmetode.(errorcode: $reason)";
    }
}

// RETURN ERROR IN NORWEGIAN
function strerror_no($reason)
{
	switch ($reason)
    {
        case "unknown_estore":
        case "invalid_estore_secret":
            return "Det oppstod en feil mellom butikken og Billmate (feilkode: $reason). Vennligst kontakt butikken for mer informasjon eller velg en annen betalingsmetode.";
        case "estore_blacklisted":
        case "customer_blacklisted":                  
            return "Det har oppstått et problem i kommunikasjonen med Billmate som håndterer faktureringen. Vennligst kontakt Billmate (http://www.billmate.no) for mer informasjon eller velg en annen betalingsmetode.";
        case "bad_customer_password":
            return "Du har oppgitt feil passord.";
        case "dead":
        case "no_such_person":
        case "pno":
        case "invalid_pno":
            return "Du har oppgitt feil personnummer. Vennligst kontroller og forsøk igjen.";
        case "under_aged":
        case "customer_not_18":
            return "Beklager, men du må være minst 18 år for å handle via Billmate.";
        case "estore_overrun":
		case "amount":
        case "customer_credit_overrun":
            return "Beklager, men Billmate som håndterer våre fakturaer, kan ikke akseptere betalingsmetoden ettersom kjøpesummen overstiger din kredittgrense. Vennligst kontakt Billmate (http://www.billmate.no) for mer informasjon eller velg en annen betalingsmetode.";
        case "blocked":
		case "customer_not_accepted":
        case "unpaid_bills":
            return "Beklager, men Billmate som håndterer våre fakturaer, kan ikke akseptere betalingsmetoden etter en kredittsjekk. Vennligst kontakt Billmate (http://www.billmate.no) for mer informasjon eller velg en annen betalingsmetode.";
        case "bad_name":
            return "Du har oppgitt et navn som ikke stemmer overens med det oppgitte personnummeret. Vennligst kontroller og forsøk igjen.";
        case "foreign_addr":
        case "bad_addr":
		case "address":
            return "Du har oppgitt en adresse som ikke stemmer overens med det oppgitte personnummeret. Vennligst kontroller og forsøk igjen.";
        case "postno":
        case "bad_postno":
            return "Du har oppgitt et postnummer som ikke eksisterer. Vennligst kontroller og forsøk igjen.";
		case "cellno":
			return "Du har oppgitt feil mobilnummer. Vennligst kontroller og forsøk igjen.";
        case "no_such_subscription":
            return "This error means that an operation such as freeze refered to a customer subscription that doesn´t exist";
        case "not_unique_subscription_no":
            return "The estore must provide estore order numbers that are unique for that estore, if they don´t this error is returned from the XML-RPC operations";;
        case "terminated":
            return "It´s not possible to i.e freeze a terminated subscription";
        case "already_set":
            return "It´s not possible to i.e freeze a frozen subscription";
        case "need_email_addr":
            return "This error may be returned when the subscription is created and (a) the subscription type requires auth by email and (b) the XML-RPC from the estore that tries to create the subscription doesn't have a valid email addr in the email field";
        default:
            return "Det har oppstått et problem i kommunikasjonen med Billmate som håndterer faktureringen. Vennligst kontakt Billmate (http://www.billmate.no) for mer informasjon eller velg en annen betalingsmetode. (errorcode: $reason)";
    }
}

// RETURN ERROR IN ENGLISH
function strerror_en($reason) 
{ 
   switch ($reason)
   {
		Case "unknown_estore":
		Case "invalid_estore_secret":
			return "An integration problem has occured between the Store and Billmate (error code: $reason). Please contact the Store for more information or choose another payment method.";
		Case "estore_blacklisted":
		Case "customer_blacklisted":
			return "A problem has occured in the communication to Billmate that handles our invoicing. Please contact Billmate (http://www.billmate.se) for more information or choose another payment method.";
		Case "bad_customer_password":
			return "The password you have given was not accepted.";
		Case "dead":
		Case "no_such_person":
		Case "invalid_pno":
		Case "pno":
			return "The social security number you have given was not accepted. Please check for misspellings and try again, or choose another payment method.";
		Case "under_aged":
		Case "customer_not_18":
			return "You have to be above the age of 18 to be able to make purchases through Billmate.";   	
		Case "amount":
		Case "customer_credit_overrun":
		Case "estore_overrun":
			return "Billmate, that handles our invoicing, cannot accept your purchase because it exceeds your accepted limit. Please contact Billmate (http://www.billmate.se) to solve this or choose another payment method.";
		Case "blocked":
		Case "customer_not_accepted":
		Case "unpaid_bills":
			return "Billmate, that handles our invoicing, cannot accept your purchase after a credit check. Please contact Billmate (http://www.billmate.se) to solve this or choose another payment method.";
		Case "bad_name":
			return "The name you have given does not correspond with the social security number you submitted. Please check for misspellings and try again, or choose another payment method.";
		Case "foreign_addr":
		Case "bad_addr":
		case "address":
			return "The address you have given does not correspond with your personal identification number you submitted. Please check for misspellings and try again, or choose another payment method.";
		Case "postno":
		Case "bad_postno":
			return "The post number you have given is not valid. Please check for misspellings and try again, or choose another payment method.";
		case "cellno":
			return "The cell number you have given is not valid. Please check for misspellings and try again, or choose another payment method.";
		Case "no_such_subscription":
			return "This error means that an operation such as freeze refered to a customer subscription that doesn't exist";
		Case "not_unique_subscription_no":
			return "The estore must provide estore order numbers that are unique for that estore, if they don't this error is returned from the XML-RPC operations";
		Case "terminated":
			return "It's not possible to i.e freeze a terminated subscription";
		Case "already_set":
			return "It's not possible to i.e freeze a frozen subscription";
		Case "need_email_addr":
			return "This error may be returned when the subscription is created and (a) the subscription type requires auth by email and (b) the XML-RPC from the estore that tries to create the subscription doesn't have a valid email addr in the email field";
		Default:
			return "A problem has occured in the communication to Billmate that handles our invoicing. Please contact Billmate (http://www.billmate.se) for more information or choose another payment method. (error code: $reason).";
   }
}

/*
 * API: update_goods_qty
 */

function update_goods_qty($eid, $invno, $secret, $artno, $newQty, &$result) {
	our_settype_integer($eid);
	our_settype_string($invno);
	our_settype_string($secret);
	our_settype_string($artno);
	our_settype_integer($newQty);
	$digestSecret = update_goods_qty_digest($invno, $artno, $newQty, $secret);
	$paramList = array($eid, $digestSecret, $invno, $artno, $newQty);
	return kred_call("update_goods_qty", $paramList, $result);
}

/*
 * API: update_charge_amount
 */

function update_charge_amount($eid, $invno, $secret, $type, $newAmount,
							  &$result) {
	our_settype_integer($eid);
	our_settype_string($invno);
	our_settype_string($secret);
	our_settype_integer($type);
	our_settype_integer($newAmount);
	$digestSecret =
		update_charge_amount_digest($invno, $type, $newAmount, $secret);
	$paramList = array($eid, $digestSecret,
					   $invno, $type, $newAmount);
	return kred_call("update_charge_amount", $paramList, $result);
}

/*
 * API: update_orderno
 */

function update_orderno($eid, $invno, $secret, $estoreOrderNo, &$result) {
	our_settype_integer($eid);
	our_settype_string($secret);
	our_settype_string($invno);
	our_settype_string($estoreOrderNo);
	$digestSecret = update_orderno_digest($invno, $estoreOrderNo, $secret);
	$paramList = array($eid, $digestSecret, $invno, $estoreOrderNo);
	return kred_call("update_orderno", $paramList, $result);
}


/*
 * API: update_notes
 */

function update_notes($eid, $invno, $secret, $notes, &$result) {
	our_settype_integer($eid);
	our_settype_string($secret);
	our_settype_string($invno);
	our_settype_string($notes);
	$digestSecret = update_notes_digest($invno, $notes, $secret);
	$paramList = array($eid, $digestSecret, $invno, $notes);
	return kred_call("update_notes", $paramList, $result);
}

/*
 * API: update_email
 */

function update_email($eid, $secret, $pno, $email, &$result) {
	our_settype_integer($eid);
	our_settype_string($secret);
	our_strip_string($pno);
	our_settype_string($email);
	$digestSecret = update_notes_digest($pno, $email, $secret);
	$paramList = array($eid, $digestSecret, $pno, $email);
	return kred_call("update_email", $paramList, $result);
}

/*
 * API: get_addresses
 */

function get_addresses($eid, $pno, $secret, $pno_encoding, $type, &$result) {
	our_settype_integer($eid);
	our_settype_string($secret);
	our_strip_string($pno);
	our_settype_integer($pno_encoding);
	$digestSecret = pno_digest($eid, $pno, $secret);
	$paramList = array($pno, $eid, $digestSecret, $pno_encoding, $type);
    $ssl = true;
    $debug = false;
	$k = new Billmate($eid,$secret,$ssl,$debug);
	$result = $k->GetAddress($pno);
	return kred_call("get_addresses", $paramList, $result);
}

/*
 * API: reserve_amount 
 */

function reserve_amount($pno, $amount, $reference, $referece_code, $orderid1, 
						$orderid2, $lev_addr, $f_addr, $email, $phone, $cell,
						$client_ip, $flags, $currency, $country, $language,
						$eid, $secret, $pno_encoding, $pclass, $ysalary,
						$goodsList, &$result) {
	our_strip_string($pno);
	our_settype_integer($amount);
	settype($referece, "string");
	settype($referece_code, "string");
	our_settype_string($orderid1);
	our_settype_string($orderid2);
	our_settype_string($email);
	our_settype_string($phone);
	our_settype_string($cell);
	our_settype_string($client_ip);
	our_settype_integer($flags);
	our_settype_integer($currency);
	our_settype_integer($country);
	our_settype_integer($language);
	our_settype_integer($eid);
	our_settype_string($secret);
	our_settype_integer($pno_encoding);
	our_settype_integer($pclass);
	our_settype_integer($ysalary);
	$digestSecret = reserve_amount_digest($eid, $pno, $amount, $secret);
	$paramList = array($pno, $amount, $reference, $referece_code, $orderid1, 
					   $orderid2, $lev_addr, $f_addr, $email, $phone, $cell,
					   $client_ip, $flags, $currency, $country, $language,
					   $eid, $digestSecret, $pno_encoding, $pclass, 
					   $ysalary, $goodsList);
	return kred_call("reserve_amount", $paramList, $result);
}



/*
 * API: cancel_reservation
 */

function cancel_reservation($rno, $eid, $secret, &$result) {
	our_settype_string($rno);
	our_settype_integer($eid);
	our_settype_string($secret);
	$digestSecret = ref_no_digest($eid, $rno, $secret);
	$paramList = array($rno, $eid, $digestSecret);
	return kred_call("cancel_reservation", $paramList, $result);
}



/*
 * API: change_reservation
 */

function change_reservation($rno, $newAmount, $eid, $secret, $flags, &$result) {
	our_settype_string($rno);
	our_settype_integer($newAmount);
	our_settype_integer($eid);
	our_settype_string($secret);
	our_settype_integer($flags);
	$digestSecret = change_res_digest($eid, $rno, $newAmount, $secret);
	$paramList = array($rno, $newAmount, $eid, $digestSecret, $flags);
	return kred_call("change_reservation", $paramList, $result);
}

/*
 * API: split_reservation
 */

function split_reservation($rno, $splitAmount, $orderid1, $orderid2,
	$flags, $eid, $secret, &$result) {
	our_settype_string($rno);
	our_settype_integer($splitAmount);
	our_settype_string($orderid1);
	our_settype_string($orderid2);
	our_settype_integer($flags);
	our_settype_integer($eid);
	our_settype_string($secret);
	$digestSecret = split_reserve_digest($eid, $rno, $splitAmount, $secret);
	$paramList = array($rno, $splitAmount, $orderid1, $orderid2, $flags,
	$eid, $digestSecret);
	return kred_call("split_reservation", $paramList, $result);
}


/*
 * API: activate_reservation 
 */

function activate_reservation($rno, $pno, $ocr, $goodslist, $reference, 
	$referece_code, $orderid1, $orderid2, $lev_addr, $f_addr, 
	$shipmenttype, $email, $phone, $cell, $client_ip, $flags,
	$currency, $country, $language,	$eid, $secret, $pno_encoding, 
	$pclass, $ysalary, $pin, &$result) {
	
	our_settype_string($rno);
	our_strip_string($pno);
	our_settype_string($ocr);
	settype($referece, "string");
	settype($referece_code, "string");
	our_settype_string($orderid1);
	our_settype_string($orderid2);

	our_settype_integer($shipmenttype);
	our_settype_string($email);
	our_settype_string($phone);
	our_settype_string($cell);
	our_settype_string($client_ip);
	our_settype_integer($flags);
	our_settype_integer($currency);
	our_settype_integer($country);
	our_settype_integer($language);
	our_settype_integer($eid);
	our_settype_string($secret);
	our_settype_integer($pno_encoding);
	our_settype_integer($pclass);
	our_settype_integer($ysalary);
	our_settype_string($pin);
	$digestSecret = activate_reservation_digest($eid, $pno, $goodslist, $secret);
	$paramList = array($rno, $pno, $ocr, $goodslist, $reference, 
	$referece_code, $orderid1, $orderid2, $lev_addr, $f_addr, 
	$shipmenttype, $email, $phone, $cell, $client_ip, $flags,
	$currency, $country, $language,	$eid, $digestSecret, $pno_encoding,
	$pclass, $ysalary * 100, $pin);
	return kred_call("activate_reservation", $paramList, $result);
}

function activate_reservation_old($rno, $pno, $goodslist, $reference, 
	$referece_code, $orderid1, $orderid2, $lev_addr, $f_addr, 
	$shipmenttype, $email, $phone, $cell, $client_ip, $flags,
	$currency, $country, $language,	$eid, $secret, $pno_encoding, 
	$pclass, $ysalary, &$result) {
	
	return activate_reservation($rno, $pno, "", $goodslist, $reference, 
	$referece_code, $orderid1, $orderid2, $lev_addr, $f_addr, 
	$shipmenttype, $email, $phone, $cell, $client_ip, $flags,
	$currency, $country, $language,	$eid, $secret, $pno_encoding, 
	$pclass, $ysalary, "", $result);
	
	}


/*
 * API: activate_reservation with ocr
 */
function activate_reservation_ocr($rno, $pno, $ocr, $goodslist, $reference, 
	$referece_code, $orderid1, $orderid2, $lev_addr, $f_addr, 
	$shipmenttype, $email, $phone, $cell, $client_ip, $flags,
	$eid, $secret, $pno_encoding, $pclass, $ysalary, &$result) {
	our_settype_string($rno);
	our_strip_string($pno);
	our_settype_string($ocr);

	settype($referece, "string");
	settype($referece_code, "string");
	our_settype_string($orderid1);
	our_settype_string($orderid2);

	our_settype_integer($shipmenttype);
	our_settype_string($email);
	our_settype_string($phone);
	our_settype_string($cell);
	our_settype_string($client_ip);
	our_settype_integer($flags);
	our_settype_integer($eid);
	our_settype_string($secret);
	our_settype_integer($pno_encoding);
	our_settype_integer($pclass);
	our_settype_integer($ysalary);
	$digestSecret = activate_reservation_digest($eid, $pno, $goodslist, $secret);

	$paramList = array($rno, $pno, $ocr, $goodslist, $reference, 
	$referece_code, $orderid1, $orderid2, $lev_addr, $f_addr, 
	$shipmenttype, $email, $phone, $cell, $client_ip, $flags,
	$eid, $digestSecret, $pno_encoding, $pclass, $ysalary * 100);
	return kred_call("activate_reservation", $paramList, $result);
}

/*
 * API: reserve_ocr_nums 
 */

function reserve_ocr_nums($no, $eid, $secret, $country, &$result) {
	our_settype_integer($no);
	our_settype_integer($eid);
	our_settype_string($secret);
	our_settype_integer($country);
	$digestSecret = ref_no_digest($eid, $no, $secret);
	$paramList = array($no, $eid, $digestSecret, $country);
	return kred_call("reserve_ocr_nums", $paramList, $result);
}

/*
 * API: is_invoice_paid
 */

function is_invoice_paid($invno, $eid, $secret, &$result) {
	our_settype_string($invno);
	our_settype_integer($eid);
	our_settype_string($secret);
	$digestSecret = ref_no_digest($eid, $invno, $secret);
	$paramList = array($invno, $eid, $digestSecret);
	return kred_call("is_invoice_paid", $paramList, $result);
}


/*
 * API: reserve_ocr_nums with email
 */

function reserve_ocr_nums_email($no, $email, $eid, $secret, &$result) {
	our_settype_integer($no);
	our_settype_string($email);
	our_settype_integer($eid);
	our_settype_string($secret);
	$digestSecret = ref_no_digest($eid, $no, $secret);
	$paramList = array($no, $email, $eid, $digestSecret);
	return kred_call("reserve_ocr_nums", $paramList, $result);
}

/*
 * API: set_customer_no
 */

function set_customer_no($pno, $cust_no, $eid, $secret, 
		$pno_encoding, &$result) {
	our_strip_string($pno);
	our_settype_string($cust_no);
	our_settype_integer($eid);
	our_settype_string($secret);
	our_settype_integer($pno_encoding);
	$digestSecret = set_customer_no_digest($eid, $pno, $cust_no, $secret);
	$paramList = array($pno, $cust_no, $eid, $digestSecret, $pno_encoding);
	return kred_call("set_customer_no", $paramList, $result);
}

/*
 * API: get_customer_no
 */

function get_customer_no($pno, $eid, $secret, $pno_encoding, &$result) {
	our_strip_string($pno);
	our_settype_integer($eid);
	our_settype_string($secret);
	our_settype_integer($pno_encoding);
	$digestSecret = ref_no_digest($eid, $pno, $secret);
	$paramList = array($pno, $eid, $digestSecret, $pno_encoding);
	return kred_call("get_customer_no", $paramList, $result);
}

/*
 * API: periodic_cost
 */
function periodic_cost($eid, $sum, $pclass, $currency, $flags, $secret,
					   &$result){
	$months     = get_months($pclass);
	$monthfee   = get_month_fee($pclass);
	$startfee   = get_start_fee($pclass);
	$rate       = get_rate($pclass);
	$result = periodic_cost2($sum, $months, $monthfee, $rate, $startfee);
	return 0;
}

function periodic_cost2($sum, $months, $monthfee, $rate, $startfee){
	$dailyrate  = daily_rate($rate);
	$monthpayment = calc_monthpayment($sum + $startfee, $dailyrate, $months);
	return round($monthpayment + $monthfee);
}


function calc_monthpayment($sum, $dailyrate, $months){
	$dates      = 0;
	$totdates   = (($months - 1) * 30);
	$denom      = calc_denom($dailyrate, $totdates);
	$totdates   = $totdates + 60;
	return ((pow($dailyrate, $totdates) * $sum) / $denom);
}

function calc_denom($dailyrate, $totdates){
	$sum = 1;
	$startdates = 0;
	while ($totdates > $startdates){
		$startdates = $startdates + 30;
		$sum = ($sum + pow($dailyrate, $startdates));
	}
	return $sum;
}

function daily_rate($rate){
	return pow((($rate / 10000) + 1), (1 / 365.25));
}

function periodic_cost_rpc($eid, $sum, $pclass, $currency, $flags, $secret,
					   &$result){
	our_settype_integer($eid);
	our_settype_integer($sum);
	our_settype_integer($pclass);
	our_settype_integer($currency);
	our_settype_integer($flags);
	$digestSecret = periodic_cost_digest($eid, $sum, $pclass, $secret);
	$paramList = array($eid, $sum, $pclass, $currency, $flags, $digestSecret);
	return kred_call("periodic_cost", $paramList, $result);
	
}

function monthly_cost($sum, $rate, $months, $monthsfee, $flags, $currency, &$result) 
{
	global $KRED_ISO3166_SE, $KRED_ISO3166_NO, $KRED_ISO3166_DK, $KRED_ISO3166_FI, $KRED_ISO3166_DE, $KRED_ISO3166_NL, $KRED_EUR, $KRED_NOK, $KRED_SEK, $KRED_DKK;
	if($rate < 100)
		$rate = $rate*100;
		
	switch ($country) {
	case $KRED_SEK:
	case $KRED_ISO3166_SE:
		$lowest_monthly_payment = 5000.00;
		$currency = $KRED_SEK;
		break;
	case $KRED_NOK:
	case $KRED_ISO3166_NO:
		$lowest_monthly_payment = 9500.00;
		$currency = $KRED_NOK;
		break;
	case $KRED_EUR: //DE, NL needs to use country code!
	case $KRED_ISO3166_FI:
		$lowest_monthly_payment = 895.00;
		$currency = $KRED_EUR;
		break;
	case $KRED_DKK:
	case $KRED_ISO3166_DK:
		$lowest_monthly_payment = 8900.00;
		$currency = $KRED_DKK;
		break;
	case $KRED_ISO3166_DE:
		$lowest_monthly_payment = 695.00;
		$currency = $KRED_EUR;
		break;
	case $KRED_ISO3166_NL:
		$lowest_monthly_payment = 500.00;
		$currency = $KRED_EUR;
		break;
	default:
		return -2;
	}			 						 
	
	$average_interest_period = 45;
	$calcRate = ($rate / 10000);
	
	$interest_value = ($average_interest_period / 365.0) * $calcRate * $sum;
	$periodic_cost =  ($sum + $interest_value)/$months;
	
	if ($flags == 1) 
	{	
		$result = round_up($periodic_cost, $currency);
	}
	else if ($flags == 0)
	{
		$periodic_cost = $periodic_cost + $monthsfee;
		if ($periodic_cost < $lowest_monthly_payment) 
			$result = round($lowest_monthly_payment, 0);
		else 
			$result = round_up($periodic_cost, $currency);
	}
	else
		return -2;
	
	return 0;
}

        /*************************************************************************
     * API: monthly_cost_new
     *************************************************************************/
    function calc_monthly_cost($sum, $pclass,
                                    $flags, $currency,
                                    &$result){
        $months;
        $monthsfee;
        $type;
        $rate;
        $startfee;
		
        
            $months = get_months($pclass);
            $monthsfee = get_month_fee($pclass);
            $type = get_type($pclass);
            $rate = get_rate($pclass);
            $startfee = get_start_fee($pclass);
			
        return monthly_cost_new2($sum, $rate, $months, $monthsfee, $startfee, $flags,
                                 $currency, $type, $result);
    }

    /*************************************************************************
     * API: monthly_cost_new2
     *************************************************************************/
function monthly_cost_new2($sum, $rate, $months,
                                     $monthsfee, $startfee,
                                     $flags, $currency,
                                     $type, &$result){
		global $KRED_ANNUITY_PCLASS, $KRED_DIVISOR_PCLASS;
        if($type == $KRED_ANNUITY_PCLASS){
            return periodic_cost_new($sum, $months, $monthsfee, $rate, $startfee,
                                 $currency, $flags, $result);
        }else if($type == $KRED_DIVISOR_PCLASS){
            return monthly_cost($sum, $rate, $months, $monthsfee, $flags, $currency,
                                $result);
        }
		
		return -1;
    }
	
/*************************************************************************
     * API: periodic_cost
     *************************************************************************/
    function periodic_cost_new($sum, $months, $monthfee,
				 $rate, $startfee, $currency, $flags, &$result){
	
	global $KRED_ACTUAL_COST, $KRED_LIMIT_COST;
	$mountscost = 0;	
	$dailyrate = daily_rate($rate);
	$monthpayment = calc_monthpayment($sum+$startfee, $dailyrate, $months);
        if($flags == $KRED_ACTUAL_COST){
            $mountscost = $monthpayment + 0.5;
        }else if($flags == $KRED_LIMIT_COST){
            $mountscost = $monthpayment + $monthfee + 0.5;
        }else{
            $result = -2;
        }
        $result = (int)$mountscost;
    }

function round_up($value, $curr) //value, currency
{
	$result;
	$divisor;
	
	if ($curr == 2) 
		$divisor = 10;
	else 
		$divisor = 100;
	
	$result = $divisor * round((($divisor/2)+$value)/$divisor); //We want to roundup to closest integer.
	
	return $result;
}
			  
function get_pclasses($eid, $cur, $secret, &$result){
	our_settype_integer($eid);
	our_settype_integer($cur);

	$digestSecret = invoice_digest($eid, $cur, $secret);
	$paramList = array($eid, $cur, $digestSecret);
    return kred_call("get_pclasses", $paramList, $result);
}

function fetch_pclasses($eid, $cur, $secret, $country, $language, &$result){
	our_settype_integer($eid);
	our_settype_integer($cur);
	our_settype_integer($country);
	our_settype_integer($language);
//	$digestSecret = invoice_digest($eid, $cur, $secret);
//	$paramList = array($eid, $cur, $digestSecret, $country, $language);

    $ssl = true;
    $debug = false;


    $k = new Billmate($eid,$secret,$ssl,$debug);


    $additionalinfo = array(
        "currency"=>$cur,
        "country"=>$country,
        "language"=>$language,
    );
   return $result = $k->FetchCampaigns($additionalinfo);
    
    //    return kred_call("get_pclasses", $paramList, $result);
}

/*
 * Digests
 */

function set_customer_no_digest($eid, $pno, $cust_no, $secret){
	$string = $eid . ":" . $pno . ":" .  $cust_no . ":" . $secret;
	return md5_base64($string);
}

function change_res_digest($eid, $pno, $amount, $secret){
	$string = $eid . ":" . $pno . ":" .  $amount . ":" . $secret;
	return md5_base64($string);
}

function split_reserve_digest($eid, $pno, $amount, $secret){
	$string = $eid . ":" . $pno . ":" .  $amount . ":" . $secret;
	return md5_base64($string);
}

function ref_no_digest($eid, $ref_no, $secret) {
	$string = $eid . ":" . $ref_no . ":" . $secret;
	return md5_base64($string);
}

function invoice_digest($eid, $invno, $secret) {
	//$string = $eid . ":" . $invno . ":" . $secret;
	//return md5_base64($string);
    $args = array($eid, $invno, $secret);
    $data = implode(":", $args);
    $preferred = array(
        'sha512',
        'sha384',
        'sha256',
        'sha224',
        'md5'
    );

    $hashes = array_intersect($preferred, hash_algos());

    if (count($hashes) == 0) {
        throw new BillmateException(
            "No available hash algorithm supported!"
        );
    }
    $hash = array_shift($hashes);
    return base64_encode(pack("H*", hash($hash, $data)));
}

function pno_digest($eid, $pno, $secret) {
	$string = $eid . ":" . $pno . ":" . $secret;
	return md5_base64($string);
}

function periodic_cost_digest($eid, $sum, $pclass, $secret) {
	$string = $eid . ":" . $sum . ":" . $pclass . ":" . $secret;
	return md5_base64($string);
}

function reserve_amount_digest($eid, $pno, $amount, $secret) {
	$string = $eid . ":" . $pno . ":" . $amount . ":" . $secret;
	return md5_base64($string);
}

function activate_part_digest($eid, $invno, $artnos, $secret) {
	$string = $eid . ":" . $invno . ":";

	foreach ($artnos as $artno)
        $string .= $artno["artno"] . ":". $artno["qty"] . ":";
     
	return md5_base64($string . $secret);

}

function activate_reservation_digest($eid, $pno, $goodsList, $secret) {
	$string = $eid . ":" . $pno . ":";
	foreach ($goodsList as $goods)
		$string .= $goods["goods"]["artno"] . ":" . 
					$goods["qty"] . ":";
     
	return md5_base64($string . $secret);
}

function add_transaction_digest($goodsList, $secret) {
	$string = "";

	foreach ($goodsList as $goods)
		$string .= $goods["goods"]["title"] . ":";
	return md5_base64($string . $secret);
}

function update_charge_amount_digest($invno, $type, $newAmount, $secret) {
	$string = $invno . ":" . $type . ":" . $newAmount . ":" . $secret;
	return md5_base64($string);
}

function update_goods_qty_digest($invno, $artno, $newQty, $secret) {
	$string = $invno . ":" . $artno . ":" . $newQty . ":" . $secret;
	return md5_base64($string);
}

function update_orderno_digest($invno, $orderno, $secret) {
	$string = $invno . ":" . $orderno . ":" . $secret;
	return md5_base64($string);
}

function update_notes_digest($invno, $notes, $secret) {
	$string = $invno . ":" . $notes . ":" . $secret;
	return md5_base64($string);
}

function md5_base64($data) {
	return base64_encode(pack("H*", md5($data)));
}

/*
 * EPI XMLRPC call
 */

function xmlrpc_call($host, $port, $request) {
	$fp = fsockopen($host, $port, $errno, $errstr, 30);
	$query = "POST / HTTP/1.0\r\nUser-Agent: Kreditor PHP Client\r\nHost: " . $host."\nConnection: close\r\nContent-Type: text/xml\r\nContent-Length: " . strlen($request) . "\r\n\r\n" . $request;
     
	if (!fputs($fp, $query, strlen($query))) {
		$errstr = "Write error";
		return 0;
	}
     
	$contents = "";
     
	while (!feof($fp))
		$contents .= fgets($fp);

	fclose($fp);
	return $contents;
}

/*
 * Utilities
 */

function our_settype_integer(&$x) {
	if (is_double($x)) {
		$x = round($x)+(($x>0)?0.00000001:-0.00000001);
	} else if (is_float($x)) {
		$x = round($x)+(($x>0)?0.00000001:-0.00000001);
	} else if (is_string($x)) {
		$x = preg_replace("/[ \n\r\t\e]/", "", $x);
    }

	settype($x, "integer");
}

function our_strip_string(&$x) {
	$x = str_replace(array(" ","-"), "", $x);
}

function our_settype_string(&$x){
	$res = "";
	$length = strlen($x);
	for($i = 0; $i < $length; $i++){
		if($x[$i] >= " "){
			$res .= $x[$i];
		}else{
			$res .= " ";
		}
	}
	$x = $res;
}

function check_params($function, $paramList) {
}

/**
 * PHP implementation of the XML-RPC protocol
 *
 * This is a PEAR-ified version of Useful inc's XML-RPC for PHP.
 * It has support for HTTP transport, proxies and authentication.
 *
 * PHP versions 4 and 5
 *
 * LICENSE: License is granted to use or modify this software
 * ("XML-RPC for PHP") for commercial or non-commercial use provided the
 * copyright of the author is preserved in any distributed or derivative work.
 *
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR "AS IS" AND ANY EXPRESSED OR
 * IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
 * NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
 * THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   Web Services
 * @package    XML_RPC
 * @author     Edd Dumbill <edd@usefulinc.com>
 * @author     Stig Bakken <stig@php.net>
 * @author     Martin Jansen <mj@php.net>
 * @author     Daniel Convissor <danielc@php.net>
 * 
 * @version    CVS: $Id: kreditor.php.src 27677 2009-12-02 13:26:35Z daniel.hansen $
 * @link       http://pear.php.net/package/XML_RPC
 */


if (!function_exists('xml_parser_create')) {
    PEAR::loadExtension('xml');
}

/**#@+
 * Error constants
 */
/**
 * Parameter values don't match parameter types
 */
define('XML_RPC_ERROR_INVALID_TYPE', 101);
/**
 * Parameter declared to be numeric but the values are not
 */
define('XML_RPC_ERROR_NON_NUMERIC_FOUND', 102);
/**
 * Communication error
 */
define('XML_RPC_ERROR_CONNECTION_FAILED', 103);
/**
 * The array or struct has already been started
 */
define('XML_RPC_ERROR_ALREADY_INITIALIZED', 104);
/**
 * Incorrect parameters submitted
 */
define('XML_RPC_ERROR_INCORRECT_PARAMS', 105);
/**
 * Programming error by developer
 */
define('XML_RPC_ERROR_PROGRAMMING', 106);
/**#@-*/


/**
 * Data types
 * @global string $GLOBALS['XML_RPC_I4']
 */
$GLOBALS['XML_RPC_I4'] = 'i4';

/**
 * Data types
 * @global string $GLOBALS['XML_RPC_Int']
 */
$GLOBALS['XML_RPC_Int'] = 'int';

/**
 * Data types
 * @global string $GLOBALS['XML_RPC_Boolean']
 */
$GLOBALS['XML_RPC_Boolean'] = 'boolean';

/**
 * Data types
 * @global string $GLOBALS['XML_RPC_Double']
 */
$GLOBALS['XML_RPC_Double'] = 'double';

/**
 * Data types
 * @global string $GLOBALS['XML_RPC_String']
 */
$GLOBALS['XML_RPC_String'] = 'string';

/**
 * Data types
 * @global string $GLOBALS['XML_RPC_DateTime']
 */
$GLOBALS['XML_RPC_DateTime'] = 'dateTime.iso8601';

/**
 * Data types
 * @global string $GLOBALS['XML_RPC_Base64']
 */
$GLOBALS['XML_RPC_Base64'] = 'base64';

/**
 * Data types
 * @global string $GLOBALS['XML_RPC_Array']
 */
$GLOBALS['XML_RPC_Array'] = 'array';

/**
 * Data types
 * @global string $GLOBALS['XML_RPC_Struct']
 */
$GLOBALS['XML_RPC_Struct'] = 'struct';


/**
 * Data type meta-types
 * @global array $GLOBALS['XML_RPC_Types']
 */
$GLOBALS['XML_RPC_Types'] = array(
    $GLOBALS['XML_RPC_I4']       => 1,
    $GLOBALS['XML_RPC_Int']      => 1,
    $GLOBALS['XML_RPC_Boolean']  => 1,
    $GLOBALS['XML_RPC_String']   => 1,
    $GLOBALS['XML_RPC_Double']   => 1,
    $GLOBALS['XML_RPC_DateTime'] => 1,
    $GLOBALS['XML_RPC_Base64']   => 1,
    $GLOBALS['XML_RPC_Array']    => 2,
    $GLOBALS['XML_RPC_Struct']   => 3,
);


/**
 * Error message numbers
 * @global array $GLOBALS['XML_RPC_err']
 */
$GLOBALS['XML_RPC_err'] = array(
    'unknown_method'      => 1,
    'invalid_return'      => 2,
    'incorrect_params'    => 3,
    'introspect_unknown'  => 4,
    'http_error'          => 5,
    'not_response_object' => 6,
    'invalid_request'     => 7,
);

/**
 * Error message strings
 * @global array $GLOBALS['XML_RPC_str']
 */
$GLOBALS['XML_RPC_str'] = array(
    'unknown_method'      => 'Unknown method',
    'invalid_return'      => 'Invalid return payload: enable debugging to examine incoming payload',
    'incorrect_params'    => 'Incorrect parameters passed to method',
    'introspect_unknown'  => 'Can\'t introspect: method unknown',
    'http_error'          => 'Didn\'t receive 200 OK from remote server.',
    'not_response_object' => 'The requested method didn\'t return an XML_RPC_Response object.',
    'invalid_request'     => 'Invalid request payload',
);


/**
 * Default XML encoding (ISO-8859-1, UTF-8 or US-ASCII)
 * @global string $GLOBALS['XML_RPC_defencoding']
 */
$GLOBALS['XML_RPC_defencoding'] = 'UTF-8';

/**
 * User error codes start at 800
 * @global int $GLOBALS['XML_RPC_erruser']
 */
$GLOBALS['XML_RPC_erruser'] = 800;

/**
 * XML parse error codes start at 100
 * @global int $GLOBALS['XML_RPC_errxml']
 */
$GLOBALS['XML_RPC_errxml'] = 100;


/**
 * Compose backslashes for escaping regexp
 * @global string $GLOBALS['XML_RPC_backslash']
 */
$GLOBALS['XML_RPC_backslash'] = chr(92) . chr(92);


/**
 * Valid parents of XML elements
 * @global array $GLOBALS['XML_RPC_valid_parents']
 */
$GLOBALS['XML_RPC_valid_parents'] = array(
    'BOOLEAN' => array('VALUE'),
    'I4' => array('VALUE'),
    'INT' => array('VALUE'),
    'STRING' => array('VALUE'),
    'DOUBLE' => array('VALUE'),
    'DATETIME.ISO8601' => array('VALUE'),
    'BASE64' => array('VALUE'),
    'ARRAY' => array('VALUE'),
    'STRUCT' => array('VALUE'),
    'PARAM' => array('PARAMS'),
    'METHODNAME' => array('METHODCALL'),
    'PARAMS' => array('METHODCALL', 'METHODRESPONSE'),
    'MEMBER' => array('STRUCT'),
    'NAME' => array('MEMBER'),
    'DATA' => array('ARRAY'),
    'FAULT' => array('METHODRESPONSE'),
    'VALUE' => array('MEMBER', 'DATA', 'PARAM', 'FAULT'),
);


/**
 * Stores state during parsing
 *
 * quick explanation of components:
 *   + ac     = accumulates values
 *   + qt     = decides if quotes are needed for evaluation
 *   + cm     = denotes struct or array (comma needed)
 *   + isf    = indicates a fault
 *   + lv     = indicates "looking for a value": implements the logic
 *               to allow values with no types to be strings
 *   + params = stores parameters in method calls
 *   + method = stores method name
 *
 * @global array $GLOBALS['XML_RPC_xh']
 */
$GLOBALS['XML_RPC_xh'] = array();


/**
 * Start element handler for the XML parser
 *
 * @return void
 */
function XML_RPC_se($parser_resource, $name, $attrs)
{
    global $XML_RPC_xh, $XML_RPC_DateTime, $XML_RPC_String, $XML_RPC_valid_parents;
    $parser = (int) $parser_resource;

    // if invalid xmlrpc already detected, skip all processing
    if ($XML_RPC_xh[$parser]['isf'] >= 2) {
        return;
    }

    // check for correct element nesting
    // top level element can only be of 2 types
    if (count($XML_RPC_xh[$parser]['stack']) == 0) {
        if ($name != 'METHODRESPONSE' && $name != 'METHODCALL') {
            $XML_RPC_xh[$parser]['isf'] = 2;
            $XML_RPC_xh[$parser]['isf_reason'] = 'missing top level xmlrpc element';
            return;
        }
    } else {
        // not top level element: see if parent is OK
        if (!in_array($XML_RPC_xh[$parser]['stack'][0], $XML_RPC_valid_parents[$name])) {
            $name = preg_replace('[^a-zA-Z0-9._-]', '', $name);
            $XML_RPC_xh[$parser]['isf'] = 2;
            $XML_RPC_xh[$parser]['isf_reason'] = "xmlrpc element $name cannot be child of {$XML_RPC_xh[$parser]['stack'][0]}";
            return;
        }
    }

    switch ($name) {
    case 'STRUCT':
        $XML_RPC_xh[$parser]['cm']++;

        // turn quoting off
        $XML_RPC_xh[$parser]['qt'] = 0;

        $cur_val = array();
        $cur_val['value'] = array();
        $cur_val['members'] = 1;
        array_unshift($XML_RPC_xh[$parser]['valuestack'], $cur_val);
        break;

    case 'ARRAY':
        $XML_RPC_xh[$parser]['cm']++;

        // turn quoting off
        $XML_RPC_xh[$parser]['qt'] = 0;

        $cur_val = array();
        $cur_val['value'] = array();
        $cur_val['members'] = 0;
        array_unshift($XML_RPC_xh[$parser]['valuestack'], $cur_val);
        break;

    case 'NAME':
        $XML_RPC_xh[$parser]['ac'] = '';
        break;

    case 'FAULT':
        $XML_RPC_xh[$parser]['isf'] = 1;
        break;

    case 'PARAM':
        $XML_RPC_xh[$parser]['valuestack'] = array();
        break;

    case 'VALUE':
        $XML_RPC_xh[$parser]['lv'] = 1;
        $XML_RPC_xh[$parser]['vt'] = $XML_RPC_String;
        $XML_RPC_xh[$parser]['ac'] = '';
        $XML_RPC_xh[$parser]['qt'] = 0;
        // look for a value: if this is still 1 by the
        // time we reach the first data segment then the type is string
        // by implication and we need to add in a quote
        break;

    case 'I4':
    case 'INT':
    case 'STRING':
    case 'BOOLEAN':
    case 'DOUBLE':
    case 'DATETIME.ISO8601':
    case 'BASE64':
        $XML_RPC_xh[$parser]['ac'] = ''; // reset the accumulator

        if ($name == 'DATETIME.ISO8601' || $name == 'STRING') {
            $XML_RPC_xh[$parser]['qt'] = 1;

            if ($name == 'DATETIME.ISO8601') {
                $XML_RPC_xh[$parser]['vt'] = $XML_RPC_DateTime;
            }

        } elseif ($name == 'BASE64') {
            $XML_RPC_xh[$parser]['qt'] = 2;
        } else {
            // No quoting is required here -- but
            // at the end of the element we must check
            // for data format errors.
            $XML_RPC_xh[$parser]['qt'] = 0;
        }
        break;

    case 'MEMBER':
        $XML_RPC_xh[$parser]['ac'] = '';
        break;

    case 'DATA':
    case 'METHODCALL':
    case 'METHODNAME':
    case 'METHODRESPONSE':
    case 'PARAMS':
        // valid elements that add little to processing
        break;
    }


    // Save current element to stack
    array_unshift($XML_RPC_xh[$parser]['stack'], $name);

    if ($name != 'VALUE') {
        $XML_RPC_xh[$parser]['lv'] = 0;
    }
}

/**
 * End element handler for the XML parser
 *
 * @return void
 */
function XML_RPC_ee($parser_resource, $name)
{
    global $XML_RPC_xh, $XML_RPC_Types, $XML_RPC_String;
    $parser = (int) $parser_resource;

    if ($XML_RPC_xh[$parser]['isf'] >= 2) {
        return;
    }

    // push this element from stack
    // NB: if XML validates, correct opening/closing is guaranteed and
    // we do not have to check for $name == $curr_elem.
    // we also checked for proper nesting at start of elements...
    $curr_elem = array_shift($XML_RPC_xh[$parser]['stack']);

    switch ($name) {
    case 'STRUCT':
    case 'ARRAY':
    $cur_val = array_shift($XML_RPC_xh[$parser]['valuestack']);
    $XML_RPC_xh[$parser]['value'] = $cur_val['value'];
        $XML_RPC_xh[$parser]['vt'] = strtolower($name);
        $XML_RPC_xh[$parser]['cm']--;
        break;

    case 'NAME':
    $XML_RPC_xh[$parser]['valuestack'][0]['name'] = $XML_RPC_xh[$parser]['ac'];
        break;

    case 'BOOLEAN':
        // special case here: we translate boolean 1 or 0 into PHP
        // constants true or false
        if ($XML_RPC_xh[$parser]['ac'] == '1') {
            $XML_RPC_xh[$parser]['ac'] = 'true';
        } else {
            $XML_RPC_xh[$parser]['ac'] = 'false';
        }

        $XML_RPC_xh[$parser]['vt'] = strtolower($name);
        // Drop through intentionally.

    case 'I4':
    case 'INT':
    case 'STRING':
    case 'DOUBLE':
    case 'DATETIME.ISO8601':
    case 'BASE64':
        if ($XML_RPC_xh[$parser]['qt'] == 1) {
            // we use double quotes rather than single so backslashification works OK
            $XML_RPC_xh[$parser]['value'] = $XML_RPC_xh[$parser]['ac'];
        } elseif ($XML_RPC_xh[$parser]['qt'] == 2) {
            $XML_RPC_xh[$parser]['value'] = base64_decode($XML_RPC_xh[$parser]['ac']);
        } elseif ($name == 'BOOLEAN') {
            $XML_RPC_xh[$parser]['value'] = $XML_RPC_xh[$parser]['ac'];
        } else {
            // we have an I4, INT or a DOUBLE
            // we must check that only 0123456789-.<space> are characters here
            if (!preg_match("/^[+-]?[0123456789 \t\.]+$/", $XML_RPC_xh[$parser]['ac'])) {
                XML_RPC_Base::raiseError('Non-numeric value received in INT or DOUBLE',
                                         XML_RPC_ERROR_NON_NUMERIC_FOUND);
                $XML_RPC_xh[$parser]['value'] = XML_RPC_ERROR_NON_NUMERIC_FOUND;
            } else {
                // it's ok, add it on
                $XML_RPC_xh[$parser]['value'] = $XML_RPC_xh[$parser]['ac'];
            }
        }

        $XML_RPC_xh[$parser]['ac'] = '';
        $XML_RPC_xh[$parser]['qt'] = 0;
        $XML_RPC_xh[$parser]['lv'] = 3; // indicate we've found a value
        break;

    case 'VALUE':
        if ($XML_RPC_xh[$parser]['vt'] == $XML_RPC_String) {
            if (strlen($XML_RPC_xh[$parser]['ac']) > 0) {
                $XML_RPC_xh[$parser]['value'] = $XML_RPC_xh[$parser]['ac'];
            } elseif ($XML_RPC_xh[$parser]['lv'] == 1) {
                // The <value> element was empty.
                $XML_RPC_xh[$parser]['value'] = '';
            }
        }

        $temp = new XML_RPC_Value($XML_RPC_xh[$parser]['value'], $XML_RPC_xh[$parser]['vt']);

        $cur_val = array_shift($XML_RPC_xh[$parser]['valuestack']);
        if (is_array($cur_val)) {
            if ($cur_val['members']==0) {
                $cur_val['value'][] = $temp;
            } else {
                $XML_RPC_xh[$parser]['value'] = $temp;
            }
            array_unshift($XML_RPC_xh[$parser]['valuestack'], $cur_val);
        } else {
            $XML_RPC_xh[$parser]['value'] = $temp;
        }
        break;

    case 'MEMBER':
        $XML_RPC_xh[$parser]['ac'] = '';
        $XML_RPC_xh[$parser]['qt'] = 0;

        $cur_val = array_shift($XML_RPC_xh[$parser]['valuestack']);
        if (is_array($cur_val)) {
            if ($cur_val['members']==1) {
                $cur_val['value'][$cur_val['name']] = $XML_RPC_xh[$parser]['value'];
            }
            array_unshift($XML_RPC_xh[$parser]['valuestack'], $cur_val);
        }
        break;

    case 'DATA':
        $XML_RPC_xh[$parser]['ac'] = '';
        $XML_RPC_xh[$parser]['qt'] = 0;
        break;

    case 'PARAM':
        $XML_RPC_xh[$parser]['params'][] = $XML_RPC_xh[$parser]['value'];
        break;

    case 'METHODNAME':
    case 'RPCMETHODNAME':
        $XML_RPC_xh[$parser]['method'] = preg_match_replace("^[\n\r\t ]+", '',
                                                      $XML_RPC_xh[$parser]['ac']);
        break;
    }

    // if it's a valid type name, set the type
    if (isset($XML_RPC_Types[strtolower($name)])) {
        $XML_RPC_xh[$parser]['vt'] = strtolower($name);
    }
}

/**
 * Character data handler for the XML parser
 *
 * @return void
 */
function XML_RPC_cd($parser_resource, $data)
{
    global $XML_RPC_xh, $XML_RPC_backslash;
    $parser = (int) $parser_resource;

    if ($XML_RPC_xh[$parser]['lv'] != 3) {
        // "lookforvalue==3" means that we've found an entire value
        // and should discard any further character data

        if ($XML_RPC_xh[$parser]['lv'] == 1) {
            // if we've found text and we're just in a <value> then
            // turn quoting on, as this will be a string
            $XML_RPC_xh[$parser]['qt'] = 1;
            // and say we've found a value
            $XML_RPC_xh[$parser]['lv'] = 2;
        }

        // replace characters that eval would
        // do special things with
        if (!isset($XML_RPC_xh[$parser]['ac'])) {
            $XML_RPC_xh[$parser]['ac'] = '';
        }
        $XML_RPC_xh[$parser]['ac'] .= $data;
    }
}

/**
 * The common methods and properties for all of the XML_RPC classes
 *
 * @category   Web Services
 * @package    XML_RPC
 * @author     Edd Dumbill <edd@usefulinc.com>
 * @author     Stig Bakken <stig@php.net>
 * @author     Martin Jansen <mj@php.net>
 * @author     Daniel Convissor <danielc@php.net>
 * 
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/XML_RPC
 */
class XML_RPC_Base {

    /**
     * PEAR Error handling
     *
     * @return object  PEAR_Error object
     */
    function raiseError($msg, $code)
    {
        include_once 'PEAR.php';
        if (is_object(@$this)) {
            return PEAR::raiseError(get_class($this) . ': ' . $msg, $code);
        } else {
            return PEAR::raiseError('XML_RPC: ' . $msg, $code);
        }
    }

    /**
     * Tell whether something is a PEAR_Error object
     *
     * @param mixed $value  the item to check
     *
     * @return bool  whether $value is a PEAR_Error object or not
     *
     * @access public
     */
    function isError($value)
    {
        return is_a($value, 'PEAR_Error');
    }
}

/**
 * The methods and properties for submitting XML RPC requests
 *
 * @category   Web Services
 * @package    XML_RPC
 * @author     Edd Dumbill <edd@usefulinc.com>
 * @author     Stig Bakken <stig@php.net>
 * @author     Martin Jansen <mj@php.net>
 * @author     Daniel Convissor <danielc@php.net>
 * 
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/XML_RPC
 */
class XML_RPC_Client extends XML_RPC_Base {

    /**
     * The path and name of the RPC server script you want the request to go to
     * @var string
     */
    var $path = '';

    /**
     * The name of the remote server to connect to
     * @var string
     */
    var $server = '';

    /**
     * The protocol to use in contacting the remote server
     * @var string
     */
    var $protocol = 'http://';

    /**
     * The port for connecting to the remote server
     *
     * The default is 80 for http:// connections
     * and 443 for https:// and ssl:// connections.
     *
     * @var integer
     */
    var $port = 80;

    /**
     * A user name for accessing the RPC server
     * @var string
     * @see XML_RPC_Client::setCredentials()
     */
    var $username = '';

    /**
     * A password for accessing the RPC server
     * @var string
     * @see XML_RPC_Client::setCredentials()
     */
    var $password = '';

    /**
     * The name of the proxy server to use, if any
     * @var string
     */
    var $proxy = '';

    /**
     * The protocol to use in contacting the proxy server, if any
     * @var string
     */
    var $proxy_protocol = 'http://';

    /**
     * The port for connecting to the proxy server
     *
     * The default is 8080 for http:// connections
     * and 443 for https:// and ssl:// connections.
     *
     * @var integer
     */
    var $proxy_port = 8080;

    /**
     * A user name for accessing the proxy server
     * @var string
     */
    var $proxy_user = '';

    /**
     * A password for accessing the proxy server
     * @var string
     */
    var $proxy_pass = '';

    /**
     * The error number, if any
     * @var integer
     */
    var $errno = 0;

    /**
     * The error message, if any
     * @var string
     */
    var $errstr = '';

    /**
     * The current debug mode (1 = on, 0 = off)
     * @var integer
     */
    var $debug = 0;

    /**
     * The HTTP headers for the current request.
     * @var string
     */
    var $headers = '';


    /**
     * Sets the object's properties
     *
     * @param string  $path        the path and name of the RPC server script
     *                              you want the request to go to
     * @param string  $server      the URL of the remote server to connect to.
     *                              If this parameter doesn't specify a
     *                              protocol and $port is 443, ssl:// is
     *                              assumed.
     * @param integer $port        a port for connecting to the remote server.
     *                              Defaults to 80 for http:// connections and
     *                              443 for https:// and ssl:// connections.
     * @param string  $proxy       the URL of the proxy server to use, if any.
     *                              If this parameter doesn't specify a
     *                              protocol and $port is 443, ssl:// is
     *                              assumed.
     * @param integer $proxy_port  a port for connecting to the remote server.
     *                              Defaults to 8080 for http:// connections and
     *                              443 for https:// and ssl:// connections.
     * @param string  $proxy_user  a user name for accessing the proxy server
     * @param string  $proxy_pass  a password for accessing the proxy server
     *
     * @return void
     */
    function XML_RPC_Client($path, $server, $port = 0,
                            $proxy = '', $proxy_port = 0,
                            $proxy_user = '', $proxy_pass = '')
    {
        $this->path       = $path;
        $this->proxy_user = $proxy_user;
        $this->proxy_pass = $proxy_pass;

        preg_match('@^(http://|https://|ssl://|sslv2://)?(.*)$@', $server, $match);
	if ($match[1] == '') {
		if ($port == 443) {
			$this->server = $match[2];
			$this->protocol = 'sslv3://';
			$this->port = 443;
		} else {
			$this->server = $match[2];
			if ($port) {
				$this->port = $port;
			}
		}
	} elseif ($match[1] == 'http://') {
		$this->server = $match[2];
		if ($port) {
		$this->port = $port;
		}
	} else {
		$this->server = $match[2];
		$this->protocol = 'sslv3://';
		if ($port) {
			$this->port = $port;
		} else {
			$this->port = 443;
		}
	}

        if ($proxy) {
            preg_match('@^(http://|https://|ssl://)?(.*)$@', $proxy, $match);
            if ($match[1] == '') {
                if ($proxy_port == 443) {
                    $this->proxy          = $match[2];
                    $this->proxy_protocol = 'sslv3://';
                    $this->proxy_port     = 443;
                } else {
                    $this->proxy = $match[2];
                    if ($proxy_port) {
                        $this->proxy_port = $proxy_port;
                    }
                }
            } elseif ($match[1] == 'http://') {
                $this->proxy = $match[2];
                if ($proxy_port) {
                    $this->proxy_port = $proxy_port;
                }
            } else {
                $this->proxy          = $match[2];
                $this->proxy_protocol = 'sslv3://';
                if ($proxy_port) {
                    $this->proxy_port = $proxy_port;
                } else {
                    $this->proxy_port = 443;
                }
            }
        }
    }

    /**
     * Change the current debug mode
     *
     * @param int $in  where 1 = on, 0 = off
     *
     * @return void
     */
    function setDebug($in)
    {
        if ($in) {
            $this->debug = 1;
        } else {
            $this->debug = 0;
        }
    }

    /**
     * Set username and password properties for connecting to the RPC server
     *
     * @param string $u  the user name
     * @param string $p  the password
     *
     * @return void
     *
     * @see XML_RPC_Client::$username, XML_RPC_Client::$password
     */
    function setCredentials($u, $p)
    {
        $this->username = $u;
        $this->password = $p;
    }

    /**
     * Transmit the RPC request via HTTP 1.0 protocol
     *
     * @param object $msg       the XML_RPC_Message object
     * @param int    $timeout   how many seconds to wait for the request
     *
     * @return object  an XML_RPC_Response object.  0 is returned if any
     *                  problems happen.
     *
     * @see XML_RPC_Message, XML_RPC_Client::XML_RPC_Client(),
     *      XML_RPC_Client::setCredentials()
     */
    function send($msg, $timeout = 0)
    {
        if (!is_a($msg, 'XML_RPC_Message')) {
            $this->errstr = 'send()\'s $msg parameter must be an'
                          . ' XML_RPC_Message object.';
            $this->raiseError($this->errstr, XML_RPC_ERROR_PROGRAMMING);
            return 0;
        }
        $msg->debug = $this->debug;
        return $this->sendPayloadHTTP10($msg, $this->server, $this->port,
                                        $timeout, $this->username,
                                        $this->password);
    }

    /**
     * Transmit the RPC request via HTTP 1.0 protocol
     *
     * Requests should be sent using XML_RPC_Client send() rather than
     * calling this method directly.
     *
     * @param object $msg       the XML_RPC_Message object
     * @param string $server    the server to send the request to
     * @param int    $port      the server port send the request to
     * @param int    $timeout   how many seconds to wait for the request
     *                           before giving up
     * @param string $username  a user name for accessing the RPC server
     * @param string $password  a password for accessing the RPC server
     *
     * @return object  an XML_RPC_Response object.  0 is returned if any
     *                  problems happen.
     *
     * @access protected
     * @see XML_RPC_Client::send()
     */
    function sendPayloadHTTP10($msg, $server, $port, $timeout = 0,
                               $username = '', $password = '')
    {
        /*
         * If we're using a proxy open a socket to the proxy server
         * instead to the xml-rpc server
         */
        if ($this->proxy) {
            if ($this->proxy_protocol == 'http://') {
                $protocol = '';
            } else {
                $protocol = $this->proxy_protocol;
            }
            if ($timeout > 0) {
                $fp = @fsockopen($protocol . $this->proxy, $this->proxy_port,
                                 $this->errno, $this->errstr, $timeout);
            } else {
                $fp = @fsockopen($protocol . $this->proxy, $this->proxy_port,
                                 $this->errno, $this->errstr);
            }
        } else {
            if ($this->protocol == 'http://') {
                $protocol = '';
            } else {
                $protocol = $this->protocol;
            }
            if ($timeout > 0) {
                $fp = @fsockopen($protocol . $server, $port,
                                 $this->errno, $this->errstr, $timeout);
            } else {
                $fp = @fsockopen($protocol . $server, $port,
                                 $this->errno, $this->errstr);
            }
        }

        /*
         * Just raising the error without returning it is strange,
         * but keep it here for backwards compatibility.
         */
        if (!$fp && $this->proxy) {
            $this->raiseError('Connection to proxy server '
                              . $this->proxy . ':' . $this->proxy_port
                              . ' failed. ' . $this->errstr,
                              XML_RPC_ERROR_CONNECTION_FAILED);
            return 0;
        } elseif (!$fp) {
            $this->raiseError('Connection to RPC server '
                              . $server . ':' . $port
                              . ' failed. ' . $this->errstr,
                              XML_RPC_ERROR_CONNECTION_FAILED);
            return 0;
        }

        if ($timeout) {
            /*
             * Using socket_set_timeout() because stream_set_timeout()
             * was introduced in 4.3.0, but we need to support 4.2.0.
             */
            socket_set_timeout($fp, $timeout);
        }

        // Pre-emptive BC hacks for fools calling sendPayloadHTTP10() directly
        if ($username != $this->username) {
            $this->setCredentials($username, $password);
        }

        // Only create the payload if it was not created previously
        if (empty($msg->payload)) {
            $msg->createPayload();
        }
        $this->createHeaders($msg);

        $op  = $this->headers . "\r\n\r\n";
        $op .= $msg->payload;

        if (!fputs($fp, $op, strlen($op))) {
            $this->errstr = 'Write error';
            return 0;
        }
        $resp = $msg->parseResponseFile($fp);

        $meta = socket_get_status($fp);
        if ($meta['timed_out']) {
            fclose($fp);
            $this->errstr = 'RPC server did not send response before timeout.';
            $this->raiseError($this->errstr, XML_RPC_ERROR_CONNECTION_FAILED);
            return 0;
        }

        fclose($fp);
        return $resp;
    }

    /**
     * Determines the HTTP headers and puts it in the $headers property
     *
     * @param object $msg       the XML_RPC_Message object
     *
     * @return boolean  TRUE if okay, FALSE if the message payload isn't set.
     *
     * @access protected
     */
    function createHeaders($msg)
    {
        if (empty($msg->payload)) {
            return false;
        }
        if ($this->proxy) {
            $this->headers = 'POST ' . $this->protocol . $this->server;
            if ($this->proxy_port) {
                $this->headers .= ':' . $this->port;
            }
        } else {
           $this->headers = 'POST ';
        }
        $this->headers .= $this->path. " HTTP/1.0\r\n";

        $this->headers .= "User-Agent: PEAR XML_RPC\r\n";
        $this->headers .= 'Host: ' . $this->server . "\r\n";

        if ($this->proxy && $this->proxy_user) {
            $this->headers .= 'Proxy-Authorization: Basic '
                     . base64_encode("$this->proxy_user:$this->proxy_pass")
                     . "\r\n";
        }

        // thanks to Grant Rauscher <grant7@firstworld.net> for this
        if ($this->username) {
            $this->headers .= 'Authorization: Basic '
                     . base64_encode("$this->username:$this->password")
                     . "\r\n";
        }

        $this->headers .= "Content-Type: text/xml\r\n";
        $this->headers .= 'Content-Length: ' . strlen($msg->payload);
        return true;
    }
}

/**
 * The methods and properties for interpreting responses to XML RPC requests
 *
 * @category   Web Services
 * @package    XML_RPC
 * @author     Edd Dumbill <edd@usefulinc.com>
 * @author     Stig Bakken <stig@php.net>
 * @author     Martin Jansen <mj@php.net>
 * @author     Daniel Convissor <danielc@php.net>
 * 
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/XML_RPC
 */
class XML_RPC_Response extends XML_RPC_Base
{
    var $xv;
    var $fn;
    var $fs;
    var $hdrs;

    /**
     * @return void
     */
    function XML_RPC_Response($val, $fcode = 0, $fstr = '')
    {
        if ($fcode != 0) {
            $this->fn = $fcode;
            $this->fs = htmlspecialchars($fstr);
        } else {
            $this->xv = $val;
        }
    }

    /**
     * @return int  the error code
     */
    function faultCode()
    {
        if (isset($this->fn)) {
            return $this->fn;
        } else {
            return 0;
        }
    }

    /**
     * @return string  the error string
     */
    function faultString()
    {
        return $this->fs;
    }

    /**
     * @return mixed  the value
     */
    function value()
    {
        return $this->xv;
    }

    /**
     * @return string  the error message in XML format
     */
    function serialize()
    {
        $rs = "<methodResponse>\n";
        if ($this->fn) {
            $rs .= "<fault>
  <value>
    <struct>
      <member>
        <name>faultCode</name>
        <value><int>" . $this->fn . "</int></value>
      </member>
      <member>
        <name>faultString</name>
        <value><string>" . $this->fs . "</string></value>
      </member>
    </struct>
  </value>
</fault>";
        } else {
            $rs .= "<params>\n<param>\n" . $this->xv->serialize() .
        "</param>\n</params>";
        }
        $rs .= "\n</methodResponse>";
        return $rs;
    }
}

/**
 * The methods and properties for composing XML RPC messages
 *
 * @category   Web Services
 * @package    XML_RPC
 * @author     Edd Dumbill <edd@usefulinc.com>
 * @author     Stig Bakken <stig@php.net>
 * @author     Martin Jansen <mj@php.net>
 * @author     Daniel Convissor <danielc@php.net>
 * 
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/XML_RPC
 */
class XML_RPC_Message extends XML_RPC_Base
{
    /**
     * The current debug mode (1 = on, 0 = off)
     * @var integer
     */
    var $debug = 0;

    /**
     * The encoding to be used for outgoing messages
     *
     * Defaults to the value of <var>$GLOBALS['XML_RPC_defencoding']</var>
     *
     * @var string
     * @see XML_RPC_Message::setSendEncoding(),
     *      $GLOBALS['XML_RPC_defencoding'], XML_RPC_Message::xml_header()
     */
    var $send_encoding = '';

    /**
     * The method presently being evaluated
     * @var string
     */
    var $methodname = '';

    /**
     * @var array
     */
    var $params = array();

    /**
     * The XML message being generated
     * @var string
     */
    var $payload = '';

    /**
     * @return void
     */
    function XML_RPC_Message($meth, $pars = 0)
    {
        $this->methodname = $meth;
        if (is_array($pars) && sizeof($pars) > 0) {
            for ($i = 0; $i < sizeof($pars); $i++) {
                $this->addParam($pars[$i]);
            }
        }
    }

    /**
     * Produces the XML declaration including the encoding attribute
     *
     * The encoding is determined by this class' <var>$send_encoding</var>
     * property.  If the <var>$send_encoding</var> property is not set, use
     * <var>$GLOBALS['XML_RPC_defencoding']</var>.
     *
     * @return string  the XML declaration and <methodCall> element
     *
     * @see XML_RPC_Message::setSendEncoding(),
     *      XML_RPC_Message::$send_encoding, $GLOBALS['XML_RPC_defencoding']
     */
    function xml_header()
    {
        global $XML_RPC_defencoding;
        if (!$this->send_encoding) {
            $this->send_encoding = $XML_RPC_defencoding;
        }
        return '<?xml version="1.0" encoding="' . $this->send_encoding . '"?>'
               . "\n<methodCall>\n";
    }

    /**
     * @return string  the closing </methodCall> tag
     */
    function xml_footer()
    {
        return "</methodCall>\n";
    }

    /**
     * @return void
     *
     * @uses XML_RPC_Message::xml_header(), XML_RPC_Message::xml_footer()
     */
    function createPayload()
    {
        $this->payload = $this->xml_header();
        $this->payload .= '<methodName>' . $this->methodname . "</methodName>\n";
        $this->payload .= "<params>\n";
        for ($i = 0; $i < sizeof($this->params); $i++) {
            $p = $this->params[$i];
            $this->payload .= "<param>\n" . $p->serialize() . "</param>\n";
        }
        $this->payload .= "</params>\n";
        $this->payload .= $this->xml_footer();
        $this->payload =  preg_replace("/[\r\n]+/", "\r\n", $this->payload);
    }

    /**
     * @return string  the name of the method
     */
    function method($meth = '')
    {
        if ($meth != '') {
            $this->methodname = $meth;
        }
        return $this->methodname;
    }

    /**
     * @return string  the payload
     */
    function serialize()
    {
        $this->createPayload();
        return $this->payload;
    }

    /**
     * @return void
     */
    function addParam($par)
    {
        $this->params[] = $par;
    }

    /**
     * Obtains an XML_RPC_Value object for the given parameter
     *
     * @param int $i  the index number of the parameter to obtain
     *
     * @return object  the XML_RPC_Value object.
     *                  If the parameter doesn't exist, an XML_RPC_Response object.
     *
     * @since Returns XML_RPC_Response object on error since Release 1.3.0
     */
    function getParam($i)
    {
        global $XML_RPC_err, $XML_RPC_str;

        if (isset($this->params[$i])) {
            return $this->params[$i];
        } else {
            $this->raiseError('The submitted request did not contain this parameter',
                              XML_RPC_ERROR_INCORRECT_PARAMS);
            return new XML_RPC_Response(0, $XML_RPC_err['incorrect_params'],
                                        $XML_RPC_str['incorrect_params']);
        }
    }

    /**
     * @return int  the number of parameters
     */
    function getNumParams()
    {
        return sizeof($this->params);
    }

    /**
     * Sets the XML declaration's encoding attribute
     *
     * @param string $type  the encoding type (ISO-8859-1, UTF-8 or US-ASCII)
     *
     * @return void
     *
     * @see XML_RPC_Message::$send_encoding, XML_RPC_Message::xml_header()
     * @since Method available since Release 1.2.0
     */
    function setSendEncoding($type)
    {
        $this->send_encoding = $type;
    }

    /**
     * Determine the XML's encoding via the encoding attribute
     * in the XML declaration
     *
     * If the encoding parameter is not set or is not ISO-8859-1, UTF-8
     * or US-ASCII, $XML_RPC_defencoding will be returned.
     *
     * @param string $data  the XML that will be parsed
     *
     * @return string  the encoding to be used
     *
     * @link   http://php.net/xml_parser_create
     * @since  Method available since Release 1.2.0
     */
    function getEncoding($data)
    {
        global $XML_RPC_defencoding;

        if (preg_match('/<\?xml[^>]*\s*encoding\s*=\s*[\'"]([^"\']*)[\'"]/i',
                       $data, $match))
        {
            $match[1] = trim(strtoupper($match[1]));
            switch ($match[1]) {
                case 'ISO-8859-1':
                case 'UTF-8':
                case 'US-ASCII':
                    return $match[1];
                    break;

                default:
                    return $XML_RPC_defencoding;
            }
        } else {
            return $XML_RPC_defencoding;
        }
    }

    /**
     * @return object  a new XML_RPC_Response object
     */
    function parseResponseFile($fp)
    {
        $ipd = '';
        while ($data = @fread($fp, 8192)) {
            $ipd .= $data;
        }
        return $this->parseResponse($ipd);
    }

    /**
     * @return object  a new XML_RPC_Response object
     */
    function parseResponse($data = '')
    {
        global $XML_RPC_xh, $XML_RPC_err, $XML_RPC_str, $XML_RPC_defencoding;

        $encoding = $this->getEncoding($data);
        $parser_resource = xml_parser_create($encoding);
        $parser = (int) $parser_resource;

        $XML_RPC_xh = array();
        $XML_RPC_xh[$parser] = array();

        $XML_RPC_xh[$parser]['cm'] = 0;
        $XML_RPC_xh[$parser]['isf'] = 0;
        $XML_RPC_xh[$parser]['ac'] = '';
        $XML_RPC_xh[$parser]['qt'] = '';
        $XML_RPC_xh[$parser]['stack'] = array();
        $XML_RPC_xh[$parser]['valuestack'] = array();

        xml_parser_set_option($parser_resource, XML_OPTION_CASE_FOLDING, true);
        xml_set_element_handler($parser_resource, 'XML_RPC_se', 'XML_RPC_ee');
        xml_set_character_data_handler($parser_resource, 'XML_RPC_cd');

        $hdrfnd = 0;
        if ($this->debug) {
            print "\n<pre>---GOT---\n";
            print isset($_SERVER['SERVER_PROTOCOL']) ? htmlspecialchars($data) : $data;
            print "\n---END---</pre>\n";
        }

        // See if response is a 200 or a 100 then a 200, else raise error.
        // But only do this if we're using the HTTP protocol.
        if (preg_match('/^HTTP/', $data) &&
            !preg_match('/^HTTP\/[0-9\.]+ 200 /', $data) &&
            !preg_match('@^HTTP/[0-9\.]+ 10[0-9]([A-Za-z ]+)?[\r\n]+HTTP/[0-9\.]+ 200@', $data))
        {
                $errstr = substr($data, 0, strpos($data, "\n") - 1);
                error_log('HTTP error, got response: ' . $errstr);
                $r = new XML_RPC_Response(0, $XML_RPC_err['http_error'],
                                          $XML_RPC_str['http_error'] . ' (' .
                                          $errstr . ')');
                xml_parser_free($parser_resource);
                return $r;
        }

        // gotta get rid of headers here
        if (!$hdrfnd && ($brpos = strpos($data,"\r\n\r\n"))) {
            $XML_RPC_xh[$parser]['ha'] = substr($data, 0, $brpos);
            $data = substr($data, $brpos + 4);
            $hdrfnd = 1;
        }

        /*
         * be tolerant of junk after methodResponse
         * (e.g. javascript automatically inserted by free hosts)
         * thanks to Luca Mariano <luca.mariano@email.it>
         */
        $data = substr($data, 0, strpos($data, "</methodResponse>") + 17);

        if (!xml_parse($parser_resource, $data, sizeof($data))) {
            // thanks to Peter Kocks <peter.kocks@baygate.com>
            if (xml_get_current_line_number($parser_resource) == 1) {
                $errstr = 'XML error at line 1, check URL';
            } else {
                $errstr = sprintf('XML error: %s at line %d',
                                  xml_error_string(xml_get_error_code($parser_resource)),
                                  xml_get_current_line_number($parser_resource));
            }
            error_log($errstr);
            $r = new XML_RPC_Response(0, $XML_RPC_err['invalid_return'],
                                      $XML_RPC_str['invalid_return']);
            xml_parser_free($parser_resource);
            return $r;
        }

        xml_parser_free($parser_resource);

        if ($this->debug) {
            print "\n<pre>---PARSED---\n";
            var_dump($XML_RPC_xh[$parser]['value']);
            print "---END---</pre>\n";
        }

        if ($XML_RPC_xh[$parser]['isf'] > 1) {
            $r = new XML_RPC_Response(0, $XML_RPC_err['invalid_return'],
                                      $XML_RPC_str['invalid_return'].' '.$XML_RPC_xh[$parser]['isf_reason']);
        } elseif (!is_object($XML_RPC_xh[$parser]['value'])) {
            // then something odd has happened
            // and it's time to generate a client side error
            // indicating something odd went on
            $r = new XML_RPC_Response(0, $XML_RPC_err['invalid_return'],
                                      $XML_RPC_str['invalid_return']);
        } else {
            $v = $XML_RPC_xh[$parser]['value'];
            $allOK=1;
            if ($XML_RPC_xh[$parser]['isf']) {
                $f = $v->structmem('faultCode');
                $fs = $v->structmem('faultString');
                $r = new XML_RPC_Response($v, $f->scalarval(),
                                          $fs->scalarval());
            } else {
                $r = new XML_RPC_Response($v);
            }
        }
        $r->hdrs = preg_split("/\r?\n/i", $XML_RPC_xh[$parser]['ha'][1]);
        return $r;
    }
}

/**
 * The methods and properties that represent data in XML RPC format
 *
 * @category   Web Services
 * @package    XML_RPC
 * @author     Edd Dumbill <edd@usefulinc.com>
 * @author     Stig Bakken <stig@php.net>
 * @author     Martin Jansen <mj@php.net>
 * @author     Daniel Convissor <danielc@php.net>
 * 
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/XML_RPC
 */
class XML_RPC_Value extends XML_RPC_Base
{
    var $me = array();
    var $mytype = 0;

    /**
     * @return void
     */
    function XML_RPC_Value($val = -1, $type = '')
    {
        global $XML_RPC_Types;
        $this->me = array();
        $this->mytype = 0;
        if ($val != -1 || $type != '') {
            if ($type == '') {
                $type = 'string';
            }
            if (!array_key_exists($type, $XML_RPC_Types)) {
                // XXX
                // need some way to report this error
            } elseif ($XML_RPC_Types[$type] == 1) {
                $this->addScalar($val, $type);
            } elseif ($XML_RPC_Types[$type] == 2) {
                $this->addArray($val);
            } elseif ($XML_RPC_Types[$type] == 3) {
                $this->addStruct($val);
            }
        }
    }

    /**
     * @return int  returns 1 if successful or 0 if there are problems
     */
    function addScalar($val, $type = 'string')
    {
        global $XML_RPC_Types, $XML_RPC_Boolean;

        if ($this->mytype == 1) {
            $this->raiseError('Scalar can have only one value',
                              XML_RPC_ERROR_INVALID_TYPE);
            return 0;
        }
        $typeof = $XML_RPC_Types[$type];
        if ($typeof != 1) {
            $this->raiseError("Not a scalar type (${typeof})",
                              XML_RPC_ERROR_INVALID_TYPE);
            return 0;
        }

        if ($type == $XML_RPC_Boolean) {
            if (strcasecmp($val, 'true') == 0
                || $val == 1
                || ($val == true && strcasecmp($val, 'false')))
            {
                $val = 1;
            } else {
                $val = 0;
            }
        }

        if ($this->mytype == 2) {
            // we're adding to an array here
            $ar = $this->me['array'];
            $ar[] = new XML_RPC_Value($val, $type);
            $this->me['array'] = $ar;
        } else {
            // a scalar, so set the value and remember we're scalar
            $this->me[$type] = $val;
            $this->mytype = $typeof;
        }
        return 1;
    }

    /**
     * @return int  returns 1 if successful or 0 if there are problems
     */
    function addArray($vals)
    {
        global $XML_RPC_Types;
        if ($this->mytype != 0) {
            $this->raiseError(
                    'Already initialized as a [' . $this->kindOf() . ']',
                    XML_RPC_ERROR_ALREADY_INITIALIZED);
            return 0;
        }
        $this->mytype = $XML_RPC_Types['array'];
        $this->me['array'] = $vals;
        return 1;
    }

    /**
     * @return int  returns 1 if successful or 0 if there are problems
     */
    function addStruct($vals)
    {
        global $XML_RPC_Types;
        if ($this->mytype != 0) {
            $this->raiseError(
                    'Already initialized as a [' . $this->kindOf() . ']',
                    XML_RPC_ERROR_ALREADY_INITIALIZED);
            return 0;
        }
        $this->mytype = $XML_RPC_Types['struct'];
        $this->me['struct'] = $vals;
        return 1;
    }

    /**
     * @return void
     */
    function dump($ar)
    {
        reset($ar);
        foreach ($ar as $key => $val) {
            echo "$key => $val<br />";
            if ($key == 'array') {
                foreach ($val as $key2 => $val2) {
                    echo "-- $key2 => $val2<br />";
                }
            }
        }
    }

    /**
     * @return string  the data type of the current value
     */
    function kindOf()
    {
        switch ($this->mytype) {
        case 3:
            return 'struct';

        case 2:
            return 'array';

        case 1:
            return 'scalar';

        default:
            return 'undef';
        }
    }

    /**
     * @return string  the data in XML format
     */
    function serializedata($typ, $val)
    {
        $rs = '';
        global $XML_RPC_Types, $XML_RPC_Base64, $XML_RPC_String, $XML_RPC_Boolean;
        if (!array_key_exists($typ, $XML_RPC_Types)) {
            // XXX
            // need some way to report this error
            return;
        }
        switch ($XML_RPC_Types[$typ]) {
        case 3:
            // struct
            $rs .= "<struct>\n";
            reset($val);
            foreach ($val as $key2 => $val2) {
                $rs .= "<member><name>${key2}</name>\n";
                $rs .= $this->serializeval($val2);
                $rs .= "</member>\n";
            }
            $rs .= '</struct>';
            break;

        case 2:
            // array
            $rs .= "<array>\n<data>\n";
            for ($i = 0; $i < sizeof($val); $i++) {
                $rs .= $this->serializeval($val[$i]);
            }
            $rs .= "</data>\n</array>";
            break;

        case 1:
            switch ($typ) {
            case $XML_RPC_Base64:
                $rs .= "<${typ}>" . base64_encode($val) . "</${typ}>";
                break;
            case $XML_RPC_Boolean:
                $rs .= "<${typ}>" . ($val ? '1' : '0') . "</${typ}>";
                break;
            case $XML_RPC_String:
                $rs .= "<${typ}>" . htmlspecialchars($val). "</${typ}>";
                break;
            default:
                $rs .= "<${typ}>${val}</${typ}>";
            }
        }
        return $rs;
    }

    /**
     * @return string  the data in XML format
     */
    function serialize()
    {
        return $this->serializeval($this);
    }

    /**
     * @return string  the data in XML format
     */
    function serializeval($o)
    {
        if (!is_object($o) || empty($o->me) || !is_array($o->me)) {
            return '';
        }
        $ar = $o->me;
        reset($ar);
        list($typ, $val) = each($ar);
        return '<value>' .  $this->serializedata($typ, $val) .  "</value>\n";
    }

    /**
     * @return mixed  the contents of the element requested
     */
    function structmem($m)
    {
        return $this->me['struct'][$m];
    }

    /**
     * @return void
     */
    function structreset()
    {
        reset($this->me['struct']);
    }

    /**
     * @return  the key/value pair of the struct's current element
     */
    function structeach()
    {
        return each($this->me['struct']);
    }

    /**
     * @return mixed  the current value
     */
    function getval()
    {
        // UNSTABLE
        global $XML_RPC_BOOLEAN, $XML_RPC_Base64;

        reset($this->me);
        $b = current($this->me);

        // contributed by I Sofer, 2001-03-24
        // add support for nested arrays to scalarval
        // i've created a new method here, so as to
        // preserve back compatibility

        if (is_array($b)) {
            foreach ($b as $id => $cont) {
                $b[$id] = $cont->scalarval();
            }
        }

        // add support for structures directly encoding php objects
        if (is_object($b)) {
            $t = get_object_vars($b);
            foreach ($t as $id => $cont) {
                $t[$id] = $cont->scalarval();
            }
            foreach ($t as $id => $cont) {
                $b->$id = $cont;
            }
        }

        // end contrib
        return $b;
    }

    /**
     * @return mixed
     */
    function scalarval()
    {
        global $XML_RPC_Boolean, $XML_RPC_Base64;
        reset($this->me);
        return current($this->me);
    }

    /**
     * @return string
     */
    function scalartyp()
    {
        global $XML_RPC_I4, $XML_RPC_Int;
        reset($this->me);
        $a = key($this->me);
        if ($a == $XML_RPC_I4) {
            $a = $XML_RPC_Int;
        }
        return $a;
    }

    /**
     * @return mixed  the struct's current element
     */
    function arraymem($m)
    {
        return $this->me['array'][$m];
    }

    /**
     * @return int  the number of elements in the array
     */
    function arraysize()
    {
        reset($this->me);
        list($a, $b) = each($this->me);
        return sizeof($b);
    }

    /**
     * Determines if the item submitted is an XML_RPC_Value object
     *
     * @param mixed $val  the variable to be evaluated
     *
     * @return bool  TRUE if the item is an XML_RPC_Value object
     *
     * @static
     * @since Method available since Release 1.3.0
     */
    function isValue($val)
    {
        return (strtolower(get_class($val)) == 'xml_rpc_value');
    }
}

/**
 * Return an ISO8601 encoded string
 *
 * While timezones ought to be supported, the XML-RPC spec says:
 *
 * "Don't assume a timezone. It should be specified by the server in its
 * documentation what assumptions it makes about timezones."
 *
 * This routine always assumes localtime unless $utc is set to 1, in which
 * case UTC is assumed and an adjustment for locale is made when encoding.
 *
 * @return string  the formatted date
 */
function XML_RPC_iso8601_encode($timet, $utc = 0)
{
    if (!$utc) {
        $t = strftime('%Y%m%dT%H:%M:%S', $timet);
    } else {
        if (function_exists('gmstrftime')) {
            // gmstrftime doesn't exist in some versions
            // of PHP
            $t = gmstrftime('%Y%m%dT%H:%M:%S', $timet);
        } else {
            $t = strftime('%Y%m%dT%H:%M:%S', $timet - date('Z'));
        }
    }
    return $t;
}

/**
 * Convert a datetime string into a Unix timestamp
 *
 * While timezones ought to be supported, the XML-RPC spec says:
 *
 * "Don't assume a timezone. It should be specified by the server in its
 * documentation what assumptions it makes about timezones."
 *
 * This routine always assumes localtime unless $utc is set to 1, in which
 * case UTC is assumed and an adjustment for locale is made when encoding.
 *
 * @return int  the unix timestamp of the date submitted
 */
function XML_RPC_iso8601_decode($idate, $utc = 0)
{
    $t = 0;
    if (preg_match('/([0-9]{4})([0-9]{2})([0-9]{2})T([0-9]{2}):([0-9]{2}):([0-9]{2})/', $idate, $regs)) {
        if ($utc) {
            $t = gmmktime($regs[4], $regs[5], $regs[6], $regs[2], $regs[3], $regs[1]);
        } else {
            $t = mktime($regs[4], $regs[5], $regs[6], $regs[2], $regs[3], $regs[1]);
        }
    }
    return $t;
}

/**
 * Converts an XML_RPC_Value object into native PHP types
 *
 * @param object $XML_RPC_val  the XML_RPC_Value object to decode
 *
 * @return mixed  the PHP values
 */
function XML_RPC_decode($XML_RPC_val)
{
    $kind = $XML_RPC_val->kindOf();

    if ($kind == 'scalar') {
        return $XML_RPC_val->scalarval();

    } elseif ($kind == 'array') {
        $size = $XML_RPC_val->arraysize();
        $arr = array();
        for ($i = 0; $i < $size; $i++) {
            $arr[] = XML_RPC_decode($XML_RPC_val->arraymem($i));
        }
        return $arr;

    } elseif ($kind == 'struct') {
        $XML_RPC_val->structreset();
        $arr = array();
        while (list($key, $value) = $XML_RPC_val->structeach()) {
            $arr[$key] = XML_RPC_decode($value);
        }
        return $arr;
    }
}

/**
 * Converts native PHP types into an XML_RPC_Value object
 *
 * @param mixed $php_val  the PHP value or variable you want encoded
 *
 * @return object  the XML_RPC_Value object
 */
function XML_RPC_encode($php_val)
{
    global $XML_RPC_Boolean, $XML_RPC_Int, $XML_RPC_Double, $XML_RPC_String,
           $XML_RPC_Array, $XML_RPC_Struct, $XML_RPC_DateTime;

    $type = gettype($php_val);
    $XML_RPC_val = new XML_RPC_Value;

    switch ($type) {
    case 'array':
        if (empty($php_val)) {
            $XML_RPC_val->addArray($php_val);
            break;
        }
        $tmp = array_diff(array_keys($php_val), range(0, count($php_val)-1));
        if (empty($tmp)) {
           $arr = array();
           foreach ($php_val as $k => $v) {
               $arr[$k] = XML_RPC_encode($v);
           }
           $XML_RPC_val->addArray($arr);
           break;
        }
        // fall though if it's not an enumerated array

    case 'object':
        $arr = array();
        foreach ($php_val as $k => $v) {
            $arr[$k] = XML_RPC_encode($v);
        }
        $XML_RPC_val->addStruct($arr);
        break;

    case 'integer':
        $XML_RPC_val->addScalar($php_val, $XML_RPC_Int);
        break;

    case 'double':
        $XML_RPC_val->addScalar($php_val, $XML_RPC_Double);
        break;

    case 'string':
    case 'NULL':
        if(preg_match('/^[0-9]{8}\T{1}[0-9]{2}\:[0-9]{2}\:[0-9]{2}$/', $php_val)) {
            $XML_RPC_val->addScalar($php_val, $XML_RPC_DateTime);
        } else {
            $XML_RPC_val->addScalar($php_val, $XML_RPC_String);
        }
        break;

    case 'boolean':
        // Add support for encoding/decoding of booleans, since they
        // are supported in PHP
        // by <G_Giunta_2001-02-29>
        $XML_RPC_val->addScalar($php_val, $XML_RPC_Boolean);
        break;

    case 'unknown type':
    default:
        $XML_RPC_val = false;
    }
    return $XML_RPC_val;
}
