<?php
/**
 * Centralised mapping of service image URLs so services.php and service.php
 * always resolve the same picture for a given row — even when the DB
 * `services.image_url` column is NULL (e.g. fresh deploy before migration).
 *
 * Keys are `services.slug` values. All URLs have been verified reachable
 * at the time of writing; if a particular URL ever 404s we also attach an
 * onerror-fallback on the client to hide the broken <img> gracefully.
 */

function bb_service_image_url(array $svc): ?string {
    if (!empty($svc['image_url'])) {
        return $svc['image_url'];
    }
    $by_slug = [
        'web-development'      => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=1200&q=70',
        'ui-ux-design'         => 'https://images.unsplash.com/photo-1586717791821-3f44a563fa4c?auto=format&fit=crop&w=1200&q=70',
        'cybersecurity'        => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&w=1200&q=70',
        'frontend-development' => 'https://images.unsplash.com/photo-1517180102446-f3ece451e9d8?auto=format&fit=crop&w=1200&q=70',
        'backend-development'  => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=1200&q=70',
        'branding'             => 'https://images.unsplash.com/photo-1572044162444-ad60f128bdea?auto=format&fit=crop&w=1200&q=70',
        'motion-design'        => 'https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=1200&q=70',
        'devops'               => 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1200&q=70',
        'support-24-7'         => 'https://images.unsplash.com/photo-1551434678-e076c223a692?auto=format&fit=crop&w=1200&q=70',
        'it-support-247'       => 'https://images.unsplash.com/photo-1551434678-e076c223a692?auto=format&fit=crop&w=1200&q=70',
        'promo-video'          => 'https://images.unsplash.com/photo-1492619375914-88005aa9e8fb?auto=format&fit=crop&w=1200&q=70',
        'motion-graphics'      => 'https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?auto=format&fit=crop&w=1200&q=70',
        'mobile-development'   => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?auto=format&fit=crop&w=1200&q=70',
    ];
    if (!empty($svc['category_slug']) && empty($by_slug[$svc['slug'] ?? ''])) {
        $by_category = [
            'design'      => 'https://images.unsplash.com/photo-1586717791821-3f44a563fa4c?auto=format&fit=crop&w=1200&q=70',
            'development' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=1200&q=70',
            'it-support'  => 'https://images.unsplash.com/photo-1551434678-e076c223a692?auto=format&fit=crop&w=1200&q=70',
            'video'       => 'https://images.unsplash.com/photo-1492619375914-88005aa9e8fb?auto=format&fit=crop&w=1200&q=70',
        ];
        return $by_category[$svc['category_slug']] ?? null;
    }
    return $by_slug[$svc['slug'] ?? ''] ?? null;
}
