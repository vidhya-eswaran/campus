import React,{useState} from 'react';
import Select from "react-select";
// import './dashboard.css'
import Sidebar from '../Sidebar';
import Header from '../Header';
import Paper from '@mui/material/Paper'; 
import {GrAddCircle} from 'react-icons/gr';
import {HiBackspace} from 'react-icons/hi';
import Button from 'react-bootstrap/Button';
import Col from 'react-bootstrap/Col';
import Form from 'react-bootstrap/Form';
import Row from 'react-bootstrap/Row'
import {MdDelete} from 'react-icons/md'



const AddSponsoruser = () => {

  const [tableData, setTableData] = useState([]);
  const addRow = () => {
    const newRow = {
      // column1: '',
      // column2: '',
      // column3: ''
    };
    setTableData([...tableData, newRow]);
  };
  
  const deleteRow = (index) => {
    const newData = [...tableData];
    newData.splice(index, 1);
    setTableData(newData);
  };
  
  const handleInputChange = (e)=>{

    function handleSelect(data) {
      setSelectedOptions(data);
    }

  const handleChange = (e, index, column) => {
    const newData = [...tableData];
    newData[index][column] = e.target.value;
    setTableData(newData);
  };
}



      // React state to manage selected options
  const [selectedOptions, setSelectedOptions] = useState();

  // Array of all options
  const optionList = [
    { value: "red", label: "Abu Sufiyan.U" },
    { value: "green", label: "Alfiya.S" },
    { value: "yellow", label: "Shivadheena.R" },
    { value: "blue", label: "Mohammed Fareestha" },
    { value: "white", label: "Rukshana rufar" },
    { value: "ShakinaJula", label: "Shakina Jula" },
    { value: "JulfaHaasi", label: "JulfaHaasi" },
    { value: "ZeenuMakr", label: "ZeenuMakr" },
    { value: "MakeDevie", label: "MakeDevie" },
  ];

  // Function triggered on selection
  function handleSelect(data) {
    setSelectedOptions(data);
  }
  
  return (
    <div>
          <div>
       
       <Sidebar/>
    <div style={{width:'82.5%',float:'right'}} >
      <Header/>
      <div className='p-4' style={{backgroundColor:'aliceblue'}}>

          <Paper elevation={2} className="pb-3">
            <Row>
              <Col xs={11}>
               <h3 className='p-4'><GrAddCircle size={35} className='pe-2 pb-1'/>Add Sponsor Details form</h3>
              </Col>
              <Col xs={1} className='text-center'>
               <a href='/MappingStystem/Sponsormaping'><HiBackspace style={{marginTop:'20px',color:'red'}} size={40}/></a> 
              </Col>
            </Row>
            <div className='pt-3'>
            <Form  className='container'>
              <Row>
              <Col>
                    <Form.Select aria-label="Default select example" >
                      <option >Select-Type</option>
                      <option  value="Sponsor">Sponsor</option>
                     </Form.Select>
                </Col>
                <Col>
                      <Form.Select aria-label="Default select example" >
                        <option >Sponsor Name</option>
                        <option value="1">Mark Antony</option>
                       </Form.Select>
                </Col>
              </Row>

                <Row className='py-3'>
                  <Col>
                    <Form.Select aria-label="Default select example" >
                    <option  className='text-center'>Select-Grade</option>
                            <option value="1">I</option>
                            <option value="1">II</option>
                            <option value="1">III</option>
                            <option value="1">IV</option>
                            <option value="1">V</option>
                            <option value="2">IV</option>
                            <option value="3">IIV</option>
                            <option value="4">IIIV</option>
                            <option value="5">VI</option>
                            <option value="6">X</option>
                            <option value="7">XI</option>
                            <option value="8">XII</option>
                    </Form.Select>
                  </Col>
                  <Col>
                    <Form.Select aria-label="Default select example">
                      <option  className='text-center'>Select-Section</option>
                            <option value="1">A</option>
                            <option value="2">B</option>
                            <option value="3">C</option>

                    </Form.Select>
                  </Col>
                </Row>

              <Row>
              <Col>
                <div styles={{width:'32%'}}  >
                  <Select 
                    options={optionList}
                    placeholder="Select Student"
                    value={selectedOptions}
                    onChange={handleSelect}
                    isSearchable={true}
                    isMulti/>
                </div>
                </Col>
              </Row>
              
              <div  style={{display:'flex',justifyContent:'end',alignContent:'end',paddingTop:'28px'}}>
                <Button  onClick={addRow} className='text-end'>Add Me</Button>
              </div>

             </Form>
            </div>
          </Paper>

          
          {tableData.map((row, index) => (
              <div className='pt-4'>
            <Paper key={index} elevation={2} className="pb-3 ">
              <Row>
                <Col xs={11}>
                <h3 className='p-4'>Extra from</h3>
                </Col>
                <Col xs={1} className='text-center pt-4'>
                   <MdDelete size={29} style={{color:'#E30101',cursor:'pointer'}} onClick={() => deleteRow(index)}/>
                </Col>
              </Row>
              <div className='pt-3'>
              <Form  className='container'>
                <Row>
                <Col>
                      <Form.Select value={row.column1} onChange={(e) => handleInputChange(e, index, 'column1')} aria-label="Default select example" >
                        <option >Select-Type</option>
                        <option  value="Sponsor">Sponsor</option>
                      </Form.Select>
                  </Col>
                  <Col>
                        <Form.Select value={row.column2} onChange={(e) => handleInputChange(e, index, 'column2')} aria-label="Default select example" >
                          <option >Sponsor Name</option>
                          <option value="1">Mark Antony</option>
                        </Form.Select>
                  </Col>
                </Row>


      <Row className='py-3'>
        <Col>
          <Form.Select value={row.column3} onChange={(e) => handleInputChange(e, index, 'column3')} aria-label="Default select example" >
          <option  className='text-center'>Select-Grade</option>
                  <option value="1">I</option>
                  <option value="1">II</option>
                  <option value="1">III</option>
                  <option value="1">IV</option>
                  <option value="1">V</option>
                  <option value="2">IV</option>
                  <option value="3">IIV</option>
                  <option value="4">IIIV</option>
                  <option value="5">VI</option>
                  <option value="6">X</option>
                  <option value="7">XI</option>
                  <option value="8">XII</option>
          </Form.Select>
        </Col>
        <Col>
          <Form.Select value={row.column4} onChange={(e) => handleInputChange(e, index, 'column4')} aria-label="Default select example">
            <option  className='text-center'>Select-Section</option>
                  <option value="1">A</option>
                  <option value="2">B</option>
                  <option value="3">C</option>

          </Form.Select>
        </Col>
      </Row>
      <Row>
      <Col>
        <div styles={{width:'32%'}}  >
          <Select value={row.column5} onChange={(e) => handleInputChange(e, index, 'column5')}
            options={optionList}
            placeholder="Select Student"
            // value={selectedOptions}
            // onChange={handleSelect}
            isSearchable={true}
            isMulti/>
        </div>
        </Col>
      </Row>


        <div  style={{display:'flex',justifyContent:'end',alignContent:'end',paddingTop:'28px'}}>
          <Button  onClick={addRow} className='text-end'>Add Me</Button>
        </div>
              </Form>
              </div>
            </Paper>
             </div>
            ))}
          
          <div className='pt-5'>
            <Button className='bg-success' type="submit">Submit form</Button>
          </div>
          </div>
    </div>
    </div>
    </div>
  )
}

export default AddSponsoruser


