import {useState} from 'react';
import './dashboard.css';
import Footer from './Footer';
import Header from './Header';
import Card from 'react-bootstrap/Card';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import studentVec from '../Assets/studentVec.png'
import sponorVec from '../Assets/sponorVec.jpg';
import incomevec from '../Assets/incomevec.jpg'
import Sidebar from '../Admindashboard/Sidebar';
import {CategoryScale} from 'chart.js'; 
import Chart from 'chart.js/auto';
import { Line } from "react-chartjs-2";
import getDay from "date-fns/getDay";
import parse from "date-fns/parse";
import startOfWeek from "date-fns/startOfWeek";
import { Calendar, dateFnsLocalizer } from "react-big-calendar";
import "react-big-calendar/lib/css/react-big-calendar.css";
import "react-datepicker/dist/react-datepicker.css";
import format from "date-fns/format";
import {BsFillCalendar2PlusFill} from 'react-icons/bs'
import ListGroup from 'react-bootstrap/ListGroup';
import Badge from 'react-bootstrap/Badge';
import Button from 'react-bootstrap/Button';
import Form from 'react-bootstrap/Form';
import Modal from 'react-bootstrap/Modal';
import { DemoContainer } from '@mui/x-date-pickers/internals/demo';
import { AdapterDayjs } from '@mui/x-date-pickers/AdapterDayjs';
import { LocalizationProvider } from '@mui/x-date-pickers/LocalizationProvider';
import { DatePicker } from '@mui/x-date-pickers/DatePicker';
import FloatingLabel from 'react-bootstrap/FloatingLabel';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import Container from 'react-bootstrap/Container';
import Navbar from 'react-bootstrap/Navbar';
import Paper from '@mui/material/Paper';
import axios from 'axios';

const data = {
  labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
  datasets: [
    {
      label: "Students",
      data: [33, 53, 85, 41, 44, 65],
      fill: true,
      backgroundColor: "rgba(75,192,192,0.2)",
      borderColor: "rgba(75,192,192,1)"
    },
    {
      label: "Sponsor",
      data: [33, 25, 35, 51, 54, 76],
      fill: false,
      borderColor: "#742774"
    }
  ]
};
Chart.register(CategoryScale)


const locales = {
  "en-IN": require("date-fns/locale/en-IN"), 
};


const localizer = dateFnsLocalizer({
  format,
  parse,
  startOfWeek,
  getDay,
  locales,
  views: {
    month: true
}
},
 );



