const express = require("express");
const sqlite3 = require("sqlite3").verbose();
const path = require("path");

const app = express();
const PORT = 3000;

// ================= DATABASE =================

const db = new sqlite3.Database("./library.db", (err) => {
    if (err) {
        console.log("Database error:", err.message);
    } else {
        console.log("SQLite database connected.");
    }
});

db.serialize(() => {

    // USERS TABLE
    db.run(`
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            roll TEXT UNIQUE NOT NULL,
            email TEXT NOT NULL,
            phone TEXT,
            department TEXT,
            year TEXT,
            gender TEXT,
            password TEXT NOT NULL,
            role TEXT DEFAULT 'student'
        )
    `);

    // BOOKS TABLE
    db.run(`
        CREATE TABLE IF NOT EXISTS books (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            book_id TEXT UNIQUE NOT NULL,
            name TEXT NOT NULL,
            author TEXT NOT NULL,
            category TEXT,
            quantity INTEGER DEFAULT 0
        )
    `);

    // BORROWINGS TABLE
    db.run(`
        CREATE TABLE IF NOT EXISTS borrowings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            roll TEXT NOT NULL,
            book_id TEXT NOT NULL,
            issue_date TEXT NOT NULL,
            expected_return_date TEXT NOT NULL,
            actual_return_date TEXT,
            status TEXT DEFAULT 'Issued'
        )
    `);

    // FINES TABLE
    db.run(`
        CREATE TABLE IF NOT EXISTS fines (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            roll TEXT NOT NULL,
            amount REAL NOT NULL,
            status TEXT DEFAULT 'Unpaid',
            created_at TEXT NOT NULL
        )
    `);

    // MATERIALS TABLE
    db.run(`
        CREATE TABLE IF NOT EXISTS materials (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            year TEXT NOT NULL,
            semester TEXT NOT NULL,
            file_name TEXT
        )
    `);

    // CONTACT TABLE
    db.run(`
        CREATE TABLE IF NOT EXISTS contacts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT NOT NULL,
            message TEXT NOT NULL,
            created_at TEXT NOT NULL
        )
    `);

    // DEFAULT ADMIN
    db.run(`
        INSERT OR IGNORE INTO users
        (name, roll, email, password, role)
        VALUES
        ('Library Admin', 'admin', 'admin@library.com', 'admin123', 'admin')
    `);
});


// ================= MIDDLEWARE =================

app.use(express.json());
app.use(express.urlencoded({ extended: true }));

app.use(express.static(path.join(__dirname, "public")));


// ================= REGISTER STUDENT =================

app.post("/api/register", (req, res) => {

    const {
        name,
        roll,
        email,
        phone,
        department,
        year,
        gender,
        password
    } = req.body;

    if (!name || !roll || !email || !password) {
        return res.status(400).json({
            error: "Please fill all required fields."
        });
    }

    const sql = `
        INSERT INTO users
        (name, roll, email, phone, department, year, gender, password)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    `;

    db.run(
        sql,
        [
            name,
            roll,
            email,
            phone,
            department,
            year,
            gender,
            password
        ],
        function (err) {

            if (err) {

                if (err.message.includes("UNIQUE")) {
                    return res.status(400).json({
                        error: "Roll number already exists."
                    });
                }

                return res.status(500).json({
                    error: err.message
                });
            }

            res.json({
                message: "Student registered successfully."
            });
        }
    );
});


// ================= LOGIN =================

app.post("/api/login", (req, res) => {

    const { roll, password } = req.body;

    const sql = `
        SELECT
            id,
            name,
            roll,
            email,
            phone,
            department,
            year,
            gender,
            role
        FROM users
        WHERE roll = ? AND password = ?
    `;

    db.get(sql, [roll, password], (err, user) => {

        if (err) {
            return res.status(500).json({
                error: err.message
            });
        }

        if (!user) {
            return res.status(401).json({
                error: "Invalid username or password."
            });
        }

        res.json({
            message: "Login successful.",
            user: user
        });
    });
});


// ================= VIEW STUDENTS =================

app.get("/api/students", (req, res) => {

    db.all(`
        SELECT
            id,
            name,
            roll,
            email,
            phone,
            department,
            year,
            gender
        FROM users
        WHERE role = 'student'
        ORDER BY id DESC
    `, [], (err, rows) => {

        if (err) {
            return res.status(500).json({
                error: err.message
            });
        }

        res.json(rows);
    });
});


// ================= ADD BOOK =================

app.post("/api/books", (req, res) => {

    const {
        bookId,
        name,
        author,
        category,
        quantity
    } = req.body;

    const sql = `
        INSERT INTO books
        (book_id, name, author, category, quantity)
        VALUES (?, ?, ?, ?, ?)
    `;

    db.run(
        sql,
        [
            bookId,
            name,
            author,
            category,
            quantity
        ],
        function (err) {

            if (err) {

                if (err.message.includes("UNIQUE")) {
                    return res.status(400).json({
                        error: "Book ID already exists."
                    });
                }

                return res.status(500).json({
                    error: err.message
                });
            }

            res.json({
                message: "Book added successfully."
            });
        }
    );
});


