// Local Storage Key
const STORAGE_KEY = "dental_inventory";

// Get Elements
const nameInput = document.getElementById("name");
const skuInput = document.getElementById("sku");
const qtyInput = document.getElementById("qty");
const unitInput = document.getElementById("unit");
const catInput = document.getElementById("cat");
const resetBtn = document.querySelector(".ghost");
const addBtn = document.querySelector(".primary");
const searchInput = document.querySelector(".search input");

let inventory = [];


// Load Inventory from Local Storage

function loadInventory() {
  const data = localStorage.getItem(STORAGE_KEY);
  inventory = data ? JSON.parse(data) : [];
  render();
}

loadInventory();


// Save to Local Storage

function saveInventory() {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(inventory));
}


// Add Item

addBtn.addEventListener("click", () => {
  const name = nameInput.value.trim();
  const sku = skuInput.value.trim();
  const qty = parseInt(qtyInput.value);
  const unit = unitInput.value;
  const category = catInput.value.trim();

  if (!name || !sku || !category) {
    alert("Please fill in all required fields.");
    return;
  }

  const item = { name, sku, qty, unit, category };
  inventory.push(item);
  saveInventory();
  render();
  clearForm();
});


// Reset Form

resetBtn.addEventListener("click", clearForm);

function clearForm() {
  nameInput.value = "";
  skuInput.value = "";
  qtyInput.value = 0;
  catInput.value = "";
}


// Compute Stock Status

function getStatus(qty) {
  if (qty == 0) return "out";
  if (qty <= 10) return "low";
  return "in";
}


// Render Inventory (Table + Cards)

function render(filter = "all", searchQuery = "") {
  const tbody = document.querySelector("tbody");
  const cardGrid = document.querySelector(".cards-grid");

  tbody.innerHTML = "";
  cardGrid.innerHTML = "";

  let filtered = inventory;


  // Apply search filter

  if (searchQuery) {
    filtered = filtered.filter(i =>
      i.name.toLowerCase().includes(searchQuery) ||
      i.sku.toLowerCase().includes(searchQuery) ||
      i.category.toLowerCase().includes(searchQuery)
    );
  }


  // Apply button filters

  if (filter === "low") filtered = filtered.filter(i => getStatus(i.qty) === "low");
  if (filter === "out") filtered = filtered.filter(i => getStatus(i.qty) === "out");

  filtered.forEach((item, index) => {
    const status = getStatus(item.qty);
    const badgeClass =
      status === "in" ? "in-stock" :
      status === "low" ? "low-stock" :
      "out-stock";

    const statusText =
      status === "in" ? "In Stock" :
      status === "low" ? "Low Stock" : 
      "Out of Stock";


    // TABLE ROW

    tbody.innerHTML += `
      <tr>
        <td>${item.name}</td>
        <td>${item.sku}</td>
        <td>${item.category}</td>
        <td>${item.qty} ${item.unit}</td>
        <td><span class="badge ${badgeClass}">${statusText}</span></td>
        <td class="actions">
          <button onclick="deleteItem(${index})">🗑️</button>
        </td>
      </tr>
    `;


    // CARD VIEW

    const color =
      status === "in" ? "var(--accent-1)" :
      status === "low" ? "#a16a00" : "#922626";

    const statLabel =
      status === "in" ? "In Stock" :
      status === "low" ? "Low Stock" :
      "Out";

    cardGrid.innerHTML += `
      <div class="p-card">
        <h3>${item.name}</h3>
        <div class="meta">Code ${item.sku} • ${item.category}</div>
        <div style="margin-top:10px;font-weight:700;color:${color}">
          ${item.qty} ${item.unit} • ${statLabel}
        </div>
      </div>
    `;
  });

  updateStats();
}



// Update Stats (Total, Low, Out)

function updateStats() {
  const total = inventory.length;
  const low = inventory.filter(i => getStatus(i.qty) === "low").length;
  const out = inventory.filter(i => getStatus(i.qty) === "out").length;

  document.querySelector(".stats .stat:nth-child(1) .num").innerText = total;
  document.querySelector(".stats .stat:nth-child(2) .num").innerText = low;
  document.querySelector(".stats .stat:nth-child(3) .num").innerText = out;
}



// Delete Item


function deleteItem(index) {
  if (confirm("Remove this item from inventory?")) {
    inventory.splice(index, 1);
    saveInventory();
    render();
  }
}

window.deleteItem = deleteItem;


// Search Input Listener


searchInput.addEventListener("input", e => {
  const query = e.target.value.toLowerCase();
  render("all", query);
});


// Filter Buttons


document.querySelectorAll(".ghost").forEach((btn, idx) => {
  btn.addEventListener("click", () => {
    if (btn.textContent === "Low Stock") render("low");
    else if (btn.textContent === "Out of Stock") render("out");
    else render("all");
  });
});

