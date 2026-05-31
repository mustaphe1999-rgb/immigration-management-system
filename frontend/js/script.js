/**
 * Immigration Management System - Frontend JavaScript
 */

const API_BASE_URL = 'http://localhost/immigration-system/api/';

/**
 * Show Alert Message
 */
function showAlert(message, type = 'info') {
    const alertElement = document.getElementById('alertMessage');
    alertElement.className = `alert ${type}`;
    alertElement.textContent = message;
    alertElement.style.display = 'block';
    
    setTimeout(() => {
        alertElement.style.display = 'none';
    }, 5000);
}

/**
 * Scroll to Section
 */
function scrollToSection(sectionId) {
    const section = document.getElementById(sectionId);
    if (section) {
        section.scrollIntoView({ behavior: 'smooth' });
    }
}

/**
 * Registration Form Handler
 */
document.getElementById('registrationForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData);
    
    try {
        const response = await fetch(API_BASE_URL + 'applicants.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert('Registration successful! Applicant ID: ' + result.applicant_id, 'success');
            e.target.reset();
        } else {
            showAlert(result.message || 'Registration failed', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('An error occurred: ' + error.message, 'error');
    }
});

/**
 * Login Form Handler
 */
document.getElementById('loginForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData);
    
    try {
        const response = await fetch(API_BASE_URL + 'auth.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert('Login successful!', 'success');
            localStorage.setItem('user', JSON.stringify(result.user));
            setTimeout(() => {
                window.location.href = 'dashboard.html';
            }, 1500);
        } else {
            showAlert(result.message || 'Login failed', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('An error occurred: ' + error.message, 'error');
    }
});

/**
 * Fetch All Applicants
 */
async function fetchApplicants(limit = 10, offset = 0) {
    try {
        const response = await fetch(`${API_BASE_URL}applicants.php?all=true&limit=${limit}&offset=${offset}`);
        const result = await response.json();
        
        if (result.success) {
            return result.data;
        } else {
            showAlert(result.message || 'Failed to fetch applicants', 'error');
            return [];
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('An error occurred: ' + error.message, 'error');
        return [];
    }
}

/**
 * Search Applicants
 */
async function searchApplicants(searchTerm) {
    try {
        const response = await fetch(`${API_BASE_URL}applicants.php?search=${encodeURIComponent(searchTerm)}`);
        const result = await response.json();
        
        if (result.success) {
            return result.data;
        } else {
            showAlert(result.message || 'Search failed', 'error');
            return [];
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('An error occurred: ' + error.message, 'error');
        return [];
    }
}

/**
 * Get Applicant by ID
 */
async function getApplicant(applicantId) {
    try {
        const response = await fetch(`${API_BASE_URL}applicants.php?id=${applicantId}`);
        const result = await response.json();
        
        if (result.success) {
            return result.data;
        } else {
            showAlert(result.message || 'Failed to fetch applicant', 'error');
            return null;
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('An error occurred: ' + error.message, 'error');
        return null;
    }
}

/**
 * Update Applicant
 */
async function updateApplicant(applicantId, data) {
    try {
        const response = await fetch(API_BASE_URL + 'applicants.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                applicant_id: applicantId,
                ...data
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert('Applicant updated successfully', 'success');
            return result.data;
        } else {
            showAlert(result.message || 'Update failed', 'error');
            return null;
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('An error occurred: ' + error.message, 'error');
        return null;
    }
}

/**
 * Delete Applicant
 */
async function deleteApplicant(applicantId) {
    if (confirm('Are you sure you want to delete this applicant?')) {
        try {
            const response = await fetch(`${API_BASE_URL}applicants.php?id=${applicantId}`, {
                method: 'DELETE'
            });
            
            const result = await response.json();
            
            if (result.success) {
                showAlert('Applicant deleted successfully', 'success');
                return true;
            } else {
                showAlert(result.message || 'Deletion failed', 'error');
                return false;
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('An error occurred: ' + error.message, 'error');
            return false;
        }
    }
    return false;
}

/**
 * Get Stored User from LocalStorage
 */
function getStoredUser() {
    const userJSON = localStorage.getItem('user');
    return userJSON ? JSON.parse(userJSON) : null;
}

/**
 * Logout User
 */
function logout() {
    localStorage.removeItem('user');
    window.location.href = 'index.html';
}

/**
 * Initialize Page
 */
document.addEventListener('DOMContentLoaded', () => {
    console.log('Immigration Management System loaded');
});