import React, { Component } from 'react';
import { Button, Modal } from 'react-bootstrap';
import $ from 'jquery'; 
import {MdDelete} from 'react-icons/md'
import {FaRegEdit} from 'react-icons/fa'
import FloatingLabel from 'react-bootstrap/FloatingLabel';
import Form from 'react-bootstrap/Form';



class AddSponsorDatatable extends React.Component {

  state = {
    showModal: false,
  };
  // .
  toggleModal = () => {
    this.setState({
      showModal: !this.state.showModal,
    });
  };
  
  componentDidMount() {
    //initialize datatable
    $(document).ready(function () {
        $('#add-sponsor').DataTable();  
    });
 }

  render(){
    // function handleClickDelete() {
    //     Swal.fire({
    //       title: 'Are you sure?',
    //       text: "You won't be able to revert this!",
    //       icon: 'warning',
    //       showCancelButton: true,
    //       confirmButtonColor: '#3085d6',
    //       cancelButtonColor: '#d33',
    //       confirmButtonText: 'Yes, delete it!'
    //     }).then((result) => {
    //       if (result.isConfirmed) {
    //         Swal.fire(
    //           'Deleted!',
    //           'User has been deleted',
    //           'success'
    //         )
    //       }
    //     })
    //   }
  ;
  
  return (
    <div className="MainDiv">
      
    <div className="container">
       
       <div >
        <Modal className='pt-5' show={this.state.showModal} onHide={this.toggleModal}>
          <Modal.Header closeButton>
            <Modal.Title>Edit Sponsor  Master</Modal.Title>
          </Modal.Header>

          <Modal.Body>
            <FloatingLabel className='pb-2' controlId="floatingPassword" label="Full Name">
              <Form.Control type="text"  />
            </FloatingLabel>
            <FloatingLabel className='pb-2' controlId="floatingPassword" label="Occupation">
              <Form.Control type="text"  />
            </FloatingLabel>
            <FloatingLabel className='pb-2' controlId="floatingPassword" label="Company's Name">
              <Form.Control type="text"  />
            </FloatingLabel>
            <FloatingLabel className='pb-2' controlId="floatingPassword" label="Location">
              <Form.Control type="text"  />
            </FloatingLabel>
            <FloatingLabel className='pb-2' controlId="floatingPassword" label="Email ID">
              <Form.Control type="text"  />
            </FloatingLabel>
            <FloatingLabel className='pb-2' controlId="floatingPassword" label="Phone Number">
              <Form.Control type="text"  />
            </FloatingLabel>
            <FloatingLabel className='pb-2' controlId="floatingPassword" label="Address">
              <Form.Control type="text"  />
            </FloatingLabel>
          </Modal.Body>

          <Modal.Footer>
            <Button variant="secondary" onClick={this.toggleModal}>
              Close
            </Button>
            <Button variant="success" onClick={this.saveChanges}>
              Save changes
            </Button>
          </Modal.Footer>
         </Modal>
       </div>
        
        <table id="add-sponsor" class="display">
          <thead>
              <tr>
                  <th>Full Name</th>
                  <th>Occupation</th>
                  <th>Company's Name</th>
                  <th>Location</th>  
                  <th>Email ID</th>  
                  <th>Phone Number</th>  
                  <th>Address</th>
                  <th>Action</th>  
              </tr>
          </thead>
          <tbody>
              <tr>
                  <td>Mohammed Fareestha</td>
                  <td>Bussiness</td>
                  <td>Eucto</td>
                  <td>Chennai</td>
                  <td>eucto@gmail.com</td>
                  <td>984034021</td>
                  <td>11/3,payappen st,seven wells chennai</td>
                  <td>
                    <FaRegEdit onClick={this.toggleModal} style={{cursor:'pointer'}} className='text-success pb-1 pe-1' size={28} title='Edit user'/>
                    <MdDelete style={{cursor:'pointer'}}  className='text-danger pb-1 ps-2 ' size={35} title='Delete user'/>
                 </td>  
                </tr>

   
         
          </tbody>
      </table>
        
      </div>
    </div>
  )
}
}


export default AddSponsorDatatable
