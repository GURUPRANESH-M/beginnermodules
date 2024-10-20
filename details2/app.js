// app.js
const express = require('express');
const mysql = require('mysql');
const bodyParser = require('body-parser');
const path = require('path');

const app = express();
const port = 3000;

// MySQL database connection
const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',   // Your MySQL username
    password: '',   // Your MySQL password
    database: 'local_login_db'  // Your MySQL database name
});

db.connect((err) => {
    if (err) throw err;
    console.log('Connected to MySQL Database');
});

// Body parser middleware
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

// Serve static files from 'public' directory
app.use(express.static(path.join(__dirname, 'public')));

// Handle registration POST request
app.post('/register', (req, res) => {
    const { username, password } = req.body;

    const query = 'INSERT INTO user_credentials (username, password) VALUES (?, ?)';
    db.query(query, [username, password], (err, result) => {
        if (err) {
            return res.status(500).send('User registration failed.');
        }
        // Insert a new row into user_details table with null fields except for username
        const detailsQuery = 'INSERT INTO user_details (username) VALUES (?)';
        db.query(detailsQuery, [username], (err, result) => {
            if (err) throw err;
            res.status(200).send('User registered successfully');
        });
    });
});

// Handle login POST request
app.post('/login', (req, res) => {
    const { username, password } = req.body;

    const query = 'SELECT * FROM user_credentials WHERE username = ? AND password = ?';
    db.query(query, [username, password], (err, result) => {
        if (err) throw err;
        if (result.length > 0) {
            res.status(200).send('Login successful');
        } else {
            res.status(401).send('Login failed');
        }
    });
});

// Handle fetch user details after login
app.get('/user-details/:username', (req, res) => {
    const username = req.params.username;
    const query = 'SELECT * FROM user_details WHERE username = ?';

    db.query(query, [username], (err, result) => {
        if (err) throw err;
        if (result.length > 0) {
            res.status(200).json(result[0]);  // Send user details as JSON
        } else {
            res.status(404).send('User not found');
        }
    });
});

app.listen(port, () => {
    console.log(`Server is running on http://localhost:${port}`);
});
