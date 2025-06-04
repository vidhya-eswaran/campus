import {useState} from 'react'
import Header from './Header';
import Sidebar from '../Admindashboard/Sidebar';
import Footer from './Footer';
import Paper from '@mui/material/Paper';
import {  Container, Row,Col } from 'react-bootstrap';
import {MdOutlineDashboardCustomize} from 'react-icons/md'
import {AiOutlineDoubleRight} from 'react-icons/ai'
import {BsCalendarWeek,BsCalendarMonth} from 'react-icons/bs';
import { DemoContainer } from '@mui/x-date-pickers/internals/demo';
import { AdapterDayjs } from '@mui/x-date-pickers/AdapterDayjs';
import { LocalizationProvider } from '@mui/x-date-pickers/LocalizationProvider';
import { DatePicker } from '@mui/x-date-pickers/DatePicker';
import Breadcrumb from 'react-bootstrap/Breadcrumb';
import Navbar from 'react-bootstrap/Navbar';
import Card from 'react-bootstrap/Card';


const Reminders = () => {
  const [showComponent1, setShowComponent1] = useState(false);
  const [showComponent2, setShowComponent2] = useState(false);
  const [showComponent3, setShowComponent3] = useState(false);

  const handleButtonClick1 = () => {
    setShowComponent1(true);
    setShowComponent2(false);
    setShowComponent3(false);
  };

  const handleButtonClick2 = () => {
    setShowComponent1(false);
    setShowComponent2(true);
    setShowComponent3(false);
  };

  const handleButtonClick3 = () => {
    setShowComponent1(false);
    setShowComponent2(false);
    setShowComponent3(true);
  };



  return (
    <div>
      <Sidebar/>
         <div style={{width:'82.5%',float:'right'}} >
      <Header/>

      <div className='py-3'>
        <Container>
          <Paper elevation={6} className="pb-5">

          <div className='py-2' style={{left:'84%',position:'absolute'}}>
          <Breadcrumb>
            <Breadcrumb.Item href="/Dashboard">Dashboard</Breadcrumb.Item>
            <Breadcrumb.Item active >Reminder</Breadcrumb.Item>
          </Breadcrumb>
          </div>

            <div style={{padding:'30px',textAlign:'center'}}>
              <h2 >Reminder</h2>
              <p>Never miss an important event again with our reminder features</p>
            </div>

            <div style={{textAlign:'center'}}>
                <button onClick={handleButtonClick1} style={{marginRight: "12px"}} class="button-17" role="button"><BsCalendarWeek size={26} className='pe-2'/><h6 className='mb-0'>Week</h6></button>
                <button onClick={handleButtonClick2} style={{marginRight: "12px"}} class="button-17" role="button"><BsCalendarMonth size={26} className='pe-2'/><h6 className='mb-0'>Month</h6></button>
                <button onClick={handleButtonClick3} style={{marginRight: "12px"}} class="button-17" role="button"><MdOutlineDashboardCustomize size={26} className='pe-2'/><h6 className='mb-0'>Custom</h6></button>
            </div>
            
            
            <div style={{textAlign:'center'}}>{showComponent3 && <Component3 />}</div>

{/* ----------------------Reminder List---------------------------------------- */}
            <section className='container'>
              <Navbar  className='p-1'  style={{backgroundColor:'#586572',margin:'56px 0 0 0',borderRadius: '10px 10px 0 0'}}>
              
                <Navbar.Brand  href="#home">
                  <h5 style={{color:'#fff', padding:'0 0px'}}>
                  <div style={{textAlign:'start'}}>{showComponent1 && <Component1 />}</div>
                  <div style={{textAlign:'start'}}>{showComponent2 && <Component2 />}</div>
                  </h5>
                </Navbar.Brand>
            
         </Navbar>
         <Paper style={{maxHeight: 500, overflow: 'auto',backgroundColor:'#F3ECEC'}} className='scroll container' >

         <div className='py-3'>
            <div className='py-2'>
            <Card style={{borderLeft: "15px solid",borderColor: "#ee7373"}}>
                <Card.Body>
                    <div className='row'>
                      <div className='col-2' style={{marginTop:'20px'}}>
                        <h6>13 August, 2022</h6>
                      </div>
                      <div className='col-1'>
                         <div className="verticalReminder"></div>
                      </div>
                      <div className='col-9'>
                         <h5>31th Sport's day</h5>
                         <p className="text">
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Odio mollitia, qui nam laboriosam ad esse temporibus nobis perferendis fuga dicta ipsam magnam eos aspernatur soluta aliquam explicabo
                          </p>
                      </div>
                    </div>
                  </Card.Body>
              </Card>
            </div>
            <div className='py-2'>
            <Card style={{borderLeft: "15px solid",borderColor: "#BAF7F3"}}>
                <Card.Body>
                    <div className='row'>
                      <div className='col-2' style={{marginTop:'20px'}}>
                        <h6>09 June, 2022</h6>
                      </div>
                      <div className='col-1'>
                         <div className="verticalReminder"></div>
                      </div>
                      <div className='col-9'>
                         <h5>Talent Show</h5>
                         <p className="text">
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Odio mollitia, qui nam laboriosam ad esse temporibus nobis perferendis fuga dicta ipsam magnam eos aspernatur soluta aliquam explicabo
                          </p>
                      </div>
                    </div>
                  </Card.Body>
              </Card>
            </div>
            <div className='py-2'>
            <Card style={{borderLeft: "15px solid",borderColor: "#FCB8C9"}}>
                <Card.Body>
                    <div className='row'>
                      <div className='col-2' style={{marginTop:'20px'}}>
                        <h6>04 May, 2022</h6>
                      </div>
                      <div className='col-1'>
                         <div className="verticalReminder"></div>
                      </div>
                      <div className='col-9'>
                         <h5>Book Fair</h5>
                         <p className="text">
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Odio mollitia, qui nam laboriosam ad esse temporibus nobis perferendis fuga dicta ipsam magnam eos aspernatur soluta aliquam explicabo
                          </p>
                      </div>
                    </div>
                  </Card.Body>
              </Card>
            </div>
            <div className='py-2'>
            <Card style={{borderLeft: "15px solid",borderColor: "#FEDCBF"}}>
                <Card.Body>
                    <div className='row'>
                      <div className='col-2' style={{marginTop:'20px'}}>
                        <h6>26 July, 2022</h6>
                      </div>
                      <div className='col-1'>
                         <div className="verticalReminder"></div>
                      </div>
                      <div className='col-9'>
                         <h5>Science Fair</h5>
                         <p className="text">
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Odio mollitia, qui nam laboriosam ad esse temporibus nobis perferendis fuga dicta ipsam magnam eos aspernatur soluta aliquam explicabo
                          </p>
                      </div>
                    </div>
                  </Card.Body>
              </Card>
            </div>
            <div className='py-2'>
            <Card style={{borderLeft: "15px solid",borderColor: "#632B75"}}>
                <Card.Body>
                    <div className='row'>
                      <div className='col-2' style={{marginTop:'20px'}}>
                        <h6>21 Feb, 2022</h6>
                      </div>
                      <div className='col-1'>
                         <div className="verticalReminder"></div>
                      </div>
                      <div className='col-9'>
                         <h5>Cultural Day</h5>
                         <p className="text">
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Odio mollitia, qui nam laboriosam ad esse temporibus nobis perferendis fuga dicta ipsam magnam eos aspernatur soluta aliquam explicabo
                          </p>
                      </div>
                    </div>
                  </Card.Body>
              </Card>
            </div>
            <div className='py-2'>
            <Card style={{borderLeft: "15px solid",borderColor: "#ee7373"}}>
                <Card.Body>
                    <div className='row'>
                      <div className='col-2' style={{marginTop:'20px'}}>
                        <h6>13 August, 2022</h6>
                      </div>
                      <div className='col-1'>
                         <div className="verticalReminder"></div>
                      </div>
                      <div className='col-9'>
                         <h5>31th Sport's day</h5>
                         <p className="text">
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Odio mollitia, qui nam laboriosam ad esse temporibus nobis perferendis fuga dicta ipsam magnam eos aspernatur soluta aliquam explicabo
                          </p>
                      </div>
                    </div>
                  </Card.Body>
              </Card>
            </div>
            <div className='py-2'>
            <Card style={{borderLeft: "15px solid",borderColor: "#BAF7F3"}}>
                <Card.Body>
                    <div className='row'>
                      <div className='col-2' style={{marginTop:'20px'}}>
                        <h6>13 August, 2022</h6>
                      </div>
                      <div className='col-1'>
                         <div className="verticalReminder"></div>
                      </div>
                      <div className='col-9'>
                         <h5>31th Sport's day</h5>
                         <p className="text">
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Odio mollitia, qui nam laboriosam ad esse temporibus nobis perferendis fuga dicta ipsam magnam eos aspernatur soluta aliquam explicabo
                          </p>
                      </div>
                    </div>
                  </Card.Body>
              </Card>
            </div>
            <div className='py-2'>
            <Card style={{borderLeft: "15px solid",borderColor: "#FCB8C9"}}>
                <Card.Body>
                    <div className='row'>
                      <div className='col-2' style={{marginTop:'20px'}}>
                        <h6>13 August, 2022</h6>
                      </div>
                      <div className='col-1'>
                         <div className="verticalReminder"></div>
                      </div>
                      <div className='col-9'>
                         <h5>31th Sport's day</h5>
                         <p className="text">
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Odio mollitia, qui nam laboriosam ad esse temporibus nobis perferendis fuga dicta ipsam magnam eos aspernatur soluta aliquam explicabo
                          </p>
                      </div>
                    </div>
                  </Card.Body>
              </Card>
            </div>
            <div className='py-2'>
            <Card style={{borderLeft: "15px solid",borderColor: "#FEDCBF"}}>
                <Card.Body>
                    <div className='row'>
                      <div className='col-2' style={{marginTop:'20px'}}>
                        <h6>13 August, 2022</h6>
                      </div>
                      <div className='col-1'>
                         <div className="verticalReminder"></div>
                      </div>
                      <div className='col-9'>
                         <h5>31th Sport's day</h5>
                         <p className="text">
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Odio mollitia, qui nam laboriosam ad esse temporibus nobis perferendis fuga dicta ipsam magnam eos aspernatur soluta aliquam explicabo
                          </p>
                      </div>
                    </div>
                  </Card.Body>
              </Card>
            </div>
            <div className='py-2'>
            <Card style={{borderLeft: "15px solid",borderColor: "#632B75"}}>
                <Card.Body>
                    <div className='row'>
                      <div className='col-2' style={{marginTop:'20px'}}>
                        <h6>13 August, 2022</h6>
                      </div>
                      <div className='col-1'>
                         <div className="verticalReminder"></div>
                      </div>
                      <div className='col-9'>
                         <h5>31th Sport's day</h5>
                         <p className="text">
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Odio mollitia, qui nam laboriosam ad esse temporibus nobis perferendis fuga dicta ipsam magnam eos aspernatur soluta aliquam explicabo
                          </p>
                      </div>
                    </div>
                  </Card.Body>
              </Card>
            </div>
         </div>

         </Paper>
            </section>
          </Paper>
      </Container>
 </div><Footer/>
      </div>
      
    </div>
  )
}
function Component1() {
  return (
    <div className='ps-2'>
      <h4>“These are reminders for the week”</h4>
    </div>
  );
}

