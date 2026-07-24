<?php
require_once __DIR__ . '/backend_config.php';

/**
 * Fetch home page sliders/widgets from EverShop GraphQL API
 * 
 * @return array Array of slider/widget data
 */
function fetchHomeSliders() {
    $query = '
        query {
            widgets {
                items {
                    widgetId
                    uuid
                    name
                    type
                    settings
                }
            }
        }
    ';

    $data = json_encode(['query' => $query]);

    $ch = curl_init(EVERSHOP_GRAPHQL_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data)
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        error_log("GraphQL API error: HTTP $httpCode");
        return [];
    }

    $result = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("JSON decode error: " . json_last_error_msg());
        return [];
    }

    if (isset($result['errors'])) {
        error_log("GraphQL errors: " . json_encode($result['errors']));
        return [];
    }

    $widgets = $result['data']['widgets']['items'] ?? [];

    // Filter for slider/carousel type widgets
    $sliders = array_filter($widgets, function($widget) {
        $type = strtolower($widget['type'] ?? '');
        return in_array($type, ['slider', 'carousel', 'hero', 'banner', 'hero-banner', 'hero-slider', 'simple_slider']);
    });

    // If no specific slider type found, return all widgets that have image settings
    if (empty($sliders)) {
        $sliders = array_filter($widgets, function($widget) {
            $settings = $widget['settings'] ?? '';
            if (is_string($settings)) {
                $settings = json_decode($settings, true);
            }
            return is_array($settings) && (
                isset($settings['image']) || 
                isset($settings['imageUrl']) || 
                isset($settings['slides']) ||
                isset($settings['images'])
            );
        });
    }

    // Parse settings JSON if it's a string
    foreach ($sliders as &$slider) {
        if (is_string($slider['settings'])) {
            $slider['settings'] = json_decode($slider['settings'], true) ?? [];
        }
        
        // Fix image URLs in settings (remove double slashes and prepend base URL)
        $settings = &$slider['settings'];
        if (isset($settings['slides']) && is_array($settings['slides'])) {
            foreach ($settings['slides'] as &$slide) {
                if (isset($slide['image']) && $slide['image']) {
                    // Remove double slashes
                    $slide['image'] = preg_replace('#/+#', '/', $slide['image']);
                    // Prepend base URL if it's a relative path
                    if (strpos($slide['image'], 'http') !== 0 && strpos($slide['image'], '/') === 0) {
                        $slide['image'] = EVERSHOP_ASSET_BASE_URL . $slide['image'];
                    }
                }
            }
        }
    }

    return array_values($sliders);
}

/**
 * Get slider images from widget settings
 * 
 * @param array $widget Widget data with settings
 * @return array Array of image URLs
 */
function getSliderImages($widget) {
    $settings = $widget['settings'] ?? [];
    $images = [];

    // Check various possible settings structures
    if (isset($settings['slides']) && is_array($settings['slides'])) {
        foreach ($settings['slides'] as $slide) {
            if (isset($slide['image']) && $slide['image']) {
                $images[] = $slide['image'];
            } elseif (isset($slide['imageUrl']) && $slide['imageUrl']) {
                $images[] = $slide['imageUrl'];
            }
        }
    } elseif (isset($settings['images']) && is_array($settings['images'])) {
        $images = $settings['images'];
    } elseif (isset($settings['image']) && $settings['image']) {
        $images[] = $settings['image'];
    } elseif (isset($settings['imageUrl']) && $settings['imageUrl']) {
        $images[] = $settings['imageUrl'];
    }

    // Prepend asset base URL if images are relative paths
    $baseUrl = EVERSHOP_ASSET_BASE_URL;
    foreach ($images as &$image) {
        if ($image && strpos($image, 'http') !== 0 && strpos($image, '/') === 0) {
            // Remove double slashes
            $image = preg_replace('#/+#', '/', $image);
            $image = $baseUrl . $image;
        }
    }

    return array_filter($images);
}

/**
 * Get slider link from widget settings
 * 
 * @param array $widget Widget data with settings
 * @return string|null Link URL or null
 */
function getSliderLink($widget) {
    $settings = $widget['settings'] ?? [];
    
    if (isset($settings['link']) && $settings['link']) {
        return $settings['link'];
    }
    if (isset($settings['url']) && $settings['url']) {
        return $settings['url'];
    }
    if (isset($settings['slides']) && is_array($settings['slides']) && count($settings['slides']) > 0) {
        $firstSlide = $settings['slides'][0];
        if (isset($firstSlide['link']) && $firstSlide['link']) {
            return $firstSlide['link'];
        }
        if (isset($firstSlide['url']) && $firstSlide['url']) {
            return $firstSlide['url'];
        }
    }
    
    return null;
}

/**
 * Get slider title from widget settings
 * 
 * @param array $widget Widget data with settings
 * @return string|null Title or null
 */
function getSliderTitle($widget) {
    $settings = $widget['settings'] ?? [];
    
    if (isset($settings['title']) && $settings['title']) {
        return $settings['title'];
    }
    if (isset($settings['heading']) && $settings['heading']) {
        return $settings['heading'];
    }
    if (isset($settings['slides']) && is_array($settings['slides']) && count($settings['slides']) > 0) {
        $firstSlide = $settings['slides'][0];
        if (isset($firstSlide['title']) && $firstSlide['title']) {
            return $firstSlide['title'];
        }
        if (isset($firstSlide['heading']) && $firstSlide['heading']) {
            return $firstSlide['heading'];
        }
    }
    
    return $widget['name'] ?? null;
}

// Fetch sliders when file is included
$homeSliders = fetchHomeSliders();