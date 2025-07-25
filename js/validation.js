function validateForm() {
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const mob = document.getElementById('mob').value;
    const password = document.getElementById('password').value;
    const cpassword = document.getElementById('cpassword').value;
    const photo_data = document.getElementById('photo_data').value;

    // Name validation
    if (!/^[a-zA-Z\s]{3,30}$/.test(name)) {
        alert('Name must be 3-30 characters long and contain only letters');
        return false;
    }

    // Email validation
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        alert('Please enter a valid email address');
        return false;
    }

    // Mobile number validation
    if (!/^\d{10}$/.test(mob)) {
        alert('Please enter a valid 10-digit mobile number');
        return false;
    }

    // Password validation
    if (password.length < 8) {
        alert('Password must be at least 8 characters long');
        return false;
    }

    if (password !== cpassword) {
        alert('Passwords do not match');
        return false;
    }

    // Photo validation
    if (!photo_data) {
        alert('Please capture or upload a photo');
        return false;
    }

    return true;
}