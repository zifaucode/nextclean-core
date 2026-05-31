---
name: NextClean Professional
colors:
  surface: '#f8f9fa'
  surface-dim: '#d9dadb'
  surface-bright: '#f8f9fa'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f3f4f5'
  surface-container: '#edeeef'
  surface-container-high: '#e7e8e9'
  surface-container-highest: '#e1e3e4'
  on-surface: '#191c1d'
  on-surface-variant: '#5a3f47'
  inverse-surface: '#2e3132'
  inverse-on-surface: '#f0f1f2'
  outline: '#8e6f77'
  outline-variant: '#e2bdc6'
  surface-tint: '#b90062'
  primary: '#ab005a'
  on-primary: '#ffffff'
  primary-container: '#d80073'
  on-primary-container: '#fff0f2'
  inverse-primary: '#ffb1c7'
  secondary: '#5d5f5f'
  on-secondary: '#ffffff'
  secondary-container: '#dfe0e0'
  on-secondary-container: '#616363'
  tertiary: '#4d5769'
  on-tertiary: '#ffffff'
  tertiary-container: '#656f82'
  on-tertiary-container: '#eff3ff'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#ffd9e2'
  primary-fixed-dim: '#ffb1c7'
  on-primary-fixed: '#3e001d'
  on-primary-fixed-variant: '#8e0049'
  secondary-fixed: '#e2e2e2'
  secondary-fixed-dim: '#c6c6c7'
  on-secondary-fixed: '#1a1c1c'
  on-secondary-fixed-variant: '#454747'
  tertiary-fixed: '#d9e3f9'
  tertiary-fixed-dim: '#bdc7dc'
  on-tertiary-fixed: '#121c2c'
  on-tertiary-fixed-variant: '#3d4759'
  background: '#f8f9fa'
  on-background: '#191c1d'
  surface-variant: '#e1e3e4'
typography:
  display-lg:
    fontFamily: Inter
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
    letterSpacing: -0.02em
  headline-md:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
    letterSpacing: -0.01em
  headline-sm:
    fontFamily: Inter
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
  body-lg:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-md:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  body-sm:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '400'
    lineHeight: 18px
  label-md:
    fontFamily: Inter
    fontSize: 13px
    fontWeight: '600'
    lineHeight: 16px
    letterSpacing: 0.05em
  label-sm:
    fontFamily: Inter
    fontSize: 11px
    fontWeight: '700'
    lineHeight: 14px
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  container-max: 1440px
  sidebar-width: 260px
  gutter: 1.5rem
  margin-page: 2rem
  stack-sm: 0.5rem
  stack-md: 1rem
  stack-lg: 2rem
---

## Brand & Style
The design system is engineered for **NextClean**, a high-efficiency admin dashboard for professional laundry operations. The brand personality is rooted in reliability, speed, and precision. It balances the utility of a logistics tool with the vibrancy of a modern consumer application.

The design style is **Corporate / Modern**, specifically a "Modern Bootstrap" aesthetic. It utilizes a utility-first logic similar to Tailwind but maintains the structural integrity of a component-based framework. The UI is characterized by heavy whitespace, high-contrast typography, and a "flat-plus" approach where depth is indicated by subtle tonal shifts rather than heavy shadows. The emotional response should be one of competence, trust, and operational clarity.

## Colors
This design system uses a high-impact **Vibrant Magenta** as the primary action color to signify energy and modern service. 

- **Primary (#D80073):** Reserved for key actions, active states, and brand-defining elements.
- **Secondary (#FFFFFF):** The workspace background, ensuring high legibility for data-dense tables.
- **Neutral Palette:** A range of Cool Grays (from #F8F9FA to #1A202C) is used for borders, secondary backgrounds, and text hierarchy.
- **Functional Colors:** Success (Teal), Warning (Amber), and Danger (Rose) are slightly desaturated to maintain a professional, dashboard-ready appearance without competing with the primary Magenta.

## Typography
**Inter** is the sole typeface for this design system, chosen for its exceptional legibility in data-heavy environments and its neutral, systematic feel. 

- **Headlines:** Use tighter letter-spacing and heavier weights (600-700) to create a strong visual anchor for page titles and section headers.
- **Body Text:** Primarily uses `body-md` (14px) for dashboard content to maximize information density while maintaining readability.
- **Labels:** Small caps with increased letter spacing are used for table headers and sidebar category labels to differentiate them from interactive data points.
- **Mobile Scaling:** On devices below 768px, `display-lg` should scale down to 24px (headline-md).

## Layout & Spacing
The layout follows a **Fluid Grid** model built on an 8px spacing rhythm, ensuring consistency across all dashboard views.

- **Dashboard Structure:** A fixed left-hand sidebar (260px) persists on desktop, collapsing to a hamburger menu on mobile. The main content area uses a fluid container with a maximum width of 1440px to prevent excessive line lengths on ultra-wide monitors.
- **Grid:** A 12-column grid system is used for content organization. Stats cards typically span 3 columns (4 per row), while primary data tables span 8-12 columns.
- **Breakpoints:**
  - **Mobile (<768px):** 1 column, 1rem margins.
  - **Tablet (768px - 1024px):** 2 columns for cards, 1.5rem margins.
  - **Desktop (>1024px):** Full 12-column availability, 2rem margins.

## Elevation & Depth
In line with a modern Bootstrap/Tailwind aesthetic, depth is communicated through **Tonal Layers** and extremely soft, large-radius shadows.

- **Level 0 (Base):** Background color (`#F8F9FA`). 
- **Level 1 (Cards/Surfaces):** Pure white (`#FFFFFF`) with a 1px border in a light neutral (`#E2E8F0`). No shadow or a very faint "glow" shadow (0 1px 3px 0 rgba(0, 0, 0, 0.05)).
- **Level 2 (Dropdowns/Modals):** Pure white with a medium-diffusion shadow (0 10px 15px -3px rgba(0, 0, 0, 0.1)) to indicate clear separation from the background.
- **Interactions:** Hover states on interactive cards should transition the border color to the primary magenta at 20% opacity rather than increasing shadow depth.

## Shapes
The design system utilizes a **Rounded** (0.5rem) shape language to soften the industrial nature of an admin dashboard while maintaining a professional structure.

- **Buttons & Inputs:** 0.5rem (8px) radius.
- **Cards & Modals:** 1rem (16px) radius for `rounded-lg` elements.
- **Chips/Status Badges:** Fully rounded (pill-shaped) to distinguish them from actionable buttons.
- **Selection Indicators:** Use a 4px vertical bar on the left side of sidebar items to indicate the active state, paired with a subtle 0.5rem rounded background highlight.

## Components
Consistent implementation of components ensures a cohesive user experience:

- **Buttons:** Primary buttons use a solid Magenta background with White text. Secondary buttons use a white background with a neutral border. Action buttons should have a minimum height of 40px for accessibility.
- **Stats Cards:** Should feature a large `display-lg` value, a `label-sm` title, and a small trend indicator (e.g., +12%).
- **Data Tables:** Use `body-md` for row content. Headers must be `label-md` with a subtle background tint (#F1F5F9). Rows should feature a subtle hover state transition.
- **Sidebars:** Dark-themed sidebar (`tertiary_color_hex`) to provide high contrast against the white content area. Icons should be line-style (2px stroke) for a clean, modern look.
- **Input Fields:** Use a 1px neutral border that turns Magenta on focus. Labels should always be visible above the input field using `label-sm`.
- **Chips:** Small, low-contrast background fills with high-contrast text for status labels (e.g., "In Progress," "Completed").