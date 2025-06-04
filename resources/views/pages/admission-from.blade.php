<!DOCTYPE html>
<html lang="en">
<head>
   
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>

<link rel="stylesheet" href="{{ asset('public/style-admission.css') }}">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <img src="{{asset ('public/images/1.jpg')}}" alt="" srcset="">
            </div>
            <div class="col-md-8">
                <div class="title">
                <h1>SANTHOSHA VIDHYALAYA</h1>
                  <h6>Dohnavur Fellowship</h6>
                  <p>ADMISSION FORM</p>
                </div>
            </div>
            <div class="col-md-2">
                <div id='profile-upload'>
                    <div class="hvr-profile-img"><input type="file" name="logo" id='getval'  class="upload w180" title="Dimensions 180 X 180" id="imag" placeholder="DROP PICS"></div>
                      <i class="fa fa-camera"></i>
                      </div>
                    
                    
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="headings">
           <h3>Class last Studied & Name of school</h3>
           </div>
           <div class="col-md-4">
               Date of Application :             <input data-date-format="dd/mm/yyyy" id="datepicker">
</div>
</div>
</div>
       
[submit "SUBMIT FORM"]
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>

<script>
    document.getElementById('getval').addEventListener('change', readURL, true);
function readURL(){
    var file = document.getElementById("getval").files[0];
    var reader = new FileReader();
    reader.onloadend = function(){
        document.getElementById('profile-upload').style.backgroundImage = "url(" + reader.result + ")";        
    }
    if(file){
        reader.readAsDataURL(file);
    }else{
    }
}
</script>
<script type="text/javascript">
    $('#datepicker').datepicker({
        weekStart: 1,
        daysOfWeekHighlighted: "6,0",
        autoclose: true,
        todayHighlight: true,
    });
    $('#datepicker').datepicker("setDate", new Date());
</script>
</body>
</html>