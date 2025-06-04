import React, { useState, useEffect } from 'react';
import Select from 'react-select';
// import axios from 'axios';
import Modal from 'react-bootstrap/Modal';
import Sidebar from '../Sidebar';
import Header from '../Header';
import Footer from '../Footer';
import Table from 'react-bootstrap/Table';
import Button from 'react-bootstrap/Button';
import Accordion from 'react-bootstrap/Accordion'
import {CgComponents} from 'react-icons/cg';
import {MdDelete} from 'react-icons/md';
import Checkbox from '@mui/material/Checkbox';
import FormGroup from '@mui/material/FormGroup';
import FormControlLabel from '@mui/material/FormControlLabel';
import FormControl from '@mui/material/FormControl';
import FormLabel from '@mui/material/FormLabel';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import InputLabel from '@mui/material/InputLabel';
import MenuItem from '@mui/material/MenuItem';
import FloatingLabel from 'react-bootstrap/FloatingLabel';
import Form from 'react-bootstrap/Form';
import { DemoContainer } from '@mui/x-date-pickers/internals/demo';
import { AdapterDayjs } from '@mui/x-date-pickers/AdapterDayjs';
import { LocalizationProvider } from '@mui/x-date-pickers/LocalizationProvider';
import { DatePicker } from '@mui/x-date-pickers/DatePicker';
import {MdCheckBox} from 'react-icons/md';
import {FaRegEdit} from 'react-icons/fa';
import {BsCompass} from 'react-icons/bs';

