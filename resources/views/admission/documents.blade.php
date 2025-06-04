<?php

$data = url()->current();
$parsedUrl = parse_url($data);

// Extract path
$path = $parsedUrl['path'];

// Split path into segments
$segments = explode('/', trim($path, '/'));
// print_r($segments);


?>
<html>
    <head>
         <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
         integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
            <link rel="stylesheet" href="{{ asset('public/css/style-1.css') }}">
<style>
    .form-row{
            margin: 20px 0;

    }
</style>
    </head>
<body>
  <div class="container form-container">
         <div class=" col-lg-12 mx-auto login-container">
            <div class="row form-header">
               <div class="col-md-2 logocol">
                  <img src="{{ asset('public/images/1.jpg') }}" alt="">
               </div>
               <div class="col-md-10 headcol">
                  <h4>Santhosha Vidhyalaya </h4>
                  <p>Admission Application Form 2024 - 2025</p>
                  <p class="cinfo">
                     <span><i class="fas fa-phone"></i>  +91 80125 12100</span>
                     <span><i class="fas fa-envelope"></i> admissions@santhoshavidhyalaya.com</span>
                     <span><i class="fas fa-map-marker-alt"></i>Dohnavur – 627102 Tirunelveli Dist. Tamilnadu</span>
                  </p>
               </div>
            </div>
  <div class="form-body">
                  <div class="form-title row">
                      
                     <h4>Documents For {{$admission->name }}</h4>
                  </div>
                  <div class="form-row row">
                     <div class="col-lg-2 col-md-4">
                        <label for="">Profile Picture :</label>
                        <!--<sup class="req">*</sup>-->
                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8">
                       <a href="{{ asset('storage/app/profile_photos/' . $admission->profile_photo) }}" download="image.jpg">
    <button>Download Image</button>
</a>
                     </div>
                         <div class="col-lg-2 col-md-4">
                        <label for="">Birth Certificate :</label>
                        <!--<sup class="req">*</sup>-->
                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8">
                       <a href="{{ asset('storage/app/birth_certificate_photos/' . $admission->profile_photo) }}" download="image.jpg">
    <button>Download Image</button>
</a>
                     </div>
                    
                  </div>
                  <div class="form-row row">
                     <div class="col-lg-2 col-md-4">
                        <label for="">Aadhaar Copy / UID:</label>
                        <!--<sup class="req">*</sup>-->
                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8">
                       <a href="{{ asset('storage/app/aadhar_card_photos/' . $admission->aadhar_card_photo) }}" download="image.jpg">
    <button>Download Image</button>
</a>
                     </div>
                         <div class="col-lg-2 col-md-4">
                        <label for="">Ration Card :</label>
                        <!--<sup class="req">*</sup>-->
                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8">
                       <a href="{{ asset('storage/app/ration_card_photos/' . $admission->ration_card_photo) }}" download="image.jpg">
    <button>Download Image</button>
</a>
                     </div>
                    
                  </div>
                  <div class="form-row row">
                     <div class="col-lg-2 col-md-4">
                        <label for="">Community Certificate :</label>
                        <!--<sup class="req">*</sup>-->
                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8">
                       <a href="{{ asset('storage/app/community_certificate_photos/' . $admission->community_certificate) }}" download="image.jpg">
    <button>Download Image</button>
</a>
                     </div>
                         <div class="col-lg-2 col-md-4">
                        <label for="">Salary Certificate / Slip or Self Declaration of Income :</label>
                        <!--<sup class="req">*</sup>-->
                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8">
                       <a href="{{ asset('storage/app/slip_photos/' . $admission->slip_photo) }}" download="image.jpg">
    <button>Download Image</button>
</a>
                     </div>
                    
                  </div>
                  <div class="form-row row">
                     <div class="col-lg-2 col-md-4">
                        <label for="">Medical Certificate: :</label>
                        <!--<sup class="req">*</sup>-->
                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8">
                       <a href="{{ asset('storage/app/medical_certificate_photos/' . $admission->medical_certificate_photo) }}" download="image.jpg">
    <button>Download Image</button>
</a>
                     </div>
                         <div class="col-lg-2 col-md-4">
                        <label for="">Organization Endorsement or Reference Letter  :</label>
                        <!--<sup class="req">*</sup>-->
                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8">
                       <a href="{{ asset('storage/app/reference_letter_photos/' . $admission->reference_letter_photo) }}" download="image.jpg">
    <button>Download Image</button>
</a>
                     </div>
                    
                  </div>
                  <div class="form-row row">
                     <div class="col-lg-2 col-md-4">
                        <label for="">Church Certificate or a Letter from the Pastor :</label>
                        <!--<sup class="req">*</sup>-->
                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8">
                       <a href="{{ asset('storage/app/church_certificate_photos/' . $admission->church_certificate_photo) }}" download="image.jpg">
    <button>Download Image</button>
</a>
                     </div>
                         <div class="col-lg-2 col-md-4">
                        <label for="">Transfer Certificate :</label>
                        <!--<sup class="req">*</sup>-->
                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8">
                       <a href="{{ asset('storage/app/transfer_certificate_photo/' . $admission->transfer_certificate_photo) }}" download="image.jpg">
    <button>Download Image</button>
</a>
                     </div>
                    
                  </div>
<!--                  <div class="form-row row">-->
<!--                     <div class="col-lg-2 col-md-4">-->
<!--                        <label for="">Migration Certificate  :</label>-->
                        <!--<sup class="req">*</sup>-->
<!--                        <span class="indc">:</span>-->
<!--                     </div>-->
<!--                     <div class="col-lg-4 col-md-8">-->
<!--                       <a href="{{ asset('storage/app/profile_photos/' . $admission->profile_photo) }}" download="image.jpg">-->
<!--    <button>Download Image</button>-->
<!--</a>-->
<!--                     </div>-->
                        
                    
                  </div>
                  </div>
<!--<p>{{$admission -> name}}</p>-->
<!--<img src="{{ asset('storage/app/profile_photos/' . $admission->profile_photo) }}" alt="Image Description" width="300">-->

<!-- Download Button -->

</body>
</html>
