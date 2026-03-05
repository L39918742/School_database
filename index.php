<?php
include("db_connect.php");
?>

<!DOCTYPE html>
<html>
<head>
    <title>School Management System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="header">
    <h1>St Alphonsus Primary School</h1>
    <p>School Management Portal</p>
</div>

<?php if(isset($_GET['msg'])) { ?>
<div class="success">
    <?= $_GET['msg']; ?>
</div>
<?php } ?>

<div class="dashboard">

    <div class="card">
        <h2>Teachers</h2>
        <a href="#" onclick="openModal('addTeacherModal'); return false;">Add Teacher</a>
        <a href="#" onclick="openModal('viewTeacherModal'); return false;">View Teachers</a>
    </div>

    <div class="card">
        <h2>Classes</h2>
        <a href="#" onclick="openModal('addClassModal'); return false;">Add Class</a>
        <a href="#" onclick="openModal('viewClassModal'); return false;">View Classes</a>
    </div>

    <div class="card">
        <h2>Pupils</h2>
        <a href="#" onclick="openModal('addPupilModal'); return false;">Add Pupil</a>
        <a href="#" onclick="openModal('viewPupilModal'); return false;">View Pupils</a>
    </div>

    <div class="card">
        <h2>Parents</h2>
        <a href="#" onclick="openModal('addParentModal'); return false;">Add Parent</a>
        <a href="#" onclick="openModal('viewParentModal'); return false;">View Parents</a>
        <a href="#" onclick="openModal('assignModal'); return false;">Assign Parent</a>
    </div>

</div>

<!-- TEACHER MODALS -->

<div id="addTeacherModal" class="modal">
<div class="modal-content">
<span class="close" onclick="closeModal('addTeacherModal')">&times;</span>
<h3>Add Teacher</h3>
<form method="POST" action="actions.php">
<input type="hidden" name="entity" value="teacher">
<input type="hidden" name="action" value="add">
<input type="text" name="first_name" placeholder="First Name" required>
<input type="text" name="last_name" placeholder="Last Name" required>
<input type="number" name="salary" placeholder="Salary">
<button type="submit">Save</button>
</form>
</div>
</div>

<div id="viewTeacherModal" class="modal">
<div class="modal-content">
<span class="close" onclick="closeModal('viewTeacherModal')">&times;</span>
<h3>All Teachers</h3>

<input type="text" onkeyup="searchTable(this,'teacherTable')" placeholder="Search...">

<?php $teachers = $conn->query("SELECT * FROM teacher"); ?>
<table id="teacherTable">
<tr><th>ID</th><th>Name</th><th>Salary</th><th>Action</th></tr>
<?php while($t = $teachers->fetch_assoc()) { ?>
<tr>
<td><?= $t['teacher_id'] ?></td>
<td><?= $t['first_name']." ".$t['last_name'] ?></td>
<td><?= $t['annual_salary'] ?></td>
<td>

<form method="POST" action="actions.php" style="display:inline;">
<input type="hidden" name="entity" value="teacher">
<input type="hidden" name="action" value="delete">
<input type="hidden" name="id" value="<?= $t['teacher_id'] ?>">
<button onclick="return confirmDelete()">Delete</button>
</form>

<button onclick="editTeacher('<?= $t['teacher_id'] ?>','<?= $t['first_name'] ?>','<?= $t['last_name'] ?>','<?= $t['annual_salary'] ?>')">Edit</button>

</td>
</tr>
<?php } ?>
</table>
</div>
</div>

<div id="editTeacherModal" class="modal">
<div class="modal-content">
<span class="close" onclick="closeModal('editTeacherModal')">&times;</span>
<h3>Edit Teacher</h3>
<form method="POST" action="actions.php">
<input type="hidden" name="entity" value="teacher">
<input type="hidden" name="action" value="edit">
<input type="hidden" id="edit_teacher_id" name="id">
<input type="text" id="edit_teacher_first" name="first_name" required>
<input type="text" id="edit_teacher_last" name="last_name" required>
<input type="number" id="edit_teacher_salary" name="salary">
<button type="submit">Update</button>
</form>
</div>
</div>