const Feesmaping = () => {

  const [show, setShow] = useState(false);

  const handleClose = () => setShow(false);
  const handleShow = () => setShow(true);


  

  const [year, setYear] = React.useState('');

  const handleChange = (event) => {
    setYear(event.target.value);
  };


  const [options, setOptions] = useState([]);
  const [loading, setLoading] = useState(false);

  // API///////////////////////////////
  // useEffect(() => {
  //   setLoading(true);
  //   axios.get('https://example.com/api/options')
  //     .then(response => {
  //       setOptions(response.data);
  //       setLoading(false);
  //     })
  //     .catch(error => {
  //       console.log(error);
  //       setLoading(false);
  //     });
  // }, []);

  return (
    <div>
       
        <Sidebar/>
    <div style={{width:'82.5%',float:'right'}} >
    <Header/>
      <div className='p-4' style={{backgroundColor:'#F7F7F7'}}>
        <div className='px-2 py-1'>
        <h4>Fee Mapping</h4>
        <hr className='feeMapping'/>
        </div>
      <Accordion>
 {/*-------------------------- 1-Standard--------------------------------------------- */}
      <Accordion.Item eventKey="0">
        <Accordion.Header>
          <CgComponents size={40} className='pe-2'/><h5>1th Standard</h5>
        </Accordion.Header>
        <Accordion.Body style={{backgroundColor:'#f3f3f3'}}>
          <FormGroup>
          <h4 style={{fontWeight:'400'}}>Total Annual fees</h4>
          <Row>
            <Col xs={4} >
             <label className='pb-3 ps-2'>Fee Category</label>
             <Form.Select className='custom-input' aria-label="Default select example" style={{width:'100%',height:'55px'}}>
                <option>Select Fee Category</option>
                <option value="1">Digital Education</option>
                <option value="2">X seed Education</option>
                <option value="3">ID Card</option>
                <option value="4">Stationery items</option>
                <option value="5">Group photo</option>
                <option value="6">Functions</option>
              </Form.Select>
            </Col>
            <Col xs={4}>
             <label className='pb-3 ps-2'>Sub Category</label>
             <Form.Select className='custom-input' aria-label="Default select example" style={{width:'100%',height:'55px'}}>
                <option>Select Sub Category</option>
                <option value="4">Uniform shoe Uniform socks</option>
                <option value="5">School Uniform dress</option>
                <option value="6">General medicine</option>
                <option value="5">School Uniform dress</option>
                <option value="6">General medicine</option>
              </Form.Select>
            </Col>
            <Col xs={4}>
             <label className='pb-3 ps-2'>Student</label>
             <Form.Select className='custom-input' aria-label="Default select example" style={{width:'100%',height:'55px'}}>
                <option>Select Student</option>
                <option value="4">Uniform shoe Uniform socks</option>
                <option value="5">School Uniform dress</option>
                <option value="6">General medicine</option>
                <option value="5">School Uniform dress</option>
                <option value="6">General medicine</option>
              </Form.Select>
            </Col>
          </Row><hr className='py-3'/>

            <Row>
            <Col className='pt-2'>
              <FormControl fullWidth>
                      <Form.Select  style={{width:'100%',height:'55px'}}>
                          <option>select year</option>
                          <option value="2023">2023</option>
                          <option value="2024">2024</option>
                          <option value="2025">2025</option>
                        </Form.Select>
                   </FormControl>
              </Col>
              <Col className='pt-2'> 
                 <FloatingLabel controlId="floatingPassword" label="₹ Amount">
                   <Form.Control className="custom-input" type="number" placeholder="₹ Amount" />
                </FloatingLabel>
              </Col>
              <Col>
                <LocalizationProvider dateAdapter={AdapterDayjs} style={{paddingTop:'0px'}}>
                      <DemoContainer components={['DatePicker']}>
                        <DatePicker label="Select Date" format='DD/MM/YYYY' />
                      </DemoContainer>
                </LocalizationProvider>
              </Col>
            </Row>
            <div className='py-4'>
             <Button style={{width:'11%'}} variant="success">Submit</Button>{' '}
            </div>
          </FormGroup>

          <div>

          <Modal className='pt-5' show={show} onHide={handleClose}>
        <Modal.Header closeButton>
          <Modal.Title>Edit 1st Standard</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <FloatingLabel className='pb-2' controlId="floatingPassword" label="Academic Year">
            <Form.Control type="text"/>
          </FloatingLabel>
          <FloatingLabel className='pb-2' controlId="floatingPassword" label="Fee Category">
            <Form.Control type="text"/>
          </FloatingLabel>
          <FloatingLabel className='pb-2' controlId="floatingPassword" label="Sub Category">
            <Form.Control type="text"/>
          </FloatingLabel>
          <FloatingLabel className='pb-2' controlId="floatingPassword" label="₹ Total Amount">
            <Form.Control type="text"/>
          </FloatingLabel>
        </Modal.Body>
        <Modal.Footer>
          <Button variant="secondary" onClick={handleClose}>
            Close
          </Button>
          <Button variant="success" onClick={handleClose}>
            Save Changes
          </Button>
        </Modal.Footer>
      </Modal>
          <Table striped bordered hover size="sm">
      <thead>
        <tr>
          <th>Academic Year</th>
          <th>Fee Category</th>
          <th>Sub Category</th>
          <th>₹ Total Amount</th>
          <th>Enter Date</th>
          <th className='text-center'>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>2023</td>
          <td>School Uniform dress</td>
          <td>Functions</td>
          <td>40333</td>
          <td>12/4/2023</td>
          <td className='text-center'>
             <FaRegEdit onClick={handleShow} style={{cursor:'pointer'}} className='text-success pb-1 pe-1' size={28} title='Edit'/>
             <MdDelete style={{cursor:'pointer'}}  className='text-danger pb-1 ps-2 ' size={35} title='Delete'/>
           </td>
        </tr>
      </tbody>
    </Table>
          </div>
        </Accordion.Body>
      </Accordion.Item>
{/*-------------------------- 2-Standard--------------------------------------------- */}
      <Accordion.Item eventKey="2">
        <Accordion.Header>
          <CgComponents size={40} className='pe-2'/><h5>2th Standard</h5>
        </Accordion.Header>
        <Accordion.Body>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
          eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
          minim veniam, quis nostrud exercitation ullamco laboris nisi ut
          aliquip ex ea commodo consequat. Duis aute irure dolor in
          reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
          pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
          culpa qui officia deserunt mollit anim id est laborum.
        </Accordion.Body>
      </Accordion.Item>
      <Accordion.Item eventKey="3">
        <Accordion.Header>
          <CgComponents size={40} className='pe-2'/><h5>3th Standard</h5>
        </Accordion.Header>
        <Accordion.Body>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
          eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
          minim veniam, quis nostrud exercitation ullamco laboris nisi ut
          aliquip ex ea commodo consequat. Duis aute irure dolor in
          reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
          pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
          culpa qui officia deserunt mollit anim id est laborum.
        </Accordion.Body>
      </Accordion.Item>
      <Accordion.Item eventKey="4">
        <Accordion.Header>
          <CgComponents size={40} className='pe-2'/><h5>4th Standard</h5>
        </Accordion.Header>
        <Accordion.Body>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
          eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
          minim veniam, quis nostrud exercitation ullamco laboris nisi ut
          aliquip ex ea commodo consequat. Duis aute irure dolor in
          reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
          pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
          culpa qui officia deserunt mollit anim id est laborum.
        </Accordion.Body>
      </Accordion.Item>
      <Accordion.Item eventKey="5">
        <Accordion.Header>
          <CgComponents size={40} className='pe-2'/><h5>5th Standard</h5>
        </Accordion.Header>
        <Accordion.Body>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
          eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
          minim veniam, quis nostrud exercitation ullamco laboris nisi ut
          aliquip ex ea commodo consequat. Duis aute irure dolor in
          reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
          pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
          culpa qui officia deserunt mollit anim id est laborum.
        </Accordion.Body>
      </Accordion.Item>
      <Accordion.Item eventKey="6">
        <Accordion.Header>
         <CgComponents size={40} className='pe-2'/><h5>6th Standard</h5>
        </Accordion.Header>
        <Accordion.Body>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
          eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
          minim veniam, quis nostrud exercitation ullamco laboris nisi ut
          aliquip ex ea commodo consequat. Duis aute irure dolor in
          reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
          pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
          culpa qui officia deserunt mollit anim id est laborum.
        </Accordion.Body>
      </Accordion.Item>
      <Accordion.Item eventKey="7">
        <Accordion.Header>
         <CgComponents size={40} className='pe-2'/><h5>7th Standard</h5>
        </Accordion.Header>
        <Accordion.Body>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
          eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
          minim veniam, quis nostrud exercitation ullamco laboris nisi ut
          aliquip ex ea commodo consequat. Duis aute irure dolor in
          reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
          pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
          culpa qui officia deserunt mollit anim id est laborum.
        </Accordion.Body>
      </Accordion.Item>
      <Accordion.Item eventKey="8">
        <Accordion.Header>
         <CgComponents size={40} className='pe-2'/><h5>8th Standard</h5>
        </Accordion.Header>
        <Accordion.Body>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
          eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
          minim veniam, quis nostrud exercitation ullamco laboris nisi ut
          aliquip ex ea commodo consequat. Duis aute irure dolor in
          reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
          pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
          culpa qui officia deserunt mollit anim id est laborum.
        </Accordion.Body>
      </Accordion.Item>
      <Accordion.Item eventKey="9">
        <Accordion.Header>
         <CgComponents size={40} className='pe-2'/><h5>9th Standard</h5>
        </Accordion.Header>
        <Accordion.Body>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
          eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
          minim veniam, quis nostrud exercitation ullamco laboris nisi ut
          aliquip ex ea commodo consequat. Duis aute irure dolor in
          reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
          pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
          culpa qui officia deserunt mollit anim id est laborum.
        </Accordion.Body>
      </Accordion.Item>
      <Accordion.Item eventKey="10">
        <Accordion.Header>
         <CgComponents size={40} className='pe-2'/><h5>10th Standard</h5>
        </Accordion.Header>
        <Accordion.Body>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
          eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
          minim veniam, quis nostrud exercitation ullamco laboris nisi ut
          aliquip ex ea commodo consequat. Duis aute irure dolor in
          reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
          pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
          culpa qui officia deserunt mollit anim id est laborum.
        </Accordion.Body>
      </Accordion.Item>
      <Accordion.Item eventKey="11">
        <Accordion.Header>
         <CgComponents size={40} className='pe-2'/><h5>11th Standard</h5>
        </Accordion.Header>
        <Accordion.Body>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
          eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
          minim veniam, quis nostrud exercitation ullamco laboris nisi ut
          aliquip ex ea commodo consequat. Duis aute irure dolor in
          reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
          pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
          culpa qui officia deserunt mollit anim id est laborum.
        </Accordion.Body>
      </Accordion.Item>
 {/*------------------ 12th Standard ------------------------------------ */}
      <Accordion.Item eventKey="13">
        <Accordion.Header>
         <CgComponents size={40} className='pe-2'/><h5>12th Standard</h5>
        </Accordion.Header>
        <Accordion.Body style={{backgroundColor:'#f3f3f3'}}>
          <FormGroup>
          <h4 style={{fontWeight:'400'}}>Total Annual fees</h4>
          <Row>
            <Col className='' >
             <label className='pb-3 ps-2'>Fee Category</label>
             <Form.Select className='custom-input' aria-label="Default select example" style={{width:'70%',height:'55px'}}>
                <option>Open this select menu</option>
                <option value="1">Digital Education</option>
                <option value="2">X seed Education</option>
                <option value="3">ID Card</option>
                <option value="4">Stationery items</option>
                <option value="5">Group photo</option>
                <option value="6">Functions</option>
              </Form.Select>
            </Col>
            <Col className=''>
             <label className='pb-3 ps-2'>Sub Category</label>
             <Form.Select className='custom-input' aria-label="Default select example" style={{width:'70%',height:'55px'}}>
                <option>Open this select menu</option>
                <option value="4">Uniform shoe Uniform socks</option>
                <option value="5">School Uniform dress</option>
                <option value="6">General medicine</option>
                <option value="5">School Uniform dress</option>
                <option value="6">General medicine</option>
              </Form.Select>
            </Col>
            <Col className=''>
             <label className='pb-3 ps-2'>Groups</label>
             <Form.Select className='custom-input' aria-label="Default select example" style={{width:'70%',height:'55px'}}>
                <option>Open this select menu</option>
                <option value="4">Group-1</option>
                <option value="5">Group-2</option>
              </Form.Select>
            </Col>
          </Row>
        <hr className='py-3'/>
            <Row>
            <Col className='pt-2'>
              <FormControl fullWidth>
                      {/* <InputLabel id="demo-simple-select-label">Select-Academic-Year</InputLabel> */}
                      <Form.Select  style={{width:'100%',height:'55px'}}>
                          <option>select year</option>
                          <option value="2023">2023</option>
                          <option value="2024">2024</option>
                          <option value="2025">2025</option>
                        </Form.Select>
                   </FormControl>
              </Col>
              <Col className='pt-2'> 
                 <FloatingLabel controlId="floatingPassword" label="₹ Amount">
                   <Form.Control className="custom-input" type="number" placeholder="₹ Amount" />
                </FloatingLabel>
              </Col>
              <Col>
                <LocalizationProvider dateAdapter={AdapterDayjs} style={{paddingTop:'0px'}}>
                      <DemoContainer components={['DatePicker']}>
                        <DatePicker label="Select Date" format='DD/MM/YYYY' />
                      </DemoContainer>
                </LocalizationProvider>
              </Col>
            </Row>
            <div className='py-4'>
             <Button style={{width:'11%'}} variant="success">Submit</Button>{' '}
            </div>
          </FormGroup>

          <div>

          <Modal className='pt-5' show={show} onHide={handleClose}>
        <Modal.Header closeButton>
          <Modal.Title>Edit 12st Standard fees</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <FloatingLabel className='pb-2' controlId="floatingPassword" label="Academic Year">
            <Form.Control type="text"/>
          </FloatingLabel>
          <FloatingLabel className='pb-2' controlId="floatingPassword" label="Group">
            <Form.Control type="text"/>
          </FloatingLabel>
          <FloatingLabel className='pb-2' controlId="floatingPassword" label="Fee Category">
            <Form.Control type="text"/>
          </FloatingLabel>
          <FloatingLabel className='pb-2' controlId="floatingPassword" label="Sub Category">
            <Form.Control type="text"/>
          </FloatingLabel>
          <FloatingLabel className='pb-2' controlId="floatingPassword" label="₹ Total Amount">
            <Form.Control type="text"/>
          </FloatingLabel>
        </Modal.Body>
        <Modal.Footer>
          <Button variant="secondary" onClick={handleClose}>
            Close
          </Button>
          <Button variant="success" onClick={handleClose}>
            Save Changes
          </Button>
        </Modal.Footer>
      </Modal>
          <Table striped bordered hover size="sm">
      <thead>
        <tr>
          <th>Academic Year</th>
          <th>Group</th>
          <th>Fee Category</th>
          <th>Sub Category</th>
          <th>₹ Total Amount</th>
          <th>Enter Date</th>
          <th className='text-center'>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>2023</td>
          <td>Group-1</td>
          <td>School Uniform dress</td>
          <td>Functions</td>
          <td>40333</td>
          <td>12/4/2023</td>
          <td className='text-center'>
             <FaRegEdit onClick={handleShow} style={{cursor:'pointer'}} className='text-success pb-1 pe-1' size={28} title='Edit'/>
             <MdDelete style={{cursor:'pointer'}}  className='text-danger pb-1 ps-2 ' size={35} title='Delete'/>
           </td>
        </tr>
      </tbody>
    </Table>
          </div>
        </Accordion.Body>
      </Accordion.Item>
 {/*------------------ Student ------------------------------------ */}
      <Accordion.Item eventKey="14">
        <Accordion.Header>
         <BsCompass size={40} className='pe-2'/><h5>Individual  Student</h5>
        </Accordion.Header>
        <Accordion.Body style={{backgroundColor:'#f3f3f3'}}>
          <FormGroup>
          <h4 style={{fontWeight:'400'}}>Total Annual fees</h4>
          <Row>
            <Col className='' >
             <label className='pb-3 ps-2'>Fee Category</label>
             <Form.Select className='custom-input' aria-label="Default select example" style={{width:'70%',height:'55px'}}>
                <option>Open this select menu</option>
                <option value="1">Digital Education</option>
                <option value="2">X seed Education</option>
                <option value="3">ID Card</option>
                <option value="4">Stationery items</option>
                <option value="5">Group photo</option>
                <option value="6">Functions</option>
              </Form.Select>
            </Col>
            <Col className=''>
             <label className='pb-3 ps-2'>Sub Category</label>
             <Form.Select className='custom-input' aria-label="Default select example" style={{width:'70%',height:'55px'}}>
                <option>Open this select menu</option>
                <option value="4">Uniform shoe Uniform socks</option>
                <option value="5">School Uniform dress</option>
                <option value="6">General medicine</option>
                <option value="5">School Uniform dress</option>
                <option value="6">General medicine</option>
              </Form.Select>
            </Col>
            <Col className=''>
             <label className='pb-3 ps-2'>Groups</label>
             <Form.Select className='custom-input' aria-label="Default select example" style={{width:'70%',height:'55px'}}>
                <option>Open this select menu</option>
                <option value="4">Group-1</option>
                <option value="5">Group-2</option>
              </Form.Select>
            </Col>
          </Row>
        <hr className='py-3'/>
            <Row>
            <Col className='pt-2'>
              <FormControl fullWidth>
                      {/* <InputLabel id="demo-simple-select-label">Select-Academic-Year</InputLabel> */}
                      <Form.Select  style={{width:'100%',height:'55px'}}>
                          <option>select year</option>
                          <option value="2023">2023</option>
                          <option value="2024">2024</option>
                          <option value="2025">2025</option>
                        </Form.Select>
                   </FormControl>
              </Col>
              <Col className='pt-2'> 
                 <FloatingLabel controlId="floatingPassword" label="₹ Amount">
                   <Form.Control className="custom-input" type="number" placeholder="₹ Amount" />
                </FloatingLabel>
              </Col>
              <Col>
                <LocalizationProvider dateAdapter={AdapterDayjs} style={{paddingTop:'0px'}}>
                      <DemoContainer components={['DatePicker']}>
                        <DatePicker label="Select Date" format='DD/MM/YYYY' />
                      </DemoContainer>
                </LocalizationProvider>
              </Col>
            </Row><hr/>

            <Row>
            <Col className='pt-2'> 
            <Select options={options} isLoading={loading} isSearchable={true} placeholder="Select an option"/>
              </Col>
            {/* <Col className='pt-2'> 
                 <FloatingLabel controlId="floatingPassword" label="Search">
                   <Form.Control className="custom-input" type="search" placeholder="₹ Amount" />
                </FloatingLabel>
              </Col>
            <Col className='pt-2'> 
                 <FloatingLabel controlId="floatingPassword" label="₹ Amount">
                   <Form.Control className="custom-input" type="number" placeholder="₹ Amount" />
                </FloatingLabel>
              </Col> */}
            </Row>
            <div className='py-4'>
             <Button style={{width:'11%'}} variant="success">Submit</Button>{' '}
            </div>
          </FormGroup>

          <div>

          <Modal className='pt-5' show={show} onHide={handleClose}>
        <Modal.Header closeButton>
          <Modal.Title>Edit 12st Standard fees</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <FloatingLabel className='pb-2' controlId="floatingPassword" label="Academic Year">
            <Form.Control type="text"/>
          </FloatingLabel>
          <FloatingLabel className='pb-2' controlId="floatingPassword" label="Roll number">
            <Form.Control type="text"/>
          </FloatingLabel>
          <FloatingLabel className='pb-2' controlId="floatingPassword" label="Student Name">
            <Form.Control type="text"/>
          </FloatingLabel>
          <FloatingLabel className='pb-2' controlId="floatingPassword" label="Fee Category">
            <Form.Control type="text"/>
          </FloatingLabel>
          <FloatingLabel className='pb-2' controlId="floatingPassword" label="Sub Category">
            <Form.Control type="text"/>
          </FloatingLabel>
          <FloatingLabel className='pb-2' controlId="floatingPassword" label="₹ Total Amount">
            <Form.Control type="text"/>
          </FloatingLabel>
        </Modal.Body>
        <Modal.Footer>
          <Button variant="secondary" onClick={handleClose}>
            Close
          </Button>
          <Button variant="success" onClick={handleClose}>
            Save Changes
          </Button>
        </Modal.Footer>
      </Modal>
          <Table striped bordered hover size="sm">
      <thead>
        <tr>
          <th>Academic Year</th>
          <th>Roll Number</th>
          <th>Student Name</th>
          <th>Fee Category</th>
          <th>Sub Category</th>
          <th>₹ Total Amount</th>
          <th>Enter Date</th>
          <th className='text-center'>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>2023</td>
          <td>11342</td>
          <td>Abu Sufiyan</td>
          <td>School Uniform dress</td>
          <td>Functions</td>
          <td>40333</td>
          <td>12/4/2023</td>
          <td className='text-center'>
             <FaRegEdit onClick={handleShow} style={{cursor:'pointer'}} className='text-success pb-1 pe-1' size={28} title='Edit'/>
             <MdDelete style={{cursor:'pointer'}}  className='text-danger pb-1 ps-2 ' size={35} title='Delete'/>
           </td>
        </tr>
      </tbody>
    </Table>
          </div>
        </Accordion.Body>
      </Accordion.Item>
    </Accordion>
      </div>
    </div>
    </div>
  )
}

export default Feesmaping
