(function () {
        // --- Global Data Stores and State ---
        let activeAccountsData = [];
        let archivedAccountsData = [];
        let rolesData = [];
        let currentStatus = 'Active'; // 'Active' or 'Archived'

        // --- Utility Function for API Calls (Centralized Fetch Logic) ---
        async function fetchData(action, bodyData = {}) {
            const formData = new FormData();
            formData.append('action', action);
            for (const key in bodyData) {
                // Ensure null or undefined values are sent as empty strings or handled correctly
                formData.append(key, bodyData[key] === null ? '' : bodyData[key]);
            }
            
            try {
                const response = await fetch('../api/account_api.php', {
                    method: 'POST',
                    body: formData,
                });
                const data = await response.json();
                
                if (!data.success) {
                    throw new Error(data.error || data.message || `API call failed for action: ${action}`);
                }
                return data;
            } catch (error) {
                console.error("API Fetch Error:", error.message);
                alert(`Error: ${error.message}`);
                return null;
            }
        }

        // --- Data Loading Functions ---

        async function LoadRoles() {
            const data = await fetchData('get_roles');
            if (data) {
                rolesData = data.data;
                const cmbRole = document.getElementById('cmbRole');
                const cmbFilter = document.getElementById('cmbFilter');
                
                // Clear existing options
                cmbRole.innerHTML = '';
                cmbFilter.innerHTML = '<option value="">All Roles</option>';
                
                rolesData.forEach(role => {
                    const optionRole = document.createElement('option');
                    optionRole.value = role.role_id;
                    optionRole.textContent = role.role_name;
                    cmbRole.appendChild(optionRole);

                    const optionFilter = optionRole.cloneNode(true);
                    cmbFilter.appendChild(optionFilter);
                });
            }
        }

        async function LoadAccounts() {
            const data = await fetchData('get_accounts_by_status', { status: 'Active' });
            if (data) {
                activeAccountsData = data.accounts;
                if (currentStatus === 'Active') {
                    PerformSearchAndFilter();
                }
            }
        }
        
        async function LoadArchivedAccounts() {
            const data = await fetchData('get_accounts_by_status', { status: 'Archived' });
            if (data) {
                archivedAccountsData = data.accounts;
                if (currentStatus === 'Archived') {
                    PerformSearchAndFilter();
                }
            }
        }
        
        // --- UI Rendering Functions ---

        function renderDataGrid(gridElement, data) {
            let html = `<thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead><tbody>`;

            if (data.length === 0) {
                html += `<tr><td colspan="6" style="text-align:center;">No accounts found.</td></tr>`;
            } else {
                data.forEach(account => {
                    const fullName = `${account.firstname} ${account.middlename ? account.middlename + ' ' : ''}${account.surname}`;
                    const actionButton = account.status === 'Active'
                        ? `<button class="btn-archive" data-account-id="${account.account_id}" data-action="Archive">Archive</button>`
                        : `<button class="btn-activate" data-account-id="${account.account_id}" data-action="Activate">Activate</button>`;

                    const editButton = `<button class="btn-edit" data-account-id="${account.account_id}" data-action="Edit">Edit</button>`;

                    html += `
                        <tr>
                            <td>${account.account_id}</td>
                            <td>${fullName}</td>
                            <td>${account.username}</td>
                            <td>${account.role_name}</td>
                            <td>${account.status}</td>
                            <td>${editButton} ${actionButton}</td>
                        </tr>
                    `;
                });
            }

            html += '</tbody>';
            gridElement.innerHTML = html;
        }
        
        // --- Filtering and Search Logic ---

        function applyFilterAndSearch(data) {
            const searchTerm = document.getElementById('txtSearch').value.toLowerCase();
            const filterRole = document.getElementById('cmbFilter').value; // Role ID or empty string

            return data.filter(account => {
                const fullName = `${account.firstname} ${account.middlename || ''} ${account.surname}`.toLowerCase();
                const roleIdMatch = filterRole === '' || String(account.role_id) === filterRole;
                
                const searchMatch = 
                    fullName.includes(searchTerm) ||
                    account.username.toLowerCase().includes(searchTerm) ||
                    account.role_name.toLowerCase().includes(searchTerm);
                
                return roleIdMatch && searchMatch;
            });
        }
        
        function PerformSearchAndFilter() {
            const activeGrid = document.getElementById('dataGridActive');
            const archivedGrid = document.getElementById('dataGridArchived');

            if (currentStatus === 'Active') {
                const filteredData = applyFilterAndSearch(activeAccountsData);
                renderDataGrid(activeGrid, filteredData);
            } else {
                const filteredData = applyFilterAndSearch(archivedAccountsData);
                renderDataGrid(archivedGrid, filteredData);
            }
        }
        
        // --- Grid Action Handlers (Edit, Archive, Activate) ---

        // Use event delegation on the grid container
        document.querySelector('.grid-container').addEventListener('click', async (event) => {
            const target = event.target;
            const accountId = target.getAttribute('data-account-id');
            const action = target.getAttribute('data-action');

            if (!accountId || !action) return;

            if (action === 'Archive' || action === 'Activate') {
                const newStatus = action === 'Archive' ? 'Archived' : 'Active';
                if (confirm(`Are you sure you want to ${action} account ID ${accountId}?`)) {
                    await UpdateAccountStatus(accountId, newStatus);
                }
            } else if (action === 'Edit') {
                // Placeholder for Edit logic (C#: Show UpdateAccountForm)
                alert(`Action: Edit account ID ${accountId}. (Functionality not yet built)`);
            }
        });
        
        async function UpdateAccountStatus(accountId, newStatus) {
            const data = await fetchData('update_account_status', {
                accountId: accountId,
                status: newStatus
            });
            
            if (data && data.success) {
                alert(data.message);
                // Reload both grids and re-render the current view
                await LoadAccounts();
                await LoadArchivedAccounts();
            }
        }

        // --- Add Account Form Logic ---
        
        function clearAddForm() {
            document.getElementById('txtFirstname').value = '';
            document.getElementById('txtMiddlename').value = '';
            document.getElementById('txtSurname').value = '';
            document.getElementById('cmbRole').selectedIndex = 0;
            document.getElementById('txtUsername').value = '';
            document.getElementById('txtPassword').value = '';
            document.getElementById('txtConfirmPassword').value = '';
        }

                // --- Modal Control Functions (Ensure these are defined) ---
        // This function needs to be accessible globally or defined within the IIFE.
        function closeRoleModal() {
            // This is the function linked to the button and 'btnCancelRole'
            document.getElementById('role-modal-overlay').style.display = 'none';
            document.getElementById('txtRoleName').value = ''; 
            // Add logic here to clear module checkboxes if necessary
        }

        function openRoleModal() {
            // This function handles displaying the modal
            document.getElementById('role-modal-overlay').style.display = 'flex';
            // Add logic here to load modules list if necessary
        }

        document.getElementById('btnAddAccount').addEventListener('click', () => {
            document.getElementById('pnlAddAccount').style.display = 'block';
            clearAddForm();
        });

        document.getElementById('btnCancelAdd').addEventListener('click', () => {
            document.getElementById('pnlAddAccount').style.display = 'none';
        });

        document.getElementById('btnSaveAccount').addEventListener('click', async () => {
            const firstname = document.getElementById('txtFirstname').value.trim();
            const middlename = document.getElementById('txtMiddlename').value.trim() || null;
            const surname = document.getElementById('txtSurname').value.trim();
            const roleId = document.getElementById('cmbRole').value;
            const username = document.getElementById('txtUsername').value.trim();
            const password = document.getElementById('txtPassword').value;
            const confirmPassword = document.getElementById('txtConfirmPassword').value;

            if (!firstname || !surname || !roleId || !username || !password || !confirmPassword) {
                alert("Please fill in all required fields.");
                return;
            }

            if (password !== confirmPassword) {
                alert("Password and Confirm Password do not match!");
                return;
            }
            
            if (password.length < 6) {
                 alert("Password must be at least 6 characters long.");
                return;
            }

            // 1. Check if Username Exists
            const check = await fetchData('check_username', { username: username });
            if (check && check.exists) {
                alert("Username already exists. Please choose a different one.");
                return;
            }

            // 2. Add Account
            const body = {
                firstname: firstname,
                middlename: middlename,
                surname: surname,
                roleId: roleId,
                username: username,
                password: password,
            };

            const data = await fetchData('add_account', body);

            if (data && data.success) {
                alert(data.message);
                document.getElementById('pnlAddAccount').style.display = 'none';
                clearAddForm();
                // Reload data to update the grid
                await LoadAccounts();
            }
        });
        
        // --- Tab and Defaults Logic ---
        
        function defaults() {
            currentStatus = 'Active';
            // Set Active tab as default
            document.getElementById('lblActiveAccounts').classList.add('tab-active');
            document.getElementById('lblActiveAccounts').classList.remove('tab-inactive');
            document.getElementById('lblArchivedAccounts').classList.add('tab-inactive');
            document.getElementById('lblArchivedAccounts').classList.remove('tab-active');
            
            // Show/Hide grids
            document.getElementById('dataGridActive').style.display = 'table';
            document.getElementById('dataGridArchived').style.display = 'none';
            
            // Clear search/filter on module load
            document.getElementById('txtSearch').value = '';
            document.getElementById('cmbFilter').selectedIndex = 0;
            
            PerformSearchAndFilter();
        }

        document.getElementById('lblActiveAccounts').addEventListener('click', () => {
            currentStatus = 'Active';
            document.getElementById('dataGridActive').style.display = 'table';
            document.getElementById('dataGridArchived').style.display = 'none';
            document.getElementById('lblActiveAccounts').classList.add('tab-active');
            document.getElementById('lblActiveAccounts').classList.remove('tab-inactive');
            document.getElementById('lblArchivedAccounts').classList.add('tab-inactive');
            document.getElementById('lblArchivedAccounts').classList.remove('tab-active');
            
            PerformSearchAndFilter();
        });

        document.getElementById('lblArchivedAccounts').addEventListener('click', () => {
            currentStatus = 'Archived';
            document.getElementById('dataGridActive').style.display = 'none';
            document.getElementById('dataGridArchived').style.display = 'table';
            document.getElementById('lblArchivedAccounts').classList.add('tab-active');
            document.getElementById('lblArchivedAccounts').classList.remove('tab-inactive');
            document.getElementById('lblActiveAccounts').classList.add('tab-inactive');
            document.getElementById('lblActiveAccounts').classList.remove('tab-active');
            
            PerformSearchAndFilter();
        });
        
        // --- Event Listeners for Controls ---
        
        // Search and filter events
        document.getElementById('txtSearch').addEventListener('input', PerformSearchAndFilter);
        document.getElementById('cmbFilter').addEventListener('change', PerformSearchAndFilter);
        
        // Placeholder for Add Role
        document.getElementById('btnAddrole').addEventListener('click', () => {
            
        });


        // --- Component Initialization ---
        async function AccountMaintenance_Load() {
            // Load all necessary data asynchronously
            await LoadRoles(); 
            await LoadAccounts();
            await LoadArchivedAccounts();
            
            // Set the initial view
            defaults(); 
        }
               
        
        // Run the initialization function when the script is loaded/executed
        AccountMaintenance_Load();
    })();