<!-- CLASS MODALS -->

<div id="addClassModal" class="modal">
<div class="modal-content">
<span class="close" onclick="closeModal('addClassModal')">&times;</span>
<h3>Add Class</h3>
<form method="POST" action="actions.php">
<input type="hidden" name="entity" value="class">
<input type="hidden" name="action" value="add">
<input type="text" name="class_name" required>
<input type="number" name="capacity" required>
<select name="teacher_id" required>
<option value="">Select Teacher</option>
<?php $tlist=$conn->query("SELECT * FROM teacher");
while($t=$tlist->fetch_assoc()){
echo "<option value='{$t['teacher_id']}'>{$t['first_name']}</option>";
} ?>
</select>
<button type="submit">Save</button>
</form>
</div>
</div>

<div id="viewClassModal" class="modal">
<div class="modal-content">
<span class="close" onclick="closeModal('viewClassModal')">&times;</span>
<h3>All Classes</h3>

<input type="text" onkeyup="searchTable(this,'classTable')" placeholder="Search...">

<?php
$classes=$conn->query("SELECT class.*,teacher.first_name FROM class LEFT JOIN teacher ON class.teacher_id=teacher.teacher_id");
?>
<table id="classTable">
<tr><th>ID</th><th>Class</th><th>Capacity</th><th>Teacher</th><th>Action</th></tr>
<?php while($c=$classes->fetch_assoc()){ ?>
<tr>
<td><?= $c['class_id'] ?></td>
<td><?= $c['class_name'] ?></td>
<td><?= $c['capacity'] ?></td>
<td><?= $c['first_name'] ?></td>
<td>
<form method="POST" action="actions.php" style="display:inline;">
<input type="hidden" name="entity" value="class">
<input type="hidden" name="action" value="delete">
<input type="hidden" name="id" value="<?= $c['class_id'] ?>">
<button onclick="return confirmDelete()">Delete</button>
</form>
<button onclick="editClass('<?= $c['class_id'] ?>','<?= $c['class_name'] ?>','<?= $c['capacity'] ?>','<?= $c['teacher_id'] ?>')">Edit</button>
</td>
</tr>
<?php } ?>
</table>
</div>
</div>

<div id="editClassModal" class="modal">
<div class="modal-content">
<span class="close" onclick="closeModal('editClassModal')">&times;</span>
<h3>Edit Class</h3>
<form method="POST" action="actions.php">
<input type="hidden" name="entity" value="class">
<input type="hidden" name="action" value="edit">
<input type="hidden" id="edit_class_id" name="id">
<input type="text" id="edit_class_name" name="class_name" required>
<input type="number" id="edit_class_capacity" name="capacity" required>
<select id="edit_class_teacher" name="teacher_id" required>
<?php
$tlist=$conn->query("SELECT * FROM teacher");
while($t=$tlist->fetch_assoc()){
echo "<option value='{$t['teacher_id']}'>{$t['first_name']}</option>";
} ?>
</select>
<button type="submit">Update</button>
</form>
</div>
</div>

<!-- PUPIL MODALS -->

<div id="addPupilModal" class="modal">
<div class="modal-content">
<span class="close" onclick="closeModal('addPupilModal')">&times;</span>
<h3>Add Pupil</h3>
<form method="POST" action="actions.php">
<input type="hidden" name="entity" value="pupil">
<input type="hidden" name="action" value="add">
<input type="text" name="first_name" required>
<input type="text" name="last_name" required>
<input type="date" name="dob" required>
<select name="class_id" required>
<?php
$clist=$conn->query("SELECT * FROM class");
while($c=$clist->fetch_assoc()){
echo "<option value='{$c['class_id']}'>{$c['class_name']}</option>";
} ?>
</select>
<button type="submit">Save</button>
</form>
</div>
</div>

