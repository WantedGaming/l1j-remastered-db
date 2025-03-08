# Changelog

## [0.6.2] - 2025-03-08

### Changed
- Updated all links across the site to be white by default and turn red when hovered
- Added global link styling rules to both style.css and player-style.css for consistent appearance

## [0.6.1] - 2025-03-08

### Changed
- Changed breadcrumbs on map pages from purple/blue to white with red color on hover
- Converted map index page from card view to list view for better readability and information display
- Added specific breadcrumb styling in the main CSS file

## [0.6.0] - 2025-03-08

### Added
- Added maps functionality with list and detail views
- Created mapids.sql with sample map data for the game
- Implemented maps/index.php with grid layout and filtering options
- Created maps/view.php for detailed map information display
- Added maps directory structure for better organization
- Integrated maps navigation in both admin and player views

### Changed
- Updated navigation menus to include maps section
- Enhanced player view with direct access to maps browsing
- Improved overall site organization with dedicated maps section
- Added map-specific styling for both list and detail views

## [0.5.0] - 2025-03-08

### Added
- Implemented user authentication system with login/logout functionality
- Created login.php with user authentication form
- Added session management across all admin pages
- Created logout.php for secure session termination
- Added user information display in header with admin badge for admin users
- Implemented access control to restrict admin pages to authenticated users
- Added CSS styling for user info and logout button in header

### Changed
- Updated all admin pages to check for authenticated sessions
- Modified player.php to include login link instead of direct admin link
- Enhanced security by redirecting unauthenticated users to login page
- Updated header navigation to display current user and logout option

## [0.4.1] - 2025-03-08

### Changed
- Fixed link colors in player view to maintain consistent white color scheme
- Ensured "View More Statistics", "View All Items", and "View All NPCs" links are white instead of purple
- Added hover effect to links that changes color to red when hovered, consistent with the site's color scheme

## [0.4.0] - 2025-03-08

### Added
- Created separate views for admin and players/guests
- Implemented a new player-focused interface based on the sample site design
- Added dedicated player.php page with gaming-focused layout
- Created new player-style.css with matching black and red color scheme
- Added boss timers section for player view
- Added database statistics display with visual counters
- Added featured items and NPCs sections
- Added quick tools section for player utilities
- Implemented navigation between admin and player views

### Changed
- Updated index.php to serve as the admin interface
- Added visual indicator for switching between views
- Enhanced navigation with clear view separation
- Improved overall user experience with role-specific interfaces
- Maintained consistent color scheme across both admin and player views

## [0.3.1] - 2025-03-08

### Changed
- Reverted table cards to their previous style, removing red accents
- Changed card gradient overlay to a subtle white gradient
- Updated card header border to use a light border instead of red
- Modified card title indicator dot to use gray instead of red
- Changed card hover border color to a subtle white instead of red

## [0.3.0] - 2025-03-08

### Added
- Completely redesigned the index page with a modern, gaming-focused interface
- Added hero section with clear call-to-action buttons
- Implemented database statistics cards with icons and animations
- Added real-time table filtering and sorting functionality
- Enhanced card design with subtle animations and improved visual hierarchy
- Added footer links section with GitHub repository link
- Implemented subtle background patterns and gradients for improved aesthetics

### Changed
- Updated CSS with modern design elements including gradients, animations, and improved spacing
- Enhanced button styles with hover effects and animations
- Improved card design with better shadows, borders, and hover effects
- Modernized the header and navigation with improved visual feedback
- Updated footer with a more professional and modern look
- Improved overall typography and spacing for better readability
- Enhanced color scheme implementation with additional accent colors

### Features
- Interactive table filtering by name
- Table sorting by name or row count
- Animated UI elements for better user engagement
- Improved visual hierarchy and information organization
- Enhanced mobile responsiveness
- Better visual feedback for interactive elements

## [0.2.0] - 2025-03-08

### Added
- Implemented edit_record.php for adding and editing database records
- Added search.php with advanced search functionality across tables
- Created export_table.php with support for CSV, JSON, and SQL formats
- Added about.php with project information and documentation
- Created tables.php for dedicated table browsing with filtering
- Enhanced CSS styling with additional components and responsive improvements
- Added form validation for record editing
- Implemented table filtering functionality

### Features
- Complete CRUD operations (Create, Read, Update, Delete) for database records
- Advanced search capabilities with highlighted results
- Multiple export formats for data portability
- Comprehensive documentation and about page
- Responsive design that works on various screen sizes
- Interactive table filtering and pagination

## [0.1.0] - 2025-03-08

### Added
- Initial project setup for L1J Remastered Database website
- Created database connection configuration in `includes/config.php`
- Implemented modern, sleek CSS styling with black and red color scheme based on provided design
- Created main index.php page with card-based layout to display database tables
- Added view_table.php to display table structure and data with pagination
- Implemented basic navigation system
- Added support for viewing table data with pagination
- Set up responsive design for mobile and desktop viewing

### Features
- Modern card/grid layout for displaying database tables
- Table structure and data viewing capabilities
- Responsive design that works on various screen sizes
- Black and red color scheme based on the provided design image