import React,{useState} from 'react';
import Header from '../Header';
import Sidebar from '../Sidebar';
import Footer from '../Footer';
import { Button, Modal } from 'react-bootstrap';
import PropTypes from 'prop-types';
import Tabs from '@mui/material/Tabs';
import Tab from '@mui/material/Tab';
import Typography from '@mui/material/Typography';
import Box from '@mui/material/Box';
import Card from 'react-bootstrap/Card';
import Form from 'react-bootstrap/Form';
import FloatingLabel from 'react-bootstrap/FloatingLabel';
import {BsBook} from 'react-icons/bs';
import {FaRegEdit} from 'react-icons/fa';
import {MdDelete} from 'react-icons/md';
import {MdMiscellaneousServices,MdOutlineCastForEducation} from 'react-icons/md';
import {RiHomeSmileLine} from 'react-icons/ri';
import Table from 'react-bootstrap/Table';


function TabPanel(props) {
    const { children, value, index, ...other } = props;
  
    return (
      <div
        role="tabpanel"
        hidden={value !== index}
        id={`simple-tabpanel-${index}`}
        aria-labelledby={`simple-tab-${index}`}
        {...other}
      >
        {value === index && (
          <Box sx={{ p: 3 }}>
            <Typography>{children}</Typography>
          </Box>
        )}
      </div>
    );
  }
  
  TabPanel.propTypes = {
    children: PropTypes.node,
    index: PropTypes.number.isRequired,
    value: PropTypes.number.isRequired,
  };
  
  function a11yProps(index) {
    return {
      id: `simple-tab-${index}`,
      'aria-controls': `simple-tabpanel-${index}`,
    };
  }
  

