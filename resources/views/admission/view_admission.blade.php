<?php
$url = "https://www.santhoshavidhyalaya.com/SVS/admission-view/20240209145035221";

// Parse the URL
$parsedUrl = parse_url($url);

// Extract path
$path = $parsedUrl['path'];

// Split path into segments
$segments = explode('/', trim($path, '/'));

// Output the segments
// print_r($segments[2]);
?>
<!--<h1> {{ $admission->first_name ?? 'NA'}}</h1>-->











<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <style>
     body {
            width: 75%;
            margin: 0 auto; /* Center the content horizontally */
            border: 2px solid #9b2612;
        }

        @media print {
            body {
                width: 95%;
                font-size: 15px;/* Full width for print */
            }
        }
        section{
            width:95%;
        }
        td{
            

        }
        td:nth-child(odd){
          color:rgb(26, 24, 24);
          font-size: 18px;
          padding: 15px 10px;
        }
    </style>
</head>
<body>
<section style="width: 100%;display: flex;background: #fff8f0;">
<div style="   width:20%; text-align: center;
    align-self: center;"><img src="{{ asset('public/images/1.png') }}"></div>
<div style="width:80%;text-align: center;"><h4 style="font-size: 2.8rem;
    text-align: center;
    margin-bottom: 0px;
    color: #962423;">Santhosha Vidhyalaya </h4>
    <p style="margin-bottom: 0px;
    text-align: center;
    font-weight: 600;
    font-size: 1.3rem;
    color: #962423;
">Admission Application Form 2024 - 2025</p>
    <p class="cinfo" style="color: #962423;">
       <span style="margin-right: 5px;"><i class="fas fa-phone"></i>  +91 80125 12100</span>
       <span style="margin-right: 5px;"><i class="fas fa-envelope"></i>  admissions@santhoshavidhyalaya.com </span>
       <span style="margin-right: 5px;"><i class="fas fa-map-marker-alt"></i>Dohnavur – 627102 Tirunelveli Dist. Tamilnadu</span>
    </p></div>
<div style="width: 20%; text-align: center;
    align-self: center;">
@if(isset($admission) && is_object($admission) && $admission->profile_photo)
    <img src="{{ asset('storage/app/profile_photos/' . $admission->profile_photo)}}" style="width: 63%;" alt="Profile Photo">
@else
    <img src="{{ asset('storage/app/profile_photos/no_pic.jpg')}}" style="width: 63%;" alt="No Image">
@endif

</div>

<?php
if(isset($admission) && is_object($admission)) {
    if(($admission->gender_1 == "Select" && $admission->gender_2 == "Select" && $admission->gender_3 == "Select") || ($admission->class_1 == "Select class" && $admission->class_2 == "Select class" && $admission->class_3 == "Select class")){
        $admission->gender_1 = "NA";
        $admission->gender_2 = "NA";
        $admission->gender_3 = "NA";
        $admission->class_1 = "NA";
        $admission->class_2 = "NA";
        $admission->class_3 = "NA";
    }
}
?>




