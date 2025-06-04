import React from 'react';
import './dashboard.css';
import Container from 'react-bootstrap/Container';
import Navbar from 'react-bootstrap/Navbar';
import Badge from '@mui/material/Badge';
import NotificationsIcon from '@mui/icons-material/Notifications';
import Avatar from '@mui/material/Avatar';
import Stack from '@mui/material/Stack';
import { deepPurple } from '@mui/material/colors';
import {BsSearch} from 'react-icons/bs';
import { NavLink } from "react-router-dom";


const Header = () => {
  return (
    <div className='headerNav'>
        <Navbar  style={{backgroundColor:'#CDCDCD'}}>
      <Container style={{width: '100%', float: "left", display: 'flex'}}>
         <div class="box">
          <BsSearch size={20}/>
           <input type="text" name="" placeholder='Search Here.....'/>
         </div>
        <Navbar.Toggle />
        <Navbar.Collapse className="justify-content-end">
        <Stack direction="row" spacing={4}>
          <div style={{paddingTop:'10px'}}>
          <Badge badgeContent={4} color="error">
          <NotificationsIcon style={{color:'#010001',cursor:'pointer'}}  />
        </Badge>
          </div>
        <div className='pe-5'>
         <NavLink to="/Setting" style={{textDecoration:'none'}}> 
          <Avatar sx={{ bgcolor: deepPurple[500] }} alt="Sindy Baker" src="/static/images/avatar/3.jpg"/>
        </NavLink>  
        </div>
       </Stack>
        </Navbar.Collapse>
      </Container>
       </Navbar>
    </div>
  )
}

export default Header