// ================= VIEW BOOKS =================

app.get("/api/books", (req, res) => {

    db.all(`
        SELECT
            *,
            CASE
                WHEN quantity > 0
                THEN 'Available'
                ELSE 'Not Available'
            END AS status
        FROM books
        ORDER BY id DESC
    `, [], (err, rows) => {

        if (err) {
            return res.status(500).json({
                error: err.message
            });
        }

        res.json(rows);
    });
});


// ================= UPDATE BOOK =================

app.put("/api/books/:id", (req, res) => {

    const {
        name,
        author,
        category,
        quantity
    } = req.body;

    db.run(`
        UPDATE books
        SET
            name = ?,
            author = ?,
            category = ?,
            quantity = ?
        WHERE id = ?
    `,
    [
        name,
        author,
        category,
        quantity,
        req.params.id
    ],
    function (err) {

        if (err) {
            return res.status(500).json({
                error: err.message
            });
        }

        res.json({
            message: "Book updated successfully."
        });
    });
});


// ================= DELETE BOOK =================

app.delete("/api/books/:id", (req, res) => {

    db.run(
        `DELETE FROM books WHERE id = ?`,
        [req.params.id],
        function (err) {

            if (err) {
                return res.status(500).json({
                    error: err.message
                });
            }

            res.json({
                message: "Book deleted successfully."
            });
        }
    );
});


// ================= SEARCH BOOK =================

app.get("/api/search-books", (req, res) => {

    const search = `%${req.query.q || ""}%`;

    db.all(`
        SELECT
            *,
            CASE
                WHEN quantity > 0
                THEN 'Available'
                ELSE 'Not Available'
            END AS status
        FROM books
        WHERE
            name LIKE ?
            OR author LIKE ?
            OR category LIKE ?
            OR book_id LIKE ?
    `,
    [
        search,
        search,
        search,
        search
    ],
    (err, rows) => {

        if (err) {
            return res.status(500).json({
                error: err.message
            });
        }

        res.json(rows);
    });
});


// ================= ISSUE BOOK =================

app.post("/api/issue", (req, res) => {

    const {
        roll,
        bookId,
        issueDate,
        returnDate
    } = req.body;

    db.get(
        `SELECT * FROM users WHERE roll = ? AND role = 'student'`,
        [roll],
        (err, student) => {

            if (err) {
                return res.status(500).json({
                    error: err.message
                });
            }

            if (!student) {
                return res.status(400).json({
                    error: "Student not found."
                });
            }

            db.get(
                `SELECT * FROM books WHERE book_id = ?`,
                [bookId],
                (err, book) => {

                    if (!book) {
                        return res.status(400).json({
                            error: "Book not found."
                        });
                    }

                    if (book.quantity <= 0) {
                        return res.status(400).json({
                            error: "Book is not available."
                        });
                    }

                    db.run(`
                        INSERT INTO borrowings
                        (
                            roll,
                            book_id,
                            issue_date,
                            expected_return_date,
                            status
                        )
                        VALUES (?, ?, ?, ?, 'Issued')
                    `,
                    [
                        roll,
                        bookId,
                        issueDate,
                        returnDate
                    ],
                    function (err) {

                        if (err) {
                            return res.status(500).json({
                                error: err.message
                            });
                        }

                        db.run(
                            `UPDATE books
                             SET quantity = quantity - 1
                             WHERE book_id = ?`,
                            [bookId]
                        );

                        res.json({
                            message: "Book issued successfully."
                        });
                    });
                }
            );
        }
    );
});


// ================= BORROW HISTORY =================

app.get("/api/borrowings", (req, res) => {

    let sql;
    let params = [];

    if (req.query.roll) {

        sql = `
            SELECT *
            FROM borrowings
            WHERE roll = ?
            ORDER BY id DESC
        `;

        params = [req.query.roll];

    } else {

        sql = `
            SELECT *
            FROM borrowings
            ORDER BY id DESC
        `;
    }

    db.all(sql, params, (err, rows) => {

        if (err) {
            return res.status(500).json({
                error: err.message
            });
        }

        res.json(rows);
    });
});


// ================= RETURN BOOK =================

