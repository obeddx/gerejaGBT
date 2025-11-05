<?php
// File: app/Helpers/TextHelper.php
// Buat file ini jika belum ada

if (!function_exists('linkify')) {
    /**
     * Mengubah URL dalam teks menjadi clickable link
     * 
     * @param string $text
     * @return string
     */
    function linkify($text) {
        // Pattern untuk mendeteksi URL (http, https, www)
        $pattern = '/(https?:\/\/[^\s]+)/i';
        
        // Ganti URL dengan tag <a>
        $replacement = '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-800 underline break-all">$1</a>';
        
        return preg_replace($pattern, $replacement, e($text));
    }
}