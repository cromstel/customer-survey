# customer-survey
Customer survey

Explanation of New Features
Admin Menu Addition:

The add_customer_survey_menu() function now includes a submenu page labeled View Feedback under Customer Survey.
This submenu page is connected to the display_survey_feedback() function, which displays all submitted feedback entries.
Feedback Viewing Functionality:

The display_survey_feedback() function retrieves all entries from the wp_customer_survey table and displays them in a neatly formatted table.
The table includes columns for the ID, Message, Submitted By (user’s email), and the Date when the feedback was submitted.
If no feedback entries are found, it displays a message indicating that no feedback has been submitted.
Feedback Table Styling:

The feedback entries are presented in a WordPress-style table for a clean and consistent look within the admin dashboard.
Usage
Submit Feedback:
Go to Customer Survey > Submit Feedback to access the form and submit feedback.
View Feedback:
Go to Customer Survey > View Feedback to view all feedback entries.
Each submission is listed with details in a table, making it easy to review customer feedback directly in the WordPress admin dashboard.
With this extended functionality, you can now both collect and view feedback submissions directly from the WordPress admin interface. This improvement makes it easy to manage customer surveys and view insights without needing external database access.
