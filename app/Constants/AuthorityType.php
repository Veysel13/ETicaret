<?php


namespace App\Constants;


class AuthorityType
{

    const BACKEND = 1;


    const DASHBOARD = 1;
    const USER  = 2;
    const USEREDIT  = 3;
    const USERDELETE  = 4;
    const RESTAURANT = 5;
    const STOREEDIT = 6;
    const STOREDELETE = 7;
    const BRAND = 8;
    const BRANDEDIT = 9;
    const BRANDDELETE = 10;
    const PRODUCT = 11;
    const PRODUCTEDIT = 12;
    const PRODUCTDELETE = 13;
    const COMPANY = 14;
    const COMPANYEDIT = 15;
    const COMPANYDELETE = 16;
    const AMAZONPRODUCT = 17;
    const AMAZONPRODUCTEDIT = 18;
    const AMAZONPRODUCTEDELETE = 19;
    const SALEORDER = 20;
    const RECEIPTENTRY = 21;
    const BARCODESCANN = 22;
    const RECEIVEDPRODUCT = 23;
    const RECEIPTREPORT = 24;
    const USERREPORT = 25;
    const STOREREPORT = 26;
    const RECEIVEDSTATUS = 27;
    const ERECEIPTSTATUS = 28;
    const ALLORDERLIST = 29;
    const RECEIVEDITEM = 30;
    const ORDERTARGET = 31;
    const RECEIPTREPORTALL = 33;
    const USERREPORTALL = 34;
    const STOREREPORTALL = 35;
    const SALEORDERDELETE = 36;
    const SALEORDERVIEW = 37;
    const PRODUCTTASKLIST = 38;
    const PRODUCTTASKEDIT = 39;
    const PRODUCTTASKDELETE = 39;
    const STOREALL = 40;
    const SALEORDEREDIT = 41;
    const ANNOUNCEMENTSEND = 42;
    const MISSINGREPORTALL = 43;
    const MISSINGREPORT = 44;
    const PRODUCTSTOCK = 45;
    const RECEIVEDREPORT = 46;
    const RECEIVEDREPORTALL = 47;
    const DASHBOARDALL = 48;
    const OFFPRODUCTTASKLIST = 49;
    const NOTFOUNDPRODUCT = 50;
    const AMAZONPRODUCTAPPROVE = 51;
    const AMAZONPRODUCTAPPROVELIST = 52;
    const AMAZONPRODUCTAPPROVEEDIT = 53;
    const AMAZONPRODUCTAPPROVEDELETE = 54;
    const ASINNOMATCH = 55;
    const ASINREQUEST = 56;
    const USERAUTHORITY = 57;
    const PREORDERVIEW = 58;
    const PRODUCTTASKLINK = 59;
    const SALEORDERDUBLICATE = 60;
    const SALEORDERTRACKINGNUMBER = 61;
    const PRODUCTTASKTOPORDER = 62;
    const PRODUCTTASKSTOREVIEW = 63;


    const authorityList=[
        ['id'=>self::DASHBOARD,'name'=>'DASHBOARD'],
        ['id'=>self::USER,'name'=>'USER'],
        ['id'=>self::USEREDIT,'name'=>'USER EDIT'],
        ['id'=>self::USERDELETE,'name'=>'USER DELETE'],
        ['id'=>self::STORE,'name'=>'STORE'],
        ['id'=>self::STOREALL,'name'=>'STORE ALL'],
        ['id'=>self::STOREEDIT,'name'=>'STORE EDIT'],
        ['id'=>self::STOREDELETE,'name'=>'STORE DELETE'],
        ['id'=>self::BRAND,'name'=>'BRAND'],
        ['id'=>self::BRANDEDIT,'name'=>'BRAND EDIT'],
        ['id'=>self::BRANDDELETE,'name'=>'BRAND DELETE'],
        ['id'=>self::PRODUCT,'name'=>'PRODUCT'],
        ['id'=>self::PRODUCTEDIT,'name'=>'PRODUCT EDIT'],
        ['id'=>self::PRODUCTDELETE,'name'=>'PRODUCT DELETE'],
        ['id'=>self::COMPANY,'name'=>'COMPANY'],
        ['id'=>self::COMPANYEDIT,'name'=>'COMPANY EDIT'],
        ['id'=>self::COMPANYDELETE,'name'=>'COMPANY DELETE'],
        ['id'=>self::AMAZONPRODUCT,'name'=>'AMAZON PRODUCT'],
        ['id'=>self::AMAZONPRODUCTEDIT,'name'=>'AMAZON PRODUCT EDIT'],
        ['id'=>self::AMAZONPRODUCTEDELETE,'name'=>'AMAZON PRODUCT DELETE'],
        ['id'=>self::SALEORDER,'name'=>'SALE ORDER'],
        ['id'=>self::RECEIPTENTRY,'name'=>'RECEIPT ENTRY'],
        ['id'=>self::RECEIVEDITEM,'name'=>'RECEIVED ITEM'],
        ['id'=>self::RECEIVEDPRODUCT,'name'=>'RECEIVED PRODUCT'],
        ['id'=>self::BARCODESCANN,'name'=>'BARCODE SCANN'],
        ['id'=>self::RECEIPTREPORT,'name'=>'RECEIPT REPORT'],
        ['id'=>self::RECEIPTREPORTALL,'name'=>'RECEIPT REPORT ALL'],
        ['id'=>self::USERREPORT,'name'=>'USER REPORT'],
        ['id'=>self::USERREPORTALL,'name'=>'USER REPORT ALL'],
        ['id'=>self::STOREREPORT,'name'=>'STORE REPORT'],
        ['id'=>self::STOREREPORTALL,'name'=>'STORE REPORT ALL'],
        ['id'=>self::RECEIVEDSTATUS,'name'=>'RECEIVED STATUS'],
        ['id'=>self::ERECEIPTSTATUS,'name'=>'ERECEIPT STATUS'],
        ['id'=>self::ALLORDERLIST,'name'=>'ALL ORDER LIST'],
        ['id'=>self::ORDERTARGET,'name'=>'ORDER TARGET'],
        ['id'=>self::SALEORDERVIEW,'name'=>'SALE ORDER VIEW'],
        ['id'=>self::SALEORDEREDIT,'name'=>'SALE ORDER EDIT'],
        ['id'=>self::SALEORDERDELETE,'name'=>'SALE ORDER DELETE'],
        ['id'=>self::PRODUCTTASKLIST,'name'=>'PRODUCT TASK LIST'],
        ['id'=>self::PRODUCTTASKEDIT,'name'=>'PRODUCT TASK EDIT'],
        ['id'=>self::PRODUCTTASKDELETE,'name'=>'PRODUCT TASK DELETE'],
        ['id'=>self::ANNOUNCEMENTSEND,'name'=>'ANNOUNCEMENT SENDER'],
        ['id'=>self::MISSINGREPORT,'name'=>'MISSING REPORT'],
        ['id'=>self::MISSINGREPORTALL,'name'=>'MISSING REPORT ALL'],
        ['id'=>self::PRODUCTSTOCK,'name'=>'Storage Inventory'],
        ['id'=>self::NOTFOUNDPRODUCT,'name'=>'NOT FOUND PRODUCT'],
    ];

    const ORDERCANCEL=[3];
}
