import React,{useState} from 'react';
import $ from 'jquery'; 
import {FaRegEye} from 'react-icons/fa'
import {FiEye} from 'react-icons/fi'
import Swal from 'sweetalert2';


class DatatableSprofile extends React.Component {


  componentDidMount() {
    //initialize datatable
    $(document).ready(function () {
        $('#example').DataTable();
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
    
  return (
    <div className="MainDiv">

      
      <div className="container">
          
          <table id="example" class="display">
            <thead>
                <tr>
                    <th>Admission No</th>
                    <th>Year</th>
                    <th>Reg.No</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Standard</th>
                    <th>Section</th>  
                    <th>Class Teacher</th>  
                    <th className='text-center'> Action</th>  
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>569283</td>
                    <td>2018</td>
                    <td>12201</td>
                    <td>Daniel Grant</td>
                    <td>Male</td>
                    <td>12</td>
                    <td>B</td>
                    <td>Abu sufiyan</td>
                    <td className='text-center'>
                    <a href='/MangerUser/Viewprofile'><FaRegEye style={{color:'#4E0172'}} size={30}/></a>
                    </td>
                </tr>
                <tr>
                    <td>569283</td>
                    <td>2018</td>
                    <td>12202</td>
                    <td>Jon Deo</td>
                    <td>Male</td>
                    <td>12</td>
                    <td>B</td>
                    <td>Abu sufiyan</td>
                    <td className='text-center'>
                    <FaRegEye style={{color:'#4E0172'}} size={30}/>
                    </td>
                </tr>
                <tr>
                    <td>51328</td>
                    <td>2019</td>
                    <td>12203</td>
                    <td>Alafiya</td>
                    <td>Female</td>
                    <td>12</td>
                    <td>B</td>
                    <td>Abu sufiyan</td>
                    <td className='text-center'>
                         <FaRegEye style={{color:'#4E0172'}} size={30}/>
                    </td>
                </tr>
                <tr>
                    <td>51328</td>
                    <td>2019</td>
                    <td>12204</td>
                    <td>Shive Dhenna</td>
                    <td>Male</td>
                    <td>12</td>
                    <td>B</td>
                    <td>Abu sufiyan</td>
                    <td className='text-center'>
                         <FaRegEye style={{color:'#4E0172'}} size={30}/>
                    </td>
                </tr>
                <tr>
                    <td>51328</td>
                    <td>2019</td>
                    <td>12205</td>
                    <td>Anis Karthi</td>
                    <td>Male</td>
                    <td>12</td>
                    <td>B</td>
                    <td>Abu sufiyan</td>
                    <td className='text-center'>
                         <FaRegEye style={{color:'#4E0172'}} size={30}/>
                    </td>
                </tr>
                <tr>
                    <td>51328</td>
                    <td>2019</td>
                    <td>12206</td>
                    <td>Kamadi</td>
                    <td>Female</td>
                    <td>12</td>
                    <td>B</td>
                    <td>Abu sufiyan</td>
                    <td className='text-center'>
                         <FaRegEye style={{color:'#4E0172'}} size={30}/>
                    </td>
                </tr>
                <tr>
                    <td>51328</td>
                    <td>2019</td>
                    <td>12207</td>
                    <td>Kamal Kanna</td>
                    <td>Male</td>
                    <td>12</td>
                    <td>B</td>
                    <td>Abu sufiyan</td>
                    <td className='text-center'>
                         <FaRegEye style={{color:'#4E0172'}} size={30}/>
                    </td>
                </tr>
                <tr>
                    <td>51328</td>
                    <td>2019</td>
                    <td>12208</td>
                    <td>Vasnavi Jha</td>
                    <td>Female</td>
                    <td>12</td>
                    <td>B</td>
                    <td>Abu sufiyan</td>
                    <td className='text-center'>
                         <FaRegEye style={{color:'#4E0172'}} size={30}/>
                    </td>
                </tr>
                <tr>
                    <td>51328</td>
                    <td>2019</td>
                    <td>12209</td>
                    <td>Abu thalip</td>
                    <td>Male</td>
                    <td>12</td>
                    <td>B</td>
                    <td>Abu sufiyan</td>
                    <td className='text-center'>
                         <FaRegEye style={{color:'#4E0172'}} size={30}/>
                    </td>
                </tr>
                <tr>
                    <td>51328</td>
                    <td>2019</td>
                    <td>12210</td>
                    <td>Kaathika</td>
                    <td>Female</td>
                    <td>12</td>
                    <td>B</td>
                    <td>Abu sufiyan</td>
                    <td className='text-center'>
                         <FaRegEye style={{color:'#4E0172'}} size={30}/>
                    </td>
                </tr>
          {/*---------- Part-2----------- */}
                <tr>
                    <td>51328</td>
                    <td>2019</td>
                    <td>11311</td>
                    <td>Jabari Jhon</td>
                    <td>Male</td>
                    <td>11</td>
                    <td>A</td>
                    <td>Karthi velu</td>
                    <td className='text-center'>
                         <FaRegEye style={{color:'#4E0172'}} size={30}/>
                    </td>
                </tr>
                <tr>
                    <td>51328</td>
                    <td>2019</td>
                    <td>11312</td>
                    <td>Zaanu Banu</td>
                    <td>Female</td>
                    <td>11</td>
                    <td>A</td>
                    <td>Karthi velu</td>
                    <td className='text-center'>
                         <FaRegEye style={{color:'#4E0172'}} size={30}/>
                    </td>
                </tr>
                <tr>
                    <td>51328</td>
                    <td>2019</td>
                    <td>11313</td>
                    <td>Gebirial</td>
                    <td>Male</td>
                    <td>11</td>
                    <td>A</td>
                    <td>Karthi velu</td>
                    <td className='text-center'>
                         <FaRegEye style={{color:'#4E0172'}} size={30}/>
                    </td>
                </tr>
                <tr>
                    <td>51328</td>
                    <td>2019</td>
                    <td>11314</td>
                    <td>Ahammed kaja</td>
                    <td>Male</td>
                    <td>11</td>
                    <td>A</td>
                    <td>Karthi velu</td>
                    <td className='text-center'>
                         <FaRegEye style={{color:'#4E0172'}} size={30}/>
                    </td>
                </tr>
                <tr>
                    <td>51328</td>
                    <td>2019</td>
                    <td>11315</td>
                    <td>Shivisha</td>
                    <td>Female</td>
                    <td>11</td>
                    <td>A</td>
                    <td>Karthi velu</td>
                    <td className='text-center'>
                         <FaRegEye style={{color:'#4E0172'}} size={30}/>
                    </td>
                </tr>
                <tr>
                    <td>51328</td>
                    <td>2019</td>
                    <td>11316</td>
                    <td>Usman Khan</td>
                    <td>Male</td>
                    <td>11</td>
                    <td>A</td>
                    <td>Karthi velu</td>
                    <td className='text-center'>
                         <FaRegEye style={{color:'#4E0172'}} size={30}/>
                    </td>
                </tr>
                <tr>
                    <td>51328</td>
                    <td>2019</td>
                    <td>11317</td>
                    <td>Bharathi kumar</td>
                    <td>Male</td>
                    <td>11</td>
                    <td>A</td>
                    <td>Karthi velu</td>
                    <td className='text-center'>
                         <FaRegEye style={{color:'#4E0172'}} size={30}/>
                    </td>
                </tr>
                <tr>
                    <td>51328</td>
                    <td>2019</td>
                    <td>11318</td>
                    <td>Yash Shing</td>
                    <td>Male</td>
                    <td>11</td>
                    <td>A</td>
                    <td>Karthi velu</td>
                    <td className='text-center'>
                         <FaRegEye style={{color:'#4E0172'}} size={30}/>
                    </td>
                </tr>
                <tr>
                    <td>51328</td>
                    <td>2019</td>
                    <td>11319</td>
                    <td>pondian</td>
                    <td>Male</td>
                    <td>11</td>
                    <td>A</td>
                    <td>Karthi velu</td>
                    <td className='text-center'>
                         <FaRegEye style={{color:'#4E0172',textAlign:'center'}} size={30}/>
                    </td>
                </tr>
           
            </tbody>
        </table>
          
        </div>
      </div>
  );
}
}


export default DatatableSprofile
