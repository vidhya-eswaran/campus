import React from 'react'
import Sidebar from '../Sidebar';
import Footer from '../Footer';
import Header from '../Header';
import Paper from '@mui/material/Paper'; 
import FloatingLabel from 'react-bootstrap/FloatingLabel';
import Form from 'react-bootstrap/Form';
import {FaUsersCog} from 'react-icons/fa'
import {TiBackspace} from 'react-icons/ti';
// import './dashboard.css'
const EditUser = () => {
  return (

          <div>
       <Sidebar/>
    <div style={{width:'82.5%',float:'right'}} >
      <Header/>





        <div className='container'>
          <h2 className='px-4 py-2' style={{fontFamily:'auto'}}><FaUsersCog className="pe-1 pb-1" size={35}  />User</h2>
          <div className='py-1'>
          <Paper elevation={2} className="pb-5" style={{backgroundColor:'#F4F4F5'}}>
            <div className='text-end p-2'>
                <a href="MangerUser/User">
                    <TiBackspace size={35} className='text-danger'/>
                </a>
            </div>
               <div className='text-center py-4'><h4>Edit User</h4></div>

             <div className='container' style={{width:'50%'}}>
             <Form>
                <FloatingLabel controlId="floatingInput" label="Name"   className="mb-3" >
                    <Form.Control type="text"  />
                </FloatingLabel>
                <FloatingLabel controlId="floatingInput" label="Email address"   className="mb-3" >
                    <Form.Control type="email"  />
                </FloatingLabel>
                <FloatingLabel controlId="floatingInput" label="Role"   className="mb-3" >
                    <Form.Control type="text"  />
                </FloatingLabel>
              <div className='text-center'><button class="button-20" role="button" type="submit">Update</button></div>
             </Form>
             
             </div>
          </Paper>
          </div>
        </div>
        <div>
          {/* <Footer /> */}
        </div>
   </div>
      
    </div>

  )
}

export default EditUser
