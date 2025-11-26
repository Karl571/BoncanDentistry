// =====================================================================
// *** GLOBAL MODAL CONSTANT AND FUNCTIONS (OUTSIDE DOMContentLoaded) ***
// =====================================================================

// Global modal content for injection
const ROLE_MODAL_HTML = `
<div id="role-modal-overlay" class="modal-overlay">
    <div id="role-maintenance-modal" class="modal-content">
        <span class="close-btn" onclick="closeRoleModal()">❌</span>
        <h2>🔑 Add New Role</h2>
        
        <div class="form-group">
            <label for="txtRoleName">Role Name</label>
            <input type="text" id="txtRoleName" placeholder="e.g., Sales Manager, Administrator" required>
        </div>

        <h3>Assign Modules (Access)</h3>
        <div class="module-list-container">
            <ul id="checkedListModules" class="checked-list">
                <li style="color:#999;">Loading modules...</li>
            </ul>
        </div>
        
        <div class="button-group-modal">
            <button id="btnSaveRole" class="btn-form-action btn-save">💾 Save Role and Access</button>
            <button id="btnCancelRole" class="btn-form-action btn-cancel" onclick="closeRoleModal()">❌ Cancel</button>
        </div>
    </div>
</div>`;

function openRoleModal() {
    // Inject the HTML structure into the main body
    document.body.insertAdjacentHTML('beforeend', ROLE_MODAL_HTML);

    // Initialize the module once the HTML is on the page
    initializeRoleMaintenance(); 
    
    // Attach close listener to the overlay
    document.getElementById('role-modal-overlay').addEventListener('click', (e) => {
        if (e.target.id === 'role-modal-overlay') {
            closeRoleModal();
        }
    });
}

function closeRoleModal() {
    const overlay = document.getElementById('role-modal-overlay');
    if (overlay) {
        overlay.remove();
    }
}

// C# Maintenance_Role.cs equivalent logic
function initializeRoleMaintenance() {
    const checkedListModules = document.getElementById('checkedListModules');
    const btnSave = document.getElementById('btnSaveRole');

    // Load modules upon modal opening
    async function loadModules() {
        checkedListModules.innerHTML = '<li style="color:#999;">Loading modules...</li>';
        try {
            const formData = new FormData();
            formData.append('action', 'get_modules');
            
            const response = await fetch('../api/role_api.php', { method: 'POST', body: formData });
            const data = await response.json();

            checkedListModules.innerHTML = '';
            
            if (data.success && data.data) {
                data.data.forEach(module => {
                    const li = document.createElement('li');
                    li.innerHTML = `
                        <input type="checkbox" id="mod-${module.module_id}" data-module-id="${module.module_id}">
                        <label for="mod-${module.module_id}">${module.module}</label>
                    `;
                    checkedListModules.appendChild(li);
                    // window.location.href = 'role_maintenance_modal.html';
                    
                });
            } else {
                checkedListModules.innerHTML = `<li style="color:red;">Error loading modules: ${data.message || data.error}</li>`;
            }
        } catch (error) {
            console.error("Error loading modules:", error);
            checkedListModules.innerHTML = '<li style="color:red;">Network or Server Error loading modules.</li>';
        }
    }

    // Save button click handler (btnSave_Click_1 equivalent)
    btnSave.addEventListener('click', async () => {
        const roleName = document.getElementById('txtRoleName').value.trim();
        const checkedBoxes = Array.from(checkedListModules.querySelectorAll('input[type="checkbox"]:checked'));
        const moduleIds = checkedBoxes.map(cb => cb.getAttribute('data-module-id'));

        if (!roleName) {
            alert("Please enter a role name.");
            return;
        }

        if (moduleIds.length === 0) {
            alert("Please select at least one module.");
            return;
        }

        // Disable button during AJAX call
        btnSave.disabled = true;
        btnSave.textContent = 'Saving...';

        try {
            const formData = new FormData();
            formData.append('action', 'save_role');
            formData.append('roleName', roleName);
            formData.append('moduleIds', JSON.stringify(moduleIds)); // Send IDs as a JSON array

            const response = await fetch('../api/role_api.php', { method: 'POST', body: formData });
            const data = await response.json();

            if (data.success) {
                alert(data.message);
                closeRoleModal();
                // Optional: Call a function here to reload the roles list on the main Account Maintenance grid
            } else {
                alert("Error: " + (data.message || data.error));
            }

        } catch (error) {
            console.error("Error saving role:", error);
            alert("Connection error while saving role.");
        } finally {
            btnSave.disabled = false;
            btnSave.textContent = '💾 Save Role and Access';
        }
    });

    loadModules();
}


