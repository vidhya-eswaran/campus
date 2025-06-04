import React,{useState} from 'react';
import './dashboard.css';
import Header from './Header';
import Footer from './Footer';
import Sidebar from './Sidebar';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Button from 'react-bootstrap/Button';
import {GrSettingsOption} from 'react-icons/gr';
import Card from 'react-bootstrap/Card';
import PropTypes from 'prop-types';
import Tabs from '@mui/material/Tabs';
import Tab from '@mui/material/Tab';
import Typography from '@mui/material/Typography';
import Box from '@mui/material/Box';
import TextField from '@mui/material/TextField';
import Form from 'react-bootstrap/Form';
import IconButton from '@mui/material/IconButton';
import OutlinedInput from '@mui/material/OutlinedInput';
import InputLabel from '@mui/material/InputLabel';
import InputAdornment from '@mui/material/InputAdornment';
import FormControl from '@mui/material/FormControl';
import Visibility from '@mui/icons-material/Visibility';
import VisibilityOff from '@mui/icons-material/VisibilityOff';
import { Container } from 'react-bootstrap';
import {MdAddAPhoto} from 'react-icons/md'







function TabPanel(props) {
    const { children, value, index, ...other } = props;
  
    return (
      <div
        role="tabpanel"
        hidden={value !== index}
        id={`vertical-tabpanel-${index}`}
        aria-labelledby={`vertical-tab-${index}`}
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
      id: `vertical-tab-${index}`,
      'aria-controls': `vertical-tabpanel-${index}`,
    };
  }


