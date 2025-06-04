<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login Form HTML Design</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style-1.css') }}">
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
                    <p>Dohnavur Fellowship</p>
                    <p class="cinfo">
                        <span><i class="fas fa-phone"></i> +91 987 676 5459</span>
                        <span><i class="fas fa-envelope"></i> adarsvidyakendra@gmail.com</span>
                        <span><i class="fas fa-map-marker-alt"></i> Smart City, Toranto, Canada</span>
                    </p>

                </div>
            </div>
        <form method="POST" action="{{ route('admission.store') }}"  enctype="multipart/form-data">
            @csrf

 <p>Note: Please review the form and identify the mandatory details marked with an asterisk (*). Gather the necessary information and start the form-filling process. Upon form submission, you will be required to pay an application fee of Rs. 300 to complete the process with debit / credit card or internet banking.</p>

            <div class="form-body">
                <div class="form-title row">
                    <h4>Student Information</h4>
                </div>







                
                <div class="form-row row">
                    <div class="col-lg-2 col-md-4">
                        <label for="">First Name</label>
                        <sup class="req">*</sup>
                        <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                        <input type="text" name="first_name" id="first_name" placeholder="Enter First Name"
                            class="form-control form-control-sm">
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label for="">Last Name</label>
                        <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                        <input type="text" name="last_name" id="last_name" placeholder="Enter Last Name"
                            class="form-control form-control-sm">
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
                            class="form-control form-control-sm">
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label for="">Gender</label>
                        <sup class="req">*</sup>
                        <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8 pt-1">
                        <input type="radio" name="gender"> Male &nbsp;&nbsp;
                        <input type="radio" name="gender"> Female &nbsp;&nbsp;
                        <input type="radio" name="gender"> Other
                    </div>
                </div>

                <div class="form-title row">
                    <h4>Parent Details</h4>
                </div>

                <div class="form-row row">
                    <div class="col-lg-2 col-md-4">
                        <label for="">Father Name</label>
                        <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                        <input type="text" name="father_name" placeholder="Enter Father Name"
                            class="form-control form-control-sm">
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label for="">Father Profession</label>
                        <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                        <input type="text" name="father_occupation" id="father_occupation" placeholder="Enter Father Occupation"
                            class="form-control form-control-sm">
                    </div>
                </div>

                <div class="form-row row">
                    <div class="col-lg-2 col-md-4">
                        <label for="">Mother Name</label>
                        <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                        <input type="text" name="mother_name" id="mother_name" placeholder="Enter Mother Name"
                            class="form-control form-control-sm">
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label for="">Mother Profession</label>
                        <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                        <input type="text" name="mother_occupation" placeholder="Enter Mother Occupation"
                            class="form-control form-control-sm">
                    </div>
                </div>

                <div class="form-row row">
                    <div class="col-lg-2 col-md-4">
                        <label for="">Father Contact No</label>
                        <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                        <input type="text" name="father_contact_no" placeholder="Enter Father Contact No"
                            class="form-control form-control-sm">
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label for="">Mother Contact No</label>
                        <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                        <input type="text" name="mother_contact_no" placeholder="Enter Mother Contact No"
                            class="form-control form-control-sm">
                    </div>
                </div>




                <div class="form-title row">
                    <h4>Contact Information</h4>
                </div>

                <div class="form-row row">
                    <div class="col-lg-2 col-md-4">
                        <label for="">Mobile Number</label>
                        <sup class="req">*</sup>
                        <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                        <input type="text" name="mobile_number" placeholder="Enter Mobile Numbber"
                            class="form-control form-control-sm">
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label for="">Email Address</label>
                        <sup class="req">*</sup>
                        <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                        <input type="text" name="email" placeholder="Enter Email Address"
                            class="form-control form-control-sm">
                    </div>
                </div>

                <div class="form-row row">
                    <div class="col-lg-2 col-md-4">
                        <label for="">City</label>
                        <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                        <input type="text" name="city" placeholder="Enter City"
                            class="form-control form-control-sm">
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label for="">State</label>
                        <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                        <input type="text" name="state" placeholder="Enter State"
                            class="form-control form-control-sm">
                    </div>
                </div>

                <div class="form-row row">
                    <div class="col-lg-2 col-md-4">
                        <label for="">Country</label>
                        <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                        <input type="text" name="country" placeholder="Enter Country"
                            class="form-control form-control-sm">
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label for="">Postal Code</label>
                        <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                        <input type="text" name="pincode" placeholder="Enter City"
                            class="form-control form-control-sm">
                    </div>
                </div>

                <div class="form-row row">
                    <div class="col-lg-2 col-md-4">
                        <label for="">Full Address</label>
                        <span class="indc">:</span>
                    </div>
                    <div class="col-lg-4 col-md-8">
                        <textarea type="text" name="address" rows="5" placeholder="Enter Full Address"
                            class="form-control form-control-sm"></textarea>
                    </div>
                    <div class="col-lg-2 col-md-4">

                    </div>
                    <div class="col-lg-4 col-md-8">

                    </div>
                </div>
                <div class="form-row row">
                    <div class="col-lg-2 col-md-4">
                        <p> 1.Child Passport Size Photo: <span class="wpcf7-form-control-wrap"
                                data-name="file-987"><input size="40" type="file"
                                    class="wpcf7-form-control wpcf7-file valid" accept="audio/*,video/*,image/*"
                                    aria-invalid="false" type="photo" name="profile_photo"></span>
                        </p>
                    </div>

                    <div class="col-lg-2 col-md-4">
                        <p> 1.Child Passport Size Photo: <span class="wpcf7-form-control-wrap"
                                data-name="file-987"><input size="40" type="file"
                                    class="wpcf7-form-control wpcf7-file valid" accept="audio/*,video/*,image/*"
                                    aria-invalid="false" type="photo" name="admission_photo"></span>
                        </p>
                    </div>
                    <div class="col-lg-4 col-md-8">
                    </div>
                    <div class="col-lg-2 col-md-4">
                    </div>
                    <div class="col-lg-4 col-md-8">
                        <button type="submit" class="btn btn-sm btn-primary">Submit Form</button>
                    </div>
                </div>
            </div>
</form>
        </div>
    </div>

</body>

</html
