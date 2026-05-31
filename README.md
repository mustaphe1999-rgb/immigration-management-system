# Immigration Management System

A comprehensive digital solution for managing immigration processes, visa applications, and border control operations.

## 📋 Project Overview

This system streamlines immigration management by providing:
- Digital applicant registration and verification
- Passport management and validation
- Visa application processing
- Border entry/exit tracking
- Role-based access control
- Comprehensive reporting and analytics
- Secure audit logging

## 🏗️ System Architecture

### Database Design
The system uses **MySQL** with the following main tables:
- `users` - System administrators and officers
- `applicants` - Registered travelers and immigrants
- `passports` - Passport records with validation
- `visa_applications` - Visa processing records
- `border_records` - Entry/exit logs
- `audit_logs` - System activity tracking
- `reports` - Generated reports

### Tech Stack

**Frontend:**
- HTML5
- CSS3
- JavaScript (Vanilla)

**Backend:**
- PHP 7.4+
- MySQL 5.7+
- Apache Server

**Server Setup:**
- XAMPP or Apache + MySQL

## 📁 Project Structure

```
immigration-management-system/
├── database/
│   └── schema.sql              # Database schema
├── backend/
│   ├── config/
│   │   └── config.php         # Configuration file
│   ├── classes/
│   │   ├── Database.php       # Database operations
│   │   ├── User.php           # User authentication
│   │   └── Applicant.php      # Applicant management
│   └── api/
│       └── applicants.php     # Applicant endpoints
├── frontend/
│   ├── index.html             # Homepage
│   ├── css/
│   │   └── style.css          # Styling
│   └── js/
│       └── script.js          # Frontend logic
└── README.md
```

## 🚀 Getting Started

### Prerequisites
- XAMPP, WAMP, or LAMP stack
- MySQL 5.7+
- PHP 7.4+
- Modern web browser

### Installation Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/mustaphe1999-rgb/immigration-management-system.git
   cd immigration-management-system
   ```

2. **Setup Database**
   - Open phpMyAdmin
   - Create new database: `immigration_db`
   - Import `database/schema.sql`

3. **Configure Backend**
   - Update `backend/config/config.php` with your database credentials
   - Set JWT_SECRET to a secure value

4. **Place Files in Web Server**
   - Copy project to `htdocs` (XAMPP) or `www` (WAMP)
   - Example: `C:/xampp/htdocs/immigration-system/`

5. **Access the Application**
   - Open browser: `http://localhost/immigration-system/`

## 📝 API Endpoints

### Applicants
- `GET /api/applicants.php?id={id}` - Get applicant details
- `GET /api/applicants.php?search={term}` - Search applicants
- `GET /api/applicants.php?all=true` - Get all applicants
- `POST /api/applicants.php` - Register new applicant
- `PUT /api/applicants.php` - Update applicant
- `DELETE /api/applicants.php?id={id}` - Delete applicant

## 👥 User Roles

1. **Administrator**
   - Full system access
   - User management
   - System configuration

2. **Immigration Officer**
   - Manage applicants
   - Process visa applications
   - Verify documents

3. **Border Control Officer**
   - Log entries and exits
   - Verify travel documents
   - Track immigrant movements

## 🔒 Security Features

- Password encryption using bcrypt
- Role-based access control (RBAC)
- SQL injection prevention
- CORS headers configuration
- Audit logging for all operations
- Session timeout management

## 📈 Future Enhancements

- [ ] Biometric integration
- [ ] Online payment gateway
- [ ] Mobile application
- [ ] AI-based document verification
- [ ] Airport system integration
- [ ] Real-time notifications
- [ ] Advanced analytics dashboard
- [ ] Multi-language support

## 📞 Support

For issues or questions, create an issue on GitHub.

## 📄 License

This project is licensed under the MIT License.

## 👨‍💻 Author

- Mustaphe Ali (@mustaphe1999-rgb)

---

**Last Updated:** May 31, 2026  
**Version:** 1.0.0