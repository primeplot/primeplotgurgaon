-- =========================================================
-- Prime Plot Gurgaon — Leads / Mini-CRM Database Schema
-- Import this via Hostinger's phpMyAdmin (hPanel > Databases > phpMyAdmin)
-- =========================================================

CREATE TABLE IF NOT EXISTS leads (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(150) NOT NULL,
  mobile VARCHAR(20) NOT NULL,
  email VARCHAR(150) DEFAULT NULL,
  plot_size VARCHAR(50) DEFAULT NULL,
  message TEXT DEFAULT NULL,
  source VARCHAR(50) DEFAULT 'Website Form',
  assigned_to ENUM('Unassigned','Marketing','Sales','Other') NOT NULL DEFAULT 'Unassigned',
  status ENUM('New','Contacted','Site Visit Scheduled','Closed','Not Interested') NOT NULL DEFAULT 'New',
  notes TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
