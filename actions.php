<?php
include("db_connect.php");

$message = "";

if(isset($_POST['entity'])) {

    $entity = $_POST['entity'];
    $action = $_POST['action'];

    /* TEACHER */

    if($entity == "teacher") {

        if($action == "add") {
            $stmt = $conn->prepare(
                "INSERT INTO teacher (first_name,last_name,annual_salary)
                 VALUES (?,?,?)"
            );
            $stmt->bind_param("ssd",
                $_POST['first_name'],
                $_POST['last_name'],
                $_POST['salary']
            );
            $stmt->execute();
            $message = "Teacher added successfully.";
        }

        if($action == "edit") {
            $stmt = $conn->prepare(
                "UPDATE teacher
                 SET first_name=?, last_name=?, annual_salary=?
                 WHERE teacher_id=?"
            );
            $stmt->bind_param("ssdi",
                $_POST['first_name'],
                $_POST['last_name'],
                $_POST['salary'],
                $_POST['id']
            );
            $stmt->execute();
            $message = "Teacher updated successfully.";
        }

        if($action == "delete") {

            $stmt = $conn->prepare("DELETE FROM teacher WHERE teacher_id=?");
            $stmt->bind_param("i", $_POST['id']);

            if($stmt->execute()) {
                $message = "Teacher deleted successfully.";
            } else {
                $message = "Cannot delete teacher. It may be assigned to a class.";
            }
        }
    }

    /* CLASS */

    if($entity == "class") {

        if($action == "add") {
            $stmt = $conn->prepare(
                "INSERT INTO class (class_name,capacity,teacher_id)
                 VALUES (?,?,?)"
            );
            $stmt->bind_param("sii",
                $_POST['class_name'],
                $_POST['capacity'],
                $_POST['teacher_id']
            );
            $stmt->execute();
            $message = "Class added successfully.";
        }

        if($action == "edit") {
            $stmt = $conn->prepare(
                "UPDATE class
                 SET class_name=?, capacity=?, teacher_id=?
                 WHERE class_id=?"
            );
            $stmt->bind_param("siii",
                $_POST['class_name'],
                $_POST['capacity'],
                $_POST['teacher_id'],
                $_POST['id']
            );
            $stmt->execute();
            $message = "Class updated successfully.";
        }

        if($action == "delete") {

            $stmt = $conn->prepare("DELETE FROM class WHERE class_id=?");
            $stmt->bind_param("i", $_POST['id']);

            if($stmt->execute()) {
                $message = "Class deleted successfully.";
            } else {
                $message = "Cannot delete class. It may contain pupils.";
            }
        }
    }

    /* PUPIL */

    if($entity == "pupil") {

        if($action == "add") {

            $stmt = $conn->prepare(
                "INSERT INTO pupil (first_name,last_name,date_of_birth,class_id)
                 VALUES (?,?,?,?)"
            );
            $stmt->bind_param("sssi",
                $_POST['first_name'],
                $_POST['last_name'],
                $_POST['dob'],
                $_POST['class_id']
            );
            $stmt->execute();
            $message = "Pupil added successfully.";
        }

        if($action == "edit") {
            $stmt = $conn->prepare(
                "UPDATE pupil
                 SET first_name=?, last_name=?, date_of_birth=?, class_id=?
                 WHERE pupil_id=?"
            );
            $stmt->bind_param("sssii",
                $_POST['first_name'],
                $_POST['last_name'],
                $_POST['dob'],
                $_POST['class_id'],
                $_POST['id']
            );
            $stmt->execute();
            $message = "Pupil updated successfully.";
        }

        if($action == "delete") {

            // delete relations first
            $stmt = $conn->prepare("DELETE FROM pupil_parent WHERE pupil_id=?");
            $stmt->bind_param("i", $_POST['id']);
            $stmt->execute();

            $stmt = $conn->prepare("DELETE FROM pupil WHERE pupil_id=?");
            $stmt->bind_param("i", $_POST['id']);

            if($stmt->execute()) {
                $message = "Pupil deleted successfully.";
            } else {
                $message = "Cannot delete pupil.";
            }
        }
    }

    /* PARENT */

    if($entity == "parent") {

        if($action == "add") {
            $stmt = $conn->prepare(
                "INSERT INTO parent_guardian (first_name,last_name,phone,email)
                 VALUES (?,?,?,?)"
            );
            $stmt->bind_param("ssss",
                $_POST['first_name'],
                $_POST['last_name'],
                $_POST['phone'],
                $_POST['email']
            );
            $stmt->execute();
            $message = "Parent added successfully.";
        }

        if($action == "edit") {
            $stmt = $conn->prepare(
                "UPDATE parent_guardian
                 SET first_name=?, last_name=?, phone=?, email=?
                 WHERE parent_id=?"
            );
            $stmt->bind_param("ssssi",
                $_POST['first_name'],
                $_POST['last_name'],
                $_POST['phone'],
                $_POST['email'],
                $_POST['id']
            );
            $stmt->execute();
            $message = "Parent updated successfully.";
        }

        if($action == "delete") {

            $stmt = $conn->prepare("DELETE FROM pupil_parent WHERE parent_id=?");
            $stmt->bind_param("i", $_POST['id']);
            $stmt->execute();

            $stmt = $conn->prepare("DELETE FROM parent_guardian WHERE parent_id=?");
            $stmt->bind_param("i", $_POST['id']);

            if($stmt->execute()) {
                $message = "Parent deleted successfully.";
            } else {
                $message = "Cannot delete parent.";
            }
        }
    }

    /* ASSIGN PARENT */

if($entity == "assign") {

    $pupil_id = $_POST['pupil_id'];
    $parent_id = $_POST['parent_id'];
    $relationship = $_POST['relationship'];

    // Check if already assigned
    $check = $conn->prepare(
        "SELECT * FROM pupil_parent WHERE pupil_id=? AND parent_id=?"
    );
    $check->bind_param("ii", $pupil_id, $parent_id);
    $check->execute();
    $result = $check->get_result();

    if($result->num_rows > 0) {

        $message = "This parent is already assigned to this pupil.";

    } else {

        // Check max 2 parents
        $count = $conn->prepare(
            "SELECT COUNT(*) as total FROM pupil_parent WHERE pupil_id=?"
        );
        $count->bind_param("i", $pupil_id);
        $count->execute();
        $data = $count->get_result()->fetch_assoc();

        if($data['total'] >= 2) {

            $message = "This pupil already has 2 parents assigned.";

        } else {

            $stmt = $conn->prepare(
                "INSERT INTO pupil_parent (pupil_id, parent_id, relationship_type)
                 VALUES (?,?,?)"
            );
            $stmt->bind_param("iis", $pupil_id, $parent_id, $relationship);

            if($stmt->execute()) {
                $message = "Parent assigned successfully.";
            } else {
                $message = "Error assigning parent.";
            }
        }
    }
}

}

header("Location: index.php?msg=".urlencode($message));
exit();
?>