<div id="viewPupilModal" class="modal">
<div class="modal-content">
<span class="close" onclick="closeModal('viewPupilModal')">&times;</span>
<h3>All Pupils</h3>

<input type="text" onkeyup="searchTable(this,'pupilTable')" placeholder="Search...">

<?php
$pupils=$conn->query("SELECT pupil.*,class.class_name FROM pupil JOIN class ON pupil.class_id=class.class_id");
?>
<table id="pupilTable">
<tr><th>ID</th><th>Name</th><th>DOB</th><th>Class</th><th>Action</th></tr>
<?php while($p=$pupils->fetch_assoc()){ ?>
<tr>
<td><?= $p['pupil_id'] ?></td>
<td><?= $p['first_name']." ".$p['last_name'] ?></td>
<td><?= $p['date_of_birth'] ?></td>
<td><?= $p['class_name'] ?></td>
<td>
<form method="POST" action="actions.php" style="display:inline;">
<input type="hidden" name="entity" value="pupil">
<input type="hidden" name="action" value="delete">
<input type="hidden" name="id" value="<?= $p['pupil_id'] ?>">
<button onclick="return confirmDelete()">Delete</button>
</form>
<button onclick="editPupil('<?= $p['pupil_id'] ?>','<?= $p['first_name'] ?>','<?= $p['last_name'] ?>','<?= $p['date_of_birth'] ?>','<?= $p['class_id'] ?>')">Edit</button>
</td>
</tr>
<?php } ?>
</table>
</div>
</div>

<div id="editPupilModal" class="modal">
<div class="modal-content">
<span class="close" onclick="closeModal('editPupilModal')">&times;</span>
<h3>Edit Pupil</h3>
<form method="POST" action="actions.php">
<input type="hidden" name="entity" value="pupil">
<input type="hidden" name="action" value="edit">
<input type="hidden" id="edit_pupil_id" name="id">
<input type="text" id="edit_pupil_first" name="first_name" required>
<input type="text" id="edit_pupil_last" name="last_name" required>
<input type="date" id="edit_pupil_dob" name="dob" required>
<select id="edit_pupil_class" name="class_id" required>
<?php
$clist=$conn->query("SELECT * FROM class");
while($c=$clist->fetch_assoc()){
echo "<option value='{$c['class_id']}'>{$c['class_name']}</option>";
} ?>
</select>
<button type="submit">Update</button>
</form>
</div>
</div>

<!-- PARENT MODALS -->

<div id="addParentModal" class="modal">
<div class="modal-content">
<span class="close" onclick="closeModal('addParentModal')">&times;</span>
<h3>Add Parent</h3>
<form method="POST" action="actions.php">
<input type="hidden" name="entity" value="parent">
<input type="hidden" name="action" value="add">
<input type="text" name="first_name" required>
<input type="text" name="last_name" required>
<input type="text" name="phone">
<input type="email" name="email">
<button type="submit">Save</button>
</form>
</div>
</div>

<div id="viewParentModal" class="modal">
<div class="modal-content">
<span class="close" onclick="closeModal('viewParentModal')">&times;</span>
<h3>All Parents</h3>

<input type="text" onkeyup="searchTable(this,'parentTable')" placeholder="Search...">

<?php $parents=$conn->query("SELECT * FROM parent_guardian"); ?>
<table id="parentTable">
<tr><th>ID</th><th>Name</th><th>Phone</th><th>Email</th><th>Action</th></tr>
<?php while($pr=$parents->fetch_assoc()){ ?>
<tr>
<td><?= $pr['parent_id'] ?></td>
<td><?= $pr['first_name']." ".$pr['last_name'] ?></td>
<td><?= $pr['phone'] ?></td>
<td><?= $pr['email'] ?></td>
<td>
<form method="POST" action="actions.php" style="display:inline;">
<input type="hidden" name="entity" value="parent">
<input type="hidden" name="action" value="delete">
<input type="hidden" name="id" value="<?= $pr['parent_id'] ?>">
<button onclick="return confirmDelete()">Delete</button>
</form>
<button onclick="editParent('<?= $pr['parent_id'] ?>','<?= $pr['first_name'] ?>','<?= $pr['last_name'] ?>','<?= $pr['phone'] ?>','<?= $pr['email'] ?>')">Edit</button>
</td>
</tr>
<?php } ?>
</table>
</div>
</div>

