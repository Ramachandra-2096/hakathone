function validateForm() {
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirm_Password").value;

    if (password !== confirmPassword) {
        alert("Passwords do not match!");
        return false;
    }
    return true;
}