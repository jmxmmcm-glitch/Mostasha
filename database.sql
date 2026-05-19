-- =============================================================
-- Emergency Medical System (Mostasha) — PostgreSQL Schema
-- =============================================================
-- This file is executed by `public/setup_db.php` after deploy,
-- or can be run manually via `psql $DATABASE_URL -f database.sql`.
-- All statements are idempotent (IF NOT EXISTS / ON CONFLICT).
-- =============================================================

-- ---------- USERS (Staff, Doctors, Nurses, Admins) ----------
CREATE TABLE IF NOT EXISTS users (
    id              SERIAL PRIMARY KEY,
    username        VARCHAR(50)  NOT NULL UNIQUE,
    password_hash   VARCHAR(255) NOT NULL,
    role            VARCHAR(20)  NOT NULL CHECK (role IN ('admin','doctor','nurse','receptionist')),
    full_name       VARCHAR(100) NOT NULL,
    created_at      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ---------- PATIENTS ----------
CREATE TABLE IF NOT EXISTS patients (
    id              SERIAL PRIMARY KEY,
    national_id     VARCHAR(20)  UNIQUE,
    full_name       VARCHAR(100) NOT NULL,
    dob             DATE,
    gender          VARCHAR(10)  NOT NULL CHECK (gender IN ('male','female')),
    phone           VARCHAR(20),
    address         TEXT,
    created_at      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ---------- VISITS (Emergency visits) ----------
CREATE TABLE IF NOT EXISTS visits (
    id              SERIAL PRIMARY KEY,
    patient_id      INT NOT NULL REFERENCES patients(id) ON DELETE CASCADE,
    arrival_time    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status          VARCHAR(20) NOT NULL DEFAULT 'triage'
                    CHECK (status IN ('triage','waiting','in_treatment','discharged','admitted')),
    priority        VARCHAR(20) NOT NULL DEFAULT 'non-urgent'
                    CHECK (priority IN ('critical','urgent','non-urgent'))
);

-- ---------- TRIAGE (Nurse assessment) ----------
CREATE TABLE IF NOT EXISTS triage (
    id              SERIAL PRIMARY KEY,
    visit_id        INT NOT NULL REFERENCES visits(id) ON DELETE CASCADE,
    nurse_id        INT NOT NULL REFERENCES users(id)  ON DELETE RESTRICT,
    blood_pressure  VARCHAR(20),
    heart_rate      INT,
    temperature     DECIMAL(4,2),
    notes           TEXT,
    created_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ---------- MEDICAL RECORDS (Doctor assessment) ----------
CREATE TABLE IF NOT EXISTS medical_records (
    id              SERIAL PRIMARY KEY,
    visit_id        INT NOT NULL REFERENCES visits(id) ON DELETE CASCADE,
    doctor_id       INT NOT NULL REFERENCES users(id)  ON DELETE RESTRICT,
    diagnosis       TEXT,
    treatment_plan  TEXT,
    prescriptions   TEXT,
    created_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ---------- INVOICES ----------
CREATE TABLE IF NOT EXISTS invoices (
    id              SERIAL PRIMARY KEY,
    visit_id        INT NOT NULL REFERENCES visits(id) ON DELETE CASCADE,
    invoice_date    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    subtotal        DECIMAL(10,2) NOT NULL,
    vat_amount      DECIMAL(10,2) NOT NULL,
    total_amount    DECIMAL(10,2) NOT NULL,
    status          VARCHAR(10) NOT NULL DEFAULT 'unpaid'
                    CHECK (status IN ('unpaid','paid'))
);

-- ---------- SETTINGS (App-wide key/value) ----------
CREATE TABLE IF NOT EXISTS settings (
    id              SERIAL PRIMARY KEY,
    setting_key     VARCHAR(100) NOT NULL UNIQUE,
    setting_value   TEXT
);

-- ---------- Default admin user ----------
-- Default password is 'password'
INSERT INTO users (username, password_hash, role, full_name)
VALUES ('admin',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'admin',
        'System Admin')
ON CONFLICT (username) DO NOTHING;

-- ---------- Default settings ----------
INSERT INTO settings (setting_key, setting_value) VALUES
    ('hospital_name', 'مركز الطوارئ الطبي الافتراضي'),
    ('phone',         '920000000'),
    ('address',       'الرياض، المملكة العربية السعودية'),
    ('vat_number',    '300000000000003')
ON CONFLICT (setting_key) DO NOTHING;
