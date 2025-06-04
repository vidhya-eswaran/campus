import React,{useState} from 'react';
import $ from 'jquery'; 
import Button from 'react-bootstrap/Button';
import {MdDelete} from 'react-icons/md'
import {FaRegEdit} from 'react-icons/fa'
import Swal from 'sweetalert2'

class DataTableSponsor extends React.Component {


  componentDidMount() {
    //initialize datatable
    $(document).ready(function () {
        $('#sponsor-table').DataTable();
    });
 }
  render(){
    function handleClickDelete() {
        Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
            Swal.fire(
              'Deleted!',
              'User has been deleted',
              'success'
            )
          }
        })
      }
    
  return (
    <div className="MainDiv">

      
      <div className="container">
          
          <table id="sponsor-table" class="display">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>  
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Tiger Nixon</td>
                    <td>System Architect</td>
                    <td>Edinburgh</td>
                    <td>
                        {/* <Button variant="info"> <BiEdit className='pb-1 pe-1' size={20}/>Edit</Button>{' '}
                        <Button variant="danger"><MdDelete className='pb-1 pe-1' size={22}/>Delete</Button>{' '}    */}
                       <a href='/EditUser' ><FaRegEdit style={{cursor:'pointer'}} className='text-success pb-1 pe-1' size={28} title='Edit user'/></a>
                        <MdDelete style={{cursor:'pointer'}} onClick={handleClickDelete}  className='text-danger pb-1 ps-2 ' size={35} title='Delete user'/>
                    </td>
                </tr>
                
                <tr>
                    <td>Garrett Winters</td>
                    <td>Accountant</td>
                    <td>Tokyo</td>
                    <td>
                        <FaRegEdit style={{cursor:'pointer'}} className='text-success pb-1 pe-1' size={28} title='Edit user'/>
                        <MdDelete style={{cursor:'pointer'}}  className='text-danger pb-1 ps-2 ' size={35} title='Delete user'/>
                    </td>
                </tr>
     
           
            </tbody>
        </table>
          
        </div>
      </div>
  );
}
}

export default DataTableSponsor
