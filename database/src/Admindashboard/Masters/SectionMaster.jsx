import React,{useState} from 'react';
import Sidebar from '../Sidebar';
import Header from '../Header';
import Footer from '../Footer';
import Paper from '@mui/material/Paper'; 
import {BsSignIntersectionY} from 'react-icons/bs';
import FloatingLabel from 'react-bootstrap/FloatingLabel';
import Form from 'react-bootstrap/Form';
import Button from 'react-bootstrap/Button';
import Table from 'react-bootstrap/Table';
import {FaRegEdit} from 'react-icons/fa';
import {MdDelete} from 'react-icons/md';
import Modal from 'react-bootstrap/Modal';
const SectionMaster = () => {
  
  const [show, setShow] = useState(false);

  const handleClose = () => setShow(false);
  const handleShow = () => setShow(true);



  return (
    <div>
    <Sidebar/>
      <div style={{width:'82.5%',float:'right'}} >
   <Header/>

   <div className='p-4' >
     <Paper elevation={2} className="pb-5" style={{backgroundColor:'rgb(232 232 232)'}}>
       <h3 className='p-3'><BsSignIntersectionY size={45} className='pe-2' />Section Master</h3>
       <div className='container'>
        <div className='row'>
          <div className='col-6' xs={6}>
            <FloatingLabel controlId="floatingInput" label="Enter Section" className="mb-3">
              <Form.Control type="text"/>
            </FloatingLabel>
          </div>
          <div className='col-6 p-2' xs={6}>
            <Button variant="success">Submit</Button>{' '}
          </div>
        </div>

        <div className='pt-5'>
        <Modal className='pt-5' show={show} onHide={handleClose}>
        <Modal.Header closeButton>
          <Modal.Title>Edit Section Master</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <FloatingLabel className='pb-2' controlId="floatingPassword" label="Section Name">
              <Form.Control type="text"  />
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
          <tr style={{background:'#535455',color:'#fff',textAlign:'center'}}>
            <th>Section Name</th>
            <th>Created By</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr style={{textAlign:'center'}}>
            <td>D</td>
            <td>Super Admin</td>
            <td>
              <FaRegEdit onClick={handleShow}  style={{cursor:'pointer'}} className='text-success pb-1 pe-1' size={28} title='Edit user'/>
              <MdDelete  style={{cursor:'pointer'}}  className='text-danger pb-1 ps-2 ' size={35} title='Delete user'/>
            </td> 
          </tr>
        </tbody>
          </Table>
        </div>
       </div>
   </Paper>
</div>
 </div>
 </div>
  )
}

export default SectionMaster
