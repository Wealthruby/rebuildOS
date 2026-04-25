Task 1: Create the Plugin Skeleton

Read README.md, PRODUCT_SPEC.md, SAFETY_AND_YMYL.md, and AGENTS.md.
Create the initial WordPress plugin structure.
Files:
rebuildos/
  rebuildos.php
  includes/
    class-rebuildos-shortcode.php
    class-rebuildos-admin.php
  assets/
    css/rebuildos.css
    js/rebuildos.js
  templates/
    app-shell.php

Requirements:
•	Plugin must activate without fatal errors.
•	Register shortcode [rebuild_os].
•	Shortcode renders RebuildOS app shell.
•	Include tabs for Today, Urge Log, Emergency Reset, Relapse Autopsy, Control Audit, Dashboard, Weekly Review, Export.
•	Include required safety disclaimer.
•	CSS must be scoped under .rebuildos-app.
•	JS must be loaded only when shortcode is present if practical.
Do not build full functionality yet.


Task 2: Build Guest localStorage Layer
Add JavaScript data storage for guest users.
Create a localStorage key:
rebuildos_v1_data
Data structure:
{
  "dailyCheckins": [],
  "urgeLogs": [],
  "relapseAutopsies": [],
  "controlAudits": [],
  "closedLoopActions": [],
  "weeklyReviews": []
}
Requirements:
•	Load saved data on page load.
•	Save new entries locally.
•	Allow clearing local data with confirmation.
•	Do not send guest data to server.


Task 3: Build Daily Rebuild Check-In
Create the Today tab.
Fields:
•	sleep quality 1-5
•	stress level 1-5
•	loneliness level 1-5
•	boredom level 1-5
•	energy level 1-5
•	screen risk 1-5
•	highest risk window
•	today’s boundary
•	minimum viable day action
•	notes
After save, show:
•	daily risk score
•	one suggested boundary
•	one minimum viable day reminder


Task 4: Build Urge Log
Create the Urge Log tab.
Fields:
•	date/time
•	urge intensity 1-10
•	trigger emotion
•	trigger context
•	device
•	location
•	app/site before urge
•	first small compromise
•	response action
•	outcome: survived / relapsed / unresolved
•	notes
After save, show a non-shaming message.


Task 5: Build Emergency Reset
Create the Emergency Reset tab.
Button:
I’m at risk
Flow:
1.	Step away from the device.
2.	Name the urge.
3.	Rate intensity.
4.	Identify the lie.
5.	Choose replacement action.
6.	Start 12-minute timer.
7.	Log outcome.
Save completed emergency reset as an urge log entry.


Task 6: Build Relapse Autopsy
Create the Relapse Autopsy tab.
Fields:
•	what happened
•	first small compromise
•	boundary that failed
•	emotion avoided
•	lie believed
•	replacement needed
•	system change for today
•	next clean action
After save, show:
•	likely weak point
•	one system adjustment
•	next clean action
Use non-shaming language only.


Task 7: Build Control Audit
Create the Control Audit tab.
Fields:
•	what feels out of control right now?
•	what app/habit/place/person/feeling has too much power?
•	what is one controllable action within reach?
•	what boundary can be set today?
•	what closed-loop task can be completed today?
After save, show:
•	control score
•	one control move for today


Task 8: Build Dashboard Insights
Create Dashboard calculations from localStorage.
Show:
•	most common trigger emotion
•	most common high-risk time window
•	most common device/context
•	average urge intensity
•	survived urges
•	relapse count
•	best replacement action
•	current rebuild score
•	suggested system adjustment
Use rule-based logic only.


Task 9: Build Weekly Rebuild Review
Create Weekly Review tab.
Fields:
•	biggest win
•	highest-risk moment
•	strongest trigger
•	best replacement action
•	repeated pattern
•	system weakness
•	next boundary
•	one sentence review
Allow user to save weekly review.


Task 10: Build Export and Import
Create Export tab.
Features:
•	Export JSON
•	Import JSON
•	Export CSV if practical
•	Clear all local data with confirmation
Include privacy reminder:
Guest data is stored only in this browser unless you export it.


Task 11: Add Admin Settings Page
Create a WordPress admin page under Settings > RebuildOS.
Settings:
•	enable/disable disclaimer display
•	customize disclaimer text
•	choose accent style
•	enable/disable guest mode
•	enable/disable export feature
Do not add paid features.


Task 12: Add Logged-In User Saving
Only after guest mode is stable.
Requirements:
•	Logged-in users can save data to their WordPress account.
•	Guest mode still works.
•	Provide migration from localStorage to account data.
•	Use secure AJAX with nonces.
•	Sanitize input.
•	Escape output.
