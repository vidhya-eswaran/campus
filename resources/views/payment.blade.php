<html>
<head>
    <title>Payment Checkout</title>
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1" / />
     <script src="https://www.paynimo.com/paynimocheckout/client/lib/jquery.min.js" type="text/javascript"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" type="text/javascript"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
</head>
<body>
    <div class="container">
        <div class="row">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <div class="col-md-12">
                <div id="worldline_embeded_popup"></div>
            </div>
        </div>
    </div>

<script type="text/javascript" src="https://www.paynimo.com/Paynimocheckout/server/lib/checkout.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        loadPaymentGateway();
    });
    function loadPaymentGateway(){
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            //var formData = $("#form").serialize();
            const reqData ={
                id: <?php echo $paymentId;?>
            }
            ///change link
            $.ajax({
                    type: 'POST',
                    cache: false,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    data: reqData,
                    url: "https://euctostaging.com/SVS/process-data",                                            
                    success: function (response)
                    {
                        console.log(response);
                        var result = JSON.parse(response);
                        obj = result['data'];
                        mer_array = result['mer_array'];
                        console.log(result);
                        function handleResponse(res)
                        {
                            if (typeof res != 'undefined' && typeof res.paymentMethod != 'undefined' && typeof res.paymentMethod.paymentTransaction != 'undefined' && typeof res.paymentMethod.paymentTransaction.statusCode != 'undefined' && res.paymentMethod.paymentTransaction.statusCode == '0300') {
                        // success block
                            } else if (typeof res != 'undefined' && typeof res.paymentMethod != 'undefined' && typeof res.paymentMethod.paymentTransaction != 'undefined' && typeof res.paymentMethod.paymentTransaction.statusCode != 'undefined' && res.paymentMethod.paymentTransaction.statusCode == '0398') {
                        // initiated block
                            } else {
                        // error block
                            }   
                        };

                        var configJson = 
                        {
                            'tarCall': false,
                            'features': {
                                'showPGResponseMsg': true,
                                'enableNewWindowFlow': false ,   //for hybrid applications please disable this by passing false
                                'enableAbortResponse': true,
                                'enableExpressPay': mer_array['enableExpressPay'] == 1? 'true' : 'false',  //if unique customer identifier is passed then save card functionality for end  end customer
                                'enableInstrumentDeRegistration': mer_array['enableInstrumentDeRegistration'] == 1 ? true:false,  //if unique customer identifier is passed then option to delete saved card by end customer
                                'enableMerTxnDetails': true,
                                'siDetailsAtMerchantEnd': mer_array['enableSIDetailsAtMerchantEnd'] == 1? true:false,
                                'enableSI': mer_array['enableEmandate'] == 1 ?true:false,
                                'hideSIDetails': mer_array['hideSIConfirmation'] == 1?true:false,
                                'enableDebitDay': mer_array['enableDebitDay'] == 1?true:false,
                                'expandSIDetails':mer_array['expandSIDetails'] == 1?true:false,
                                'enableTxnForNonSICards':mer_array['enableTxnForNonSICards'] == 1?true:false,
                                'showSIConfirmation':mer_array['showSIConfirmation'] == 1?true:false,
                                'showSIResponseMsg': mer_array['showSIResponseMsg'] == 1? true:false,
                            },
                            
                            'consumerData': {
                                'deviceId': 'WEBSH2',
                                //possible values 'WEBSH1', 'WEBSH2' and 'WEBMD5'
                                //'debitDay':'10',
                                'token': obj['hash'],
                                'returnUrl': obj['data'][12],
                                /*'redirectOnClose': 'https://www.tekprocess.co.in/MerchantIntegrationClient/MerchantResponsePage.jsp',*/
                                'responseHandler': handleResponse,
                                'paymentMode': mer_array['paymentMode'],
                                'checkoutElement': mer_array['embedPaymentGatewayOnPage'] == "1" ? "#worldline_embeded_popup" :"",
                                'merchantLogoUrl': mer_array['logoURL']? mer_array['logoURL'] : '' ,  //provided merchant logo will be displayed
                                'merchantId': obj['data'][0],
                                'currency': obj['data'][15],
                                'consumerId': obj['data'][8],  //Your unique consumer identifier to register a eMandate/eNACH
                                'consumerMobileNo': obj['data'][9],
                                'consumerEmailId': obj['data'][10],
                                'txnId': obj['data'][1],   //Unique merchant transaction ID
                                'items': [{
                                    'itemId': obj['data'][14],
                                    'amount': obj['data'][2],
                                    'comAmt': '0'
                                }],
                                'cartDescription': '}{custname:'+obj['data'][13],
                                'merRefDetails': [
                                    {"name": "Txn. Ref. ID", "value": obj['data'][1]}
                                ],
                                'customStyle': {
                                    'PRIMARY_COLOR_CODE': mer_array['primaryColor'] ,   //merchant primary color code
                                    'SECONDARY_COLOR_CODE': mer_array['secondaryColor'],   //provide merchant's suitable color code
                                    'BUTTON_COLOR_CODE_1': mer_array['buttonColor1'] ,   //merchant's button background color code
                                    'BUTTON_COLOR_CODE_2': mer_array['buttonColor2']    //provide merchant's suitable color code for button text
                                },
                                'accountNo': obj['data'][11],    //Pass this if accountNo is captured at merchant side for eMandate/eNACH
                                'accountHolderName': obj['data'][16],  //Pass this if accountHolderName is captured at merchant side for ICICI eMandate & eNACH registration this is mandatory field, if not passed from merchant Customer need to enter in Checkout UI.
                                'ifscCode': obj['data'][17],        //Pass this if ifscCode is captured at merchant side.
                                'accountType': obj['data'][18],  //Required for eNACH registration this is mandatory field
                                'debitStartDate': obj['data'][3],
                                'debitEndDate': obj['data'][4],
                                'maxAmount': obj['data'][5],
                                'amountType': obj['data'][6],
                                'frequency': obj['data'][7]  //  Available options DAIL, WEEK, MNTH, QURT, MIAN, YEAR, BIMN and ADHO
                            }
                        };
                        
                        console.log(configJson);       

                        $.pnCheckout(configJson);
                        if(configJson.features.enableNewWindowFlow)
                        {
                            pnCheckoutShared.openNewWindow();
                        }
                    }
            });

        }
    
</script>

</body>
</html>