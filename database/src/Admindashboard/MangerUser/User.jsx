import React, { useMemo,useState } from 'react';
import Sidebar from '../Sidebar';
import Footer from '../Footer';   
import Header from '../Header';
import Paper from '@mui/material/Paper'; 
import DataTableUser from '../MangerUser/DataTableUser';
import Row from 'react-bootstrap/Row';
import {FaUsersCog} from 'react-icons/fa';
import Button from 'react-bootstrap/Button';
import Form from 'react-bootstrap/Form';
import Modal from 'react-bootstrap/Modal';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';



const Manageuser = () => {

  const [show, setShow] = useState(false);
  const handleClose = () => setShow(false);
  const handleShow = () => setShow(true);

  function handleClick() {
    toast.success('User add successfully', {
      position: "bottom-right",
      autoClose: 1500,
      hideProgressBar: false,
      closeOnClick: false,
      pauseOnHover: true,
      draggable: false,
      progress: undefined,
      theme: "dark",
      });
  }


  return (
    <div>
       <Sidebar/>
    <div style={{width:'82.5%',float:'right'}} >
      <Header/>

      <ToastContainer
      position="top-right"
      autoClose={1000}
      hideProgressBar={false}
      newestOnTop={false}
      closeOnClick={false}
      rtl={false}
      pauseOnFocusLoss
      draggable={false}
      pauseOnHover
      theme="dark"/>



        <div className='container'>
          <h2 className='p-4' style={{fontFamily:'auto'}}><FaUsersCog className="pe-1 pb-1" size={35}  />User</h2>
          <div className='py-1'>
          <Paper elevation={2} className="pb-5">
             <Row>
               <div className='col-6 p-4'><h4>Manger user</h4></div>
               <div className='col-6 text-end p-4'>
                <button onClick={handleShow} style={{width:'25%'}} className='button-42 ' role='button'>Add user</button>
               </div>
             </Row>
             <Modal show={show} onHide={handleClose} centered>
        <Modal.Header style={{backgroundColor:'#F4FFF9'}}  >
          <Modal.Title>ADD USER</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <Form>

            <Form.Group className="mb-3" controlId="exampleForm.ControlInput1">
              <Form.Label>User name</Form.Label>
              <Form.Control type="text" placeholder="Enter user name"/>
            </Form.Group>

            <Form.Group className="mb-3" controlId="exampleForm.ControlInput1">
              <Form.Label>Email address</Form.Label>
              <Form.Control type="text" placeholder="name@example.com" />
            </Form.Group>

            <Form.Group className="mb-3" controlId="exampleForm.ControlInput1">
              <Form.Label>Role</Form.Label>
              <Form.Control type="Text" placeholder="Enter role" />
            </Form.Group>

          </Form>
        </Modal.Body>
        <Modal.Footer>
          <Button variant="secondary" onClick={handleClose}>
            Close
          </Button>
          <Button variant="success"  onClick={() => {handleClose(); handleClick();}}>
            update
          </Button>
        </Modal.Footer>
      </Modal>

             <div className='container'>
               <DataTableUser/>
             </div>
          </Paper>
          </div>
        </div>
   </div>
    </div>
  )
}

export default Manageuser