<div id="editParentModal" class="modal">
<div class="modal-content">
<span class="close" onclick="closeModal('editParentModal')">&times;</span>
<h3>Edit Parent</h3>
<form method="POST" action="actions.php">
<input type="hidden" name="entity" value="parent">
<input type="hidden" name="action" value="edit">
<input type="hidden" id="edit_parent_id" name="id">
<input type="text" id="edit_parent_first" name="first_name" required>
<input type="text" id="edit_parent_last" name="last_name" required>
<input type="text" id="edit_parent_phone" name="phone">
<input type="email" id="edit_parent_email" name="email">
<button type="submit">Update</button>
</form>
</div>
</div>

<!-- ASSIGN PARENT MODAL -->

<div id="assignModal" class="modal">
<div class="modal-content">
<span class="close" onclick="closeModal('assignModal')">&times;</span>
<h3>Assign Parent to Pupil</h3>

<form method="POST" action="actions.php">
<input type="hidden" name="entity" value="assign">
<input type="hidden" name="action" value="add">

<select name="pupil_id" required>
<option value="">Select Pupil</option>
<?php
$plist = $conn->query("SELECT * FROM pupil");
while($p = $plist->fetch_assoc()){
echo "<option value='{$p['pupil_id']}'>{$p['first_name']} {$p['last_name']}</option>";
}
?>
</select>

<select name="parent_id" required>
<option value="">Select Parent</option>
<?php
$prlist = $conn->query("SELECT * FROM parent_guardian");
while($pr = $prlist->fetch_assoc()){
echo "<option value='{$pr['parent_id']}'>{$pr['first_name']} {$pr['last_name']}</option>";
}
?>
</select>

<input type="text" name="relationship" placeholder="Relationship (Mother/Father)" required>

<button type="submit">Assign</button>
</form>
</div>
</div>

<!-- JAVASCRIPT -->

<script>

function openModal(id){ document.getElementById(id).style.display="block"; }
function closeModal(id){ document.getElementById(id).style.display="none"; }

function confirmDelete(){
    return confirm("Are you sure you want to delete this record?");
}

function searchTable(input, tableId){
    var filter=input.value.toLowerCase();
    var rows=document.getElementById(tableId).getElementsByTagName("tr");
    for(var i=1;i<rows.length;i++){
        rows[i].style.display=rows[i].innerText.toLowerCase().includes(filter)?"":"none";
    }
}

function editTeacher(id,f,l,s){
document.getElementById("edit_teacher_id").value=id;
document.getElementById("edit_teacher_first").value=f;
document.getElementById("edit_teacher_last").value=l;
document.getElementById("edit_teacher_salary").value=s;
openModal("editTeacherModal");
}

function editClass(id,n,c,t){
document.getElementById("edit_class_id").value=id;
document.getElementById("edit_class_name").value=n;
document.getElementById("edit_class_capacity").value=c;
document.getElementById("edit_class_teacher").value=t;
openModal("editClassModal");
}

function editPupil(id,f,l,d,c){
document.getElementById("edit_pupil_id").value=id;
document.getElementById("edit_pupil_first").value=f;
document.getElementById("edit_pupil_last").value=l;
document.getElementById("edit_pupil_dob").value=d;
document.getElementById("edit_pupil_class").value=c;
openModal("editPupilModal");
}

function editParent(id,f,l,p,e){
document.getElementById("edit_parent_id").value=id;
document.getElementById("edit_parent_first").value=f;
document.getElementById("edit_parent_last").value=l;
document.getElementById("edit_parent_phone").value=p;
document.getElementById("edit_parent_email").value=e;
openModal("editParentModal");
}

</script>

</body>
</html>