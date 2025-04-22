<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>MU Transit - Admin Panel</title>
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
    .add-bus-btn {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    }
    .add-bus-btn:hover {
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
      margin: 10% auto;
      padding: 20px;
      border-radius: 5px;
      width: 60%;
      max-width: 500px;
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
  </style>
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Sidebar -->
  <?php include('sidenavbar.php')?>

  <!-- Bus List -->
  <div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Buses</h2>
      <button class="add-bus-btn" onclick="openAddBusModal()">
        <i class="fas fa-plus"></i> Add Bus
      </button>
    </div>

    <table class="table table-bordered table-striped text-center">
      <thead class="table-dark">
        <tr>
          <th>Bus Num</th>
          <th>Seating Capacity</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="busTable">
        <!-- Data will be dynamically loaded -->
      </tbody>
    </table>
  </div>

  <!-- Add Bus Modal -->
  <div id="addBusModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Add New Bus</h3>
        <span class="close-btn" onclick="closeAddBusModal()">&times;</span>
      </div>
      <form id="addBusForm">
        <div class="form-group">
          <label for="busNum">Bus Number:</label>
          <input type="text" class="form-control" id="busNum" required>
        </div>
        <div class="form-group">
          <label for="seatingCapacity">Seating Capacity:</label>
          <input type="number" class="form-control" id="seatingCapacity" min="1" required>
        </div>
        <div class="d-flex justify-content-end">
          <button type="button" class="btn btn-secondary me-2" onclick="closeAddBusModal()">Cancel</button>
          <button type="button" class="btn btn-success" onclick="saveBusData()">Save Bus</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Bus Modal -->
  <div id="editBusModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Edit Bus</h3>
        <span class="close-btn" onclick="closeEditBusModal()">&times;</span>
      </div>
      <form id="editBusForm">
        <input type="hidden" id="editBusKey">
        <div class="form-group">
          <label for="editBusNum">Bus Number:</label>
          <input type="text" class="form-control" id="editBusNum" required>
        </div>
        <div class="form-group">
          <label for="editSeatingCapacity">Seating Capacity:</label>
          <input type="number" class="form-control" id="editSeatingCapacity" min="1" required>
        </div>
        <div class="d-flex justify-content-end">
          <button type="button" class="btn btn-secondary me-2" onclick="closeEditBusModal()">Cancel</button>
          <button type="button" class="btn btn-primary" onclick="updateBusData()">Update Bus</button>
        </div>
      </form>
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

    // Load buses on page load
    window.onload = function () {
      loadBusData();
    };

    function loadBusData() {
      const table = document.getElementById("busTable");
      table.innerHTML = '';
      db.ref("buses").once("value", function (snapshot) {
        snapshot.forEach(function (childSnapshot) {
          const key = childSnapshot.key;
          const data = childSnapshot.val();
          appendBusRow(data, key);
        });
      });
    }

    function appendBusRow(data, key) {
      const table = document.getElementById("busTable");
      const row = document.createElement("tr");
      row.setAttribute("data-key", key);
      row.innerHTML = `
        <td>${data.busNum || data.busNo}</td>
        <td>${data.seatingCapacity || '-'}</td>
        <td class="btn-group">
          <button class="btn btn-sm btn-warning" onclick="openEditBusModal('${key}')">
            <i class="fas fa-edit"></i> Edit
          </button>
          <button class="btn btn-sm btn-danger" onclick="deleteRow(this)">
            <i class="fas fa-trash"></i> Delete
          </button>
        </td>`;
      table.appendChild(row);
    }

    // Open Add Bus Modal
    function openAddBusModal() {
      document.getElementById("addBusModal").style.display = "block";
      document.getElementById("addBusForm").reset();
    }

    // Close Add Bus Modal
    function closeAddBusModal() {
      document.getElementById("addBusModal").style.display = "none";
    }

    // Open Edit Bus Modal
    function openEditBusModal(key) {
      document.getElementById("editBusModal").style.display = "block";
      document.getElementById("editBusForm").reset();
      document.getElementById("editBusKey").value = key;
      
      // Fetch current bus data
      db.ref("buses/" + key).once("value", function(snapshot) {
        const data = snapshot.val();
        document.getElementById("editBusNum").value = data.busNum || data.busNo || '';
        document.getElementById("editSeatingCapacity").value = data.seatingCapacity || '';
      });
    }

    // Close Edit Bus Modal
    function closeEditBusModal() {
      document.getElementById("editBusModal").style.display = "none";
    }

    // Save Bus Data from Modal
    function saveBusData() {
      const busNum = document.getElementById("busNum").value.trim();
      const seatingCapacity = document.getElementById("seatingCapacity").value.trim();

      // Basic validation
      if (!busNum || !seatingCapacity) {
        alert("Please fill all fields");
        return;
      }

      const busData = {
        busNum: busNum,
        seatingCapacity: parseInt(seatingCapacity)
      };

      const newBusRef = db.ref("buses").push();
      newBusRef.set(busData, function (error) {
        if (!error) {
          alert("Bus saved successfully!");
          closeAddBusModal();
          appendBusRow(busData, newBusRef.key);
        } else {
          alert("Error saving bus.");
        }
      });
    }

    // Update Bus Data from Edit Modal
    function updateBusData() {
      const key = document.getElementById("editBusKey").value;
      const busNum = document.getElementById("editBusNum").value.trim();
      const seatingCapacity = document.getElementById("editSeatingCapacity").value.trim();

      // Basic validation
      if (!busNum || !seatingCapacity) {
        alert("Please fill all fields");
        return;
      }

      const updatedData = {
        busNum: busNum,
        seatingCapacity: parseInt(seatingCapacity)
      };

      db.ref("buses/" + key).set(updatedData, function (error) {
        if (!error) {
          alert("Bus updated successfully!");
          closeEditBusModal();
          // Update the row in the table
          const row = document.querySelector(`tr[data-key="${key}"]`);
          if (row) {
            row.cells[0].textContent = busNum;
            row.cells[1].textContent = seatingCapacity;
          } else {
            // If row not found, reload all data
            loadBusData();
          }
        } else {
          alert("Error updating bus.");
        }
      });
    }

    function deleteRow(button) {
      if (!confirm("Are you sure you want to delete this bus?")) {
        return;
      }
      
      const row = button.closest("tr");
      const key = row.getAttribute("data-key");

      if (key) {
        db.ref("buses/" + key).remove(function (error) {
          if (!error) {
            row.remove();
            alert("Bus deleted successfully!");
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
      const addModal = document.getElementById("addBusModal");
      const editModal = document.getElementById("editBusModal");
      
      if (event.target == addModal) {
        closeAddBusModal();
      }
      
      if (event.target == editModal) {
        closeEditBusModal();
      }
    }
  </script>
</body>
</html>