import React,{useState} from 'react';
// import './dashboard.css';
import Sidebar from '../Sidebar';
import Header from '../Header';
import Footer from '../Footer';
import Paper from '@mui/material/Paper'; 
import {GrAddCircle} from 'react-icons/gr';
import {BsBackspace} from 'react-icons/bs';
import Button from 'react-bootstrap/Button';
import Col from 'react-bootstrap/Col';
import Form from 'react-bootstrap/Form';
import Row from 'react-bootstrap/Row';

const Msponsor = () => {
    const [validated, setValidated] = useState(false);

    const handleSubmit = (event) => {
      const form = event.currentTarget;
      if (form.checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
      }
  
      setValidated(true);
    };
  return (
    <div>
       
    <Sidebar/>
 <div style={{width:'82.5%',float:'right'}} >
   <Header/>
    <div className='p-4'>
    <Paper elevation={2} className="pb-5">
            <div className='row'>
              <div className='col-6'>
               <h3 className='p-4'><GrAddCircle size={35} className='pe-2 pb-1'/>Sponsor Details form</h3>
              </div>
              <div className='col-6 text-end'>
               <a href="/Masters/AddSponsorlist"><BsBackspace size={60} style={{paddingTop:'20px',cursor:'pointer',color:'red'}}/></a>
              </div>
            </div>
            <div className='pt-3'>
            <Form noValidate validated={validated} onSubmit={handleSubmit} className='container'>
      <Row className="mb-3">
        <Form.Group as={Col} md="6" controlId="validationCustom01">
          <Form.Label>Full Name</Form.Label>
          <Form.Control
            required
            type="text"
            placeholder="First name"
            defaultValue=""/>
          <Form.Control.Feedback>Looks good!</Form.Control.Feedback>
        </Form.Group>
        <Form.Group as={Col} md="6" controlId="validationCustom02">
          <Form.Label>Occupation</Form.Label>
          <Form.Control
            required
            type="text"
            placeholder="Business or working proposition"
            defaultValue="" />
          <Form.Control.Feedback>Looks good!</Form.Control.Feedback>
        </Form.Group>

      </Row>
      <Row className="mb-3">
        <Form.Group as={Col} md="6" controlId="validationCustom01">
          <Form.Label>Company's Name</Form.Label>
          <Form.Control
            required
            type="text"
            placeholder="example company"
            defaultValue=""/>
          <Form.Control.Feedback>Looks good!</Form.Control.Feedback>
        </Form.Group>
        <Form.Group as={Col} md="6" controlId="validationCustom02">
          <Form.Label>Location</Form.Label>
          <Form.Control
            required
            type="text"
            placeholder="Ex: Chennai"
            defaultValue="" />
          <Form.Control.Feedback>Looks good!</Form.Control.Feedback>
        </Form.Group>
      </Row>

      <Row className="mb-3">
        <Form.Group as={Col} md="6" controlId="validationCustom01">
          <Form.Label>Email ID</Form.Label>
          <Form.Control
            required
            type="text"
            placeholder="example@gmail.com"
            defaultValue=""/>
          <Form.Control.Feedback>Looks good!</Form.Control.Feedback>
        </Form.Group>
        <Form.Group as={Col} md="6" controlId="validationCustom02">
          <Form.Label>Phone Number</Form.Label>
          <Form.Control
            required
            type="number"
            placeholder="9840xxxxx5"
            defaultValue="" />
          <Form.Control.Feedback>Looks good!</Form.Control.Feedback>
        </Form.Group>
        </Row>

      <Row className="mb-3">
        <Form.Group as={Col} md="6" controlId="validationCustom01">
          <Form.Label>Address Line1</Form.Label>
          <Form.Control
            required
            type="text"
            placeholder=""
            defaultValue=""/>
          <Form.Control.Feedback>Looks good!</Form.Control.Feedback>
        </Form.Group>
        <Form.Group as={Col} md="6" controlId="validationCustom02">
          <Form.Label>Address Line2</Form.Label>
          <Form.Control
            required
            type="text"
            placeholder=""
            defaultValue="" />
          <Form.Control.Feedback>Looks good!</Form.Control.Feedback>
        </Form.Group>
        </Row>

      <Row className="mb-3">
        <Form.Group as={Col} md="4" controlId="validationCustom01">
          <Form.Label>City</Form.Label>
          <Form.Control
            required
            type="text"
            placeholder=""
            defaultValue=""/>
          <Form.Control.Feedback>Looks good!</Form.Control.Feedback>
        </Form.Group>
        <Form.Group as={Col} md="4" controlId="validationCustom02">
          <Form.Label>State</Form.Label>
          <Form.Control
            required
            type="text"
            placeholder=""
            defaultValue="" />
          <Form.Control.Feedback>Looks good!</Form.Control.Feedback>
        </Form.Group>
        <Form.Group as={Col} md="4" controlId="validationCustom02">
          <Form.Label>Pincode</Form.Label>
          <Form.Control
            required
            type="number"
            placeholder=""
            defaultValue="" />
          <Form.Control.Feedback>Looks good!</Form.Control.Feedback>
        </Form.Group>
        </Row>

      <div className='pt-3'>
       <Button  type="submit">Submit form</Button>
      </div>
    </Form>
            </div>
          </Paper>
    </div>
 </div>
 </div>
  )
}

export default Msponsor
