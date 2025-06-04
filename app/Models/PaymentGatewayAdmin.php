<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGatewayAdmin extends Model
{
    use HasFactory;
    protected $fillable = [
            'merchantSchemeCode' ,
            'typeOfPayment',
            'currency',
            'primaryColor',
            'secondaryColor',
            'buttonColor1',
            'buttonColor2',
            'logoURL',
            'enableExpressPay',
            'separateCardMode',
            'enableNewWindowFlow',
            'merchantMessage',
            'disclaimerMessage',
            'paymentMode',
            'paymentModeOrder',
            'enableInstrumentDeRegistration',
            'transactionType',
            'hideSavedInstruments',
            'saveInstrument',
            'displayTransactionMessageOnPopup',
            'embedPaymentGatewayOnPage',
            'enableEmandate',
            'hideSIConfirmation',
            'expandSIDetails',
            'enableDebitDay',
            'showSIResponseMsg',
            'showSIConfirmation',
            'enableTxnForNonSICards',
            'showAllModesWithSI',
            'enableSIDetailsAtMerchantEnd'
    ];
}
