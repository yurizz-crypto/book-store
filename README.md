# PageTurner (Book Store)

This repository contains the third activity for the PageTurner project, focusing on a Book Store management system. Included in this repository is an exported PostgreSQL database dump to quickly set up the application with pre-existing tables and seeded test data.

## 🚀 Setup Instructions

Follow these steps to get the project and database running locally:

### 1. Prerequisites
- **PostgreSQL** (v12 or higher)
- **pgAdmin 4** (for database management)
- Relevant backend dependencies (e.g., Node.js/PHP)

### 2. Database Setup & Import
Since the database schema and seeded data are provided as a `.sql` dump file, follow these steps to import it:
1. Open **pgAdmin 4** and create a new database named `pageturner_db` (or your preferred name).
2. Right-click the newly created database in the left sidebar and select **Restore...**.
3. In the **Filename** field, click the folder icon, locate, and select the `.sql` dump file included with this.
4. Click **Restore** to import all the tables and the seeded data.

### 3. Environment Configuration
1. Create a `.env` file in the root directory based on your `.env.example` file.
2. Update the database credentials to match your local PostgreSQL setup:
   ```env
   DB_HOST=localhost
   DB_USER=your_postgres_username
   DB_PASSWORD=your_postgres_password
   DB_NAME=pageturner_db
   DB_PORT=5432

### 3. Installation

Clone the repository and install the necessary dependencies:
    git clone [https://github.com/yurizz-crypto/book-store.git](https://github.com/yurizz-crypto/book-store.git)
    cd book-store
    run [npm install]

### 4. Running the Application
Start the development server:
    run [npm run dev]

🔐 Test Account Credentials
The following accounts are pre-seeded in the database for testing login and role-based access:

Customer:
Email-> darkenborder7@gmail.com
Password-> password123

Customer:
Email-> yuridonatosalise@gmail.com
Password-> password123
