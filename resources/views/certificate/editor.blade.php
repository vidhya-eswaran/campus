<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Course Completion Certificate</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jodit/3.18.4/jodit.min.css" />
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Times+New+Roman:wght@400;700&display=swap");

    body {
      font-family: "Times New Roman", serif;
      margin: 0;
      padding: 0;
      background-color: #f8f8f8;
    }

    .certificate-container {
      display: flex;
      max-width: 800px;
      background-color: white;
      margin: 30px auto;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      position: relative;
      overflow: hidden;
    }

    .certificate-container::before {
      content: "";
      position: absolute;
      top: 50%;
      left: 53%;
      width: 700px;
      height: 700px;
      background: url("https://santhoshavidhyalaya.com/svsportaladmintest/static/media/newlogo.f86bd51493e0e8166940.jpg") no-repeat center;
      background-size: contain;
      opacity: 0.03;
      transform: translate(-50%, -50%);
      z-index: 0;
    }

    .left-bar {
      background-color: #b91c1c;
      width: 60px;
      position: absolute;
      left: 0;
      top: 0;
      bottom: 0;
      z-index: 1;
    }

    .right-bar {
      background-color: #b91c1c;
      height: 60px;
      width: 30%;
      z-index: 1;
    }

    .content {
      padding-left: 80px;
      width: 100%;
      position: relative;
      z-index: 1;
    }

    .header {
      display: flex;
      align-items: center;
      width: 100%;
      padding-top: 20px;
    }

    .logo {
      width: 140px;
      height: 140px;
    }

    .sign-logo-div {
      padding: 20px;
      padding-top: 50px;
      display: flex;
      justify-content: end;
    }

    .sign-logo {
      width: 180px;
      height: 180px;
    }

    .school-details {
      text-align: center;
      margin-left: 10px;
      width: 70%;
    }

    .school-details h1 {
      color: #b91c1c;
      font-size: 22px;
      margin: 0;
      font-weight: bold;
    }

    .school-details p {
      font-size: 14px;
      color: black;
      margin: 0;
    }

    .date {
      text-align: right;
      font-weight: bold;
      margin-top: 10px;
    }

    .certificate-title {
      text-align: center;
      font-size: 20px;
      text-decoration: underline;
      font-weight: bold;
      margin-top: 15px;
    }

    p {
      font-size: 16px;
      line-height: 1.6;
    }

    table {
      width: 100%;
      margin-top: 10px;
      font-size: 16px;
    }

    td {
      padding: 5px;
    }

    .img-top {
      width: 20%;
    }

    .footer-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      max-width: 900px;
      background-color: white;
      margin-top: 50px;
    }

    .contact-info {
      color: #b91c1c;
      font-size: 16px;
      font-weight: 600;
    }

    .address-box {
      background-color: #b91c1c;
      color: white;
      font-size: 16px;
      font-weight: bold;
      text-align: center;
      width: 60%;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2 style="text-align: center">Jodit HTML to PDF Converter</h2>
    <textarea id="jodit_editor" name="jodit_html">
      <!-- Corrected HTML Template with Static Content -->
      <div class="certificate-container">
        <div class="left-bar"></div>
        <div class="content">
          <div class="header">
            <div class="img-top">
              <img
                src="https://santhoshavidhyalaya.com/svsportaladmintest/static/media/newlogo.f86bd51493e0e8166940.jpg"
                alt="School Logo"
                class="logo"
              />
            </div>
            <div class="school-details">
              <h1>SANTHOSHA VIDHYALAYA</h1>
              <p>Matriculation & Higher Secondary School</p>
            </div>
            <div class="right-bar"></div>
          </div>

          <div class="date">Date: ______________</div>

          <h2 class="certificate-title">Course Completion Certificate</h2>

          <p>
            This is to certify that
            <span class="underline">________________________</span>, S/O or D/O
            <span class="underline">________________________</span>, was a student
            of the Higher Secondary Course in this school from
            <span class="underline">_______</span> to
            <span class="underline">_______</span>. He/She appeared for the higher
            secondary (+2) Examination through this school in March 2023 and
            passed successfully in the first attempt. For details of his/her
            marks, please refer to the mark certificate.
          </p>

          <p>
            He/She has completed the course successfully in the following
            subjects:
          </p>

          <table>
            <tr>
              <td><strong>Part 1</strong></td>
              <td>:</td>
              <td>_________</td>
            </tr>
            <tr>
              <td><strong>Part 2</strong></td>
              <td>:</td>
              <td>_________</td>
            </tr>
            <tr>
              <td><strong>Part 3</strong></td>
              <td>:</td>
              <td>_________</td>
            </tr>
          </table>

          <div class="sign-logo-div">
            <img
              src="https://www.santhoshavidhyalaya.com/SVSTEST/public/images/signature.png"
              alt="signature Logo"
              class="sign-logo"
            />
          </div>

          <div class="footer-container">
            <div class="contact-info">
              <p class="">
                <strong>www.santhoshavidhyalaya.com</strong><br />
                +91 80125 12100, +91 80125 12143<br />
                info@santhoshavidhyalaya.com
              </p>
            </div>
            <div class="address-box">
              <p>
                Dohnavur Fellowship, Dohnavur - 627102<br />
                Tirunelveli District, Tamil Nadu, India
              </p>
            </div>
          </div>
        </div>
      </div>
    </textarea>

    <button class="btn-generate" onclick="generatePdf()">Generate PDF</button>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jodit/3.18.4/jodit.min.js"></script>
  <script>
    // Initialize Jodit Editor
    const editor = new Jodit("#jodit_editor", {
      height: 600,
      toolbarSticky: false,
      uploader: { insertImageAsBase64URI: true },
    });

    // Generate PDF from HTML Template
    function generatePdf() {
      const htmlContent = editor.value;

      fetch("/SVSTEST/api/templateeditor/generate-pdf", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": "{{ csrf_token() }}",
        },
        body: JSON.stringify({ jodit_html: htmlContent }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.pdf_link) {
            alert("PDF Generated Successfully!");
            window.open(data.pdf_link, "_blank");
          } else {
            alert("Error generating PDF!");
          }
        })
        .catch((error) => console.error("Error:", error));
    }
  </script>
</body>
</html>
