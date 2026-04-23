<?php
/**
 * Shared <head> for all BitBuddy pages.
 * Caller may set before including:
 *   $page_title    — string, inserted into <title>
 *   $extra_head    — string, extra HTML injected at end of <head>
 *   $include_smoke — bool, whether to include smoke-bg.js (hero background)
 */
$page_title    = $page_title    ?? 'BitBuddy';
$extra_head    = $extra_head    ?? '';
$include_smoke = $include_smoke ?? false;
?>
<!-- Theme initialization — prevents flash of unstyled content -->
<script>
    (function() {
        try {
            var saved = localStorage.getItem('bitbuddy-theme');
            var prefersLight = window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches;
            var theme = saved || (prefersLight ? 'light' : 'dark');
            document.documentElement.setAttribute('data-theme', theme);
            document.documentElement.style.colorScheme = theme;
        } catch (e) {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    })();
</script>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title><?php echo htmlspecialchars($page_title); ?></title>

<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin=""/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>

<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link rel="stylesheet" href="theme.css?v=4"/>

<script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    "background":                  "var(--color-background)",
                    "on-background":               "var(--color-on-background)",
                    "surface":                     "var(--color-surface)",
                    "surface-dim":                 "var(--color-surface-dim)",
                    "surface-bright":              "var(--color-surface-bright)",
                    "surface-variant":             "rgb(var(--rgb-surface-variant) / <alpha-value>)",
                    "surface-container":           "var(--color-surface-container)",
                    "surface-container-low":       "var(--color-surface-container-low)",
                    "surface-container-high":      "rgb(var(--rgb-surface-container-high) / <alpha-value>)",
                    "surface-container-highest":   "var(--color-surface-container-highest)",
                    "surface-container-lowest":    "var(--color-surface-container-lowest)",
                    "surface-tint":                "var(--color-surface-tint)",
                    "inverse-surface":             "var(--color-inverse-surface)",
                    "inverse-on-surface":          "var(--color-inverse-on-surface)",
                    "on-surface":                  "var(--color-on-surface)",
                    "on-surface-variant":          "var(--color-on-surface-variant)",
                    "primary":                     "rgb(var(--rgb-primary) / <alpha-value>)",
                    "primary-dim":                 "var(--color-primary-dim)",
                    "primary-fixed":               "var(--color-primary-fixed)",
                    "primary-fixed-dim":           "var(--color-primary-fixed-dim)",
                    "primary-container":           "var(--color-primary-container)",
                    "inverse-primary":             "var(--color-inverse-primary)",
                    "on-primary":                  "var(--color-on-primary)",
                    "on-primary-container":        "var(--color-on-primary-container)",
                    "on-primary-fixed":            "var(--color-on-primary-fixed)",
                    "on-primary-fixed-variant":    "var(--color-on-primary-fixed-variant)",
                    "secondary":                   "var(--color-secondary)",
                    "secondary-dim":               "rgb(var(--rgb-secondary-dim) / <alpha-value>)",
                    "secondary-fixed":             "var(--color-secondary-fixed)",
                    "secondary-fixed-dim":         "var(--color-secondary-fixed-dim)",
                    "secondary-container":         "var(--color-secondary-container)",
                    "on-secondary":                "var(--color-on-secondary)",
                    "on-secondary-container":      "var(--color-on-secondary-container)",
                    "on-secondary-fixed":          "var(--color-on-secondary-fixed)",
                    "on-secondary-fixed-variant":  "var(--color-on-secondary-fixed-variant)",
                    "tertiary":                    "var(--color-tertiary)",
                    "tertiary-dim":                "rgb(var(--rgb-tertiary-dim) / <alpha-value>)",
                    "tertiary-fixed":              "var(--color-tertiary-fixed)",
                    "tertiary-fixed-dim":          "var(--color-tertiary-fixed-dim)",
                    "tertiary-container":          "var(--color-tertiary-container)",
                    "on-tertiary":                 "var(--color-on-tertiary)",
                    "on-tertiary-container":       "var(--color-on-tertiary-container)",
                    "on-tertiary-fixed":           "var(--color-on-tertiary-fixed)",
                    "on-tertiary-fixed-variant":   "var(--color-on-tertiary-fixed-variant)",
                    "outline":                     "var(--color-outline)",
                    "outline-variant":             "rgb(var(--rgb-outline-variant) / <alpha-value>)",
                    "error":                       "var(--color-error)",
                    "error-dim":                   "var(--color-error-dim)",
                    "error-container":             "var(--color-error-container)",
                    "on-error":                    "var(--color-on-error)",
                    "on-error-container":          "var(--color-on-error-container)"
                },
                borderRadius: {
                    DEFAULT: "0.25rem",
                    lg: "0.5rem",
                    xl: "0.75rem",
                    full: "9999px"
                },
                fontFamily: {
                    headline: ["Inter", "sans-serif"],
                    body:     ["Inter", "sans-serif"],
                    label:    ["Inter", "sans-serif"]
                }
            }
        }
    }
</script>

<script src="theme.js?v=4" defer></script>
<?php if ($include_smoke): ?>
<script src="smoke-bg.js" defer></script>
<?php endif; ?>
<style>
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
</style>
<?php echo $extra_head; ?>
