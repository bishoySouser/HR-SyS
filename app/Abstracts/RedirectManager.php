<?php

namespace App\Abstracts;

class RedirectManager {
    public function redirectWithAction($action) {
        switch ($action) {
            case 'save_and_back':
                return redirect()->back();
            case 'save_and_edit':
                // Assuming you have a route named 'item.preview' for item preview
                return redirect()->route('item.preview');
            case 'save_and_new':
                return redirect()->to('https://example.com');
            case 'save_and_preview':
                return redirect()->to('https://example.com');
            default:
                echo 'Invalid action!';
                break;
        }
    }
}