function Component2() {
  return (
    <div className='ps-2'>
      <h4>“These are reminders for the month”</h4>
    </div>
  );
}

function Component3() {
  return(
    <div className='pt-4 d-flex' style={{marginLeft:'156px'}}>

       <div className='d-flex'>
        <h5 className='p-4'>From</h5>
        <LocalizationProvider dateAdapter={AdapterDayjs} >
              <DemoContainer components={['DatePicker']}>
                <DatePicker  format='DD/MM/YYYY' />
              </DemoContainer>
            </LocalizationProvider>
       </div>

       <div className='d-flex'>
        <h5 className='p-4' >To</h5>
        <LocalizationProvider dateAdapter={AdapterDayjs} >
              <DemoContainer components={['DatePicker']}>
                <DatePicker format='DD/MM/YYYY'  />
              </DemoContainer>
            </LocalizationProvider>
       </div>
       <div className='pt-2 ps-4'>
         <button  class="button-18" role="button"><h6 className='mb-0'>Sumbit</h6></button>
       </div>


    </div>
  );
}

export default Reminders





{/* <div className='py-2'>
<Card style={{backgroundColor:'#B2D6E9'}} >
    <Card.Body>
      <Row>
        <Col xs={2}>
           <h6>13 Aug, 2022</h6>
        </Col>
        <Col xs={1}>
          <div className="verticalReminder"></div>
        </Col>
        <Col xs={9}>
          <h5>31th Sport's day</h5>
          <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Odio mollitia, qui nam laboriosam ad esse temporibus nobis perferendis fuga dicta ipsam magnam eos aspernatur soluta aliquam explicabo voluptas alias corporis.</p>
        </Col>
      </Row>
    </Card.Body>
  </Card>
</div>

<div className='py-2'>
<Card style={{backgroundColor:'#F9B395'}} >
    <Card.Body>
      <Row>
        <Col xs={2}>
           <h6>31 Aug, 2022</h6>
        </Col>
        <Col xs={1}>
          <div className="verticalReminder"></div>
        </Col>
        <Col xs={9}>
          <h5>Science Fair</h5>
          <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Odio mollitia, qui nam laboriosam ad esse temporibus nobis perferendis fuga dicta ipsam magnam eos aspernatur soluta aliquam explicabo voluptas alias corporis.</p>
        </Col>
      </Row>
    </Card.Body>
  </Card>
</div>

<div className='py-2'>
<Card style={{backgroundColor:'#6D5C7D'}} >
    <Card.Body>
      <Row>
        <Col xs={2}>
           <h6>07 jun, 2022</h6>
        </Col>
        <Col xs={1}>
          <div className="verticalReminder"></div>
        </Col>
        <Col xs={9}>
          <h5>Cultural Day for 12th student</h5>
          <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Odio mollitia, qui nam laboriosam ad esse temporibus nobis perferendis fuga dicta ipsam magnam eos aspernatur soluta aliquam explicabo voluptas alias corporis.</p>
        </Col>
      </Row>
    </Card.Body>
  </Card>
</div>

<div className='py-2'>
<Card style={{backgroundColor:'#035D72'}} >
    <Card.Body>
      <Row>
        <Col xs={2}>
           <h6>11 jan, 2022</h6>
        </Col>
        <Col xs={1}>
          <div className="verticalReminder"></div>
        </Col>
        <Col xs={9}>
          <h5>Talent Show for primary student</h5>
          <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Odio mollitia, qui nam laboriosam ad esse temporibus nobis perferendis fuga dicta ipsam magnam eos aspernatur soluta aliquam explicabo voluptas alias corporis.</p>
        </Col>
      </Row>
    </Card.Body>
  </Card>
</div>

<div className='py-2'>
<Card style={{backgroundColor:'#B2D6E9'}} >
    <Card.Body>
      <Row>
        <Col xs={2}>
           <h6>13 Aug, 2022</h6>
        </Col>
        <Col xs={1}>
          <div className="verticalReminder"></div>
        </Col>
        <Col xs={9}>
          <h5>31th Sport's day</h5>
          <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Odio mollitia, qui nam laboriosam ad esse temporibus nobis perferendis fuga dicta ipsam magnam eos aspernatur soluta aliquam explicabo voluptas alias corporis.</p>
        </Col>
      </Row>
    </Card.Body>
  </Card>
</div>

<div className='py-2'>
<Card style={{backgroundColor:'#F9B395'}} >
    <Card.Body>
      <Row>
        <Col xs={2}>
           <h6>31 Aug, 2022</h6>
        </Col>
        <Col xs={1}>
          <div className="verticalReminder"></div>
        </Col>
        <Col xs={9}>
          <h5>Science Fair</h5>
          <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Odio mollitia, qui nam laboriosam ad esse temporibus nobis perferendis fuga dicta ipsam magnam eos aspernatur soluta aliquam explicabo voluptas alias corporis.</p>
        </Col>
      </Row>
    </Card.Body>
  </Card>
</div>

<div className='py-2'>
<Card style={{backgroundColor:'#6D5C7D'}} >
    <Card.Body>
      <Row>
        <Col xs={2}>
           <h6>07 jun, 2022</h6>
        </Col>
        <Col xs={1}>
          <div className="verticalReminder"></div>
        </Col>
        <Col xs={9}>
          <h5>Cultural Day for 12th student</h5>
          <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Odio mollitia, qui nam laboriosam ad esse temporibus nobis perferendis fuga dicta ipsam magnam eos aspernatur soluta aliquam explicabo voluptas alias corporis.</p>
        </Col>
      </Row>
    </Card.Body>
  </Card>
</div>

<div className='py-2'>
<Card style={{backgroundColor:'#035D72'}} >
    <Card.Body>
      <Row>
        <Col xs={2}>
           <h6>11 jan, 2022</h6>
        </Col>
        <Col xs={1}>
          <div className="verticalReminder"></div>
        </Col>
        <Col xs={9}>
          <h5>Talent Show for primary student</h5>
          <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Odio mollitia, qui nam laboriosam ad esse temporibus nobis perferendis fuga dicta ipsam magnam eos aspernatur soluta aliquam explicabo voluptas alias corporis.</p>
        </Col>
      </Row>
    </Card.Body>
  </Card>
</div> */}