</section>
<section class="students" style="width: 100%;background: #fff8f0;">
    <div style="width: 100%;">
        <h6 style="    font-size: 1rem;
        padding: 10px;
        border-bottom: 1px solid #962423;
        width: 300px;
        margin: 0px;
        font-weight: 600;">APPLICANT DETAILS</h6>
    </div>
    <div  style="width: 100%;">
    <table style="width: 100%;">
        <tr>
            <td>
                Name:
            </td>
            <td>
               
                {{$admission->name ?? 'NA'}}
   
            </td>
            <td>
                Date of Application :
            </td>
            <td>
                {{$admission->date_form ?? 'NA'}}
               
            </td>
            <td>
                Mothertongue of the pupil:
            </td>
            <td>
                {{$admission->language ?? 'NA'}}
                
            </td>
        </tr>
        <tr>
            <td>
                State:
            </td>
            <td>
                {{$admission->state_student ?? 'NA'}}
               
            </td>
            <td>
                Date of Birth 
            </td>
            <td>
                {{$admission->date_of_birth ?? 'NA'}}
              
            </td>
            <td>
                Gender
            </td>
            <td>
                {{$admission->gender ?? 'NA'}}
              
            </td>
        </tr>
        <tr>
            <td>Blood Group:</td>
            <td>   {{$admission->blood_group ?? 'NA'}}
               </td>
            <td>Nationality:</td>
            <td>  {{$admission->nationality ?? 'NA'}}
               </td>
            <td>Religion:</td>
            <td>   {{$admission->religion ?? 'NA'}}
              </td>
        </tr>
        <tr>
            <td>Church Denomination:</td>
            <td>    {{$admission->church_denomination?? 'NA'}}
                </td>
            <td>Caste:</td>
            <td>  {{$admission->caste?? 'NA'}}
                </td>
            <td>Caste Classification:</td>
            <td> {{$admission->caste_type?? 'NA'}}
                </td>
        </tr>
        <tr>
            <td>Aadhaar Card No:</td>
            <td>  {{$admission->aadhar_card_no?? 'NA'}}
               </td>
            <td>Ration Card No:</td>
            <td>  {{$admission->ration_card_no?? 'NA'}}
               </td>
            <td>EMIS NO (If the child studied in the state of TamilNadu):</td>
            <td>   {{$admission->emis_no?? 'NA'}}
               </td>
        </tr>
        <tr>
            <td>Vegetarian Or Non-Vegetarian:</td>
            <td>   {{$admission->veg_or_non?? 'NA'}}
                </td>
            <td>Details of Chronic Diseases, if any:</td>
            <td>  {{$admission->chronic_des?? 'NA'}}
                </td>
            <td>Are you taking any medicine or treatment at present?:</td>
            <td> {{$admission->medicine_taken?? 'NA'}}
                </td>
        </tr>
    </table>
</div>
</section>

