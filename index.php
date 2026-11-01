<?php
/**
 * ROOT ENTRY POINT — Teh Kitta
 * Tampilkan landing page (company profile & kemitraan).
 * Tombol "Dashboard" / "Login Mitra" di landing akan mengarah ke login
 * atau langsung ke dashboard bila sudah login.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/landing.php';
