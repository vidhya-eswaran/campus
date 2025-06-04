import React,{useRef} from 'react';
// import './dashboard.css';
import Header from '../Header';
import Sidebar from '../Sidebar';
import Footer from '../Footer';
import Breadcrumb from 'react-bootstrap/Breadcrumb';
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import logo from '../MangerUser/logo.jpeg'
import {useReactToPrint} from 'react-to-print';
import Swal from 'sweetalert2';
import {AiFillPrinter} from 'react-icons/ai';
import Button from '@mui/material/Button';

const Viewprofile = () => {




  const componentRef = useRef();
  const handlePrint = useReactToPrint({
      content: () => componentRef.current,
      documentTitle:'Leave Data',
      onAfterPrint:()=> Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'File Download Successfully',
        showConfirmButton: false,
        timer: 1700
      })
  })
  return (
    <div>
      <Sidebar/>
       <div style={{width:'82.5%',float:'right'}} >
         <Header/>
         <div className='row'>
          <section className='p-4 col-6'>      
              <Breadcrumb>
                  <Breadcrumb.Item href="/Profile">Student_Profile</Breadcrumb.Item>
                  <Breadcrumb.Item active>View_Detail</Breadcrumb.Item>
              </Breadcrumb>
          </section>
          <section className='col-6 text-end px-5 py-4' >
            <Button onClick={handlePrint}  style={{color :'#E91E63'}} role="button"><AiFillPrinter className='pe-2' size={35}/>Print</Button>
          </section>   
         </div>
            <div ref={componentRef} className='pt-3' >
        <section className='px-5 pb-4' >
            <div style={{border:'1px solid black'}} className='container'>
                <div className='d-flex'>
                    <img style={{width:'8%'}} src={logo} />
                    <h4 className='pt-4 ps-3'>Santhosha Vidhyalaya Higher secondary school, Dohnavur campus</h4>
                </div><hr/>
                <div className='row'>
                    <div className='col-4'>
                      <p>Full Name</p>
                      <p>Register no</p>
                      <p>Std & Sec </p>
                      <p>Gender</p>
                      <p>Official Email</p>
                      <p>Contact Number</p>
                      <p>Alternate number</p>
                    </div>
                    <div className='col-4'>
                      <p>: Daniel Grant.R</p>
                      <p>: 12201</p>
                      <p>: XII-B</p>
                      <p>: Male</p>
                      <p>: dg5177@svs.edu.in</p>
                      <p>: 727623892</p>
                      <p>: 982882735</p>
                    </div>
                    <div className='col-4'>
                      <img className="imageSizeHrtable" src="https://nearyou.imeche.org/images/default-source/sofe-2012-edinburgh-heat/passport-size.jpg?sfvrsn=2" alt="Preview"/>
                    </div>
                  </div><hr/>
                  <div className='row'>
                    <div className='col-4'>
                      <p>Group</p>
                      <p>DOB</p>
                      <p>Blood Group</p>
                      <p>Identification Marks</p>
                    </div>
                    <div className='col-4'>
                      <p>: Biology with Maths</p>
                      <p>: 31/08/2001</p>
                      <p>: B+ postive</p>  
                      <p>: Birthmarks, moles, body piercings</p>  
                    </div>
                  </div><hr/>
                  <div className='row'>
                    <h4 className='pb-2'>Parents Details</h4>

                    <div className='col-4'>
                      <p>Father Name</p>
                      <p>Father's occupation </p>
                      <p>Mother Name</p>
                      <p>Mother's occupation </p>
                    </div>
                    <div className='col-8'>
                      <p>: Mark Antony</p>
                      <p>: Bussiness</p>
                      <p>: Jessica Alba</p>
                      <p>: House wife</p>
                    </div>
                  </div><hr/>
                  <div className="row">
                    <h4 className='p-2'>Address</h4>
                     <div className='col-4'>
                      <p>Communication Address</p>
                      <p>Permanent</p>
                     </div>
                     <div className='col-8'>
                      <p>: 11/7,payappet street, sevenwell, Dohnavur-627102</p>
                      <p>: old no-4, guajppan stareet , nungabakkam, Chennai-60021</p>
                     </div>
                  </div><hr/>
                </div>
         </section>
         </div>
       </div>
   </div>
  )
}

export default Viewprofile


     {/* <section className='container'>
          
            {/* <Row>
            <Col xs={4} style={{paddingLeft:'100px'}}>
                  <h3>Daniel Grant.R</h3>
                  <p>Student  XII-B</p>
                  <p style={{fontWeight:'600'}}>Postion: Sports vice-captain</p>
                </Col>
                <Col xs={4} className='text-center' >
                  <img style={{width:'50%'}} src='https://nearyou.imeche.org/images/default-source/sofe-2012-edinburgh-heat/passport-size.jpg?sfvrsn=2' alt='boy-img'/>
                </Col>
              
            </Row>
            <Row className='pt-3'>
                <Col xs={6} style={{paddingLeft:'100px'}} >
                <h4>Register No :</h4>
                <h4>Name :</h4>
                <h4>Age :</h4>
                <h4>Gender :</h4>
                <h4>Email :</h4>
                <h4>Phone No :</h4>
                </Col>
                <Col xs={6}>
                <h5>12201</h5>
                <h5>Daniel Grant.R</h5>
                <h5>19</h5>
                <h5>Male</h5>
                <h5>dg5177@svs.edu.in</h5>
                <h5>98408165</h5>
                </Col>
            </Row> </section> */}