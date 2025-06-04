                <form id="combinationdrug_add_form" autocomplete="off" role="form" enctype="multipart/form-data" method="POST" action="{{ route('store') }}">
                        @csrf

 <div class="container form-container">
      <div class=" col-lg-12 mx-auto login-container">
         
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
                <input type="text" placeholder="Enter First Name" name="name" class="form-control form-control-sm">
              </div>
              </div>
               <div class="form-row row">
                    <!-- Other form fields -->

                    <div class="col-lg-2 col-md-4">
                        <label for="file">File Upload</label>
                    </div>
                    <div class="col-lg-4 col-md-8">
                        <input type="file" name="file" class="form-control form-control-sm">

                    </div>
                </div>
                </div>
              </div>
              
                                              <button type="submit" id="combinationdrugAddBtn" class="btn btn-primary">{{ __('Add Combination Drug') }}</button>

                </form>