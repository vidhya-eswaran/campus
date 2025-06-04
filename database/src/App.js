import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.min.js';
//Bootstrap and jQuery libraries
import 'bootstrap/dist/css/bootstrap.min.css';
import 'jquery/dist/jquery.min.js';
//Datatable Modules
import "datatables.net-dt/js/dataTables.dataTables"
import "datatables.net-dt/css/jquery.dataTables.min.css";
import "datatables.net-editor/js/dataTables.editor";
import {BrowserRouter as Router,Routes,Route} from 'react-router-dom';
import Login from './Access/Login';
import Dashboard from './Admindashboard/Dashboard';
import Reminders from './Admindashboard/Reminders';
import Test from './Admindashboard/Test';
import User from './Admindashboard/MangerUser/User';
import EditUser from './Admindashboard/MangerUser/EditUser';
import StudentUser from './Admindashboard/MangerUser/StudentUser';
import Mfees from './Admindashboard/Masters/Mfees';
import Setting from './Admindashboard/Setting';
import Profile from './Admindashboard/StudentProfile/Profile';
import Viewprofile from './Admindashboard/MangerUser/Viewprofile';
import Bulkupload from './Admindashboard/MangerUser/Bulkupload';
import AddSponsoruser from './Admindashboard/MappingStystem/AddSponsoruser';
import Sponsormaping from './Admindashboard/MappingStystem/Sponsormaping';
import Feesmaping from './Admindashboard/MappingStystem/Feesmaping';
import Role from './Admindashboard/MangerUser/Role';
import Msponsor from './Admindashboard/Masters/Msponsor';
import AddSponsorlist from './Admindashboard/Masters/AddSponsorlist';
import AddStudent from './Admindashboard/Masters/AddStudent';
import AddStudentlist from './Admindashboard/Masters/AddStudentlist';
import Invoice from './Admindashboard/Invoice';
import Viewstudentdata from './Admindashboard/Masters/Viewstudentdata';
import GroupMaster from './Admindashboard/Masters/GroupMaster';
import ClassMaster from './Admindashboard/Masters/ClassMaster';
import SectionMaster from './Admindashboard/Masters/SectionMaster';
import ModeofpaymentMaster from './Admindashboard/Masters/ModeofpaymentMaster';

function App() {
  return (
    <div>
      <Router>
        <Routes>
            <Route path='/' element={<Login/>} />
            <Route  path='/Dashboard' element={<Dashboard/>} />
            <Route path='/reminders' element={<Reminders/>} />
            <Route path='/MangerUser/User' element={<User/>} />
            <Route path='/MangerUser/StudentUser' element={<StudentUser/>} />
            <Route path='/StudentProfile/Profile' element={<Profile/>} />
            <Route path='/MangerUser/Bulkupload' element={<Bulkupload/>} />
            <Route path='/Masters/Mfees' element={<Mfees/>} />
            <Route path='/Setting' element={<Setting/>} />
            <Route path='/MangerUser/EditUser' element={<EditUser/>} />
            <Route path='/MangerUser/Viewprofile' element={<Viewprofile/>} />
            <Route path='/MappingStystem/AddSponsoruser' element={<AddSponsoruser/>} />
            <Route path='/MappingStystem/Sponsormaping' element={<Sponsormaping/>} />
            <Route path='/MappingStystem/Feesmaping' element={<Feesmaping/>} />
            <Route path='/MangerUser/Role' element={<Role/>} />
            <Route path='/Masters/Msponsor' element={<Msponsor/>} />
            <Route path='/Masters/AddSponsorlist' element={<AddSponsorlist/>} />
            <Route path='/Masters/Viewstudentdata' element={<Viewstudentdata/>} />
            <Route path='/Masters/AddStudent' element={<AddStudent/>} />
            <Route path='/Masters/AddStudentlist' element={<AddStudentlist/>} />
            <Route path='/Masters/GroupMaster' element={<GroupMaster/>} />
            <Route path='/Masters/ClassMaster' element={<ClassMaster/>} />
            <Route path='/Masters/SectionMaster' element={<SectionMaster/>} />
            <Route path='/Masters/ModeofpaymentMaster' element={<ModeofpaymentMaster/>} />
            <Route path='/Test' element={<Test/>} />
            <Route path='/Invoice' element={<Invoice/>} />
      </Routes>
     </Router>

    </div>
  );
}

export default App;
