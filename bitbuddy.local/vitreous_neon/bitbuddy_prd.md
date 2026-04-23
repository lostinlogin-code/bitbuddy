# BitBuddy Product Requirements Document

## Project Overview
BitBuddy is a premium digital services marketplace featuring a high-end, glassmorphic aesthetic with dark and light theme mirrors.

## Theme System
### Dark Theme (Primary)
- **Background:** #050505 with subtle radial gradient dark navy in center.
- **Accent:** #00D1FF (electric blue neon).
- **Cards:** `backdrop-filter: blur(25px)`, `background: rgba(255,255,255,0.03)`, `border: 1px solid rgba(0,209,255,0.2)`.
- **Effects:** Glowing orbs (radial gradients) and intense hover glows.

### Light Theme (Mirror)
- **Background:** #f0f8ff with soft radial gradient light blue.
- **Accent:** #0099CC.
- **Cards:** `background: rgba(255,255,255,0.75)`, `border: 1px solid rgba(0,153,204,0.25)`.

## Animation & Core System
- **Page Load:** Fade-in + slide-up (30px) with 150ms stagger.
- **Scroll:** Intersection Observer for fade-in, scale (0.95 -> 1), and slide-in effects.
- **Hover:** Card lift (-6px), button scaling (1.04), and nav link underline slides.
- **Micro-interactions:** Focus glows on inputs, ripple effects on buttons, and smooth tab switching.

## Global Components
- **Navbar:** Transparent initially, blurs/darkens on scroll. Includes logo, links, and a pill-shaped theme toggle.
- **Footer:** Dark glass panel with a glowing blue divider and social icon hover effects.

## Required Screens
1. **Landing Page:** Hero with drifting orbs, stats count-up, popular services, and floating testimonials.
2. **Services Catalog:** Hero panel, filter pills (Design, Dev, IT, Video), and a 3-column service grid.
3. **Single Service Page:** Icon-focused hero, detailed description, and a pulsing "Order Now" CTA.
4. **Auth Page:** Centered glass card with "Login/Register" tab switcher and focus-glow inputs.
5. **User Dashboard:** Sidebar navigation, active order stats, and a status-badged orders table.
6. **Contacts Page:** Two-column layout (Form vs Info) with scroll-staggered animations.
7. **404 Page:** Centered glass card with floating "404" text and an intense neon glow.

## Typography
- **Font:** Inter (Google Fonts).
- **H1:** 64px, Extra Bold (800).
- **Body:** 16px, Regular (400).
- **Price:** 28px, Bold (700), Accent color.