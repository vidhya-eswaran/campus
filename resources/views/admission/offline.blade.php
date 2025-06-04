<!doctype html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Admission Application Form 2024 - 2025</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
         integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
      <link rel="stylesheet" href="{{ asset('public/css/style-1.css') }}">
      <style>
         label{
         font-size: 14px !important;
         }
        select.form-select{
                 color: #817b8f;
         }
         @media(max-width:760px){
             select.form-select {
    width: -webkit-fill-available !important;
}
         }
         .par-span{
             position: absolute;
             /*top: 5px;*/
             font-size: 14px;
             padding: 0 10px;
         }
         input[disabled] {
    background-color: #f5f5f5; /* Light gray background */
    cursor: not-allowed; /* Not-allowed cursor */
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
          
    {{-- Or loop through an array --}}
            <form method="POST" action="{{ route('admission.offline_store') }}"  enctype="multipart/form-data">
               @csrf
               

               <div class="form-body">
                  <div class="form-title row">
                       <p style="font-size: 14px;
    text-align: center;">Note: Please review the form and identify the mandatory details marked with an asterisk (*). Gather the necessary information and start the form-filling process. Upon form submission, you will be required to pay an application fee of Rs. 300 to complete the process with debit / credit card or internet banking.</p>
                     <h4>APPLICANT DETAILS</h4>
                  </div>
                  <div class="form-row row">
                     <div class="col-lg-2 col-md-4">
                        <label for="">Name</label>
                        <sup class="req">*</sup>
                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8">
                        <input type="text" name="name" id="first_name" placeholder="Enter Name"
                           class="form-control form-control-sm" required value="{{ isset($data->name) ? $data->name : '' }}">
                     </div>
                     <div class="col-lg-2 col-md-4">
                        <label for="">Date of Application</label>
                        <!--<sup class="req">*</sup>-->
                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-3 col-md-8">
                        <input type="date" name="date_form" id="date"  value="{{ \Carbon\Carbon::now()->toDateString() }}"
                           class="form-control form-control-sm" readonly>
                     </div>
                  </div>
                  <div class="form-row row">
                     <div class="col-lg-2 col-md-4">
                        <label for="">Mother tongue of the pupil</label>
                        
                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8">
                        <input type="text" name="language" id="last_name" value="{{ isset($data->language) ? $data->language : '' }}" placeholder="Mother tongue of the pupil"
                           class="form-control form-control-sm" >
                     </div>
                     <div class="col-lg-2 col-md-4">
                        <label for="">State </label>
                                                <sup class="req">*</sup>

                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8">
                        <input type="text" name="state_student" id="last_name" placeholder="State"
                           class="form-control form-control-sm" required>
                     </div>
                  </div>
                  <div class="form-row row">
                     <div class="col-lg-2 col-md-4">
                        <label for="">Date of Birth</label>
                        <sup class="req">*</sup>
                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8">
                        <input type="date" placeholder="Enter Date of Birth" name="date_of_birth" id="date_of_birth "
                           class="form-control form-control-sm" required>
                     </div>
                     <div class="col-lg-2 col-md-4">
                        <label for="">Gender</label>
                        <sup class="req">*</sup>
                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8 pt-1">
                        <input type="radio" name="gender" value="Male"> Male &nbsp;&nbsp;
                        <input type="radio" name="gender" value="Female"> Female &nbsp;&nbsp;
                     </div>
                  </div>
                  <div class="form-title row">
                     <div class="col-lg-2 col-md-4">
                        <label for="">Blood Group</label>
                        <sup class="req">*</sup>
                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8">
                        <select class="form-select" name="blood_group" aria-label="Default select example">
                           <option value="" >Select Blood Group: </option>
                           <option value="A+">A+</option>
                           <option value="B+">B+</option>
                           <option value="O+">O+</option>
                           <option value="AB+">AB+</option>
                           <option value="A-">A-</option>
                           <option value="B-">B-</option>
                           <option value="AB-">AB-</option>
                           <option value="O-">O-</option>
                        </select>
                     </div>
                     <div class="col-lg-2 col-md-4">
                        <label for="">Nationality </label>
                        <sup class="req">*</sup>
                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8">
                        <select class="form-select" name="nationality" aria-label="Default select example" required>
                           <option value="" >Select Nationality : </option>
                           <option value="Indian">Indian</option>
                           <option value="Other">Other</option>
                        </select>
                     </div>
                  </div>
                  <div class="form-row row">
                     <div class="col-lg-2 col-md-4">
                        <label for="">Religion </label>
                         <!--<sup class="req">*</sup>-->
                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8">
                        <input type="text" name="religion" id="last_name" placeholder="Religion"
                           class="form-control form-control-sm">
                     </div>
                     <div class="col-lg-2 col-md-4">
                        <label for="">Church Denomination </label>
                         <!--<sup class="req">*</sup>-->
                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8">
                        <input type="text" name="church_denomination" id="last_name" placeholder="For Christian Applicants"
                           class="form-control form-control-sm" >
                     </div>
                  </div>
                  <div class="form-row row">
                     <div class="col-lg-2 col-md-4">
                        <label for="">Caste </label>
                         <!--<sup class="req">*</sup>-->
                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8">
                        <input type="text" name="caste" id="last_name" placeholder="Caste"
                           class="form-control form-control-sm" >
                     </div>
                     <div class="col-lg-2 col-md-4">
                        <label for="">Caste Classification</label>
                         <!--<sup class="req">*</sup>-->
                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8">
                        <select class="form-select" name="caste_type" aria-label="Default select example">
                           <option value="" >Select Caste: </option>
                           <option value="SC">SC</option>
                           <option value="ST">ST</option>
                           <option value="MBC">MBC</option>
                           <option value="BC">BC </option>
                           <option value="OC">OC</option>
                        </select>
                     </div>
                  </div>
                  <div class="form-row row">
                     <div class="col-lg-2 col-md-4">
                        <label for="">Aadhaar Card No </label>
                         <!--<sup class="req">*</sup>-->
                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8">
                        <input type="text" name="aadhar_card_no" id="last_name" placeholder="Aadhaar Card No"
                           class="form-control form-control-sm">
                     </div>
                     <div class="col-lg-2 col-md-4">
                        <label for="">Ration Card No </label>
                         <!--<sup class="req">*</sup>--> 
                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8">
                        <input type="text" name="ration_card_no" id="last_name" placeholder="Ration Card No"
                           class="form-control form-control-sm">
                     </div>
                  </div>
                  <div class="form-row row">
                     <div class="col-lg-2 col-md-4">
                        <label for="">EMIS NO (If the child studied in the state of TamilNadu) </label>
                         
                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8">
                        <input type="text" name="emis_no" id="last_name" placeholder="EMIS NO"
                           class="form-control form-control-sm" >
                     </div>
                     <div class="col-lg-2 col-md-4">
                        <label for="">Food Choice : </label>
  <sup class="req">*</sup>
                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8">
                        
                                 <select class="form-select" name="veg_or_non" aria-label="Default select example" required>
                           <option value="" >Select Food : </option>
                           <option value="Vegetarian">Vegetarian</option>
                           <option value="Non-Vegetarian">Non-Vegetarian</option>
                        </select>
                     </div>
                  </div>
                  <div class="form-row row">
                     <div class="col-lg-2 col-md-4">
                        <label for="">Details of Chronic Diseases, if any </label>
                          <sup class="req">*</sup>

                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8">
                        <input type="text" name="chronic_des" id="last_name" placeholder="Details of Chronic Diseases"
                           class="form-control form-control-sm" required>
                     </div>
                     <div class="col-lg-2 col-md-4">
                        <label for="">Are you taking any medicine or treatment at present?                        </label>
                                                  <sup class="req">*</sup>

                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8">
                        <select class="form-select" name="medicine_taken" aria-label="Default select example" required>
                           <option value="" >Select Medicine Taken: </option>
                           <option value="Yes">Yes</option>
                           <option value="No">No</option>
                        </select>
                     </div>
                  </div>



                 <div class="form-title row">
    <h4>Family Details</h4>
</div>
<div class="form-row row">
    <div class="col-lg-2 col-md-4">
        <label for="">Father's Name</label>
        <sup class="req">*</sup>
        <span class="indc">:</span>
    </div>
    <div class="col-lg-4 col-md-8" style="position:relative;">
        <select
            class="form-select par-span father-title"
            style="width: 20%;padding: 4px 10px; border-radius: 0;"
            name="father_title"
            aria-label="Default select example"
        >
            <option value="Mr.">Mr.</option>
            <option value="Late.">Late.</option>
             <option value="Rev.">Rev.</option>
              <option value="Pr.">Pr.</option>
               <option value="Bishop">Bishop</option>
               <option value="Evangelist">Evangelist</option>
               <option value="Missionary">Missionary</option>
        </select>
        <input
            type="text"
            name="father_name"
            placeholder="Enter Father's Name"
            class="form-control form-control-sm father-name"
            style="padding:.25rem 5.5rem"
        >
    </div>
    <div class="col-lg-2 col-md-4">
        <label for="">Father's Profession <sup class="req">*</sup></label>
        
        <span class="indc">:</span>
    </div>
    <div class="col-lg-4 col-md-8">
        <input
            type="text"
            name="father_occupation"
            id="father_occupation"
            placeholder="Enter Father's Occupation"
            class="form-control form-control-sm father-field"
            required
        >
    </div>
</div>
<div class="form-row row">
    <div class="col-lg-2 col-md-4">
        <label for="">Father's Mobile No <sup class="req">*</sup></label>
       
        <span class="indc">:</span>
    </div>
    <div class="col-lg-4 col-md-8">
        <input
            type="tel"
            name="father_contact_no"
            placeholder="Enter Father's Mobile No"
            class="form-control form-control-sm father-field"
            required
        >
    </div>
    <div class="col-lg-2 col-md-4">
        <label for="">Father's Email Id <sup class="req">*</sup></label>
        <span class="indc">:</span>
    </div>
    <div class="col-lg-4 col-md-8">
        <input
            type="email"
            name="father_email_id"
            placeholder="Enter Father's Email Id"
            class="form-control form-control-sm father-field"
            required
        >
    </div>
    <div class="col-lg-2 col-md-4">
        <label for="">Father's Monthly Income <sup class="req">*</sup></label>
        <span class="indc">:</span>
    </div>
    <div class="col-lg-4 col-md-8">
        <input
            type="text"
            name="father_income"
            placeholder="Enter Father's Monthly Income"
            class="form-control form-control-sm father-field"
            required
        >
    </div>
    <div class="col-lg-2 col-md-4">
        <label for="">Organization Employed <sup class="req">*</sup></label>
        <span class="indc">:</span>
    </div>
    <div class="col-lg-4 col-md-8">
        <input
            type="text"
            name="father_organization"
            placeholder="Enter Organization Employed"
            class="form-control form-control-sm father-field"
            required
        >
    </div>
</div>

       
                  
                   
               <div class="form-row row">
    <div class="col-lg-2 col-md-4">
        <label for="">Mother's Name</label>
        <sup class="req">*</sup>
        <span class="indc">:</span>
    </div>
    <div class="col-lg-4 col-md-8" style="position:relative;">
        <select
            class="form-select par-span mother-title"
            style="width: 20%;padding: 4px 10px; border-radius: 0;"
            name="mother_title"
            aria-label="Default select example"
        >
            <option value="Mrs.">Mrs.</option>
                   <option value="Ms.">Ms.</option>
              <option value="Late.">Late.</option>
             <option value="Rev.">Rev.</option>
              <option value="Pr.">Pr.</option>
               <option value="Bishop">Bishop</option>
               <option value="Evangelist">Evangelist</option>
               <option value="Missionary">Missionary</option>
        </select>
        <input
            type="text"
            name="mother_name"
            id="mother_name"
            placeholder="Enter Mother's Name"
            class="form-control form-control-sm mother-name"
            required
            style="padding:.25rem 5.5rem"
        >
    </div>
    <div class="col-lg-2 col-md-4">
        <label for="">Mother's Profession</label>
        <sup class="req">*</sup>
        <span class="indc">:</span>
    </div>
    <div class="col-lg-4 col-md-8">
        <input
            type="text"
            name="mother_occupation"
            placeholder="Enter Mother's Occupation"
            class="form-control form-control-sm mother-field"
            required
        >
    </div>
</div>
<div class="form-row row">
    <div class="col-lg-2 col-md-4">
        <label for="">Mother's Mobile No</label>
        <sup class="req">*</sup>
        <span class="indc">:</span>
    </div>
    <div class="col-lg-4 col-md-8">
        <input
            type="text"
            name="mother_contact_no"
            placeholder="Enter Mother's Mobile No"
            class="form-control form-control-sm mother-field"
            required
        >
    </div>
    <div class="col-lg-2 col-md-4">
        <label for="">Mother's Email Id</label>
        <sup class="req">*</sup>
        <span class="indc">:</span>
    </div>
    <div class="col-lg-4 col-md-8">
        <input
            type="email"
            name="mother_email_id"
            placeholder="Enter Mother's Email Id"
            class="form-control form-control-sm mother-field"
        >
    </div>
    <div class="col-lg-2 col-md-4">
        <label for="">Mother's Monthly Income</label>
        <sup class="req">*</sup>
        <span class="indc">:</span>
    </div>
    <div class="col-lg-4 col-md-8">
        <input
            type="text"
            name="mother_income"
            placeholder="Enter Mother's Monthly Income"
            class="form-control form-control-sm mother-field"
            required
        >
    </div>
    <div class="col-lg-2 col-md-4">
        <label for="">Organization Employed</label>
        <sup class="req">*</sup>
        <span class="indc">:</span>
    </div>
    <div class="col-lg-4 col-md-8">
        <input
            type="text"
            name="mother_organization"
            placeholder="Enter Organization Employed"
            class="form-control form-control-sm mother-field"
            required
        >
    </div>
</div>
     <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Father Section Logic
        const fatherTitle = document.querySelector('.father-title');
        const fatherFields = document.querySelectorAll('.father-field');

        fatherTitle.addEventListener('change', function () {
            fatherFields.forEach(field => {
                const parentDiv = field.closest('.col-lg-4'); // Find the closest parent div
                const label = parentDiv.previousElementSibling; // Locate the associated label
                const reqTag = label.querySelector('.req'); // Find the <sup class="req"> tag

                if (fatherTitle.value === 'Late.') {
                    // Disable fields
                    field.disabled = true;
                    field.style.opacity = '0.5'; // Dim the input field
                    field.removeAttribute('required');

                    // Hide the <sup> tag
                    if (reqTag) {
                        reqTag.style.display = 'none';
                    }
                } else {
                    // Enable fields
                    field.disabled = false;
                    field.style.opacity = '1'; // Restore opacity
                    field.setAttribute('required', true);

                    // Show the <sup> tag
                    if (reqTag) {
                        reqTag.style.display = '';
                    }
                }
            });
        });

        // Mother Section Logic
        const motherTitle = document.querySelector('.mother-title');
        const motherFields = document.querySelectorAll('.mother-field');

        motherTitle.addEventListener('change', function () {
            motherFields.forEach(field => {
                const parentDiv = field.closest('.col-lg-4'); // Find the closest parent div
                const label = parentDiv.previousElementSibling; // Locate the associated label
                const reqTag = label.querySelector('.req'); // Find the <sup class="req"> tag

                if (motherTitle.value === 'Late.') {
                    // Disable fields
                    field.disabled = true;
                    field.style.opacity = '0.5'; // Dim the input field
                    field.removeAttribute('required');

                    // Hide the <sup> tag
                    if (reqTag) {
                        reqTag.style.display = 'none';
                    }
                } else {
                    // Enable fields
                    field.disabled = false;
                    field.style.opacity = '1'; // Restore opacity
                    field.setAttribute('required', true);

                    // Show the <sup> tag
                    if (reqTag) {
                        reqTag.style.display = '';
                    }
                }
            });
        });
    });