const Setting = () => {

  
  const [image, setImage] = useState(null);
  const [error, setError] = useState(null);

  const handleChanges = (event) => {
    let selectedFile = event.target.files[0];

    if (!selectedFile) {
      setImage(null);
      setError("No file selected");
      return;
    }

    if (selectedFile.type.match(/image\/*/) == null) {
      setImage(null);
      setError("File is not an image");
      return;
    }

    setError(null);
    setImage(selectedFile);
  };





    const [value, setValue] = React.useState(0);

    const handleChange = (event, newValue) => {
      setValue(newValue);
    };
    const [showPassword, setShowPassword] = React.useState(false);

    const handleClickShowPassword = () => setShowPassword((show) => !show);
    
    const handleMouseDownPassword = (event) => {
      event.preventDefault();
    };
  return (
    <div>
       
    <Sidebar/>
      <div style={{width:'82.5%',float:'right'}} >
        <Header/>

            <div className='p-4'>
                <h3 ><GrSettingsOption  size={34} className='pe-2 pb-1'/>Setting</h3>
                <hr className='settingHr'/>
            </div>
            <section className='container'>
                <Box sx={{ flexGrow: 1, bgcolor: 'background.paper', display: 'flex', height: '100%' }}>
        <Tabs
            orientation="vertical"
            variant="scrollable"
            value={value}
            onChange={handleChange}
            aria-label="Vertical tabs example"
            sx={{ borderRight: 2, borderColor: 'divider' }}>

            <Tab label="Change Password" {...a11yProps(0)} />
            <Tab label="Admin Profile" {...a11yProps(1)} />
            <Tab label="Notifcation" {...a11yProps(2)} />

        </Tabs>
        <TabPanel value={value} index={0}>
            <h6 style={{fontSize: '3rem',fontWeight: 100}}>Change Password</h6>
            <li>Strong password required. Enter 8-30 characters. Do not include common words or name </li>
            <li>Combine uppercase letters, lowercase letter, number and symbols </li>
            <li>Example : Useradmin@71932 </li>

            <section className='pt-4'>
             <Card style={{ width: '100%' ,textAlign:'center'}}>
             <Card.Body>
               <Card.Title className='mb-0'>User name</Card.Title>
               <p className='mt-0'>Jhon Deo</p>

                  <div className='d-grid'>
                    <TextField type='password' id="outlined-basic" label="Old Password" variant="outlined" />
    {/*----------------- PAssword ------------------------------------------------------*/}
                    <Form.Group controlId="formBasicPassword" className='py-4'>
                            <FormControl style={{width:'100%'}} variant="outlined" >
                        <InputLabel htmlFor="outlined-adornment-password">New Password</InputLabel>
                        <OutlinedInput fullWidth
                        id="outlined-adornment-password"
                        type={showPassword ? 'text' : 'password'}
                        endAdornment={
                        <InputAdornment position="end">
                            <IconButton
                            aria-label="toggle password visibility"
                            onClick={handleClickShowPassword}
                            onMouseDown={handleMouseDownPassword}
                            edge="end">
                            {showPassword ? <VisibilityOff /> : <Visibility />}
                            </IconButton>
                        </InputAdornment>
                        }
                        label="New Password"/>
                        </FormControl> 
                            </Form.Group>
{/*----------------- PAssword ------------------------------------------------------*/} 
                 <TextField type='password' id="outlined-basic" label="Confirm Password" variant="outlined" />
                 <div className='text-center py-3'><button class="button-20" role="button" type="submit">Update</button></div>
                  </div>
                </Card.Body>
                </Card>
              
            </section>
        </TabPanel>
{/*----------------------- Tabpanel-2-------------------------------------------- */}
        <TabPanel value={value} index={1} style={{width:'80%'}}>
            <section >
              <Container>
                <div className='row'>
                    <div className='col-2 '>
                       {/* <img style={{width:'100%'}} src="https://images.pexels.com/photos/220453/pexels-photo-220453.jpeg?cs=srgb&dl=pexels-pixabay-220453.jpg&fm=jpg" alt="profile-img" />  */}
                       <div className="" style={{width: '150px'}}>
                {error && <div className="error text-danger">{error}</div>}
                             
                     {image && (
                            <div>
                               <img className="imageSize" src={URL.createObjectURL(image)} alt="Preview" />
                            </div>
                      )}
                      
                      {!image && (
                            <div>
                               <img className="imageSize" src="https://ionicframework.com/docs/img/demos/avatar.svg" alt="okdbhg"/>
                            </div>
                      )}       
                        <div className='uploadProfileBtn'>
                <label for="file-input" class="custom-file-upload">Edit Profile<MdAddAPhoto size={25} className='ps-2'/></label>
                <input className="han" id="file-input" type="file" onChange={handleChanges}/>
            </div> 
                 
         
                 
                </div>
                    </div>
                    <div className='col-10' style={{padding:'30px 0 50px 70px'}}>
                      <h5>Udhaya Suriya A</h5>
                      <p>~ Adminator</p>
                      <Button >Edit Profile</Button>
                    </div>

                </div>

                <div className='row p-4'>
                <Form>
                  <Form.Group className="mb-3" controlId="formBasicEmail">
                    <Form.Label>User Name</Form.Label>
                    <Form.Control type="text" />
                  </Form.Group>
                  <Form.Group className="mb-3" controlId="formBasicEmail">
                    <Form.Label>Full Name</Form.Label>
                    <Form.Control type="text" />
                  </Form.Group>
                  <Form.Group className="mb-3" controlId="formBasicEmail">
                    <Form.Label>Email</Form.Label>
                    <Form.Control type="text" />
                  </Form.Group>
                  <Form.Group className="mb-3" controlId="formBasicEmail">
                    <Form.Label>Password</Form.Label>
                    <Form.Control type="Password" />
                  </Form.Group>
                  <Form.Group className="mb-3" controlId="formBasicEmail">
                    <Form.Label>Title</Form.Label>
                    <Form.Control type="text" />
                  </Form.Group>
              </Form>
                </div>
               </Container>
            </section>
        </TabPanel>
        <TabPanel value={value} index={2} style={{width:'80%'}}>
            Lorem ipsum dolor, sit amet consectetur adipisicing elit. Nobis doloribus temporibus pariatur amet iste quibusdam provident ipsa voluptatibus    voluptate non, deserunt soluta quidem officiis modi maiores illo illum consectetur.
        </TabPanel>
        </Box>
            </section>

      </div>
 </div>
  )
}

export default Setting
