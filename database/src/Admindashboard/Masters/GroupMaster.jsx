import React,{useState} from 'react';
import Sidebar from '../Sidebar';
import Header from '../Header';
import Footer from '../Footer';
import Paper from '@mui/material/Paper'; 
import {MdOutlineGroupWork} from 'react-icons/md';
import FloatingLabel from 'react-bootstrap/FloatingLabel';
import Form from 'react-bootstrap/Form';
import Button from 'react-bootstrap/Button' ;
import Table from 'react-bootstrap/Table';
import {FaRegEdit} from 'react-icons/fa';
import {MdDelete} from 'react-icons/md';
import Modal from 'react-bootstrap/Modal';

const GroupMaster = () => {

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
            <h3 className='p-3'><MdOutlineGroupWork size={45} className='pe-2' />Group Master for higher secondary</h3>
            <div className='p-3'>
            <Form>
              <Form.Group className="pb-3" controlId="formBasicEmail">
                <FloatingLabel controlId="floatingSelect" label="Select Group">
                 <Form.Select>
                    <option value="group1">Group-I</option>
                    <option value="group2">Group-II</option>
                  </Form.Select>
                </FloatingLabel>
            </Form.Group>

                <Form.Group className="mb-3" controlId="formBasicEmail">
                    <FloatingLabel controlId="floatingTextarea2" label="Add Course">
                        <Form.Control
                        as="textarea"
                        placeholder="Add Course"
                        style={{ height: '100px' }}/>
                    </FloatingLabel>
                </Form.Group>
           </Form>

           <div className='py-4'>
              <Button variant="success">Submit</Button>{' '}
            </div>    
      
      <div className='pt-5'>
        <Modal className='pt-5' show={show} onHide={handleClose}>
        <Modal.Header closeButton>
          <Modal.Title>Edit group Master</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <FloatingLabel className='pb-2' controlId="floatingPassword" label="Group Name">
              <Form.Control type="text"  />
          </FloatingLabel>
          <FloatingLabel className='pb-2' controlId="floatingPassword" label="Description">
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

          <Table className='pt-3' striped bordered hover size="sm">
        <thead>
          <tr style={{background:'#535455',color:'#fff',textAlign:'center'}}>
            <th>Group Name</th>
            <th>Description</th>
            <th>Created By</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr style={{textAlign:'center'}}>
            <td>Commerce</td>
            <td>Computer science,Economics</td>
            <td>Super Admin</td>
            <td>
              <FaRegEdit onClick={handleShow}  style={{cursor:'pointer'}} className='text-success pb-1 pe-1' size={28} title='Edit user'/>
              <MdDelete style={{cursor:'pointer'}}  className='text-danger pb-1 ps-2 ' size={35} title='Delete user'/>
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
export default GroupMaster
