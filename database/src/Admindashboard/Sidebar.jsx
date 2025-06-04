import React from 'react';
import './dashboard.css';
import logo from '../Assets/logo.jpeg'
import {FaPeopleCarry} from 'react-icons/fa';
import {AiFillSetting,AiOutlineDashboard} from 'react-icons/ai';
import {BiChevronDown,BiSitemap} from 'react-icons/bi'
import {FiLogOut} from 'react-icons/fi';
import {FaFileInvoiceDollar} from 'react-icons/fa';
import {GiNailedHead,GiTakeMyMoney} from 'react-icons/gi';
import {AiOutlineMenu} from 'react-icons/ai';
import {MdManageHistory} from 'react-icons/md';
import {SiGoogletagmanager} from 'react-icons/si';
import {BsFillPersonLinesFill} from 'react-icons/bs';
import {CgEditBlackPoint} from 'react-icons/cg';
import axios from 'axios';


const Test = () => {
  //agent b
     const userId = sessionStorage.getItem('token_id');
      const accessToken = sessionStorage.getItem('accessToken');

  const handleLogout = async () => {
    try {
      const payload = {
       id: userId
       };
       const config = {
        headers: {
          'Authorization': `Bearer ${accessToken}`
        }
      };
      const response = await axios.post('http://127.0.0.1:8000/api/logout', payload);

      // const response = await axios.post('http://127.0.0.1:8000/api/logout', payload, config);
            sessionStorage.removeItem('user_id');
      sessionStorage.removeItem('email');
      sessionStorage.removeItem('user_type');
          sessionStorage.removeItem('name');
      sessionStorage.removeItem('accessToken');
      sessionStorage.removeItem('token_id');

 
      // setIsLoggedIn(false);
    
      
      // Store the response data in session storage 
    console.log("logout");
        window.location.href = '/';
    } catch (error) {
      console.error(error);
    }
  };
  //end
  return (
    <div className="sidebar">
      <div className='damDiv'>
        <div className='row' >
          <div style={{backgroundColor:'#192F59',borderRight:'1px solid'}} className='col-auto col-sm-12  d-flex flex-column justify-content-between min-vh-100 p-0'>
            <div className=''>
                <div className='bg-white'>
                  <img style={{width:'40%'}} src={logo} alt='logo' />
                  <AiOutlineMenu className='navmenuside'/>
                </div>

                <div className='damIt'>
                  <div>
                  <ul class="nav nav-pills flex-column mt-2 mt-sm-0" id="parentM">

    {/*-------------------- DASHBOARD -----------------------------------------------*/}
                  <li class="nav-item my-1 py-2 py-sm-0">
                    <a href="/dashboard " className="nav-link text-white text-center text-sm-start menuText" aria-current="page">
                      <AiOutlineDashboard size={25}/><span style={{fontSize:'20px'}} className='ms-2 d-none d-sm-inline menuSpanText'>Dashboard</span>
                    </a>
                  </li>
    {/*-------------------- Management User -----------------------------------------------*/}
                  <li class="nav-item my-1 py-2 py-sm-0">
                    <a href="#submenu" class="nav-link text-white text-center text-sm-start menuText" data-bs-toggle = "collapse" aria-current="page">
                      <SiGoogletagmanager size={25}/><span style={{fontSize:'20px'}} className='ms-2 d-none d-sm-inline'>Manage user</span>
                      <BiChevronDown size={25} className='ms-0 ms-sm-3 d-none d-sm-inline' style={{float:'right'}}/>
                    </a>

                    <ul class="nav collapse ms-1" id='submenu' data-bs-parent = "parentM">
                        <li class="nav-item" style={{marginLeft:'30px'}}>
                            <a class="nav-link text-white" href="/MangerUser/User" aria-current="page" ><CgEditBlackPoint className='pe-1 pt-1'/><span className='d-none d-sm-inline menuSpanText'>User</span></a>
                        </li>
                        <li class="nav-item" style={{marginLeft:'30px'}}>
                            <a class="nav-link text-white" href="/MangerUser/Role" aria-current="page" ><CgEditBlackPoint className='pe-1 pt-1'/><span className='d-none d-sm-inline menuSpanText'>Role</span></a>
                        </li>
                        <li class="nav-item" style={{marginLeft:'30px'}}>
                            <a class="nav-link text-white" href="/MangerUser/StudentUser" aria-current="page" ><CgEditBlackPoint className='pe-1 pt-1'/><span className='d-none d-sm-inline menuSpanText'>Student user</span></a>
                        </li>
                    </ul>
                  </li>
    {/*-------------------- MASTERS -----------------------------------------------*/}
                  <li class="nav-item my-1 py-2 py-sm-0">
                    <a href="#submenu1" class="nav-link text-white text-center text-sm-start menuText" data-bs-toggle = "collapse" aria-current="page">
                      <GiNailedHead size={29}/><span style={{fontSize:'20px'}} className='ms-2 d-none d-sm-inline'>Masters</span>
                      <BiChevronDown size={25} className='ms-0 ms-sm-3 d-none d-sm-inline' style={{float:'right'}}/>
                    </a>
                    <ul  class="nav collapse ms-1" id='submenu1' data-bs-parent = "parentM">
                        <li style={{marginLeft:'30px'}} class="nav-item">
                            <a class="nav-link text-white" href="/Masters/AddStudentlist" aria-current="page" ><CgEditBlackPoint className='pe-1'/><span className='d-none d-sm-inline'>Add student</span></a>
                        </li>
                        <li style={{marginLeft:'30px'}} class="nav-item">
                            <a class="nav-link text-white" href="#student_profile" aria-current="page" ><CgEditBlackPoint className='pe-1'/><span className='d-none d-sm-inline'>Staff Details</span></a>
                        </li>
                        <li style={{marginLeft:'30px'}} class="nav-item ">
                            <a class="nav-link text-white" href="/Masters/Mfees"><CgEditBlackPoint className='pe-1'/><span className='d-none d-sm-inline'>Fees Category</span></a>
                        </li>
                        <li style={{marginLeft:'30px'}} class="nav-item ">
                            <a class="nav-link text-white" href="/Masters/AddSponsorlist"><CgEditBlackPoint className='pe-1'/><span className='d-none d-sm-inline'>Sponsor</span></a>
                        </li>
                        <li style={{marginLeft:'30px'}} class="nav-item ">
                            <a class="nav-link text-white" href="/Masters/SectionMaster"><CgEditBlackPoint className='pe-1'/><span className='d-none d-sm-inline'>Section</span></a>
                        </li>
                        <li style={{marginLeft:'30px'}} class="nav-item ">
                            <a class="nav-link text-white" href="/Masters/ClassMaster"><CgEditBlackPoint className='pe-1'/><span className='d-none d-sm-inline'>Class</span></a>
                        </li>
                        <li style={{marginLeft:'30px'}} class="nav-item ">
                            <a class="nav-link text-white" href="/Masters/GroupMaster"><CgEditBlackPoint className='pe-1'/><span className='d-none d-sm-inline'>Group</span></a>
                        </li>
                        <li style={{marginLeft:'30px'}}class="nav-item ">
                            <a class="nav-link text-white" href="/Masters/ModeofpaymentMaster"><CgEditBlackPoint className='pe-1'/><span className='d-none d-sm-inline'>Mode of payment</span></a>
                        </li>
                    </ul>
                  </li>

    {/*-------------------- STUDENT FEES -----------------------------------------------*/}
                  <li class="nav-item my-1 py-2 py-sm-0">
                    <a href="#submenu2" class="nav-link text-white text-center text-sm-start menuText" data-bs-toggle = "collapse" aria-current="page">
                      <GiTakeMyMoney size={29}/><span style={{fontSize:'20px'}} className='ms-2 d-none d-sm-inline'>Student Fees</span>
                      <BiChevronDown size={25} className='ms-0 ms-sm-3 d-none d-sm-inline' style={{float:'right'}}/>
                    </a>

                    <ul class="nav collapse ms-1" id='submenu2' data-bs-parent = "parentM">
                        <li style={{marginLeft:'30px'}} class="nav-item">
                            <a class="nav-link text-white" href="#student_profile" aria-current="page" ><CgEditBlackPoint className='pe-1'/><span className='d-none d-sm-inline menuSpanText'>Tuition Fees</span></a>
                        </li>
                        <li style={{marginLeft:'30px'}} class="nav-item">
                            <a class="nav-link text-white" href="#student_profile" aria-current="page" ><CgEditBlackPoint className='pe-1'/><span className='d-none d-sm-inline menuSpanText'>Bus Fees</span></a>
                        </li>
                        <li style={{marginLeft:'30px'}} class="nav-item ">
                            <a class="nav-link text-white" href="#SponserProfile"><CgEditBlackPoint className='pe-1'/><span className='d-none d-sm-inline'>Hostal Fees</span></a>
                        </li>
                    </ul>
                  </li>
    {/*-------------------- Genrate Invoice -----------------------------------------------*/}
                  <li class="nav-item my-1 py-2 py-sm-0">
                    <a href="#submenu3" class="nav-link text-white text-center text-sm-start menuText" data-bs-toggle = "collapse" aria-current="page">
                      <FaFileInvoiceDollar size={25}/><span style={{fontSize:'20px'}} className='ms-2 d-none d-sm-inline'>Genrate Invoice</span>
                      <BiChevronDown size={25} className='ms-0 ms-sm-3 d-none d-sm-inline' style={{float:'right'}}/>
                    </a>

                    <ul class="nav collapse ms-1" id='submenu3' data-bs-parent = "parentM">
                        <li style={{marginLeft:'30px'}} class="nav-item">
                            <a class="nav-link text-white" href="#student_profile" aria-current="page" ><CgEditBlackPoint className='pe-1'/><span className='d-none d-sm-inline menuSpanText'>Genrate Tuition</span></a>
                        </li>
                        <li style={{marginLeft:'30px'}} class="nav-item ">
                            <a class="nav-link text-white" href="#SponserProfile"><CgEditBlackPoint className='pe-1'/><span className='d-none d-sm-inline'>Genrate Hostal&others</span></a>
                        </li>
                    </ul>
                  </li>
    {/*-------------------- Maping System -----------------------------------------------*/}
                  <li class="nav-item my-1 py-2 py-sm-0">
                    <a href="#submenu4" class="nav-link text-white text-center text-sm-start menuText" data-bs-toggle = "collapse" aria-current="page">
                      <BiSitemap size={29}/><span style={{fontSize:'20px'}} className='ms-2 d-none d-sm-inline'>Maping System</span>
                      <BiChevronDown size={25} className='ms-0 ms-sm-3 d-none d-sm-inline' style={{float:'right'}}/>
                    </a>

                    <ul class="nav collapse ms-1" id='submenu4' data-bs-parent = "parentM">
                        <li style={{marginLeft:'30px'}} class="nav-item">
                            <a class="nav-link text-white" href="/MappingStystem/Feesmaping" aria-current="page" ><CgEditBlackPoint className='pe-1'/><span className='d-none d-sm-inline menuSpanText'>Fees Maping</span></a>
                        </li>
                        <li style={{marginLeft:'30px'}} class="nav-item">
                            <a class="nav-link text-white" href="/MappingStystem/Sponsormaping" aria-current="page" ><CgEditBlackPoint className='pe-1'/><span className='d-none d-sm-inline menuSpanText'>Sponsor Maping</span></a>
                        </li>
                    </ul>
                  </li>
      {/*-------------------- History -----------------------------------------------*/}
                      <li class="nav-item my-1 py-2 py-sm-0">
                    <a href="#submenu5" class="nav-link text-white text-center text-sm-start menuText" data-bs-toggle = "collapse" aria-current="page">
                      <MdManageHistory size={29}/><span style={{fontSize:'20px'}} className='ms-2 d-none d-sm-inline'>Histroy</span>
                      <BiChevronDown size={25} className='ms-0 ms-sm-3 d-none d-sm-inline' style={{float:'right'}}/>
                    </a>
                    <ul  class="nav collapse ms-1" id='submenu5' data-bs-parent = "parentM">
                        <li style={{marginLeft:'30px'}} class="nav-item">
                            <a class="nav-link text-white" href="/Masters/AddStudentlist" aria-current="page" ><CgEditBlackPoint className='pe-1'/><span className='d-none d-sm-inline'>Add student</span></a>
                        </li>
                    </ul>
                  </li>

    {/*-------------------- Student Profile -----------------------------------------------*/}
                  <li class="nav-item text-white my-1 py-2 py-sm-0 ">
                    <a href="/StudentProfile/Profile" class="nav-link text-white text-center text-sm-start menuText" aria-current="page">
                      <BsFillPersonLinesFill className='text-white' size={25}/><span style={{fontSize:'20px'}} className='ms-2 d-none d-sm-inline menuSpanText'>User Profile</span>
                    </a>
                  </li>
    {/*-------------------- SPONSOR Payment -----------------------------------------------*/}
                  <li class="nav-item text-white my-1 py-2 py-sm-0 ">
                    <a href="/fees" class="nav-link text-white text-center text-sm-start menuText" aria-current="page">
                      <FaPeopleCarry className='text-white' size={25}/><span style={{fontSize:'20px'}} className='ms-2 d-none d-sm-inline menuSpanText'>Sponsor Payment </span>
                    </a>
                  </li>

    {/*-------------------- SETTING-----------------------------------------------*/}
              <li class="nav-item text-white my-1 py-2 py-sm-0 ">
                    <a href="/Setting" class="nav-link text-white text-center text-sm-start menuText" aria-current="page">
                      <AiFillSetting className='text-white' size={25}/><span style={{fontSize:'20px'}} className='ms-2 d-none d-sm-inline menuSpanText'>Setting</span>
                    </a>
                  </li>
                  </ul>
                </div>
                </div>

                <div>
            <hr className='text-white'/>
                <div className='pb-2 ps-4 ' >
                  <a class="dropdown-item" href="/"><h6 className='text-danger' style={{fontSize:'20px',cursor:'pointer'}}><FiLogOut size={30} className='pe-2'/>Logout</h6></a>
                </div>
            </div> 
            </div>
            
        {/* LOGOUT SECTION */}
        {/* <div>
            <hr className='text-white'/>
                <div className='pb-2 ps-4 ' >
                  <a class="dropdown-item" href="#"><h6 className='text-danger ' style={{fontSize:'20px'}}><FiLogOut size={30} className='pe-2'/>Logout</h6></a>
                </div>
            </div> */}
        {/* LOGOUT SECTION */}

          </div>
        </div>
      </div>
    </div>
  )
}

export default Test



// import React, { useState } from 'react';
// import './signin.css';

// const Test = () => {
//   const [isOpen, setIsOpen] = useState(false);

//   const toggleNavbar = () => {
//     setIsOpen(!isOpen);
//   };

//   return (
//     <nav className="navbar">
//       <button className="navbar-toggle" onClick={toggleNavbar}>
//         <span className="navbar-toggle-icon"></span>
//       </button>
//       <ul className={isOpen ? "navbar-menu open" : "navbar-menu"}>
//         <li className="navbar-item">
//           <a className="navbar-link" href="#">Home</a>
//         </li>
//         <li className="navbar-item">
//           <a className="navbar-link" href="#">About</a>
//         </li>
//         <li className="navbar-item">
//           <a className="navbar-link" href="#">Services</a>
//         </li>
//         <li className="navbar-item">
//           <a className="navbar-link" href="#">Contact</a>
//         </li>
//       </ul>
//     </nav>
//   );
// };

// export default Test;
