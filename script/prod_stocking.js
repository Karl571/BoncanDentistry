// SELECT ELEMENTS
const nameInput = document.getElementById("name");
const skuInput = document.getElementById("sku");
const qtyInput = document.getElementById("qty");
const unitInput = document.getElementById("unit");
const catInput = document.getElementById("cat");

const addBtn = document.querySelector(".primary");
const resetBtn = document.querySelector(".ghost");
const tableBody = document.querySelector("tbody");


// FUNCTION → Determine Stock Status Badge
function getStatusBadge(qty) {
  if (qty <= 0) {
    return `<span class="badge out-stock">Out of Stock</span>`;
  } else if (qty < 20) {
    return `<span class="badge low-stock">Low Stock</span>`;
  } else {
    return `<span class="badge in-stock">In Stock</span>`;
  }
}


// FUNCTION → Add Item to Table
function addItem() {
  const name = nameInput.value.trim();
  const sku = skuInput.value.trim();
  const qty = parseInt(qtyInput.value);
  const unit = unitInput.value;
  const cat = catInput.value.trim();

  if (name === "" || sku === "" || cat === "") {
    alert("Please complete all fields.");
    return;
  }

  const row = document.createElement("tr");

  row.innerHTML = `
    <td>${name}</td>
    <td>${sku}</td>
    <td>${cat}</td>
    <td>${qty} ${unit}</td>
    <td>${getStatusBadge(qty)}</td>
    <td class="actions">
      <button onclick="editItem(this)">✏️</button>
      <button onclick="removeItem(this)">🗑️</button>
    </td>
  `;

  tableBody.appendChild(row);
  clearForm();
}


// FUNCTION → Clear All Form Inputs
function clearForm() {
  nameInput.value = "";
  skuInput.value = "";
  qtyInput.value = 0;
  unitInput.value = "pcs";
  catInput.value = "";
}


// FUNCTION → Remove Item
function removeItem(btn) {
  if (confirm("Are you sure you want to delete this item?")) {
    btn.closest("tr").remove();
  }
}


// OPTIONAL: Edit Item Function
function editItem(btn) {
  const row = btn.closest("tr");
  const cells = row.querySelectorAll("td");

  nameInput.value = cells[0].innerText;
  skuInput.value = cells[1].innerText;
  catInput.value = cells[2].innerText;

  const qtyData = cells[3].innerText.split(" ");
  qtyInput.value = qtyData[0];
  unitInput.value = qtyData[1];

  row.remove(); // remove old row so the new edited one will be added fresh
}


// EVENT LISTENERS
addBtn.addEventListener("click", addItem);
resetBtn.addEventListener("click", clearForm);