<section class="students" style="width: 100%;background: #fff8f0;">
    <div style="width: 100%;">
        <h6 style="    font-size: 1rem;
        padding: 10px;
        border-bottom: 1px solid #962423;
        width: 300px;
        margin: 0px;
        font-weight: 600;">FAMILY DETAILS</h6>
    </div>
    <div  style="width: 100%;">
    <table style="width: 100%;">
        <tr>
            <td style="width:17%">
                Father's Name:
            </td>
            <td style="width:17%">
                {{$admission->father_name?? 'NA'}}
               
            </td>
            <td style="width:17%">
                Mother's Name:
            </td>
            <td style="width:17%">
                
                {{$admission->mother_name?? 'NA'}}
               
            </td>
            <td style="width:17%">
                Guardian's Name:
            </td>
            <td style="width:17%">
               
                {{$admission->guardian_name?? 'NA'}}
                
            </td>
        </tr>
        <tr>
            <td style="width:17%">
                Father's Profession:
            </td>
            <td style="width:17%">
                {{$admission->father_occupation?? 'NA'}}
                
            </td>
            <td style="width:17%">
                Mother's Profession 
            </td>
            <td style="width:17%">
                {{$admission->mother_occupation?? 'NA'}}
               
            </td>
            <td style="width:17%">
                Guardian's Profession:
            </td>
            <td style="width:17%">
                {{$admission->guardian_occupation?? 'NA'}}
              
            </td>
        </tr>
        <tr>
            <td style="width:17%">Father's Occupation: </td>
            <td style="width:17%">  {{$admission->father_occupation?? 'NA'}}</td>
            <td style="width:17%">Mother's Occupation:</td>
            <td style="width:17%">  {{$admission->first_name?? 'NA'}}</td>
            <td style="width:17%">Guardian's Occupation:</td>
            <td style="width:17%">  {{$admission->first_name?? 'NA'}}</td>
        </tr>
        <tr>
            <td style="width:17%">Father's Mobile No: </td>
            <td style="width:17%">    {{$admission->father_contact_no?? 'NA'}}
               </td>
            <td style="width:17%">Mother's Mobile No:</td>
            <td style="width:17%"> 
                {{$admission->mother_contact_no?? 'NA'}}
                </td>
            <td style="width:17%">Guardian's Mobile No:</td>
            <td style="width:17%">   {{$admission->guardian_contact_no?? 'NA'}}
               </td>
        </tr>
        <tr>
            <td style="width:17%">Father's Email ID: </td>
            <td style="width:17%">   {{$admission->father_email_id?? 'NA'}}
               </td>
            <td style="width:17%">Mother's Email ID:</td>
            <td style="width:17%">   {{$admission->mother_email_id?? 'NA'}}
               
                </td>
            <td style="width:17%">Guardian's Email ID:</td>
            <td style="width:17%">  {{$admission->guardian_email_id?? 'NA'}}
               </td>
        </tr>
             <tr>
            <td style="width:17%">Father's Income: </td>
            <td style="width:17%">   {{$admission->father_income?? 'NA'}}
               </td>
            <td style="width:17%">Mother's Income:</td>
            <td style="width:17%">   {{$admission->mother_income?? 'NA'}}
               
                </td>
            <td style="width:17%">Guardian's Income:</td>
            <td style="width:17%">  {{$admission->guardian_income?? 'NA'}}
               </td>
        </tr>
             <tr>
            <td style="width:17%">Father's Organization Employed: </td>
            <td style="width:17%">   {{$admission->father_organization?? 'NA'}}
               </td>
            <td style="width:17%">Mother's Organization Employed:</td>
            <td style="width:17%">   {{$admission->mother_organization?? 'NA'}}
               
                </td>
            <td style="width:17%">Guardian's Organization Employed:</td>
            <td style="width:17%">  {{$admission->guardian_organization?? 'NA'}}
               </td>
        </tr>
       <tr>
        <td colspan="5">Information of your brother (s) & sister (s) (If studying in school):</td>
       </tr>
       <tr>
        <th>Name</th>
        <th>Gender</th>
        <th>Class</th>
    </tr>
    <tr>
        <td> {{$admission->brother_1?? 'NA'}}</td>
        <td>{{$admission->gender_1?? 'NA'}}</td>
        <td>{{$admission->class_1?? 'NA'}}</td>
    </tr>
    
    <tr>
        <td>{{$admission->brother_2?? 'NA'}}</td>
        <td>{{$admission->gender_2?? 'NA'}}</td>
        <td>{{$admission->class_2?? 'NA'}}</td>
    </tr>
       <tr>
        <td>{{$admission->brother_3?? 'NA'}}</td>
        <td>{{$admission->gender_3?? 'NA'}}</td>
        <td>{{$admission->class_3?? 'NA'}}</td>
    </tr>
    </table>
</div>
</section>
<section class="address" style="width: 100%;background: #fff8f0;">
    <div style="width: 100%;">
        <h6 style="    font-size: 1rem;
        padding: 10px;
        border-bottom: 1px solid #962423;
        width: 300px;
        margin: 0px;
        font-weight: 600;">PERMANENT ADDRESS</h6>
    </div>
    <div>
        <table>
            <tr>
                <td style="width:17%">House NO: </td>
                <td style="width:17%">   {{$admission->house_no?? 'NA'}}
                   </td>
                <td style="width:17%">Street Name:</td>
                <td style="width:17%">   {{$admission->street?? 'NA'}}
                   </td>
                <td style="width:17%">Village:</td>
                <td style="width:17%">  {{$admission->city?? 'NA'}}
                   </td>
            </tr>
            <tr>
                <td style="width:17%">Post office: </td>
                <td style="width:17%">   {{$admission->district?? 'NA'}}
                    </td>
                <td style="width:17%">State: </td>
                <td style="width:17%"> {{$admission->state?? 'NA'}}
                    </td>
                <td style="width:17%">Pincode:</td>
                <td style="width:17%">  {{$admission->pincode?? 'NA'}}
                    </td>
            </tr>
        
        </table>
    </div>
