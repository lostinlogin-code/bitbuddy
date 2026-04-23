-- Idempotent: fills image_url for every service, refreshing any URL that was
-- previously set to a known-broken Unsplash photo id.
-- Safe to re-run.

USE `bitbuddy_db`;

-- Clear broken URLs so the UPDATE...WHERE image_url IS NULL pattern below
-- refills them with the new working ones.
UPDATE `services` SET `image_url` = NULL
  WHERE `image_url` IN (
    'https://images.unsplash.com/photo-1561070791-2526d30994b8?auto=format&fit=crop&w=800&q=70',
    'https://images.unsplash.com/photo-1587620962725-abab7fe55159?auto=format&fit=crop&w=800&q=70'
  );

UPDATE `services` SET `image_url` = 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=800&q=70'
  WHERE `slug` = 'web-development' AND (`image_url` IS NULL OR `image_url` = '');

UPDATE `services` SET `image_url` = 'https://images.unsplash.com/photo-1586717791821-3f44a563fa4c?auto=format&fit=crop&w=1200&q=70'
  WHERE `slug` = 'ui-ux-design' AND (`image_url` IS NULL OR `image_url` = '');

UPDATE `services` SET `image_url` = 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&w=800&q=70'
  WHERE `slug` = 'cybersecurity' AND (`image_url` IS NULL OR `image_url` = '');

UPDATE `services` SET `image_url` = 'https://images.unsplash.com/photo-1517180102446-f3ece451e9d8?auto=format&fit=crop&w=1200&q=70'
  WHERE `slug` = 'frontend-development' AND (`image_url` IS NULL OR `image_url` = '');

UPDATE `services` SET `image_url` = 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=800&q=70'
  WHERE `slug` = 'backend-development' AND (`image_url` IS NULL OR `image_url` = '');

UPDATE `services` SET `image_url` = 'https://images.unsplash.com/photo-1572044162444-ad60f128bdea?auto=format&fit=crop&w=800&q=70'
  WHERE `slug` = 'branding' AND (`image_url` IS NULL OR `image_url` = '');

UPDATE `services` SET `image_url` = 'https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=800&q=70'
  WHERE `slug` = 'motion-design' AND (`image_url` IS NULL OR `image_url` = '');

UPDATE `services` SET `image_url` = 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=800&q=70'
  WHERE `slug` = 'devops' AND (`image_url` IS NULL OR `image_url` = '');

UPDATE `services` SET `image_url` = 'https://images.unsplash.com/photo-1551434678-e076c223a692?auto=format&fit=crop&w=800&q=70'
  WHERE `slug` IN ('it-support-247', 'support-24-7') AND (`image_url` IS NULL OR `image_url` = '');

UPDATE `services` SET `image_url` = 'https://images.unsplash.com/photo-1492619375914-88005aa9e8fb?auto=format&fit=crop&w=800&q=70'
  WHERE `slug` = 'promo-video' AND (`image_url` IS NULL OR `image_url` = '');

UPDATE `services` SET `image_url` = 'https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?auto=format&fit=crop&w=800&q=70'
  WHERE `slug` = 'motion-graphics' AND (`image_url` IS NULL OR `image_url` = '');

UPDATE `services` SET `image_url` = 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?auto=format&fit=crop&w=800&q=70'
  WHERE `slug` = 'mobile-development' AND (`image_url` IS NULL OR `image_url` = '');