</script>

                    
                  <div class="form-row row">
                     <div class="col-lg-2 col-md-4">
                        <label for="">Guardian's Name</label>
                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8" style="position:relative;">
    <!--                     <select class="form-select par-span" style="    width: 35%;padding: 4px 10px;-->
    <!--border-radius: 0;" name="guardian_title" aria-label="Default select example">-->
    <!--                         <option value="">Select Mr/Mrs</option>-->
    <!--                     <option value="Mr.">Mr.</option>-->
    <!--    <option value="Mrs.">Mrs.</option>-->
    <!--    <option value="Ms.">Ms.</option>-->
    <!--    <option value="Dr.">Dr.</option>-->
                          
    <!--                    </select>-->
                        <input type="text" name="guardian_name" id="guardian_name" placeholder="Enter Guardian's Name"
                           class="form-control form-control-sm">
                     </div>
                     <div class="col-lg-2 col-md-4">
                        <label for="">Guardian's Profession</label>
                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8">
                        <input type="text" name="guardian_occupation" placeholder="Enter Guardian's Occupation"
                           class="form-control form-control-sm">
                     </div>
                  </div>
                  
                    
                    <div class="form-row row">
                     <div class="col-lg-2 col-md-4">
                        <label for="">Guardian's Mobile No</label>
                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8">
                        <input type="number" name="guardian_contact_no" value=""  placeholder="Enter Guardian's Mobile No"
                           class="form-control form-control-sm">
                     </div>
                     <div class="col-lg-2 col-md-4">
                        <label for="">Guardian's Email Id</label>
                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8">
                        <input type="text" name="guardian_email_id" value="" placeholder="Enter Guardian's Email"
                           class="form-control form-control-sm">
                     </div>
                       <div class="col-lg-2 col-md-4">
                        <label for="">Guardian's Monthly Income</label>
                         <!--<sup class="req">*</sup>-->
                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8">
                        <input type="text" name="guardian_income"  placeholder="Enter Guardian's Monthly Income"
                           class="form-control form-control-sm" >
                     </div>
                           <div class="col-lg-2 col-md-4">
                        <label for="">Organization Employed</label>
                         <!--<sup class="req">*</sup>-->
                        <span class="indc">:</span>
                     </div>
                     <div class="col-lg-4 col-md-8">
                        <input type="text" name="guardian_organization"  placeholder="Enter Organization Employed"
                           class="form-control form-control-sm" >
                     </div>
                     </div>
                      <div class="form-row row">
    <div class="col-lg-6 col-md-4">
        <label>Does the child have any other sibling(s) studying in Santhosha Vidhyalaya</label>
        <sup class="req">*</sup>
        <span class="indc">:</span>
    </div>
    <div class="col-lg-4 col-md-8">
        <select class="form-select" name="check_siblings" id="check_siblings" required>
            <option value="">Select</option>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
        </select>
    </div>
