<?php

namespace App\Abstracts;

class RedirectManager {
    public function redirectWithAction($action, $base_url, $model_id) {
        switch ($action) {
            case 'save_and_back':
                return $base_url;
            case 'save_and_edit':
                // Assuming you have a route named 'item.preview' for item preview
                return $base_url."/$model_id/edit";
            case 'save_and_new':
                return $base_url."/create";
            case 'save_and_preview':
                return $base_url."/$model_id/show";
            default:
                echo 'Invalid action!';
                break;
        }
    }
}