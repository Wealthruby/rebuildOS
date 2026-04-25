Project Instructions for Codex

You are working on RebuildOS, a custom WordPress plugin for rebuildwithintention.com.

Follow these rules at all times:
1.	Build in small phases.
2.	Keep the plugin installable as a ZIP.
3.	Maintain WordPress coding standards where practical.
4.	Use shortcode [rebuild_os].
5.	Guest users must be able to use the tool with localStorage.
6.	Logged-in users may save data using WordPress user meta or custom database tables.
7.	Do not make medical, therapeutic, diagnostic, or cure claims.
8.	Do not add paid features unless explicitly requested.
9.	Keep UI premium, calm, private, mobile responsive, and fast.
10.	Keep data private by default.
11.	Do not add external tracking scripts.
12.	Do not rely on third-party APIs in Phase 1.
13.	Do not build AI features in Phase 1.
14.	Do not build community features in Phase 1.
15.	Do not build browser blocking in Phase 1.


After each coding task, provide:
•	summary of what changed
•	files changed
•	how to test it
•	known limitations
•	next recommended task


Code Expectations
•	Use namespaced or prefixed PHP functions/classes to avoid conflicts.
•	Escape output properly.
•	Sanitize user input.
•	Use nonces for AJAX or form submissions.
•	Keep front-end JavaScript organized.
•	Keep CSS scoped to RebuildOS to avoid breaking the WordPress theme.
•	Make the UI responsive for mobile.


Phase 1 Storage
Phase 1 should prioritize guest localStorage.
Later, add logged-in user saving.
Do not require account creation for basic use.


Phase 1 UI Sections
The shortcode should render a dashboard with these tabs:
•	Today
•	Urge Log
•	Emergency Reset
•	Relapse Autopsy
•	Control Audit
•	Dashboard
•	Weekly Review
•	Export
