import React from 'react';
import './signin.css';
import { useNavigate } from "react-router-dom";
import backgroundImg from '../Assets/backgroundImg.png';
import {BiError} from 'react-icons/bi';
import Form from 'react-bootstrap/Form';
import Logo from '../Assets/logo.jpeg';
import TextField from '@mui/material/TextField';
import Box from '@mui/material/Box';
import IconButton from '@mui/material/IconButton';
import OutlinedInput from '@mui/material/OutlinedInput';
import InputLabel from '@mui/material/InputLabel';
import InputAdornment from '@mui/material/InputAdornment';
import FormControl from '@mui/material/FormControl';
import Visibility from '@mui/icons-material/Visibility';
import VisibilityOff from '@mui/icons-material/VisibilityOff';
import {useForm} from 'react-hook-form';
import axios from 'axios';
 
// import React, { useState } from 'react';


//export default function LoginForm() {

const Login = () => {
  // const [token, setToken] = useState('');

  const navigate = useNavigate();

  const {register, handleSubmit, formState:{errors}} = useForm('')


    const [showPassword, setShowPassword] = React.useState(false);

    const handleClickShowPassword = () => setShowPassword((show) => !show);
    
    const handleMouseDownPassword = (event) => {
      event.preventDefault();
    };
    
  

  
//     http://127.0.0.1:8000/api/login
//  input names : email , password
    const onSubmit = async (data) => {
      try{
        const payload = {
          email: data.emailSvs,
          password:data.passSvs
        };
        const response = await axios.post('http://127.0.0.1:8000/api/login', payload);
        // console.log(data);
      
      // Store the response data in session storage
      sessionStorage.setItem('user_id', response.data.id);
      sessionStorage.setItem('email', response.data.email);
      sessionStorage.setItem('name', response.data.name);
      sessionStorage.setItem('user_type', response.data.user_type);
      sessionStorage.setItem('accessToken', response.data.token.accessToken);
       sessionStorage.setItem('token_id', response.data.token.token.id);

      const userId = sessionStorage.getItem('token_id');
      console.log( userId);

         console.log( response.data.token);

         if (response.data.user_type === 'admin') {
 
        window.location.href = '/dashboard';
        } else {
          // Redirect to another page for non-admin users, or do something else
        }
      } catch (error) {
        console.log(error);
      }
    };
  

console.log(errors)

  return (
    <div>
      <div style={{ backgroundImage: `url(${backgroundImg})`, backgroundSize: 'cover', height: '100vh',backgroundRepeat:'no-repeat' }}>
           <div className='container pt-5'>
           <div className='row'>
            <div className='col-md-6 offset-1'>
                <h1 className='welcomeTxt'>Welcome</h1>
            </div>
            <div className='col-md-5'>
                <div className='py-4' style={{border:'1px solid white',background:'#fff',borderRadius:'10px'}}>
            {/* <Form method='post' onSubmit={handleSubmit((data)=>{
               navigate('/dashboard');
            })}> */}
                <Form method='post' onSubmit={handleSubmit(onSubmit)}> 
              
              <div className='logo-img' style={{textAlign:'center'}}>  
                <img src={Logo} alt="logo" style={{width:'35%'}} />
               </div>
            <Form.Group className="mb-3" style={{padding:'0 50px'}} controlId="formBasicEmail">
            <Box
                sx={{
                    width: 500,
                    maxWidth: '100%',
                }}>
              <TextField {...register('emailSvs', {required:'Email is required'})} type={'email'} fullWidth id="outlined-basic" label="Email" variant="outlined" />
              {errors?.emailSvs && <p style={{fontSize: '17px'}} className='text-danger'><BiError className='error-icon'/>{errors.emailSvs.message}</p>}
            </Box>
            </Form.Group>
{/*------------------------------------------- Password --------------------------------------------*/}
                <Form.Group style={{padding:'0px 98px 0px 0px'}} className="mb-3" controlId="formBasicPassword">
                <FormControl style={{width:'100%'}} variant="outlined" className='mb-3 mx-5 w-100'>
              <InputLabel htmlFor="outlined-adornment-password">Password</InputLabel>
              <OutlinedInput fullWidth {...register('passSvs', 
              {required:'Password is required'})}
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
            label="Password"/>
            {errors?.passSvs && <p style={{fontSize: '17px'}} className='text-danger'><BiError className='error-icon'/>{errors.passSvs.message}</p>}
            {/* <p className='text-danger'>{errors.password?.message}</p> */}
            </FormControl> 
                </Form.Group>

{/*------------------------------------------- Password --------------------------------------------*/}
                <div className='d-flex container'>
                  {/* <Form.Group style={{padding:'0 50px'}}  className="mb-3 d-flex" controlId="formBasicCheckbox">
                      <Form.Check type="checkbox" label="Remember Me" />
                  </Form.Group> */}

                  <a href="#" className='forgetPass'>Forget Password?</a>

                </div>

                
                <div className='logo-button pt-3' style={{textAlign:'center'}}> 
                 <button type='submit' style={{width:'80%'}} className="button-5" role="button"><h5>Sign In</h5></button>
                </div>
    </Form>
    </div>
    
            </div>
           </div>

           </div>
       </div>
    </div>
  )
}

export default Login
