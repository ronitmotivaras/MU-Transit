<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>MU Transit - Faculty Admin Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: white;
      display: flex;
    }
    .sidebar {
      width: 250px;
      height: 100vh;
      position: fixed;
      background-color: #212529;
      padding-top: 20px;
      color: white;
      overflow-y: auto;
    }
    .sidebar h3 {
      text-align: center;
    }
    .sidebar a {
      color: white;
      padding: 12px 15px;
      display: flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
    }
    .sidebar a:hover {
      background-color: #343a40;
    }
    .content {
      flex-grow: 1;
      margin-left: 250px;
      padding: 20px;
      width: calc(100% - 250px);
      background-color: white;
    }
    .btn-group {
      display: flex;
      justify-content: center;
      gap: 5px;
    }
    .add-faculty-btn {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    }
    .add-faculty-btn:hover {
      background-color: #218838;
    }
    /* Modal styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.5);
    }
    .modal-content {
      background-color: white;
      margin: 5% auto;
      padding: 20px;
      border-radius: 5px;
      width: 70%;
      max-width: 600px;
    }
    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #dee2e6;
      padding-bottom: 10px;
      margin-bottom: 20px;
    }
    .close-btn {
      font-size: 24px;
      cursor: pointer;
    }
    .form-group {
      margin-bottom: 15px;
    }
    table {
      table-layout: fixed;
    }
    td {
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
    .invalid-feedback {
      display: none;
      color: #dc3545;
      font-size: 14px;
    }
    .is-invalid {
      border-color: #dc3545;
    }
    .is-invalid + .invalid-feedback {
      display: block;
    }
  </style>
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Sidebar -->
  <?php include('sidenavbar.php')?>

  <!-- Faculty List -->
  <div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Faculty</h2>
      <button class="add-faculty-btn" onclick="openAddFacultyModal()">
        <i class="fas fa-plus"></i> Add Faculty
      </button>
    </div>

    <table class="table table-bordered table-striped text-center">
      <thead class="table-dark">
        <tr>
          <th>Faculty ID</th>
          <th>Name</th>
          <th>City</th>
          <th>Address</th>
          <th>Phone No.</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="facultyTable">
        <!-- Data will be dynamically loaded -->
      </tbody>
    </table>
  </div>

  <!-- Add Faculty Modal -->
  <div id="addFacultyModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Add New Faculty</h3>
        <span class="close-btn" onclick="closeAddFacultyModal()">&times;</span>
      </div>
      <form id="addFacultyForm">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="facultyId">Faculty ID:</label>
              <input type="text" class="form-control" id="facultyId" required maxlength="6">
              <div class="invalid-feedback">Faculty ID must be 6 digits only.</div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="name">Name:</label>
              <input type="text" class="form-control" id="name" required>
              <div class="invalid-feedback">Name must contain only alphabets.</div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="city">City:</label>
              <input type="text" class="form-control" id="city" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="phoneNo">Phone No.:</label>
              <input type="tel" class="form-control" id="phoneNo" required maxlength="10">
              <div class="invalid-feedback">Phone number must be exactly 10 digits.</div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="address">Address:</label>
              <textarea class="form-control" id="address" rows="2" required></textarea>
            </div>
          </div>
        </div>
        <div class="d-flex justify-content-end mt-3">
          <button type="button" class="btn btn-secondary me-2" onclick="closeAddFacultyModal()">Cancel</button>
          <button type="button" class="btn btn-success" onclick="validateAndSaveFacultyData()">Save Faculty</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Faculty Modal -->
  <div id="editFacultyModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Edit Faculty</h3>
        <span class="close-btn" onclick="closeEditFacultyModal()">&times;</span>
      </div>
      <form id="editFacultyForm">
        <input type="hidden" id="editFacultyKey">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="editFacultyId">Faculty ID:</label>
              <input type="text" class="form-control" id="editFacultyId" required maxlength="6">
              <div class="invalid-feedback">Faculty ID must be 6 digits only.</div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="editName">Name:</label>
              <input type="text" class="form-control" id="editName" required>
              <div class="invalid-feedback">Name must contain only alphabets.</div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="editCity">City:</label>
              <input type="text" class="form-control" id="editCity" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="editPhoneNo">Phone No.:</label>
              <input type="tel" class="form-control" id="editPhoneNo" required maxlength="10">
              <div class="invalid-feedback">Phone number must be exactly 10 digits.</div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="editAddress">Address:</label>
              <textarea class="form-control" id="editAddress" rows="2" required></textarea>
            </div>
          </div>
        </div>
        <div class="d-flex justify-content-end mt-3">
          <button type="button" class="btn btn-secondary me-2" onclick="closeEditFacultyModal()">Cancel</button>
          <button type="button" class="btn btn-primary" onclick="validateAndUpdateFacultyData()">Update Information</button>
        </div>
      </form>
    </div>
  </div>

  <!-- View Faculty Modal -->
  <div id="viewFacultyModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Faculty Details</h3>
        <span class="close-btn" onclick="closeViewFacultyModal()">&times;</span>
      </div>
      <div class="faculty-details">
        <table class="table table-bordered">
          <tr>
            <th width="30%">Faculty ID</th>
            <td id="viewFacultyId"></td>
          </tr>
          <tr>
            <th>Name</th>
            <td id="viewName"></td>
          </tr>
          <tr>
            <th>City</th>
            <td id="viewCity"></td>
          </tr>
          <tr>
            <th>Address</th>
            <td id="viewAddress"></td>
          </tr>
          <tr>
            <th>Phone No.</th>
            <td id="viewPhoneNo"></td>
          </tr>
        </table>
      </div>
      <div class="d-flex justify-content-end mt-3">
        <button type="button" class="btn btn-secondary" onclick="closeViewFacultyModal()">Close</button>
      </div>
    </div>
  </div>

  <!-- Firebase -->
  <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js"></script>
  <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-database-compat.js"></script>

  <script>
    // Firebase config
    const firebaseConfig = {
      databaseURL: "https://mu-transit-86f0a-default-rtdb.firebaseio.com/"
    };
    firebase.initializeApp(firebaseConfig);
    const db = firebase.database();

    // Load faculty on page load
    window.onload = function () {
      loadFacultyData();
      
      // Add input validation listeners
      document.getElementById("facultyId").addEventListener("input", validateFacultyId);
      document.getElementById("name").addEventListener("input", validateName);
      document.getElementById("phoneNo").addEventListener("input", validatePhoneNo);
      
      document.getElementById("editFacultyId").addEventListener("input", validateEditFacultyId);
      document.getElementById("editName").addEventListener("input", validateEditName);
      document.getElementById("editPhoneNo").addEventListener("input", validateEditPhoneNo);
    };

    function loadFacultyData() {
      const table = document.getElementById("facultyTable");
      table.innerHTML = '';
      db.ref("faculty").once("value", function (snapshot) {
        snapshot.forEach(function (childSnapshot) {
          const key = childSnapshot.key;
          const data = childSnapshot.val();
          appendFacultyRow(data, key);
        });
      });
    }

    function appendFacultyRow(data, key) {
      const table = document.getElementById("facultyTable");
      const row = document.createElement("tr");
      row.setAttribute("data-key", key);
      row.innerHTML = `
        <td>${data.facultyId}</td>
        <td>${data.name}</td>
        <td>${data.city}</td>
        <td>${data.address}</td>
        <td>${data.phoneNo}</td>
        <td class="btn-group">
          <button class="btn btn-sm btn-info" onclick="openViewFacultyModal('${key}')">
            <i class="fas fa-eye"></i> View
          </button>
          <button class="btn btn-sm btn-warning" onclick="openEditFacultyModal('${key}')">
            <i class="fas fa-edit"></i> Edit
          </button>
          <button class="btn btn-sm btn-danger" onclick="deleteFaculty(this)">
            <i class="fas fa-trash"></i> Delete
          </button>
        </td>`;
      table.appendChild(row);
    }
    
    // Validation functions
    function validateFacultyId() {
      const facultyIdInput = document.getElementById("facultyId");
      const value = facultyIdInput.value.trim();
      const isValid = /^\d{6}$/.test(value);
      
      if (value && !isValid) {
        facultyIdInput.classList.add("is-invalid");
        return false;
      } else {
        facultyIdInput.classList.remove("is-invalid");
        return true;
      }
    }
    
    function validateName() {
      const nameInput = document.getElementById("name");
      const value = nameInput.value.trim();
      const isValid = /^[A-Za-z\s]+$/.test(value);
      
      if (value && !isValid) {
        nameInput.classList.add("is-invalid");
        return false;
      } else {
        nameInput.classList.remove("is-invalid");
        return true;
      }
    }
    
    function validatePhoneNo() {
      const phoneInput = document.getElementById("phoneNo");
      const value = phoneInput.value.trim();
      const isValid = /^\d{10}$/.test(value);
      
      if (value && !isValid) {
        phoneInput.classList.add("is-invalid");
        return false;
      } else {
        phoneInput.classList.remove("is-invalid");
        return true;
      }
    }
    
    function validateEditFacultyId() {
      const facultyIdInput = document.getElementById("editFacultyId");
      const value = facultyIdInput.value.trim();
      const isValid = /^\d{6}$/.test(value);
      
      if (value && !isValid) {
        facultyIdInput.classList.add("is-invalid");
        return false;
      } else {
        facultyIdInput.classList.remove("is-invalid");
        return true;
      }
    }
    
    function validateEditName() {
      const nameInput = document.getElementById("editName");
      const value = nameInput.value.trim();
      const isValid = /^[A-Za-z\s]+$/.test(value);
      
      if (value && !isValid) {
        nameInput.classList.add("is-invalid");
        return false;
      } else {
        nameInput.classList.remove("is-invalid");
        return true;
      }
    }
    
    function validateEditPhoneNo() {
      const phoneInput = document.getElementById("editPhoneNo");
      const value = phoneInput.value.trim();
      const isValid = /^\d{10}$/.test(value);
      
      if (value && !isValid) {
        phoneInput.classList.add("is-invalid");
        return false;
      } else {
        phoneInput.classList.remove("is-invalid");
        return true;
      }
    }

    // Open Add Faculty Modal
    function openAddFacultyModal() {
      document.getElementById("addFacultyModal").style.display = "block";
      document.getElementById("addFacultyForm").reset();
      
      // Clear validation states
      document.getElementById("facultyId").classList.remove("is-invalid");
      document.getElementById("name").classList.remove("is-invalid");
      document.getElementById("phoneNo").classList.remove("is-invalid");
    }

    // Close Add Faculty Modal
    function closeAddFacultyModal() {
      document.getElementById("addFacultyModal").style.display = "none";
    }

    // Open Edit Faculty Modal
    function openEditFacultyModal(key) {
      document.getElementById("editFacultyModal").style.display = "block";
      document.getElementById("editFacultyForm").reset();
      document.getElementById("editFacultyKey").value = key;
      
      // Clear validation states
      document.getElementById("editFacultyId").classList.remove("is-invalid");
      document.getElementById("editName").classList.remove("is-invalid");
      document.getElementById("editPhoneNo").classList.remove("is-invalid");
      
      // Fetch current faculty data
      db.ref("faculty/" + key).once("value", function(snapshot) {
        const data = snapshot.val();
        document.getElementById("editFacultyId").value = data.facultyId || '';
        document.getElementById("editName").value = data.name || '';
        document.getElementById("editAddress").value = data.address || '';
        document.getElementById("editCity").value = data.city || '';
        document.getElementById("editPhoneNo").value = data.phoneNo || '';
      });
    }

    // Close Edit Faculty Modal
    function closeEditFacultyModal() {
      document.getElementById("editFacultyModal").style.display = "none";
    }

    // Open View Faculty Modal
    function openViewFacultyModal(key) {
      document.getElementById("viewFacultyModal").style.display = "block";
      
      // Fetch current faculty data
      db.ref("faculty/" + key).once("value", function(snapshot) {
        const data = snapshot.val();
        document.getElementById("viewFacultyId").textContent = data.facultyId || '';
        document.getElementById("viewName").textContent = data.name || '';
        document.getElementById("viewAddress").textContent = data.address || '';
        document.getElementById("viewCity").textContent = data.city || '';
        document.getElementById("viewPhoneNo").textContent = data.phoneNo || '';
      });
    }

    // Close View Faculty Modal
    function closeViewFacultyModal() {
      document.getElementById("viewFacultyModal").style.display = "none";
    }

    // Validate and Save Faculty Data from Modal
    function validateAndSaveFacultyData() {
      // Run all validations
      const facultyIdValid = validateFacultyId();
      const nameValid = validateName();
      const phoneValid = validatePhoneNo();
      
      if (!facultyIdValid || !nameValid || !phoneValid) {
        return; // Stop if any validation fails
      }
      
      saveFacultyData();
    }
    
    // Save Faculty Data from Modal
    function saveFacultyData() {
      const facultyId = document.getElementById("facultyId").value.trim();
      const name = document.getElementById("name").value.trim();
      const address = document.getElementById("address").value.trim();
      const city = document.getElementById("city").value.trim();
      const phoneNo = document.getElementById("phoneNo").value.trim();

      // Basic validation
      if (!facultyId || !name || !address || !city || !phoneNo) {
        alert("Please fill all fields");
        return;
      }

      const facultyData = {
        facultyId: facultyId,
        name: name,
        address: address,
        city: city,
        phoneNo: phoneNo
      };

      const newFacultyRef = db.ref("faculty").push();
      newFacultyRef.set(facultyData, function (error) {
        if (!error) {
          alert("Faculty saved successfully!");
          closeAddFacultyModal();
          appendFacultyRow(facultyData, newFacultyRef.key);
        } else {
          alert("Error saving faculty.");
        }
      });
    }

    // Validate and Update Faculty Data from Edit Modal
    function validateAndUpdateFacultyData() {
      // Run all validations
      const facultyIdValid = validateEditFacultyId();
      const nameValid = validateEditName();
      const phoneValid = validateEditPhoneNo();
      
      if (!facultyIdValid || !nameValid || !phoneValid) {
        return; // Stop if any validation fails
      }
      
      updateFacultyData();
    }
    
    // Update Faculty Data from Edit Modal
    function updateFacultyData() {
      const key = document.getElementById("editFacultyKey").value;
      const facultyId = document.getElementById("editFacultyId").value.trim();
      const name = document.getElementById("editName").value.trim();
      const address = document.getElementById("editAddress").value.trim();
      const city = document.getElementById("editCity").value.trim();
      const phoneNo = document.getElementById("editPhoneNo").value.trim();

      // Basic validation
      if (!facultyId || !name || !address || !city || !phoneNo) {
        alert("Please fill all fields");
        return;
      }

      const updatedData = {
        facultyId: facultyId,
        name: name,
        address: address,
        city: city,
        phoneNo: phoneNo
      };

      db.ref("faculty/" + key).set(updatedData, function (error) {
        if (!error) {
          alert("Faculty updated successfully!");
          closeEditFacultyModal();
          // Update the row in the table
          const row = document.querySelector(`tr[data-key="${key}"]`);
          if (row) {
            row.cells[0].textContent = facultyId;
            row.cells[1].textContent = name;
            row.cells[2].textContent = city;
            row.cells[3].textContent = address;
            row.cells[4].textContent = phoneNo;
          } else {
            // If row not found, reload all data
            loadFacultyData();
          }
        } else {
          alert("Error updating faculty.");
        }
      });
    }

    function deleteFaculty(button) {
      if (!confirm("Are you sure you want to delete this faculty?")) {
        return;
      }
      
      const row = button.closest("tr");
      const key = row.getAttribute("data-key");

      if (key) {
        db.ref("faculty/" + key).remove(function (error) {
          if (!error) {
            row.remove();
            alert("Faculty deleted successfully!");
          } else {
            alert("Error deleting from database.");
          }
        });
      } else {
        row.remove(); // Only from UI if no key exists
      }
    }

    // Close the modals when clicking outside of them
    window.onclick = function(event) {
      const addModal = document.getElementById("addFacultyModal");
      const editModal = document.getElementById("editFacultyModal");
      const viewModal = document.getElementById("viewFacultyModal");
      
      if (event.target == addModal) {
        closeAddFacultyModal();
      }
      
      if (event.target == editModal) {
        closeEditFacultyModal();
      }
      
      if (event.target == viewModal) {
        closeViewFacultyModal();
      }
    }
    
    // Enforce numeric input for Faculty ID and Phone No
    document.getElementById("facultyId").addEventListener("keypress", function(e) {
      if (!/\d/.test(e.key)) {
        e.preventDefault();
      }
    });
    
    document.getElementById("phoneNo").addEventListener("keypress", function(e) {
      if (!/\d/.test(e.key)) {
        e.preventDefault();
      }
    });
    
    document.getElementById("editFacultyId").addEventListener("keypress", function(e) {
      if (!/\d/.test(e.key)) {
        e.preventDefault();
      }
    });
    
    document.getElementById("editPhoneNo").addEventListener("keypress", function(e) {
      if (!/\d/.test(e.key)) {
        e.preventDefault();
      }
    });
  </script>
</body>
</html>