const Dashboard = () => {




function handleClick() {
  toast.success('Reminder setup successfully', {
    position: "bottom-left",
    autoClose: 1500,
    hideProgressBar: false,
    closeOnClick: false,
    pauseOnHover: true,
    draggable: false,
    progress: undefined,
    theme: "dark",
    });
}
const [isLoggedIn, setIsLoggedIn] = useState(sessionStorage.getItem('user_id') !== null);

if (!isLoggedIn) {
  // Redirect to the login page if user is not logged in
  // window.location.href = '/';
 }
  const [show, setShow] = useState(false);

  const handleClose = () => setShow(false);
  const handleShow = () => setShow(true);

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
      theme="dark" />
    
    <div className='container pt-4'>

{/*------------------ Heading------------------------------------ */}
      <section>
        <h4>Admin Dashboard</h4>
         <hr className='hrAdminDashboard'/>
        <p>HOME</p>
      </section>
{/*------------------ Cards------------------------------------ */}

      <section>
        <div className='row'>

{/*--------------- Card-1----------------------------------- */}
          <div className='col-4'>
            <Card>
            <Card.Body className='card1' style={{backgroundColor:'#f0f3f5'}}>
              <Row>
                <Col xs={4}>
                  <img style={{width:'110%'}} src={studentVec} alt="vector-img"/>
                </Col>
                <Col xs={1}>
                  <div className="vertical"></div>
                </Col>
                <Col xs={7}>
                  <h4 style={{fontFamily:'auto', fontSize:'x-large'}}>Total Students</h4>
                  <h3 className='pt-2 ps-4'>3247</h3>
                </Col>  
              </Row>
              </Card.Body>
            </Card>
          </div>
 {/*--------------- Card-2----------------------------------- */}
 <div className='col-4'>
            <Card>
            <Card.Body className='card1'style={{backgroundColor:'#f0f3f5'}}>
              <Row>
                <Col xs={4}>
                  <img style={{width:'110%'}} src={sponorVec} alt="vector-img"/>
                </Col>
                <Col xs={1}>
                  <div className="vertical"></div>
                </Col>
                <Col xs={7}>
                  <h4 style={{fontFamily:'auto', fontSize:'x-large'}}>Total Sponsor</h4>
                  <h3 className='pt-2 ps-4'>1386</h3>
                </Col>  
              </Row>
              </Card.Body>
            </Card>
          </div>
{/*--------------- Card-3----------------------------------- */}      
<div className='col-4'>
            <Card>
            <Card.Body className='card1' style={{backgroundColor:'#f0f3f5'}}>
              <Row>
                <Col xs={4}>
                  <img style={{width:'110%'}} src={incomevec} alt="vector-img"/>
                </Col>
                <Col xs={1}>
                  <div className="vertical"></div>
                </Col>
                <Col xs={7}>
                  <h4 style={{fontFamily:'auto', fontSize:'x-large'}}>Total Staff</h4>
                  <h3 className='pt-2 ps-4'>248</h3>
                </Col>  
              </Row>
              </Card.Body>
            </Card>
          </div>

{/*--------------- Card-4----------------------------------- */}
{/* <div className='col-3'>
            <Card>
            <Card.Body className='card1'>
              <Row>
                <Col xs={4}>
                  <img style={{width:'150%'}} src={studentVec} alt="vector-img"/>
                </Col>
                <Col xs={2}>
                  <div className="vertical"></div>
                </Col>
                <Col xs={6}>
                  <h5 style={{fontFamily:'auto', fontSize:'x-large'}}>T-Staff</h5>
                  <h4 className='pt-2'>54700</h4>
                </Col>  
              </Row>
              </Card.Body>
            </Card>
          </div> */}

        </div>
      </section>

  {/*-------------------- Chat Section--------------------------- */}
      <section className='pt-5'>
        <h4>Earnings</h4>
         <hr className='earnAdminDashboard'/>
        <div className='container pt-3 chartDashboard'>
         <Line data={data} />
        </div>
      </section>

{/*------------------------- Calander---------------------------- */}

<section className='pt-5'>
        <h4>Event Calendar</h4>
         <hr className='calAdminDashboard'/>
        <div className='container pt-3'>

        <div className='pb-3'>
          <button onClick={handleShow} class="button-37" role="button"><BsFillCalendar2PlusFill size={30} className="pe-2"/>Add Reminder </button>
{/* -------------------------Model POPUP------------------------------------------ */}
       <div>
          <Modal show={show} onHide={handleClose} centered>
        <Modal.Header style={{backgroundColor:'#E6E6E6'}}>
          <Modal.Title>Reminder Setup</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <Form>

          {/* <label className='pb-3 ps-2'>Select-Type</label> */}
             <Form.Select className='' aria-label="Default select example">
                <option value="1">Self</option>
                <option value="2">Student/Parent</option>
                <option value="3">Sponsor</option>
              </Form.Select>



            <Form.Group className="mb-3" controlId="exampleForm.ControlInput1">
            <LocalizationProvider dateAdapter={AdapterDayjs} >
              <DemoContainer components={['DatePicker']}>
                <DatePicker format='DD/MM/YYYY' />
              </DemoContainer>
            </LocalizationProvider>
            </Form.Group>

            <Form.Group className="mb-3" controlId="exampleForm.ControlInput1">
            <FloatingLabel controlId="floatingInput" label="Title"className="mb-3" >
                <Form.Control type="text" placeholder="name@example.com" />
              </FloatingLabel>
            </Form.Group>
    
           
            <Form.Group className="mb-3">
              <FloatingLabel controlId="floatingTextarea2" label="Description">
        <Form.Control as="textarea" placeholder="Description" style={{ height: '100px' }}/>
      </FloatingLabel>
            </Form.Group>

            <Form.Group className="mb-3 d-flex" controlId="exampleForm.ControlInput1">
              <h6 className='pe-3 pt-2'>Pick your reminder color : </h6>
              <Form.Control style={{width:'50%'}} type="color" id="favcolor" name="favcolor"  />
            </Form.Group>
    


          </Form>
        </Modal.Body>
        <Modal.Footer style={{backgroundColor:'#E6E6E6'}}>
          <Button variant="secondary" onClick={handleClose}>
            Close
          </Button>
          <Button variant="success" onClick={() => {handleClose(); handleClick();}}>
            Update
          </Button>
        </Modal.Footer>
      </Modal>
      </div>
  {/* -------------------------Model POPUP------------------------------------------ */}
        </div>

        <div className='pb-5'>
        <Row>
            <Col xs={8}>
              
              <Calendar localizer={localizer} startAccessor="start" endAccessor="end" style={{ height: 500, margin: "10px" }} views={[ "month"]}/>
            </Col>
            <Col xs={4}>
            <Navbar  className='p-1'  style={{backgroundColor:'#586572',margin:'56px 0 0 0',borderRadius: '10px 10px 0 0'}}>
            <Container>
              <Navbar.Brand  href="#home">
                 <h5 style={{color:'#fff', padding:'0 90px'}}>Reminders</h5>
              </Navbar.Brand>
            </Container>
         </Navbar>
         <Paper style={{maxHeight: 450, overflow: 'auto'}} className='scroll' >
            <Card style={{ width: '18rem' }}>
              <ListGroup variant="flush">

                <ListGroup.Item style={{width:'110%'}}>
                   <div><Badge pill  bg="warning" text="dark">13 jun, 2022</Badge>{' '}</div>
                  <h4 className='py-2'>41th Sport's Day</h4>
                  <span> Organize a day filled with various sports activities like races, obstacle courses, and team games for student</span>
                </ListGroup.Item>

                <ListGroup.Item style={{width:'110%'}}>
                   <div><Badge pill  bg="success" text="dark">14 jun, 2022</Badge>{' '}</div>
                  <h4 className='py-2'>Science Fair for 9th </h4>
                  <span>Host a science fair where students can showcase their experiments, inventions, and research projects</span>
                </ListGroup.Item>
                <ListGroup.Item style={{width:'110%'}}>
                <div><Badge pill  bg="info">07 Aug, 2022</Badge>{' '}</div>
                  <h4 className='py-2'>Cultural Day</h4>
                  <span>Celebrate the diversity of your school community by organizing a cultural day where students can learn about different cultures</span>
                </ListGroup.Item>

                <ListGroup.Item style={{width:'110%'}}>
                <div><Badge pill  bg="danger">31 Aug, 2022</Badge>{' '}</div>
                  <h4 className='py-2'>Parent-Teacher Conferences: </h4>
                  <span>Organize parent-teacher conferences where parents can meet with teachers to discuss their child's academic progress and receive feedback.</span>
                </ListGroup.Item>
              </ListGroup>
            </Card>
        </Paper>
        <Navbar  className='p-1'  style={{backgroundColor:'#E5E3DA',borderRadius: '0 0 10px 10px '}}>
            <Container> 
               <div style={{width:'110%',textAlign:'end'}}>
                  <a href='/reminders' style={{textDecoration:'none'}}>All Reminders...</a>
                </div>
            </Container>
         </Navbar>
          
       
            </Col>
          </Row> 
        </div>

  
        </div>
      </section>

    </div>
<Footer/>
    </div>
    
</div>
  )
}

export default Dashboard



















