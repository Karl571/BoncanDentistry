document.addEventListener('DOMContentLoaded', () => {
    //const lblTime = document.getElementById('lblTime');
    //const lblDate = document.getElementById('lblDate');
    const loginForm = document.getElementById('login-form');
    //const lblCancel = document.getElementById('lblCancel');

    // --- Timer Tick Equivalent (Updates time and date) ---
   /* function updateClock() {
        const now = new Date();
        
        // Time format: hh:mm:ss tt (e.g., 03:30:00 PM)
        const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
        lblTime.textContent = now.toLocaleTimeString('en-US', timeOptions);
        
        // Date format: dddd, MMMM dd yyyy (e.g., Tuesday, January 10 2024)
        const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: '2-digit' };
        lblDate.textContent = now.toLocaleDateString('en-US', dateOptions);
    }

    // Start the timer
    setInterval(updateClock, 1000);
    updateClock(); // Call immediately to avoid a blank display
*/
    // --- btnLogin_Click Equivalent (Handles form submission) ---
    loginForm.addEventListener('submit', async (event) => {
        event.preventDefault(); // Prevent default form submission
        
        const username = document.getElementById('txtUsername').value;
        const password = document.getElementById('txtPassword').value;

        // The empty check is largely redundant since input fields are 'required' in HTML,
        // but it's good practice for client-side validation.
        if (!username || !password) {
            alert("Please enter both username and password.");
            return;
        }
        
        // Prepare data for the PHP script
        const formData = new URLSearchParams();
        formData.append('username', username);
        formData.append('password', password);

        try {
            const response = await fetch('login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: formData
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                // Handle non-200 responses (401 Unauthorized, 500 Server Error)
                alert(data.message || "An unknown login error occurred.");
                return;
            }
            
            // Login Successful - C# equivalent of showing admin_vw
            alert("Login Successful! Redirecting...");
            
            // Store user data (Fullname, RoleId, RoleName) in localStorage or sessionStorage 
            // so admin_vw.html can retrieve it.
            sessionStorage.setItem('user', JSON.stringify(data.user));

            // Redirect to the main admin view
            const roleId = data.user.RoleId;

if (roleId == 1) {
    window.location.href = 'modules/admin_vw.html';
}
else if (roleId == 23) {
    window.location.href = 'customer modules/patient_dashboard.php';
}
else {
    alert("Unknown user role.");
}


        } catch (error) {
            console.error("Network or processing error during login:", error);
            alert("Connection error. Could not reach the server.");
        }
    });
    
    // --- lblCancel_Click Equivalent ---
    lblCancel.addEventListener('click', (e) => {
        e.preventDefault();
        if (confirm("Are you sure you want to exit the application?")) {
            // In a web environment, exiting typically means closing the tab or redirecting.
            alert("Exiting application.");
            window.close(); // Tries to close the window/tab (often blocked by browser)
            // Or, redirect back to a welcome/landing page
            // window.location.href = 'index.html'; 
        }
    });

});