const Mfees = () => {


  //////////////////////////Table-4/////////////////////////////////
  const [show, setShow] = useState(false);

  const handleClose = () => setShow(false);
  const handleShow = () => setShow(true);





    const [value, setValue] = React.useState(0);
    const [name, setName] = useState('');
    const [status, setStatus] = useState('');
    const [data, setData] = useState([]);

     const handleChange = (event, newValue) => {
      setValue(newValue);
    };

    const handleNameChange = (e) => {
      setName(e.target.value);
    };
  
    const handleGenderChange = (e) => {
      setStatus(e.target.value);
    };
  
    const handleSchoolSubmit = (e) => {
      e.preventDefault();
      const newData = { name: name, status: status };
      setData([...data, newData]);
      setName('');
      setStatus('');
    };

    const handleMisSubmit = (e) => {
      e.preventDefault();
      const newData = { name: name, status: status };
      setData([...data, newData]);
      setName('');
      setStatus('');
    };
      
    const handleHostalSubmit = (e) => {
      e.preventDefault();
      const newData = { name: name, status: status };
      setData([...data, newData]);
      setName('');
      setStatus('');
    };

    const handleOtherSubmit = (e) => {
      e.preventDefault();
      const newData = { name: name, status: status };
      setData([...data, newData]);
      setName('');
      setStatus('');
    };
  
  return (
    <div>
       
       <Sidebar/>
    <div style={{width:'82.5%',float:'right'}} >
      <Header/>
      <div>
      <Box sx={{ width: '100%' }}>
      <Box sx={{ borderBottom: 1, borderColor: 'divider' }}>
        <Tabs value={value} onChange={handleChange} aria-label="basic tabs example" centered style={{backgroundColor:'#F3EDE2'}}>
          <Tab label="School Fees" {...a11yProps(0)} />
          <Tab label="School miscellaneous bill" {...a11yProps(1)} />
          <Tab label="Hostel Bill" {...a11yProps(2)} />
          <Tab label="Other Fees" {...a11yProps(3)} />
        </Tabs>
      </Box>
{/*-------------------------------------------- Tab-1------------------------------------------------ */}
      <TabPanel value={value} index={0} >
        <Form onSubmit={handleSchoolSubmit}>
         <Card style={{ width: '50%' }}>
            <Card.Body>
                <Card.Title><BsBook size={27} className="pe-2"/>School Fees</Card.Title>
                <Card.Subtitle className="mb-2 text-muted">Enter the Sub-heading</Card.Subtitle>
                <Card.Text>
                
                <FloatingLabel controlId="floatingInput" label="Type" className="mb-3" >
                    <Form.Control value={name} onChange={handleNameChange} type="text" placeholder='Type'  />
                </FloatingLabel>
                </Card.Text>
                <lable className="pe-3">Status: </lable>
                <div class="form-check form-check-inline">
                    <input checked={status === 'Active'} onChange={handleGenderChange}  class="form-check-input" type="radio" name="Active" id="inlineRadio1" value="Active" />
                    <label  class="form-check-label" for="inlineRadio1">Active</label>
                </div>
                <div class="form-check form-check-inline">
                    <input   checked={status === 'InActive'} onChange={handleGenderChange} class="form-check-input" type="radio" name="InActive" id="inlineRadio2" value="InActive" />
                    <label style={{color:'red'}} class="form-check-label" for="inlineRadio2">InActive</label>
                 </div>
               
            </Card.Body>
            <div style={{padding:'10px'}}>
             <Button type="submit"  style={{width:'45%'}} variant="success">Submit</Button>{' '}
            </div>
         </Card> 
      </Form>  


   {/*--------------------------- Table-1 -----------------------------*/}
         <div className='pt-5'>
            <Table striped bordered hover size="sm">
            <div>
       <Modal className='pt-5' show={show} onHide={handleClose}>
        <Modal.Header closeButton>
          <Modal.Title>Edit School Fees</Modal.Title>
        </Modal.Header>
        <Modal.Body>
           <FloatingLabel className='pb-2' controlId="floatingPassword" label="Sub-heading">
              <Form.Control type="text"  />
            </FloatingLabel>
            <FloatingLabel controlId="floatingSelect" label="Action Section">
                <Form.Select >
                  <option value="1">Active</option>
                  <option value="2">InActive</option>
                </Form.Select>
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
           </div>
        <thead>
          <tr>
            <th>Sub-heading</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Digital Education </td>
            <td>Active</td>
            <td>
              <FaRegEdit onClick={handleShow} style={{cursor:'pointer'}} className='text-success pb-1 pe-1' size={28} title='Edit user'/>
              <MdDelete style={{cursor:'pointer'}}  className='text-danger pb-1 ps-2 ' size={35} title='Delete user'/>
            </td> 
          </tr>
        </tbody>
      </Table>
  
          </div>

      </TabPanel>
{/*-------------------------------------------- Tab-2------------------------------------------------ */}      
      <TabPanel value={value} index={1}>
      <Form onSubmit={handleMisSubmit}>
      <Card style={{ width: '50%' }}>
            <Card.Body>
                <Card.Title><MdMiscellaneousServices size={30} className="pe-2 "/>School miscellaneous bill</Card.Title>
                <Card.Subtitle className="mb-2 text-muted">Enter the type</Card.Subtitle>
                <Card.Text>
                <FloatingLabel controlId="floatingInput" label="Type" className="mb-3" >
                    <Form.Control type="text" placeholder='Type'  />
                </FloatingLabel>
                </Card.Text>
                <lable className="pe-3">Status: </lable>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1" />
                    <label class="form-check-label" for="inlineRadio1">Active</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2" />
                    <label style={{color:'red'}} class="form-check-label" for="inlineRadio2">InActive</label>
                 </div>
            </Card.Body>
            <div style={{padding:'10px'}}>
             <Button style={{width:'45%'}} variant="success">Submit</Button>{' '}
            </div>
         </Card>
      </Form>

       {/*--------------------------- Table-2 -----------------------------*/}
       <div className='pt-5'>

       <div>
       <Modal className='pt-5' show={show} onHide={handleClose}>
        <Modal.Header closeButton>
          <Modal.Title>Edit School miscellaneous bill</Modal.Title>
        </Modal.Header>
        <Modal.Body>
           <FloatingLabel className='pb-2' controlId="floatingPassword" label="Sub-heading">
              <Form.Control type="text"  />
            </FloatingLabel>
            <FloatingLabel controlId="floatingSelect" label="Action Section">
                <Form.Select >
                  <option value="1">Active</option>
                  <option value="2">InActive</option>
                </Form.Select>
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
       </div>
            <Table striped bordered hover size="sm">
        <thead>
          <tr>
            <th>Sub-heading</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Digital Education </td>
            <td>Active</td>
            <td>
              <FaRegEdit onClick={handleShow} style={{cursor:'pointer'}} className='text-success pb-1 pe-1' size={28} title='Edit user'/>
              <MdDelete style={{cursor:'pointer'}}  className='text-danger pb-1 ps-2 ' size={35} title='Delete user'/>
            </td> 
          </tr>
        </tbody>
      </Table>
          </div>

      </TabPanel>
{/*-------------------------------------------- Tab-3------------------------------------------------ */}      
      <TabPanel value={value} index={2}>
        <Form onSubmit={handleHostalSubmit}>
           <Card style={{ width: '50%' }}>
            <Card.Body>
                <Card.Title><RiHomeSmileLine size={30} className="pe-2 pb-1"/>Hostel Bill</Card.Title>
                <Card.Subtitle className="mb-2 text-muted">Enter the type</Card.Subtitle>
                <Card.Text>
                <FloatingLabel controlId="floatingInput" label="Type" className="mb-3" >
                    <Form.Control type="text" placeholder='Type'  />
                </FloatingLabel>
                </Card.Text>
                <lable className="pe-3">Status: </lable>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1" />
                    <label class="form-check-label" for="inlineRadio1">Active</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2" />
                    <label style={{color:'red'}} class="form-check-label" for="inlineRadio2">InActive</label>
                 </div>
            </Card.Body>
            <div style={{padding:'10px'}}>
             <Button style={{width:'45%'}} variant="success">Submit</Button>{' '}
            </div>
          </Card>
         </Form>
                {/*--------------------------- Table-3 -----------------------------*/}
       <div className='pt-5'>

       <div>
       <Modal className='pt-5' show={show} onHide={handleClose}>
        <Modal.Header closeButton>
          <Modal.Title>Edit Hostel Bill</Modal.Title>
        </Modal.Header>
        <Modal.Body>
           <FloatingLabel className='pb-2' controlId="floatingPassword" label="Sub-heading">
              <Form.Control type="text"  />
            </FloatingLabel>
            <FloatingLabel controlId="floatingSelect" label="Action Section">
                <Form.Select >
                  <option value="1">Active</option>
                  <option value="2">InActive</option>
                </Form.Select>
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
       </div>
            <Table striped bordered hover size="sm">
        <thead>
          <tr>
            <th>Sub-heading</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Digital Education </td>
            <td>Active</td>
            <td>
              <FaRegEdit onClick={handleShow} style={{cursor:'pointer'}} className='text-success pb-1 pe-1' size={28} title='Edit user'/>
              <MdDelete style={{cursor:'pointer'}}  className='text-danger pb-1 ps-2 ' size={35} title='Delete user'/>
            </td> 
          </tr>
        </tbody>
      </Table>
          </div>
      </TabPanel>
{/*-------------------------------------------- Tab-4------------------------------------------------ */}       
      <TabPanel value={value} index={3}>
        <Form onSubmit={handleOtherSubmit}>
         <Card style={{ width: '50%' }}>
            <Card.Body>
                <Card.Title><MdOutlineCastForEducation size={30} className="pe-2 pb-1"/>Other hostel and Educational Expenditure</Card.Title>
                <Card.Subtitle className="mb-2 text-muted">Enter the type</Card.Subtitle>
                <Card.Text>
                <FloatingLabel controlId="floatingInput" label="Type" className="mb-3" >
                    <Form.Control type="text" placeholder='Type'  />
                </FloatingLabel>
                </Card.Text>
                <lable className="pe-3">Status: </lable>
                <div class="form-check form-check-inline">
                    <input  class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1" />
                    <label class="form-check-label" for="inlineRadio1">Active</label>
                </div>
                <div class="form-check form-check-inline">
                    <input  class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2" />
                    <label style={{color:'red'}} class="form-check-label" for="inlineRadio2">InActive</label>
                 </div>
            </Card.Body>
            <div style={{padding:'10px'}}>
             <Button style={{width:'45%'}} variant="success">Submit</Button>{' '}
            </div>
         </Card>
         </Form>

                {/*--------------------------- Table-4 -----------------------------*/}
       <div className='pt-5'>
       <div>
       <Modal className='pt-5' show={show} onHide={handleClose}>
        <Modal.Header closeButton>
          <Modal.Title>Edit Other Fee Section</Modal.Title>
        </Modal.Header>
        <Modal.Body>
           <FloatingLabel className='pb-2' controlId="floatingPassword" label="Sub-heading">
              <Form.Control type="text"  />
            </FloatingLabel>
            <FloatingLabel controlId="floatingSelect" label="Action Section">
                <Form.Select >
                  <option value="1">Active</option>
                  <option value="2">InActive</option>
                </Form.Select>
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
       </div>

            <Table striped bordered hover size="sm">
        <thead>
          <tr>
            <th>Sub-heading</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Digital Education </td>
            <td>Active</td>
            <td>
              <FaRegEdit onClick={handleShow} style={{cursor:'pointer'}} className='text-success pb-1 pe-1' size={28} title='Edit user'/>
              <MdDelete style={{cursor:'pointer'}}  className='text-danger pb-1 ps-2 ' size={35} title='Delete user'/>
            </td>   
          </tr>
        </tbody>
      </Table>
          </div>
      </TabPanel>
    </Box>
      </div>
      {/* <Footer/> */}
    </div>
    </div>
  )
}

export default Mfees