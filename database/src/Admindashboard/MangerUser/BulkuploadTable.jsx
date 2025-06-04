import React, { Component } from "react";
import { Table, Button, Popconfirm, Row, Col, Upload } from "antd";
import { ExcelRenderer } from "react-excel-renderer";
import { EditableFormRow, EditableCell } from '../StudentProfile/editable';
import {AiFillDelete} from 'react-icons/ai';
import {MdUpload} from 'react-icons/md';
import Sample_Excel from '../MangerUser/SVS-Sample-profile.xlsx';

export default class BulkuploadTable extends Component {
  constructor(props) {
    super(props);
    this.state = {
      cols: [],
      rows: [],
      errorMessage: null,
      columns: [
        // {
        //   title: "NAME",
        //   dataIndex: "name",
        //   editable: true
        // },
        // {
        //   title: "AGE",
        //   dataIndex: "age",
        //   editable: true
        // },
        // {
        //   title: "GENDER",
        //   dataIndex: "gender",
        //   editable: true
        // },
        // {
        //   title: "Action",
        //   dataIndex: "action",
        //   render: (text, record) =>
        //     this.state.rows.length >= 1 ? (
        //       <Popconfirm
        //         title="Sure to delete?"
        //         onConfirm={() => this.handleDelete(record.key)}
        //       >
        //         <AiFillDelete size={20} style={{color:'red'}}  />
        //         {/* <Icon
        //           type="delete"
        //           theme="filled"
        //           style={{ color: "red", fontSize: "20px" }}
        //         /> */}
        //       </Popconfirm>
        //     ) : null
        // }
      ]
    };
  }

  handleSave = row => {
    const newData = [...this.state.rows];
    const index = newData.findIndex(item => row.key === item.key);
    const item = newData[index];
    newData.splice(index, 1, {
      ...item,
      ...row
    });
    this.setState({ rows: newData });
  };

  checkFile(file) {
    let errorMessage = "";
    if (!file || !file[0]) {
      return;
    }
    const isExcel =
      file[0].type === "application/vnd.ms-excel" ||
      file[0].type ===
        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
    if (!isExcel) {
      errorMessage = "You can only upload Excel file!";
    }
    console.log("file", file[0].type);
    const isLt2M = file[0].size / 1024 / 1024 < 2;
    if (!isLt2M) {
      errorMessage = "File must be smaller than 2MB!";
    }
    console.log("errorMessage", errorMessage);
    return errorMessage;
  }

  fileHandler = fileList => {
    console.log("fileList", fileList);
    let fileObj = fileList;
    if (!fileObj) {
      this.setState({
        errorMessage: "No file uploaded!"
      });
      return false;
    }
    console.log("fileObj.type:", fileObj.type);
    if (
      !(
        fileObj.type === "application/vnd.ms-excel" ||
        fileObj.type ===
          "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
      )
    ) {
      this.setState({
        errorMessage: "Unknown file format. Only Excel files are uploaded!"
      });
      return false;
    }
    //just pass the fileObj as parameter
    ExcelRenderer(fileObj, (err, resp) => {
      if (err) {
        console.log(err);
      } else {
        let newRows = [];
        resp.rows.slice(1).map((row, index) => {
          if (row && row !== "undefined") {
            newRows.push({
              key: index,
              name: row[0],
              age: row[1],
              gender: row[2]
            });
          }
        });
        if (newRows.length === 0) {
          this.setState({
            errorMessage: "No data found in file!"
          });
          return false;
        } else {
          this.setState({
            cols: resp.cols,
            rows: newRows,
            errorMessage: null
          });
        }
      }
    });
    return false;
  };

  handleSubmit = async () => {
    console.log("submitting: ", this.state.rows);
    //submit to API
    //if successful, banigate and clear the data
    this.setState({ rows: [] })
  };

  handleDelete = key => {
    const rows = [...this.state.rows];
    this.setState({ rows: rows.filter(item => item.key !== key) });
  };
  // handleAdd = () => {
  //   const { count, rows } = this.state;
  //   const newData = {
  //     key: count,
  //     name: "Student's name",
  //     age: "Enter Student Age",
  //     gender: "Enter Gender"
  //   };
  //   this.setState({
  //     rows: [newData, ...rows],
  //     count: count + 1
  //   });
  // };

  render() {
    const components = {
      body: {
        row: EditableFormRow,
        cell: EditableCell
      }
    };
    const columns = this.state.columns.map(col => {
      if (!col.editable) {
        return col;
      }
      return {
        ...col,
        onCell: record => ({
          record,
          editable: col.editable,
          dataIndex: col.dataIndex,
          title: col.title,
          handleSave: this.handleSave
        })
      };
    });
    return (
      <>
        <Row gutter={16}>
          <Col xs={6}
            span={8}
            style={{
              display: "flex",
              justifyContent: "space-between",
              alignItems: "center",
              marginBottom: "5%"
            }} >
            <div style={{ display: "flex", alignItems: "center" }}>
              <div className="page-title"><h5>Upload Student Data</h5></div>
            </div>
          </Col>
          <Col span={8}>
            <a href={Sample_Excel} target="_blank" rel="noopener noreferrer" download>
              <Button className="bg-success text-light">Download Sample excel sheet</Button>{' '}
            </a>
          </Col>
        </Row>
        <div>
          <Row>
            <Col>
            <Upload
              name="file"
              beforeUpload={this.fileHandler}
              onRemove={() => this.setState({ rows: [] })}
              multiple={false} >
              <Button>
                {/* <Icon type="upload" />  */}
              <MdUpload size={25} className="pe-1" />  Click to Upload Excel File
              </Button>
            </Upload>
            </Col>


            <Col 
            span={8}
            align="right"
            style={{ display: "flex", justifyContent: "space-between",textAlign:'end' }}>
            {this.state.rows.length > 0 && (
              <>
                <div className="text-end">
                <Button className="button-61"
                  onClick={this.handleSubmit}
                  size="large"
                  type="primary"
                  style={{ marginBottom: 16, marginLeft: 10,height:'50%' }}> Submit Data </Button>
                </div>
              </>
            )}
          </Col>
        
        </Row>
        </div>
        <div style={{ marginTop: 20 }}>
          {/* <Table
            components={components}
            rowClassName={() => "editable-row"}
            dataSource={this.state.rows}
            columns={columns}
          /> */}
        </div>
      </>
    );
  }
}

 