app.post("/api/return", (req, res) => {

    const {
        roll,
        bookId,
        actualReturnDate
    } = req.body;

    db.get(`
        SELECT *
        FROM borrowings
        WHERE
            roll = ?
            AND book_id = ?
            AND status = 'Issued'
        ORDER BY id DESC
        LIMIT 1
    `,
    [
        roll,
        bookId
    ],
    (err, borrowing) => {

        if (!borrowing) {
            return res.status(400).json({
                error: "Active borrowing record not found."
            });
        }

        db.run(`
            UPDATE borrowings
            SET
                actual_return_date = ?,
                status = 'Returned'
            WHERE id = ?
        `,
        [
            actualReturnDate,
            borrowing.id
        ]);

        db.run(`
            UPDATE books
            SET quantity = quantity + 1
            WHERE book_id = ?
        `,
        [bookId]);

        const dueDate =
            new Date(borrowing.expected_return_date);

        const returnDate =
            new Date(actualReturnDate);

        const lateDays = Math.max(
            0,
            Math.ceil(
                (returnDate - dueDate) /
                (1000 * 60 * 60 * 24)
            )
        );

        const fine = lateDays * 10;

        if (fine > 0) {

            db.run(`
                INSERT INTO fines
                (roll, amount, status, created_at)
                VALUES (?, ?, 'Unpaid', ?)
            `,
            [
                roll,
                fine,
                new Date().toISOString()
            ]);
        }

        res.json({
            message:
                fine > 0
                ? `Book returned. Fine ₹${fine} added.`
                : "Book returned successfully."
        });
    });
});


// ================= ADD FINE =================

app.post("/api/fines", (req, res) => {

    const {
        roll,
        amount
    } = req.body;

    db.run(`
        INSERT INTO fines
        (roll, amount, status, created_at)
        VALUES (?, ?, 'Unpaid', ?)
    `,
    [
        roll,
        amount,
        new Date().toISOString()
    ],
    function (err) {

        if (err) {
            return res.status(500).json({
                error: err.message
            });
        }

        res.json({
            message: "Fine added successfully."
        });
    });
});


// ================= VIEW FINES =================

app.get("/api/fines", (req, res) => {

    let sql;
    let params = [];

    if (req.query.roll) {

        sql = `
            SELECT *
            FROM fines
            WHERE roll = ?
            ORDER BY id DESC
        `;

        params = [req.query.roll];

    } else {

        sql = `
            SELECT *
            FROM fines
            ORDER BY id DESC
        `;
    }

    db.all(sql, params, (err, rows) => {

        if (err) {
            return res.status(500).json({
                error: err.message
            });
        }

        res.json(rows);
    });
});


// ================= ADD MATERIAL =================

app.post("/api/materials", (req, res) => {

    const {
        title,
        year,
        semester,
        fileName
    } = req.body;

    db.run(`
        INSERT INTO materials
        (title, year, semester, file_name)
        VALUES (?, ?, ?, ?)
    `,
    [
        title,
        year,
        semester,
        fileName
    ],
    function (err) {

        if (err) {
            return res.status(500).json({
                error: err.message
            });
        }

        res.json({
            message: "Study material added successfully."
        });
    });
});


// ================= SEARCH MATERIAL =================

app.get("/api/materials", (req, res) => {

    let sql = `SELECT * FROM materials`;
    let params = [];
    let conditions = [];

    if (req.query.year) {
        conditions.push("year = ?");
        params.push(req.query.year);
    }

    if (req.query.semester) {
        conditions.push("semester = ?");
        params.push(req.query.semester);
    }

    if (conditions.length > 0) {
        sql += " WHERE " + conditions.join(" AND ");
    }

    sql += " ORDER BY id DESC";

    db.all(sql, params, (err, rows) => {

        if (err) {
            return res.status(500).json({
                error: err.message
            });
        }

        res.json(rows);
    });
});


// ================= CONTACT =================

app.post("/api/contact", (req, res) => {

    const {
        name,
        email,
        message
    } = req.body;

    db.run(`
        INSERT INTO contacts
        (name, email, message, created_at)
        VALUES (?, ?, ?, ?)
    `,
    [
        name,
        email,
        message,
        new Date().toISOString()
    ],
    function (err) {

        if (err) {
            return res.status(500).json({
                error: err.message
            });
        }

        res.json({
            message: "Contact message submitted successfully."
        });
    });
});


// ================= DASHBOARD STATISTICS =================

app.get("/api/stats", (req, res) => {

    db.get(
        `SELECT COUNT(*) AS count
         FROM users
         WHERE role = 'student'`,
        [],
        (err, students) => {

            db.get(
                `SELECT COUNT(*) AS count
                 FROM books`,
                [],
                (err, books) => {

                    db.get(
                        `SELECT COUNT(*) AS count
                         FROM borrowings
                         WHERE status = 'Issued'`,
                        [],
                        (err, issued) => {

                            db.get(
                                `SELECT COALESCE(SUM(amount),0) AS total
                                 FROM fines
                                 WHERE status = 'Unpaid'`,
                                [],
                                (err, fines) => {

                                    res.json({
                                        students: students.count,
                                        books: books.count,
                                        issued: issued.count,
                                        fines: fines.total
                                    });

                                }
                            );
                        }
                    );
                }
            );
        }
    );
});


// ================= START SERVER =================

app.listen(PORT, () => {

    console.log(
        `Library Management System running at http://localhost:${PORT}`
    );

    console.log(
        "Database file: library.db"
    );
});