# Shop-Filter-And-Search
This plugin customizes the behavior of JetSmartFilters to provide the following functionalities: 1. User can see only his related product categories in the filter on the shop page. 2. User can search only his related assigned category products in the shop page search.
# JetSmartFilters Customizations

## Overview
This plugin customizes the behavior of JetSmartFilters to provide the following functionalities:
1. User can see only his related product categories in the filter on the shop page.
2. User can search only his related assigned category products in the shop page search.

## Installation
1. Download the plugin files.
2. Upload the plugin folder to the `/wp-content/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.

## Functionality Details

### 1. Customization: Show only user's related product categories in the filter
- Description: This customization modifies the behavior of JetSmartFilters to display only the product categories related to the logged-in user in the filter on the shop page.
- Implementation:
  - Hook: 'jet-smart-filters/filter-instance/args'
  - Function: 'gc_shop_modify_filter'
- Usage: No additional steps required. The functionality is automatically applied once the plugin is activated.

### 2. Customization: Search only user's related assigned category products
- Description: This customization adjusts the search functionality on the shop page to filter search results based on the product categories assigned to the logged-in user.
- Implementation:
  - Hook: 'pre_get_posts'
  - Function: 'gc_modify_search_query'
- Usage: No additional steps required. The functionality is automatically applied once the plugin is activated.

## Usage
1. Ensure that JetSmartFilters plugin is installed and activated.
2. Log in as a user with assigned product categories.
3. Visit the shop page and observe the filter behavior and search functionality.

## Contributing
Contributions to enhance the functionality or fix issues are welcome. Please fork the repository, make your changes, and submit a pull request.

## License
This project is licensed under the [insert license here]. See the LICENSE file for details.
