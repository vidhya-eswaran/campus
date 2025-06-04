// import React, { useState } from "react";

// function RowForm() {
//   // define the input fields for each row form
//   return (
//     <div>
//       <input type="text" placeholder="Name" />
//       <input type="text" placeholder="Age" />
//       <input type="text" placeholder="Email" />
//     </div>
//   );
// }

// function DynamicTable() {
//   const [rows, setRows] = useState([]);

//   const handleAddRow = () => {
//     // add a new object to the rows array with default values
//     setRows([...rows, { name: "", age: "", email: "" }]);
//   };

//   return (
//     <div>
//       {/* render existing rows */}
//       {rows.map((row, index) => (
//         <RowForm key={index} />
//       ))}

//       {/* add row button */}
//       <button onClick={handleAddRow}>Add Row</button>
//     </div>
//   );
// }
// export default DynamicTable

import { useState } from "react";





const Test = () => {
  const [tableData, setTableData] = useState([]);
  const addRow = () => {
    const newRow = {
      column1: '',
      column2: '',
      column3: ''
    };
    setTableData([...tableData, newRow]);
  };
  
  const deleteRow = (index) => {
    const newData = [...tableData];
    newData.splice(index, 1);
    setTableData(newData);
  };
  
  const handleChange = (e, index, column) => {
    const newData = [...tableData];
    newData[index][column] = e.target.value;
    setTableData(newData);
  };
  return (
    <div><button onClick={addRow}>Add Row</button>
      <table>
      
  <thead>
    <tr>
      <th>Column 1</th>
      <th>Column 2</th>
      <th>Column 3</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    {tableData.map((row, index) => (
      <tr key={index}>
        <td><input value={row.column1} onChange={(e) => handleChange(e, index, 'column1')} /></td>
        <td><input value={row.column2} onChange={(e) => handleChange(e, index, 'column2')} /></td>
        <td><input value={row.column3} onChange={(e) => handleChange(e, index, 'column3')} /></td>
        <td><button onClick={() => deleteRow(index)}>Delete</button></td>
      </tr>
    ))}
  </tbody>
</table>

    </div>
  )
}

export default Test