// =====================================================================
// *** DOM CONTENT LOADED (MAIN APPLICATION LOGIC) ***
// =====================================================================

document.addEventListener('DOMContentLoaded', () => {
    // 1. Element References
    const roleIdInput = document.getElementById('role-id');
    const nameLabel = document.getElementById('name-lbl');
    const roleLabel = document.getElementById('role-lbl');
    const panel1 = document.getElementById('panel1');
    const viewPnl = document.getElementById('view-pnl');
    const btnLogout = document.getElementById('btn-logout');

    const defaultColor = 'var(--default-color)';
    const activeColor = 'var(--active-color)';

    // --- NEW LOGIC: RETRIEVE USER DATA FROM SESSION ---
    let currentUser = null;
    const sessionUser = sessionStorage.getItem('user'); // Data stored by login.js
    
    if (sessionUser) {
        try {
            currentUser = JSON.parse(sessionUser);
            
            // 1. Populate Header Labels
            nameLabel.textContent = currentUser.FullName || 'User';
            roleLabel.textContent = currentUser.RoleName || 'Guest';
            
            // 2. Set the Role ID into the hidden input (CRITICAL for fetching modules)
            roleIdInput.value = currentUser.RoleId;
            
        } catch (e) {
            console.error("Error parsing session data:", e);
            alert("Session error. Please log in again.");
            // If session data is corrupted, redirect to login
            window.location.href = 'login.html'; 
        }
    } else {
        // If no user data is found, redirect to login page
        alert("Session expired or invalid. Please log in.");
        window.location.href = 'login.html'; 
        return; // Stop execution
    }

    // --- Helper function to load the actual HTML content (Updated) ---
    async function loadModuleContent(moduleName) {
        // Clear existing content
        viewPnl.innerHTML = '<div class="loading-text">Loading ' + moduleName + '...</div>';
        
        // Use a 50ms delay to simulate network latency
        await new Promise(resolve => setTimeout(resolve, 50)); 
        
        let contentHTML = '';
        
        // Logic to determine which HTML to load DITO MAGLALAGAY NG MODULE NA TATAWAGIN KAPAG EXISTING NA
        if (moduleName === "Dashboard" ) {
            // Load the HTML content for Account Maintenance
            try {
                const response = await fetch('dashboard.html');
                if (response.ok) {
                    contentHTML = await response.text();
                } else {
                    throw new Error(`Failed to load: ${response.statusText}`);
                }
            } catch (error) {
                console.error(`Error loading module ${moduleName}:`, error);
                contentHTML = `<div style="color:red;padding:10px;">Error loading ${moduleName}: ${error.message}</div>`;
            }
        } 
        
        else if (moduleName.includes("Account Maintenance")) {
            // Load the HTML content for Account Maintenance
            try {
                const response = await fetch('account_maintenance.html');
                if (response.ok) {
                    contentHTML = await response.text();
                } else {
                    throw new Error(`Failed to load: ${response.statusText}`);
                }
            } catch (error) {
                console.error(`Error loading module ${moduleName}:`, error);
                contentHTML = `<div style="color:red;padding:10px;">Error loading ${moduleName}: ${error.message}</div>`;
            }
        } 
        
        else if (moduleName === "Product Maintenance") {
            // Load the HTML content for Product Maintenance
            try {
                const response = await fetch('product_maintenance.html');
                if (response.ok) {
                    contentHTML = await response.text();
                } else {
                    throw new Error(`Failed to load: ${response.statusText}`);
                }
            } catch (error) {
                console.error(`Error loading module ${moduleName}:`, error);
                contentHTML = `<div style="color:red;padding:10px;">Error loading ${moduleName}: ${error.message}</div>`;
            }
        }
        else if (moduleName === "Service Maintenance") {
            // Load the HTML content for Account Maintenance
            try {
                const response = await fetch('service_maintenance.html');
                if (response.ok) {
                    contentHTML = await response.text();
                } else {
                    throw new Error(`Failed to load: ${response.statusText}`);
                }
            } catch (error) {
                console.error(`Error loading module ${moduleName}:`, error);
                contentHTML = `<div style="color:red;padding:10px;">Error loading ${moduleName}: ${error.message}</div>`;
            }
        }
        else if (moduleName === "Product Stocking") {
            // Load the HTML content for Account Maintenance
            try {
                const response = await fetch('prod_stockling.html');
                if (response.ok) {
                    contentHTML = await response.text();
                } else {
                    throw new Error(`Failed to load: ${response.statusText}`);
                }
            } catch (error) {
                console.error(`Error loading module ${moduleName}:`, error);
                contentHTML = `<div style="color:red;padding:10px;">Error loading ${moduleName}: ${error.message}</div>`;
            }
        }
        else if (moduleName === "Reports") {
            // Load the HTML content for Account Maintenance
            try {
                const response = await fetch('reports.html');
                if (response.ok) {
                    contentHTML = await response.text();
                } else {
                    throw new Error(`Failed to load: ${response.statusText}`);
                }
            } catch (error) {
                console.error(`Error loading module ${moduleName}:`, error);
                contentHTML = `<div style="color:red;padding:10px;">Error loading ${moduleName}: ${error.message}</div>`;
            }
        }
         else if (moduleName === "Customer Dashboard") {
            // Load the HTML content for Account Maintenance
            try {
                const response = await fetch('patient_dashboard.html');
                if (response.ok) {
                    contentHTML = await response.text();
                } else {
                    throw new Error(`Failed to load: ${response.statusText}`);
                }
            } catch (error) {
                console.error(`Error loading module ${moduleName}:`, error);
                contentHTML = `<div style="color:red;padding:10px;">Error loading ${moduleName}: ${error.message}</div>`;
            }
        }
       else if (moduleName.includes("Customer Dashboard") || moduleName.includes("Patient Dashboard")) {
    try {
        const response = await fetch('patient_dashboard.html');
        if (response.ok) contentHTML = await response.text();
        else throw new Error(response.statusText);
    } catch (error) {
        contentHTML = `<div style="color:red;padding:10px;">Error loading ${moduleName}: ${error.message}</div>`;
    }
}
else if (moduleName.includes("Customer Appointment") || moduleName.includes("Appointment")) {
    try {
        const response = await fetch('appointment.html');
        if (response.ok) contentHTML = await response.text();
        else throw new Error(response.statusText);
    } catch (error) {
        contentHTML = `<div style="color:red;padding:10px;">Error loading ${moduleName}: ${error.message}</div>`;
    }
}
else if (moduleName.includes("Customer PatientRecord") || moduleName.includes("Patient")) {
    try {
        const response = await fetch('patient_record.html');
        if (response.ok) contentHTML = await response.text();
        else throw new Error(response.statusText);
    } catch (error) {
        contentHTML = `<div style="color:red;padding:10px;">Error loading ${moduleName}: ${error.message}</div>`;
    }
}
else if (moduleName.includes("Customer Service") || moduleName.includes("Service")) {
    try {
        const response = await fetch('service.html');
        if (response.ok) contentHTML = await response.text();
        else throw new Error(response.statusText);
    } catch (error) {
        contentHTML = `<div style="color:red;padding:10px;">Error loading ${moduleName}: ${error.message}</div>`;
    }
}
else if (moduleName.includes("Customer Transaction") || moduleName.includes("Transaction")) {
    try {
        const response = await fetch('transaction.html');
        if (response.ok) contentHTML = await response.text();
        else throw new Error(response.statusText);
    } catch (error) {
        contentHTML = `<div style="color:red;padding:10px;">Error loading ${moduleName}: ${error.message}</div>`;
    }
}
else if (moduleName.includes("Customer Profile") || moduleName.includes("Profile")) {
    try {
        const response = await fetch('profile.html');
        if (response.ok) contentHTML = await response.text();
        else throw new Error(response.statusText);
    } catch (error) {
        contentHTML = `<div style="color:red;padding:10px;">Error loading ${moduleName}: ${error.message}</div>`;
    }
}

        
        // Add more module loading logic here as needed...
        else {
            contentHTML = `<div class="module-content"><h2>${moduleName}</h2><p>Module content placeholder.</p></div>`;
        }

        // Display the loaded content
        viewPnl.innerHTML = contentHTML;

        // Execute any scripts contained within the loaded HTML (e.g., account_maintenance.html's <script>)
        Array.from(viewPnl.querySelectorAll("script")).forEach(oldScript => {
            const newScript = document.createElement("script");
            Array.from(oldScript.attributes)
                .forEach(attr => newScript.setAttribute(attr.name, attr.value));
            newScript.appendChild(document.createTextNode(oldScript.innerHTML));
            oldScript.parentNode.replaceChild(newScript, oldScript);
        });
        
        // *** NEW HOOK: Initialize the loaded module's custom scripts/listeners ***
        if (moduleName.includes("Account Maintenance")) {
            // This MUST be called AFTER the HTML is placed and its inline script is executed
            initializeAccountMaintenance(); 
        }

        // Update active button state
        panel1.querySelectorAll('button').forEach(btn => {
            btn.style.backgroundColor = defaultColor;
            btn.classList.remove('active');
        });

        // Find and set the active button
        const activeButton = Array.from(panel1.querySelectorAll('button')).find(
            btn => btn.textContent.trim() === moduleName
        );
        if (activeButton) {
            activeButton.style.backgroundColor = activeColor;
            activeButton.classList.add('active');
        }
    }

    // --- NEW FUNCTION: Account Maintenance Initialization (Hooks up Add Role button) ---
    function initializeAccountMaintenance() {
        console.log("Initializing Account Maintenance event listeners...");
        
        // Find the Add Role button from the loaded HTML content
        const btnAddrole = document.getElementById('btnAddrole');
        
        if (btnAddrole) {
            // Hook up the C# btnAddrole_Click equivalent
            btnAddrole.addEventListener('click', () => {
                openRoleModal(); // Call the global modal function
            });
        } else {
            console.error("#btnAddrole not found after loading account_maintenance.html");
        }
        
        // Other Account Maintenance listeners (e.g., LoadAccounts, filtering) would go here
    }

    // --- Helper function for group headers ---
    function createGroupHeader(groupName) {
        const header = document.createElement('div');
        header.className = 'group-header'; // You'll need CSS for this
        header.textContent = groupName;
        return header;
    }

    // --- Helper function for modules (buttons) ---
    function createModuleButton(module) {
        const button = document.createElement('button');
        button.textContent = module.module;
        button.setAttribute('data-module-id', module.module_id);
        button.addEventListener('click', () => {
            loadModuleContent(module.module);
        });
        return button;
    }

    // --- Helper function to add a group and its modules ---
    function addGroup(groupName, moduleRows) {
        // Only add the group if there are modules in it
        if (moduleRows.length > 0) {
            panel1.appendChild(createGroupHeader(groupName));
            moduleRows.forEach(module => {
                panel1.appendChild(createModuleButton(module));
            });
        }
    }

    // --- populatenavi (Main function for sidebar modules) ---
    async function populatenavi(roleId) {
        panel1.innerHTML = '<div class="loading-text">Fetching modules...</div>';
        
        try {
            const response = await fetch('../api/getmodule.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `role_id=${roleId}`,
            });

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.error || 'Failed to retrieve modules.');
            }

            // Clear loading text
            panel1.innerHTML = ''; 

            const analyticsRows = [];
            const transactionRows = [];
            const inventoryRows = [];
            const servicesRows = [];
            const maintenanceRows = [];
            const otherRows = [];
            const customerRows = [];

            // Grouping logic (Based on module name)
            data.modules.forEach(row => {
                if (row.module.includes("Reports") || row.module === "Dashboard") {
                    analyticsRows.push(row);
                } else if (row.module.includes("Maintenance")) {
                    maintenanceRows.push(row);
                }else if (row.module.startsWith("Customer") || row.module.includes("Customer")) { 
                 customerRows.push(row);   
                }else if (row.module.includes("Doctor")||row.module.includes("Transaction")|| row.module.includes("Request")|| row.module.includes("Logs")) {
                    transactionRows.push(row);
                } else if (row.module.includes("Stocking")) {
                    inventoryRows.push(row);
                }else { 
                    otherRows.push(row);
                }
            });

            // Add groups in desired order
            addGroup("Analytics", analyticsRows);
            addGroup("Maintenance", maintenanceRows);
            addGroup("Inventory", inventoryRows);
            addGroup("Customer", customerRows);
            addGroup("Transaction", transactionRows); 
            addGroup("Other", otherRows);

            // Click the first button (mimics C# PerformClick)
            const firstButton = panel1.querySelector('button');
            if (firstButton) {
                firstButton.click();
            }

        } catch (error) {
            console.error("Error fetching modules:", error);
            panel1.innerHTML = `<div style="color:red;padding:10px;">Error fetching modules: ${error.message || 'Check PHP/Connection configuration.'}</div>`;
        }
    }

    // --- Initial Load (admin_vw_Load) ---
    const finalRoleId = parseInt(roleIdInput.value);
    
    if (!isNaN(finalRoleId) && finalRoleId > 0) {
        populatenavi(finalRoleId); // Start the module fetch
    } else {
        alert("Invalid Role ID found in session. Redirecting to login.");
        window.location.href = '../login.html';
    }

    // --- Logout Handler (btnLogout_Click) ---
    btnLogout.addEventListener('click', () => {
        const userName = nameLabel.textContent;
        if (confirm(`Are you sure you want to log out ${userName}?`)) {
            // Clear session data and redirect to login
            sessionStorage.removeItem('user');
            alert("Logging out and returning to login page.");
            window.location.href = '../index.php'; 
        }
    });
    z
});