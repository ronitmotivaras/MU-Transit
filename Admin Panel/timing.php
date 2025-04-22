<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>MU Transit - Shifts Admin Panel</title>
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
    .add-shift-btn {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    }
    .add-shift-btn:hover {
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

  <!-- Shifts List -->
  <div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Shifts</h2>
      <button class="add-shift-btn" onclick="openAddShiftModal()">
        <i class="fas fa-plus"></i> Add Shift
      </button>
    </div>

    <table class="table table-bordered table-striped text-center">
      <thead class="table-dark">
        <tr>
          <th>Shift Name</th>
          <th>Time</th>
          <th>Day</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="shiftTable">
        <!-- Data will be dynamically loaded -->
      </tbody>
    </table>
  </div>

  <!-- Add Shift Modal -->
  <div id="addShiftModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Add New Shift</h3>
        <span class="close-btn" onclick="closeAddShiftModal()">&times;</span>
      </div>
      <form id="addShiftForm">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="shiftName">Shift Name:</label>
              <input type="text" class="form-control" id="shiftName" required>
              <div class="invalid-feedback">Shift name is required.</div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="shiftTime">Time:</label>
              <input type="time" class="form-control" id="shiftTime" required>
              <div class="invalid-feedback">Valid time is required.</div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="shiftDay">Day:</label>
              <select class="form-control" id="shiftDay" required>
                <option value="">Select a day</option>
                <option value="Sunday">Sunday</option>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
              </select>
              <div class="invalid-feedback">Please select a day.</div>
            </div>
          </div>
        </div>
        <div class="d-flex justify-content-end mt-3">
          <button type="button" class="btn btn-secondary me-2" onclick="closeAddShiftModal()">Cancel</button>
          <button type="button" class="btn btn-success" onclick="validateAndSaveShiftData()">Save Shift</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Shift Modal -->
  <div id="editShiftModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Edit Shift</h3>
        <span class="close-btn" onclick="closeEditShiftModal()">&times;</span>
      </div>
      <form id="editShiftForm">
        <input type="hidden" id="editShiftKey">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="editShiftName">Shift Name:</label>
              <input type="text" class="form-control" id="editShiftName" required>
              <div class="invalid-feedback">Shift name is required.</div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="editShiftTime">Time:</label>
              <input type="time" class="form-control" id="editShiftTime" required>
              <div class="invalid-feedback">Valid time is required.</div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="editShiftDay">Day:</label>
              <select class="form-control" id="editShiftDay" required>
                <option value="">Select a day</option>
                <option value="Sunday">Sunday</option>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
              </select>
              <div class="invalid-feedback">Please select a day.</div>
            </div>
          </div>
        </div>
        <div class="d-flex justify-content-end mt-3">
          <button type="button" class="btn btn-secondary me-2" onclick="closeEditShiftModal()">Cancel</button>
          <button type="button" class="btn btn-primary" onclick="validateAndUpdateShiftData()">Update Information</button>
        </div>
      </form>
    </div>
  </div>

  <!-- View Shift Modal -->
  <div id="viewShiftModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Shift Details</h3>
        <span class="close-btn" onclick="closeViewShiftModal()">&times;</span>
      </div>
      <div class="shift-details">
        <table class="table table-bordered">
          <tr>
            <th width="30%">Shift Name</th>
            <td id="viewShiftName"></td>
          </tr>
          <tr>
            <th>Time</th>
            <td id="viewShiftTime"></td>
          </tr>
          <tr>
            <th>Day</th>
            <td id="viewShiftDay"></td>
          </tr>
        </table>
      </div>
      <div class="d-flex justify-content-end mt-3">
        <button type="button" class="btn btn-secondary" onclick="closeViewShiftModal()">Close</button>
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

    // Load shifts on page load
    window.onload = function () {
      loadShiftData();
      
      // Add input validation listeners
      document.getElementById("shiftName").addEventListener("input", validateShiftName);
      document.getElementById("editShiftName").addEventListener("input", validateEditShiftName);
    };

    function loadShiftData() {
      const table = document.getElementById("shiftTable");
      table.innerHTML = '';
      db.ref("shifts").once("value", function (snapshot) {
        snapshot.forEach(function (childSnapshot) {
          const key = childSnapshot.key;
          const data = childSnapshot.val();
          appendShiftRow(data, key);
        });
      });
    }

    function appendShiftRow(data, key) {
      const table = document.getElementById("shiftTable");
      const row = document.createElement("tr");
      row.setAttribute("data-key", key);
      
      // Format the time for display
      let displayTime = data.time;
      if (data.time) {
        const timeParts = data.time.split(':');
        if (timeParts.length === 2) {
          const hours = parseInt(timeParts[0]);
          const minutes = timeParts[1];
          const period = hours >= 12 ? 'PM' : 'AM';
          const displayHours = hours % 12 || 12;
          displayTime = `${displayHours}:${minutes} ${period}`;
        }
      }
      
      row.innerHTML = `
        <td>${data.shiftName}</td>
        <td>${displayTime}</td>
        <td>${data.day}</td>
        <td class="btn-group">
          <button class="btn btn-sm btn-info" onclick="openViewShiftModal('${key}')">
            <i class="fas fa-eye"></i> View
          </button>
          <button class="btn btn-sm btn-warning" onclick="openEditShiftModal('${key}')">
            <i class="fas fa-edit"></i> Edit
          </button>
          <button class="btn btn-sm btn-danger" onclick="deleteShift(this)">
            <i class="fas fa-trash"></i> Delete
          </button>
        </td>`;
      table.appendChild(row);
    }
    
    // Validation functions
    function validateShiftName() {
      const shiftNameInput = document.getElementById("shiftName");
      const value = shiftNameInput.value.trim();
      
      if (!value) {
        shiftNameInput.classList.add("is-invalid");
        return false;
      } else {
        shiftNameInput.classList.remove("is-invalid");
        return true;
      }
    }
    
    function validateEditShiftName() {
      const shiftNameInput = document.getElementById("editShiftName");
      const value = shiftNameInput.value.trim();
      
      if (!value) {
        shiftNameInput.classList.add("is-invalid");
        return false;
      } else {
        shiftNameInput.classList.remove("is-invalid");
        return true;
      }
    }
    
    function validateTime(timeInput) {
      const value = timeInput.value.trim();
      
      if (!value) {
        timeInput.classList.add("is-invalid");
        return false;
      } else {
        timeInput.classList.remove("is-invalid");
        return true;
      }
    }
    
    function validateDay(daySelect) {
      const value = daySelect.value;
      
      if (!value) {
        daySelect.classList.add("is-invalid");
        return false;
      } else {
        daySelect.classList.remove("is-invalid");
        return true;
      }
    }

    // Open Add Shift Modal
    function openAddShiftModal() {
      document.getElementById("addShiftModal").style.display = "block";
      document.getElementById("addShiftForm").reset();
      
      // Clear validation states
      document.getElementById("shiftName").classList.remove("is-invalid");
      document.getElementById("shiftTime").classList.remove("is-invalid");
      document.getElementById("shiftDay").classList.remove("is-invalid");
    }

    // Close Add Shift Modal
    function closeAddShiftModal() {
      document.getElementById("addShiftModal").style.display = "none";
    }

    // Open Edit Shift Modal
    function openEditShiftModal(key) {
      document.getElementById("editShiftModal").style.display = "block";
      document.getElementById("editShiftForm").reset();
      document.getElementById("editShiftKey").value = key;
      
      // Clear validation states
      document.getElementById("editShiftName").classList.remove("is-invalid");
      document.getElementById("editShiftTime").classList.remove("is-invalid");
      document.getElementById("editShiftDay").classList.remove("is-invalid");
      
      // Fetch current shift data
      db.ref("shifts/" + key).once("value", function(snapshot) {
        const data = snapshot.val();
        document.getElementById("editShiftName").value = data.shiftName || '';
        document.getElementById("editShiftTime").value = data.time || '';
        document.getElementById("editShiftDay").value = data.day || '';
      });
    }

    // Close Edit Shift Modal
    function closeEditShiftModal() {
      document.getElementById("editShiftModal").style.display = "none";
    }

    // Open View Shift Modal
    function openViewShiftModal(key) {
      document.getElementById("viewShiftModal").style.display = "block";
      
      // Fetch current shift data
      db.ref("shifts/" + key).once("value", function(snapshot) {
        const data = snapshot.val();
        document.getElementById("viewShiftName").textContent = data.shiftName || '';
        
        // Format time for display
        let displayTime = data.time || '';
        if (data.time) {
          const timeParts = data.time.split(':');
          if (timeParts.length === 2) {
            const hours = parseInt(timeParts[0]);
            const minutes = timeParts[1];
            const period = hours >= 12 ? 'PM' : 'AM';
            const displayHours = hours % 12 || 12;
            displayTime = `${displayHours}:${minutes} ${period}`;
          }
        }
        
        document.getElementById("viewShiftTime").textContent = displayTime;
        document.getElementById("viewShiftDay").textContent = data.day || '';
      });
    }

    // Close View Shift Modal
    function closeViewShiftModal() {
      document.getElementById("viewShiftModal").style.display = "none";
    }

    // Validate and Save Shift Data from Modal
    function validateAndSaveShiftData() {
      const shiftNameInput = document.getElementById("shiftName");
      const timeInput = document.getElementById("shiftTime");
      const daySelect = document.getElementById("shiftDay");
      
      // Run all validations
      const nameValid = validateShiftName();
      const timeValid = validateTime(timeInput);
      const dayValid = validateDay(daySelect);
      
      if (!nameValid || !timeValid || !dayValid) {
        return; // Stop if any validation fails
      }
      
      saveShiftData();
    }
    
    // Save Shift Data from Modal
    function saveShiftData() {
      const shiftName = document.getElementById("shiftName").value.trim();
      const time = document.getElementById("shiftTime").value;
      const day = document.getElementById("shiftDay").value;

      const shiftData = {
        shiftName: shiftName,
        time: time,
        day: day
      };

      const newShiftRef = db.ref("shifts").push();
      newShiftRef.set(shiftData, function (error) {
        if (!error) {
          alert("Shift saved successfully!");
          closeAddShiftModal();
          appendShiftRow(shiftData, newShiftRef.key);
        } else {
          alert("Error saving shift.");
        }
      });
    }

    // Validate and Update Shift Data from Edit Modal
    function validateAndUpdateShiftData() {
      const shiftNameInput = document.getElementById("editShiftName");
      const timeInput = document.getElementById("editShiftTime");
      const daySelect = document.getElementById("editShiftDay");
      
      // Run all validations
      const nameValid = validateEditShiftName();
      const timeValid = validateTime(timeInput);
      const dayValid = validateDay(daySelect);
      
      if (!nameValid || !timeValid || !dayValid) {
        return; // Stop if any validation fails
      }
      
      updateShiftData();
    }
    
    // Update Shift Data from Edit Modal
    function updateShiftData() {
      const key = document.getElementById("editShiftKey").value;
      const shiftName = document.getElementById("editShiftName").value.trim();
      const time = document.getElementById("editShiftTime").value;
      const day = document.getElementById("editShiftDay").value;

      const updatedData = {
        shiftName: shiftName,
        time: time,
        day: day
      };

      db.ref("shifts/" + key).set(updatedData, function (error) {
        if (!error) {
          alert("Shift updated successfully!");
          closeEditShiftModal();
          
          // Format time for display
          let displayTime = time;
          if (time) {
            const timeParts = time.split(':');
            if (timeParts.length === 2) {
              const hours = parseInt(timeParts[0]);
              const minutes = timeParts[1];
              const period = hours >= 12 ? 'PM' : 'AM';
              const displayHours = hours % 12 || 12;
              displayTime = `${displayHours}:${minutes} ${period}`;
            }
          }
          
          // Update the row in the table
          const row = document.querySelector(`tr[data-key="${key}"]`);
          if (row) {
            row.cells[0].textContent = shiftName;
            row.cells[1].textContent = displayTime;
            row.cells[2].textContent = day;
          } else {
            // If row not found, reload all data
            loadShiftData();
          }
        } else {
          alert("Error updating shift.");
        }
      });
    }

    function deleteShift(button) {
      if (!confirm("Are you sure you want to delete this shift?")) {
        return;
      }
      
      const row = button.closest("tr");
      const key = row.getAttribute("data-key");

      if (key) {
        db.ref("shifts/" + key).remove(function (error) {
          if (!error) {
            row.remove();
            alert("Shift deleted successfully!");
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
      const addModal = document.getElementById("addShiftModal");
      const editModal = document.getElementById("editShiftModal");
      const viewModal = document.getElementById("viewShiftModal");
      
      if (event.target == addModal) {
        closeAddShiftModal();
      }
      
      if (event.target == editModal) {
        closeEditShiftModal();
      }
      
      if (event.target == viewModal) {
        closeViewShiftModal();
      }
    }
  </script>
</body>
</html>