</section>
<section class="address" style="width: 100%;background: #fff8f0;">
    <div style="width: 100%;">
        <h6 style="    font-size: 1rem;
        padding: 10px;
        border-bottom: 1px solid #962423;
        width: 300px;
        margin: 0px;
        font-weight: 600;">COMMUNICATION ADDRESS</h6>
    </div>
    <div>
        <table>
            <tr>
                <td style="width:17%">House NO: </td>
                <td style="width:17%">  {{$admission->house_no_1?? 'NA'}}
                   </td>
                <td style="width:17%">Street Name:</td>
                <td style="width:17%">   {{$admission->street_1?? 'NA'}}
                   </td>
                <td style="width:17%">Village:</td>
                <td style="width:17%">   {{$admission->city_1?? 'NA'}}
                    </td>
            </tr>
            <tr>
                <td style="width:17%">Post office: </td>
                <td style="width:17%">  {{$admission->district_1?? 'NA'}}
                    </td>
                <td style="width:17%">State: </td>
                <td style="width:17%">  {{$admission->state_1?? 'NA'}}
                    </td>
                <td style="width:17%">Pincode:</td>
                <td style="width:17%">  {{$admission->pincode_1?? 'NA'}}
                    </td>
            </tr>
            
        </table>
    </div>
</section>
<section class="address" style="width: 100%;background: #fff8f0;">
    <div style="width: 100%;">
        <h6 style="    font-size: 1rem;
        padding: 10px;
        border-bottom: 1px solid #962423;
        width: 600px;
        margin: 0px;
        font-weight: 600;text-transform: uppercase;">References (The administration will contact the references given)</h6>
    </div>
     <table>
            <tr>
                <td style="width:17%">Name: </td>
                <td style="width:17%">  {{$admission->reference_name_1?? 'NA'}}
                    </td>
                <td style="width:17%">Phone Number:</td>
                <td style="width:17%">  {{$admission->reference_phone_1?? 'NA'}}
                    </td>
                         <td style="width:17%">Name: :</td>
                <td style="width:17%">  {{$admission->reference_name_2?? 'NA'}}
                    </td>
               
            </tr>
             <tr>
              
                <td style="width:17%">Phone Number:</td>
                <td style="width:17%">  {{$admission->reference_phone_2?? 'NA'}}
                    </td>
                      
               
            </tr>
            </table>
    </section>

<section class="address" style="width: 100%;background: #fff8f0;">
    <div style="width: 100%;">
        <h6 style="    font-size: 1rem;
        padding: 10px;
        border-bottom: 1px solid #962423;
        width: 600px;
        margin: 0px;
        font-weight: 600;text-transform: uppercase;">Group & Language Choice </h6>
    </div>
    <div>
        <table>
            <tr>
                <td style="width:17%">Class last Studied: </td>
                <td style="width:17%">  {{$admission->last_class_std?? 'NA'}}
                    </td>
                <td style="width:17%">Name of School Last Studied :</td>
                <td style="width:17%">  {{$admission->last_school?? 'NA'}}
                    </td>
                         <td style="width:17%">Location of School last studied (Mention District & State): :</td>
                <td style="width:17%">  {{$admission->last_school_state?? 'NA'}}
                    </td>
               
            </tr>
            <tr>
                 <td style="width:17%">Class for which admission is sought Std :</td>
                <td style="width:17%">  {{$admission->admission_for_class?? 'NA'}}
                    </td>
                <td style="width:17%">Last Studied Syllabus: </td>
                <td style="width:17%">  {{$admission->syllabus?? 'NA'}}
                   </td>
                <td style="width:17%">Please choose first preference:</td>
                <td style="width:17%">   {{$admission->group_no?? 'NA'}}
                    </td>
                
            </tr>
            <tr>
                
                <td style="width:17%">Second Choice of Group (Only for Class 11):</td>
                <td style="width:17%"> {{$admission->second_group_no?? 'NA'}}
                    </td>
                <td style="width:17%">Language (Only for Class 11): ( for std . XI only): </td>
                <td style="width:17%"> {{$admission->second_language?? 'NA'}}</td>
                 <td style="width:17%">Did the child study Tamil as Second Language::</td>
                <td style="width:17%"> {{$admission->second_language_school?? 'NA'}}
                    </td>
            </tr>
        </table>
    </div>
</section>

</body>
</html>

