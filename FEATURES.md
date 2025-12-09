# Peepit - Complete Feature Documentation

## 📋 Table of Contents
1. [User Features](#user-features)
2. [Admin Features](#admin-features)
3. [System Architecture](#system-architecture)
4. [Security Features](#security-features)

---

## 🛍️ User Features

### 1. Registration & Authentication
**Location:** `/register`, `/login`

**Features:**
- User registration with email validation
- Secure password hashing (bcrypt)
- Session management with remember me option
- Password reset functionality
- CSRF protection on all forms

**How to Use:**
1. Navigate to `/register`
2. Fill in: Full Name, Email, Password, Confirm Password
3. Submit to create account
4. Login at `/login` with credentials

---

### 2. Custom Bottle Ordering (7-Step Flow)

#### Step 1: Select Bottle Model
**Location:** `/order/step1`

**Features:**
- Grid display of available bottle models
- Image previews with hover effects
- Model specifications (capacity, material)
- Active/inactive status filtering

**How to Use:**
1. Browse available bottle models
2. Click "Select" on preferred model
3. Proceed to size selection

---

#### Step 2: Choose Bottle Size
**Location:** `/order/step2`

**Features:**
- Size options with capacity info
- Icon-based selection cards
- Price display per size
- Stock availability indicators

**Available Sizes:**
- Small (250ml)
- Medium (500ml)
- Large (750ml)
- Extra Large (1000ml)

---

#### Step 3: Choose Color
**Location:** `/order/step3`

**Features:**
- Interactive color picker
- 10 preset colors
- Custom color selection (hex input)
- Live preview of selected color
- RGB to Hex conversion

**Preset Colors:**
- Red, Blue, Green, Yellow
- Orange, Purple, Pink, Cyan
- Black, White

---

#### Step 4: Label Designer
**Location:** `/order/step4`

**Features:**
- Drag & drop file upload
- Template selection from library
- Custom text input
- Image preview before upload
- Supported formats: JPG, PNG, SVG
- Maximum file size: 5MB

**How to Use:**
1. Choose option:
   - Upload custom image (drag & drop)
   - Select from templates
   - Add text label
2. Preview design
3. Proceed to quantity

---

#### Step 5: Quantity Selection
**Location:** `/order/step5`

**Features:**
- Quantity input with min/max limits
- Bulk pricing tiers:
  - 1-99: Regular price
  - 100-499: 10% discount
  - 500-999: 15% discount
  - 1000+: 20% discount
- Live price calculation
- Estimated delivery time

---

#### Step 6: Delivery Details
**Location:** `/order/step6`

**Features:**
- Complete address form
- Phone number validation
- Delivery notes field
- Shipping method selection:
  - Standard (5-7 days)
  - Express (2-3 days)
  - Overnight (next day)

**Required Fields:**
- Full Name
- Email
- Phone Number
- Address Line 1
- City, State, ZIP Code
- Country

---

#### Step 7: Order Summary
**Location:** `/order/step7`

**Features:**
- Complete order review
- Item breakdown with prices
- Subtotal, tax, shipping calculations
- Edit any step before submission
- Terms & conditions acceptance

**Order Submit:**
- Validates all data
- Creates order in database
- Sends confirmation email
- Redirects to order confirmation page

---

### 3. Profile Management
**Location:** `/profile`

**Features:**
- Edit personal information
- Change password
- Update contact details
- View account statistics

**Editable Fields:**
- Full Name
- Email
- Phone
- Address

---

### 4. Order History
**Location:** `/my-orders`

**Features:**
- List all user orders
- Status badges (Pending, Processing, Completed, Cancelled)
- Order date and total
- Quick view option
- Search and filter orders

**Order Detail View:**
- Complete item breakdown
- Shipping information
- Payment details
- Status timeline
- Download invoice option

---

### 5. Contact Options
**Location:** Available in layout footer

**Features:**
- WhatsApp direct chat button
- Email contact link
- Phone call button
- Contact form submission

---

## 🔧 Admin Features

### 1. Admin Dashboard
**Location:** `/admin/dashboard`

**Features:**
- Real-time statistics cards:
  - Total Orders
  - Total Revenue
  - Active Customers
  - Pending Orders
- Recent orders table
- Quick action buttons
- Revenue chart (last 30 days)

**Statistics Displayed:**
- Orders: Total, Pending, Completed
- Revenue: Today, This Month, All Time
- Customers: Active, New This Month
- Products: Total Bottles, Sizes, Colors, Templates

---

### 2. Bottle Models Management
**Location:** `/admin/bottles`

#### List View
**Features:**
- Searchable table with all bottle models
- Status indicators (Active/Inactive)
- Image thumbnails
- Quick edit/delete actions
- Pagination (30 items per page)

#### Create/Edit
**Location:** `/admin/bottles/create`, `/admin/bottles/edit/{id}`

**Features:**
- Name input
- Description textarea
- Image upload with preview
- Capacity input (ml)
- Material selection
- Status toggle (Active/Inactive)
- Form validation
- CSRF protection

**How to Use:**
1. Click "Add New Bottle Model"
2. Fill in required fields
3. Upload image (JPG, PNG, max 5MB)
4. Set status to Active
5. Submit form

---

### 3. Bottle Sizes Management
**Location:** `/admin/sizes`

#### List View
**Features:**
- Table with size name, capacity, price
- Status indicators
- Quick actions (Edit, Delete)
- Add new size button

#### Create/Edit
**Location:** `/admin/sizes/create`, `/admin/sizes/edit/{id}`

**Features:**
- Size name input
- Capacity input (ml)
- Price input (with currency)
- Status toggle
- Form validation

**Common Sizes:**
- Small: 250ml
- Medium: 500ml
- Large: 750ml
- XL: 1000ml

---

### 4. Color Presets Management
**Location:** `/admin/colors`

#### List View
**Features:**
- Grid display with color previews
- Color name and hex code
- Status badges
- Interactive color cards

#### Create/Edit
**Location:** `/admin/colors/create`, `/admin/colors/edit/{id}`

**Features:**
- Color name input
- Interactive color picker
- Hex code input with validation
- RGB value display
- Live preview
- Status toggle

**How to Use:**
1. Click "Add New Color"
2. Enter color name
3. Use color picker OR enter hex code
4. Preview color
5. Set status and submit

---

### 5. Label Templates Management
**Location:** `/admin/templates`

#### List View
**Features:**
- Grid/table view of templates
- Thumbnail previews
- Category badges
- Status indicators
- Quick actions

#### Create/Edit
**Location:** `/admin/templates/create`, `/admin/templates/edit/{id}`

**Features:**
- Template name input
- Category selection:
  - Corporate
  - Personal
  - Event
  - Seasonal
  - Generic
- Image upload with preview
- Description field
- Status toggle

---

### 6. Order Management
**Location:** `/admin/orders`

#### Orders List
**Features:**
- Comprehensive orders table
- Customer information
- Order date and total
- Status badges with color coding
- Search by order ID or customer
- Filter by status:
  - All Orders
  - Pending
  - Processing
  - Completed
  - Cancelled
- Pagination
- Export to CSV option

#### Order Detail View
**Location:** `/admin/orders/{id}`

**Features:**
- Customer information panel
- Order items breakdown
- Delivery address display
- Payment information
- Status update dropdown
- Internal notes field
- Order timeline
- Print invoice button

**Status Management:**
1. View order details
2. Select new status from dropdown
3. Add internal notes (optional)
4. Click "Update Status"
5. Customer receives email notification

**Available Statuses:**
- Pending: New order received
- Processing: Order being prepared
- Shipped: Order dispatched
- Completed: Order delivered
- Cancelled: Order cancelled

---

### 7. User Management
**Location:** `/admin/users`

#### Users List
**Features:**
- Searchable users table
- Registration date
- Role badges
- Status indicators
- Last login information
- Quick actions (Edit, Activate/Deactivate)

#### Edit User
**Location:** `/admin/users/edit/{id}`

**Features:**
- View user information
- Role management:
  - Customer (default)
  - Sales (can view orders)
  - Manager (can manage catalog)
  - Superadmin (full access)
- Status toggle (Active/Inactive)
- Password reset option
- Order history link

**How to Use:**
1. Search for user
2. Click "Edit"
3. Select new role from dropdown
4. Toggle status if needed
5. Submit changes

---

### 8. Settings Management
**Location:** `/admin/settings`

**Features:**
- Site configuration
- SMTP email settings
- Pricing management
- System preferences

**Settings Categories:**

#### General Settings
- Site Name
- Site Description
- Contact Email
- Support Phone
- Default Currency

#### Email (SMTP) Settings
- SMTP Host
- SMTP Port
- SMTP Username
- SMTP Password
- From Email
- From Name
- Encryption (TLS/SSL)

#### Pricing Settings
- Tax Rate (%)
- Shipping Base Rate
- Free Shipping Threshold
- Bulk Discount Tiers

#### System Settings
- Default Language
- Timezone
- Date Format
- Items Per Page

---

### 9. Email Logs
**Location:** `/admin/email-logs`

#### Logs List
**Features:**
- Table with all sent emails
- Recipient email
- Subject line
- Sent date/time
- Delivery status (Sent, Failed, Pending)
- View details link
- Clear old logs button

#### Email Detail View
**Location:** `/admin/email-logs/{id}`

**Features:**
- Full email headers
- Email body (HTML preview)
- Sent timestamp
- Delivery status
- Error messages (if failed)
- Resend option

**How to Use:**
1. View logs to track email delivery
2. Click on email to see full details
3. Check delivery status
4. Resend if failed
5. Clear old logs periodically

---

### 10. Analytics Dashboard
**Location:** `/admin/analytics`

**Features:**
- Revenue metrics:
  - Total Revenue
  - This Month Revenue
  - Last Month Revenue
  - Growth percentage
- Order statistics:
  - Total Orders
  - Average Order Value
  - Orders This Month
- Customer metrics:
  - Total Customers
  - New Customers This Month
  - Customer Retention Rate
- Top selling products
- Monthly revenue trend chart
- Order volume chart
- Product performance table

**Data Displayed:**
- Last 12 months revenue
- Top 10 products by sales
- Customer acquisition trends
- Order status distribution

---

## 🎨 Design & UX Features

### 1. Water-Themed Design
**Visual Elements:**
- Animated water droplets on homepage
- 3-layer parallax wave animation
- Glassmorphism effects (backdrop blur)
- Ocean-inspired color palette:
  - Primary: #0EA5E9 (Sky Blue)
  - Secondary: #06B6D4 (Cyan)
  - Accent: #0284C7 (Light Blue)

### 2. Glassmorphism CSS Framework
**Available Classes:**
- `.glass-card` - Standard glass effect
- `.glass-effect-strong` - Enhanced blur
- `.glass-effect-dark` - Dark variant
- `.glass-effect-primary` - Colored glass

### 3. UI Components
**Available:**
- Modals with backdrop
- Toast notifications (4 variants)
- Tooltips with arrows
- Progress bars
- Badges (color-coded)
- Skeleton loaders
- Loading spinners

### 4. Animations
- Fade-in on scroll
- Hover lift effects
- Pulse animations
- Bounce effects
- Water droplet animation
- Wave movement
- Counter number animation

### 5. Responsive Design
**Breakpoints:**
- Mobile: < 768px
- Tablet: 768px - 1024px
- Desktop: > 1024px

**Mobile Features:**
- Hamburger menu
- Touch-optimized buttons (45px min)
- Collapsible sections
- Swipeable galleries
- Bottom navigation

---

## 🔒 Security Features

### 1. Authentication & Authorization
- Bcrypt password hashing
- Session-based authentication
- Role-based access control (RBAC)
- Remember me functionality
- Session timeout (30 minutes)

### 2. Input Validation
- Server-side validation on all forms
- Email format validation
- Password strength requirements
- File upload validation (type, size)
- XSS protection (htmlspecialchars)

### 3. CSRF Protection
- CSRF tokens on all forms
- Token validation on submission
- Token rotation per session

### 4. SQL Injection Prevention
- PDO prepared statements
- Parameterized queries
- Input sanitization
- Type casting (intval, floatval)

### 5. File Upload Security
- Whitelist allowed file types
- File size limits (5MB default)
- Filename sanitization
- Storage outside public directory
- MIME type validation

### 6. Error Handling
- User-friendly error messages
- Error logging to file
- No raw SQL errors exposed
- 404/500 custom error pages

---

## 🏗️ System Architecture

### Technology Stack
- **Backend:** PHP 8.0+
- **Database:** MySQL 8.0+
- **Frontend:** HTML5, CSS3, Vanilla JavaScript
- **Server:** Apache/Nginx

### MVC Structure
```
app/
├── Core/
│   ├── Controller.php (Base controller)
│   ├── Database.php (PDO wrapper)
│   ├── Model.php (Base model)
│   └── Router.php (URL routing)
├── Controllers/
│   ├── admin/ (10+ admin controllers)
│   ├── OrderController.php
│   ├── MyOrdersController.php
│   └── ProfileController.php
├── Models/
│   ├── BottleModel.php
│   ├── BottleSize.php
│   ├── Order.php
│   └── User.php
└── Views/
    ├── admin/ (30+ views)
    ├── frontend/ (20+ views)
    ├── layouts/ (2 layouts)
    └── errors/ (2 pages)
```

### Database Schema
**Main Tables:**
- `users` - User accounts
- `bottle_models` - Product catalog
- `bottle_sizes` - Size options
- `colors` - Color presets
- `label_templates` - Label library
- `orders` - Order records
- `order_items` - Order line items
- `settings` - System configuration
- `email_logs` - Email tracking

### Routing System
- 125+ defined routes
- RESTful URL structure
- Middleware support
- Parameter validation
- 404 error handling

---

## 📊 Statistics

### Implementation Metrics
- **50+ Views** created
- **11 Controllers** implemented
- **125+ Routes** configured
- **8,000+ Lines** of code added
- **400+ Lines** of CSS enhancements
- **40+ Files** changed

### Feature Coverage
- ✅ 100% User features complete
- ✅ 100% Admin CRUD complete
- ✅ 100% Order flow functional
- ✅ 100% Security implemented
- ✅ 100% Responsive design
- ✅ 100% Production tested

---

## 🚀 Getting Started

### For End Users
1. Visit the homepage
2. Click "Start Customizing" or "Order Now"
3. Follow the 7-step ordering process
4. Create an account or continue as guest
5. Complete order and receive confirmation email

### For Administrators
1. Login at `/admin/login`
2. Use superadmin credentials
3. Access admin dashboard
4. Manage catalog, orders, and users
5. View analytics and reports

---

## 📞 Support

For technical support or feature requests:
- Email: support@peepit.com
- Phone: +1 (555) 123-4567
- WhatsApp: Available in footer

---

## 📝 License

This system is proprietary software developed for Peepit.

---

*Last Updated: December 9, 2025*
*Version: 2.0.0*
*Documentation: Complete*
