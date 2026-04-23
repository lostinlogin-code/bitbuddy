# Design System Specification: The Ethereal Exchange

## 1. Overview & Creative North Star
This design system is built to facilitate a high-end digital marketplace where services aren't just "listed"—they are curated. Moving away from the rigid, grid-heavy "template" look of traditional e-commerce, this system leans into **"The Ethereal Exchange."** 

The Creative North Star is defined by depth, luminescence, and fluidity. We achieve a premium feel by treating the interface as a series of floating glass apertures suspended over a deep, infinite void. We break the standard UI mold through intentional asymmetry, overlapping containers that defy traditional gutters, and a typographic scale that values editorial impact over density.

## 2. Color & Atmospheric Depth
Our palette is rooted in a deep, near-black foundation (`#0e0e0e`) contrasted with high-energy neon accents (`primary: #69daff`). This creates a high-contrast, "cyber-premium" aesthetic.

### The "No-Line" Rule
To maintain a high-end editorial feel, **1px solid borders are strictly prohibited for sectioning.** 
*   **Boundary Definition:** Use background shifts between `surface` and `surface-container-low` to define separate areas of the layout.
*   **Tonal Transitions:** Sections should feel like they melt into one another or are layered on top of each other, rather than being "boxed in."

### Surface Hierarchy & Nesting
Treat the UI as a physical stack of semi-transparent sheets. 
*   **Base Level:** `surface` (#0e0e0e).
*   **Secondary Contexts:** Use `surface-container-low` for large content areas.
*   **Interactive Components:** Use `surface-container-highest` for cards or modals to create a "lifted" appearance.
*   **The Glass Rule:** Any floating element must utilize a `backdrop-filter: blur(25px)` combined with a semi-transparent `surface-variant` color. This allows background "Glowing Orbs" to bleed through, creating a sense of three-dimensional space.

### Signature Textures
Main CTAs and Hero sections should utilize a subtle linear gradient (from `primary` to `primary-container`) rather than flat fills. This provides a "liquid light" effect that feels custom-engineered.

## 3. Typography: Editorial Authority
We use **Inter** as our sole typeface, relying on extreme weight and size contrast to establish hierarchy.

*   **Display LG (64px / 800):** Reserved for hero headlines. Use tight letter-spacing (-0.02em) to create an authoritative, "fashion-journal" look.
*   **Price Displays (28px / 700):** Prices are the most critical data point in a marketplace. They must always use the `primary` accent color to "pop" against the dark background.
*   **The Contrast Principle:** Pair massive `display-lg` headers with generous `body-md` (0.875rem) copy. The vast difference in scale creates an intentional, high-design friction.

## 4. Elevation & Depth
In this system, elevation is conveyed through light and transparency, not shadow alone.

*   **The Layering Principle:** Achieve depth by stacking surface tiers. A `surface-container-highest` card sitting on a `surface-container-low` section creates a natural, soft lift.
*   **Ambient Shadows:** For floating modals or detached navigation, use shadows with a blur radius of 40px+ and an opacity of 8%. The shadow color must be a tinted version of `primary` or `on-surface` (never pure black) to mimic a glowing object reflecting in a dark space.
*   **The "Ghost Border" Fallback:** If containment is required for accessibility, use a "Ghost Border": the `outline-variant` token at 15% opacity. This provides a structural hint without "closing" the design.
*   **Floating Orbs:** Place large, soft radial gradients (using `primary` and `secondary_dim`) at 5% opacity behind glass containers to simulate environmental light.

## 5. Components

### Buttons: The Neon Kinetic
*   **Primary:** A solid `primary` fill that utilizes an "intense hover glow." On hover, the button should emit a 20px outer shadow of the same color.
*   **Secondary (Glass):** Semi-transparent `surface-variant` with a 25px backdrop blur and a `Ghost Border`. 
*   **Transitions:** All buttons must use a `0.3s cubic-bezier(0.4, 0, 0.2, 1)` transition for "smooth hover lifts."

### Input Fields: The Recessed Aperture
*   Forbid high-contrast boxes. Fields should be `surface-container-lowest` with a subtle bottom-only `Ghost Border`. 
*   **Focus State:** The border animates to a 100% opaque `primary` color with a soft glow.

### Cards: The Frosted Pane
*   Cards never have solid borders or dividers. 
*   Use `surface-container-high` as the base.
*   **Interactions:** On hover, the card should scale (1.02) and increase its `backdrop-filter` blur intensity. 

### Marketplace-Specific: The "Service Tile"
*   Incorporate vertical white space (from the Spacing Scale) instead of divider lines between service listings.
*   The "Price" must be anchored to the top-right or bottom-right, detached from the description, to allow the typography to breathe.

## 6. Do’s and Don’ts

### Do:
*   **Do** use asymmetrical margins. If the left margin is 80px, try a right margin of 120px for hero content to create visual interest.
*   **Do** utilize staggered entry animations. When a page loads, elements should "fade-in + slide-up" in 50ms increments.
*   **Do** use `primary_fixed` for small, critical UI hints like notification dots.

### Don’t:
*   **Don’t** use 100% opaque, high-contrast borders. It kills the "ethereal" glass effect.
*   **Don’t** use standard "Drop Shadows" (0, 4, 4, 0). They feel dated and "heavy." Use ambient glows instead.
*   **Don’t** crowd the layout. If you feel a section needs a line to separate it, try adding 32px of extra whitespace instead.
*   **Don’t** use grey for text. Use `on_surface_variant` (#adaaaa) for secondary info to maintain the cool, blue-tinted atmosphere.