</div>

<p style="font-size:14px;">Information of your brother(s) & sister(s) (If studying in school):</p>
<div id="siblings_table" style="width:100%; overflow:auto; display:none;">
    <table class="table" style="background:#727272 !important;color:white;">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Class</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>1</th>
                <td><input class="wpcf7-form-control wpcf7-text" type="text" name="brother_1"></td>
                <td>
                    <select class="form-select" name="gender_1" >
                        <option value="">Select</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </td>
                <td>
                    <select class="form-select" name="class_1" >
                        <option value="">Select class</option>
                        <option value="lkg">LKG</option>
                        <option value="ukg">UKG</option>
                        <option value="1">I</option>
                        <option value="2">II</option>
                        <option value="3">III</option>
                        <option value="4">IV</option>
                        <option value="5">V</option>
                        <option value="6">VI</option>
                        <option value="7">VII</option>
                        <option value="8">VIII</option>
                        <option value="9">IX</option>
                        <option value="10">X</option>
                        <option value="11">XI</option>
                        <option value="12">XII</option>
                    </select>
                </td>
            </tr>
            	<tr>
								<th scope="row">
									<p>2
									</p>
								</th>
								<td>
									<p><span class="wpcf7-form-control-wrap" data-name="brother2"><input class="wpcf7-form-control wpcf7-text" aria-invalid="false" value="" type="text" name="brother_2"></span>
									</p>
								</td>
								<td>
									<p><span class="wpcf7-form-control-wrap" data-name="gender2"><select class="form-select" name="gender_2" aria-label="Default select example" >
                           <option value="" >Select </option>
                           <option value="Male">Male</option>
                           <option value="Female">Female</option>
                        </select></span>
									</p>
								</td>
								<td>
									<p><span class="wpcf7-form-control-wrap" data-name="class2"><select class="form-select"  name= "class_2" aria-label="Default select example" >
                            <option value="" >Select class</option>
                        <option value="lkg">LKG</option>
                          <option value="ukg">UKG</option>
                            <option value="1">I</option>
                            <option value="2">II</option>
                            <option value="3">III</option>
                            <option value="4">IV</option>
                            <option value="5">V</option>
                            <option value="6">VI</option>
                            <option value="7">VII</option>
                            <option value="8">VIII</option>
                             <option value="9">IX</option>
                            <option value="10">X</option>
                            <option value="11">XI</option>
                            <option value="12">XII</option>
                          </select></span>
									</p>
								</td>
							</tr>
								<tr>
								<th scope="row">
									<p>3
									</p>
								</th>
								<td>
									<p><span class="wpcf7-form-control-wrap" data-name="brother2"><input class="wpcf7-form-control wpcf7-text" aria-invalid="false" value="" type="text" name="brother_3"></span>
									</p>
								</td>
								<td>
									<p><span class="wpcf7-form-control-wrap" data-name="gender2"><select class="form-select" name="gender_3" aria-label="Default select example" >
                           <option value="" >Select </option>
                           <option value="Male">Male</option>
                           <option value="Female">Female</option>
                        </select></span>
									</p>
								</td>
								<td>
									<p><span class="wpcf7-form-control-wrap" data-name="class2"><select class="form-select"  name= "class_3" aria-label="Default select example" >
                            <option value="" >Select class</option>
                     <option value="lkg">LKG</option>
                          <option value="ukg">UKG</option>
                            <option value="1">I</option>
                            <option value="2">II</option>
                            <option value="3">III</option>
                            <option value="4">IV</option>
                            <option value="5">V</option>
                            <option value="6">VI</option>
                            <option value="7">VII</option>
                            <option value="8">VIII</option>
                             <option value="9">IX</option>
                            <option value="10">X</option>
                            <option value="11">XI</option>
                            <option value="12">XII</option>
                          </select></span>
									</p>
								</td>
							</tr>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#check_siblings').on('change', function() {
        if ($(this).val() === 'Yes') {
            $('#siblings_table').show();
        } else {
            $('#siblings_table').hide();
        }
    });
});
</script>




                  <div class="form-title row">
                     <h4>Permanent Address</h4>
                  </div>
                  <div class="form-row row">
                    <div class="col-lg-2 col-md-4">
                       <label for="">House No:</label>
                       <sup class="req">*</sup>
                       <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                       <input type="text" name="house_no" value="" placeholder="Enter House No"
                          class="form-control form-control-sm" required>
                    </div>
                    <div class="col-lg-2 col-md-4">
                       <label for="">Street </label>
                       <sup class="req">*</sup>
                       <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                       <input type="text" name="street" placeholder="Enter Street "
                          class="form-control form-control-sm" required>
                    </div>
                 </div>
                 <div class="form-row row">
                    <div class="col-lg-2 col-md-4">
                       <label for="">Town / City</label>
                        <sup class="req">*</sup>
                       <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                       <input type="text" name="city" placeholder="Enter Town / City"
                          class="form-control form-control-sm" required>
                    </div>
                    <div class="col-lg-2 col-md-4">
                       <label for="">District </label>
                                              <sup class="req">*</sup>

                       <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                       <input type="text" name="district" placeholder="Enter District "
                          class="form-control form-control-sm" required>
                    </div>
                 </div>
                 <div class="form-row row">
                    <div class="col-lg-2 col-md-4">
                       <label for="">State:</label>
                        <sup class="req">*</sup>
                       <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                       <input type="text" name="state" placeholder="Enter State"
                          class="form-control form-control-sm" required>
                    </div>
                    <div class="col-lg-2 col-md-4">
                       <label for="">Postal Code</label>
                        <sup class="req">*</sup>
                       <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                       <input type="text" name="pincode" value="" placeholder="Enter Postal Code"
                          class="form-control form-control-sm" required>
                    </div>
                 </div>
                  <div class="form-title row">
                    <h4 style="width: 600px;">Address For Communication</h4>
                 </div>
                  <div class="form-row row">
                    <div class="col-lg-2 col-md-4">
                       <label for="">House No:</label>
                       <sup class="req">*</sup>
                       <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                       <input type="text" name="house_no_1" value="" placeholder="Enter House No"
                          class="form-control form-control-sm" required>
                    </div>
                    <div class="col-lg-2 col-md-4">
                       <label for="">Street </label>
                       <sup class="req">*</sup>
                       <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                       <input type="text" name="street_1" placeholder="Enter Street "
                          class="form-control form-control-sm" required>
                    </div>
                 </div>
                 <div class="form-row row">
                    <div class="col-lg-2 col-md-4">
                       <label for="">Town / City</label>
                        <sup class="req">*</sup>
                       <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                       <input type="text" name="city_1" placeholder="Enter Town / City"
                          class="form-control form-control-sm" required>
                    </div>
                    <div class="col-lg-2 col-md-4">
                       <label for="">District </label>
                                               <sup class="req">*</sup>

                       
                       <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                       <input type="text" name="district_1" placeholder="Enter District "
                          class="form-control form-control-sm"required >
                    </div>
                 </div>
                 <div class="form-row row">
                    <div class="col-lg-2 col-md-4">
                       <label for="">State:</label>
                        <sup class="req">*</sup>
                       <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                       <input type="text" name="state_1" placeholder="Enter State"
                          class="form-control form-control-sm" required>
                    </div>
                    <div class="col-lg-2 col-md-4">
                       <label for="">Postal Code</label>
                        <sup class="req">*</sup>
                       <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                       <input type="text" name="pincode_1" value="" placeholder="Enter Postal Code"
                          class="form-control form-control-sm" required>
                    </div>
                 </div>


                 <div class="form-title row">
                    <h4>Group & Language Choice</h4>
                 </div>
            
                 <div class="form-row row">
                    <div class="col-lg-2 col-md-4">
                       <label for="">Class last Studied:</label>
                        <sup class="req">*</sup>
                       <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                        <select class="form-select"  name= "last_class_std" aria-label="Default select example" required>
                            <option value="" >Select class</option>
                            <option value="lkg">LKG</option>
                          <option value="ukg">UKG</option>
                            <option value="1">I</option>
                            <option value="2">II</option>
                            <option value="3">III</option>
                            <option value="4">IV</option>
                            <option value="5">V</option>
                            <option value="6">VI</option>
                            <option value="7">VII</option>
                            <option value="8">VIII</option>
                             <option value="9">IX</option>
                            <option value="10">X</option>
                            <option value="11">XI</option>
                            <option value="12">XII</option>
                          </select>
                    </div>
                    <div class="col-lg-2 col-md-4">
                       <label for="">Name of School Last Studied:</label>
                       <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                       <input type="text" name="last_school" value="" placeholder="Enter School"
                          class="form-control form-control-sm" >
                    </div>
                 </div>
                   <div class="form-row row">
                    <div class="col-lg-2 col-md-4">
                       <label for="">Class for which admission is sought:</label>
                        <sup class="req">*</sup>
                       <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                        <select class="form-select"  name= "admission_for_class" aria-label="Default select example" required>
                            <option value="" >Select class</option>
                     <option value="lkg">LKG</option>
                          <option value="ukg">UKG</option>
                            <option value="1">I</option>
                            <option value="2">II</option>
                            <option value="3">III</option>
                            <option value="4">IV</option>
                            <option value="5">V</option>
                            <option value="6">VI</option>
                            <option value="7">VII</option>
                            <option value="8">VIII</option>
                             <option value="9">IX</option>
                            <option value="10">X</option>
                            <option value="11">XI</option>
                            <option value="12">XII</option>
                            
                          </select>
                    </div>
                    <div class="col-lg-2 col-md-4">
                       <label for="">Location of School last studied (Mention District & State):</label>
                       <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                       <input type="text" name="last_school_state" value="" placeholder="Enter District & State"
                          class="form-control form-control-sm" >
                    </div>
                 </div>

                 <div class="form-row row">
                    <div class="col-lg-2 col-md-4">
                       <label for="">Did the child study Tamil as Second Language: </label>
                        <sup class="req">*</sup>
                       <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                        <select class="form-select"  name= "second_language_school" aria-label="Default select example" required>
                            <option value="" >Select class</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                       
                          </select>
                    </div>
                    <div class="col-lg-2 col-md-4">
                       <label for="">Last Studied Syllabus:</label>
                       <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                        <select class="form-select" name="syllabus" aria-label="Default select example">
                            <option value="" >Select Syllabus</option>
                            <option value="State">State </option>
                            <option value="CBSE">CBSE</option>
                            <option value="CISCE">CISCE</option>
                            <option value="IGCSE">IGCSE</option>
                            <option value="Other">Other</option>
                          </select>
                    </div>
                 </div>
                 <table class="table table-striped">
                    <tbody><tr style="border:1px solid black" ;="">
                        <td>
                            <p>Group I
                            </p>
                        </td>
                        <td>
                            <p>English, Maths, Physics, Chemistry, Biology
                            </p>
                        </td>
                    </tr>
                    <tr style="border:1px solid black" ;="">
                        <td>
                            <p>Group II
                            </p>
                        </td>
                        <td>
                            <p>English, Computer Science, Physics, Chemistry, Biology
                            </p>
                        </td>
                    </tr>
                    <tr style="border:1px solid black" ;="">
                        <td>
                            <p>Group III
                            </p>
                        </td>
                        <td>
                            <p>English, Maths, Physics, Chemistry, Computer Science
                            </p>
                        </td>
                    </tr>
                    <tr style="border:1px solid black" ;="">
                        <td>
                            <p>Group IV
                            </p>
                        </td>
                        <td>
                            <p>English, Accountancy, Computer Application, Commerce, Economics
                            </p>
                        </td>
                    </tr>
                    <tr style="border:1px solid black" ;="">
                        <td>
                            <p>Group V
                            </p>
                        </td>
                        <td>
                            <p>English, Accountancy, Business Maths, Commerce, Economics
                            </p>
                        </td>
                    </tr>
                </tbody>
                  </table>
                   <div class="form-row row">
                    <div class="col-lg-2 col-md-4">
                       <label for="">Group Preference - First Choice (Only for Class XI):</label>
                       <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                        <select class="form-select" name="group_no" aria-label="Default select example">
                            <option value="" >Select Group</option>
                            <option value="Group I - English, Maths, Physics, Chemistry, Biology">Group I - English, Maths, Physics, Chemistry, Biology</option>
                            <option value="Group II - English, Computer Science, Physics, Chemistry, Biology">Group II - English, Computer Science, Physics, Chemistry, Biology</option>
                            <option value="Group III - English, Maths, Physics, Chemistry, Computer Science">Group III - English, Maths, Physics, Chemistry, Computer Science </option>
                            <option value="Group IV - English, Accountancy, Computer Application, Commerce, Economics">Group IV - English, Accountancy, Computer Application, Commerce, Economics </option>
                            <option value="Group V - English, Accountancy, Business Maths, Commerce, Economics">Group V - English, Accountancy, Business Maths, Commerce, Economics </option>

                          </select>
                    </div>
                    <div class="col-lg-2 col-md-4">
                       <label for="">Group Preference - Second Choice (Only for Class XI):</label>
                       <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                        <select class="form-select" name="second_group_no" aria-label="Default select example">
                            <option value="" >Select Group</option>
                               <option value="Group I - English, Maths, Physics, Chemistry, Biology">Group I - English, Maths, Physics, Chemistry, Biology </option>
                            <option value="Group II - English, Computer Science, Physics, Chemistry, Biology">Group II - English, Computer Science, Physics, Chemistry, Biology</option>
                            <option value="Group III - English, Maths, Physics, Chemistry, Computer Science">Group III - English, Maths, Physics, Chemistry, Computer Science </option>
                            <option value="Group IV - English, Accountancy, Computer Application, Commerce, Economics">Group IV - English, Accountancy, Computer Application, Commerce, Economics </option>
                            <option value="Group V - English, Accountancy, Business Maths, Commerce, Economics">Group V - English, Accountancy, Business Maths, Commerce, Economics </option>

                          </select>
                    </div>
                                      <p>*Note: If any of the above groups has less than 15 candidates, then the second option of the student will be considered. </p>

                 </div>
                 <div class="form-row row">
                    <div class="col-lg-2 col-md-4">
                       <label for="">Language (Only for Class XI):</label>
                       <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                        <select class="form-select" name="second_language" aria-label="Default Second language">
                            <option >Preferred Second Language: </option>
                            <option value="Tamil">Tamil</option>
                            <option value="Hindi">Hindi</option>
                            <option value="French">French</option>
                          </select>
                    </div>
                    <p>Note: Tamil and English are mandatory for Class 1 to X</p>
                    </div>
                <div class="form-row row">
                     
                        <div class="form-title row">
                           <h4 style="width:600px">References <span style="font-size:14px">(The administration will contact the references given)</span> </h4>
                        </div>
                    
                        <div class="col-lg-2 col-md-4">
                       <label for="">Name</label>
                                               <sup class="req">*</sup>
                       <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                       <input type="text" name="reference_name_1" value="" placeholder="Enter Reference Name"
                          class="form-control form-control-sm" required>
                    </div>
                          <div class="col-lg-2 col-md-4">
                       <label for="">Mobile No</label>
                                               <sup class="req">*</sup>

                       <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                       <input type="text" name="reference_phone_1" value="" placeholder="Enter Mobile No"
                          class="form-control form-control-sm" required>
                    </div>
                            <div class="col-lg-2 col-md-4">
                       <label for="">Name</label>
                                               <sup class="req">*</sup>

                       <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                       <input type="text" name="reference_name_2" value="" placeholder="Enter Reference Name"
                          class="form-control form-control-sm" required >
                    </div>
                          <div class="col-lg-2 col-md-4">
                       <label for="">Mobile No</label>
                                               <sup class="req">*</sup>
                       <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                       <input type="text" name="reference_phone_2" value="" placeholder="Enter Mobile No"
                          class="form-control form-control-sm" required>
                    </div>
                        
                        </div>
                  <div class="form-row row">
                     <div class="form-row row">
                        <div class="form-title row">
                           <h4 style="width:500px">Documents to be submitted along with Application </h4>
                        </div>
                        <div class="col-lg-6 col-md-4">
                           <div class="form-title row">
                              <h4 style="width:500px">For All Applicants </h4>
                           </div>
                           <p> Child Passport Size Photo <sup class="req">*</sup>:<br> <span class="wpcf7-form-control-wrap"
                              data-name="file-987"><input size="40" type="file"
                              class="wpcf7-form-control wpcf7-file valid" accept="audio/*,video/*,image/*"
                              aria-invalid="false" type="photo" name="profile_photo" ></span>
                           </p>
                           <p> Birth Certificate  <sup class="req">*</sup>:<br> <span class="wpcf7-form-control-wrap"
                              data-name="file-987"><input size="40" type="file"
                              class="wpcf7-form-control wpcf7-file valid" accept="audio/*,video/*,image/*"
                              aria-invalid="false" type="photo" name="birth_certificate_photo" ></span>
                           </p>
                           <p> Aadhaar Copy / UID <sup class="req">*</sup><br> <span class="wpcf7-form-control-wrap"
                              data-name="file-987"><input size="40" type="file"
                              class="wpcf7-form-control wpcf7-file valid" accept="audio/*,video/*,image/*"
                              aria-invalid="false" type="photo" name="aadhar_card_photo" ></span>
                           </p>
                           <p> Ration Card  <br> <span class="wpcf7-form-control-wrap"
                              data-name="file-987"><input size="40" type="file"
                              class="wpcf7-form-control wpcf7-file valid" accept="audio/*,video/*,image/*"
                              aria-invalid="false" type="photo" name="ration_card_photo" ></span>
                           </p>
                           <p> Community Certificate  <sup class="req">*</sup><br> <span class="wpcf7-form-control-wrap"
                              data-name="file-987"><input size="40" type="file"
                              class="wpcf7-form-control wpcf7-file valid" accept="audio/*,video/*,image/*"
                              aria-invalid="false" type="photo" name="community_certificate_photo"  ></span>
                           </p>
                           <p> Salary Certificate / Slip or Self Declaration of Income  <sup class="req">*</sup><br> <span class="wpcf7-form-control-wrap"
                              data-name="file-987"><input size="40" type="file"
                              class="wpcf7-form-control wpcf7-file valid" accept="audio/*,video/*,image/*"
                              aria-invalid="false" type="photo" name="slip_photo" ></span>
                           </p>
                           <p> Medical Certificate:<br> <span class="wpcf7-form-control-wrap"
                              data-name="file-987"><input size="40" type="file"
                              class="wpcf7-form-control wpcf7-file valid" accept="audio/*,video/*,image/*"
                              aria-invalid="false" type="photo" name="medical_certificate_photo"></span>
                           </p>
                           <p> Organization Endorsement or Reference Letter  <sup class="req">*</sup>: <span class="wpcf7-form-control-wrap"
                              data-name="file-987"><input size="40" type="file"
                              class="wpcf7-form-control wpcf7-file valid" accept="audio/*,video/*,image/*"
                              aria-invalid="false" type="photo" name="reference_letter_photo" ></span>
                           </p>
                        </div>
                        <div class="col-lg-6 col-md-4">
                           <div class="form-title row">
                              <h4 style="width:500px">Church Endorsement  </h4>
                           </div>
                           <p> Church Certificate or a Letter from the Pastor <br><span class="wpcf7-form-control-wrap"
                              data-name="file-987"><input size="40" type="file"
                              class="wpcf7-form-control wpcf7-file valid" accept="audio/*,video/*,image/*"
                              aria-invalid="false" type="photo" name="church_certificate_photo"></span>
                           </p>
                           <div class="form-title row">
                              <h4 style="width:500px">Original Documents  </h4>
                           </div>
                           <p> Transfer Certificate:<br> <span class="wpcf7-form-control-wrap"
                              data-name="file-987"><input size="40" type="file"
                              class="wpcf7-form-control wpcf7-file valid" accept="audio/*,video/*,image/*"
                              aria-invalid="false" type="photo" name="transfer_certificate_photo"></span>
                           </p>
                           <p> Migration Certificate <span class="wpcf7-form-control-wrap"
                              data-name="file-987"><input size="40" type="file"
                              class="wpcf7-form-control wpcf7-file valid" accept="audio/*,video/*,image/*"
                              aria-invalid="false" type="photo" name="admission_photo"></span>
                           </p>
                        </div>
                     </div>
                     <div class="col-lg-4 col-md-8">
                     </div>
                     <div class="col-lg-2 col-md-4">
                     </div>
               
                        <div class="form-title row">
                           <h4 style="width:500px">Declaration: </h4>
                           <p>	<input type="checkbox" id="color_blue" name="Declaration" style="margin: 0 20px 0 0" required>I, hereby declare that all the information provided in this application form is true, complete, and accurate to the best of my knowledge. </p>
                           <p>I further acknowledge that Santhosha Vidhyalaya reserves the right to verify the information provided in this application and, if necessary, contact the listed references. </p>
                           <p>I understand the importance of providing accurate information and agree to adhere to all rules, policies, and procedures set forth by Santhosha Vidhyalaya. </p>
                           <p>I also understand that the submission of this application does not imply confirmation of admission for my child.</p>
                        </div>
                     <div class="col-lg-4 col-md-8">
                        <button type="submit" class="btn btn-sm btn-primary">Submit Form</button>
                     </div>
                  </div>
               </div>
            </form>

         </div>
      </div>
      <script>
        // Check if the success message is present and display the alert
        @if(session('success'))
    <script>
        swal("Success!", "{{ session('success') }}", "success");
        // or any other SweetAlert configuration
    </script>
@endif
s
    </script>
   </body>
   </html>