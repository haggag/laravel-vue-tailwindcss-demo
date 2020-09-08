# Overview

The goal of this excerise is to demonstrate Laravel, Vue, and Tailwind CSS.

## Excerise

The excerise instructions can be found [here](exercise/README.md).

## Solution

The solution can be found under the solution folder.

# Highlights
The project was a little bit rushed to get it done quickly (while experimenting with Tailwind CSS) and It doesn't really touch the problems that large scale apps face but I tried to hint some of them. Here you are some highlights and thoughts about the app:
* I have tried to use some best practices that are more appropriate for larger projects, such as:
componentizing the project (on both Vue and Laravel sides). Though with refactoring, more consolidation and code reused can be achieved
* I used Vuex, services, filters ...etc
* I have done a basic notifications system, where real-time notifications are sent via Pusher and are persisted in the database. Users are notified by the notification badge/icon if they have unread notifications. Once the notifications page is opened, all are marked read.
* Real-time Pusher notifications: real-time notifications are sent while the user is logged in via WebSockets to keep the user up to date with the status of the background jobs. Some of the real-time notifications aren't persisted in the DB. Just sent if the user is online to show the progress.
* Notifications are sent in real-time, but I log them in the console to review them if you need them.
* I have configured Github Actions as the basis for simple CI that runs automated tests and other tasks.

Here you are some more details :

## About Performance:
* Done some caching: Total balance is cached per user and whenever an operation is made the delta of the change (increase or decrease) is directly added to the balance to avoid full recalculation.
* The Skeleton loader is implementing while fetching data from the API.
* Minimal data is stored in backend and different formats are generated on client-side (i.e. date_time and currency formatting)
* Some Vue best practices are considered, for example, vi-if is used to show/hide components less frequently toggled. On the other hand, v-show is used for frequently toggled components such as when hovering on components.
* Vuex is used to centralize data storage and avoids data duplication
* Smart functionality is used to pop items from the store after adding an item (instead of reloading all page data). Similar functionality can be implemented for deleting/updating entries to compensate for lost items (but wasn't implemented in phase1)
* Network access is minimized for example pagination values are computed on CRUD operations (instead of reading it from the server). Upon API success the new item is added/moved/deleted in the client-side without needing to retrieving the full page again from the server.
* Purged Tailwindcss to get rid of all unused classes. Similarly, minified JS code.
* Background job processing: Regulating writing to the database is
one of the worst problems I had in the Kayako was that users can show up in at any time (especially during campaigns, hot seasons …etc) and start using the system. By doing so the system sees a huge non-deterministic spike in writing to the DB, which ends up with random outages. While with typical Writer/Readers cluster configuration reading can scale well but write usually is the bottleneck. The solution is queuing and more importantly to be able to fine-tune the producing rate.
* Hence I have enabled the following to be processed in the background by queuing a job which is processed asynchronously: 1. Efficient Processing the CSV file (with placeholder code to validate the records on the go ). Sending the notifications are also send in the background job.
* Submitting the Upload File form almost takes only the time needed to upload the file, with almost no processing done. Even showing the records count is delayed until the background job starts processing. Initially, the message is displayed without the count, then when the job starts the count is efficiently calculated (see later on that) then another real-time notification is sent to the client with the records count, then
* Sending the pusher notifications
* Not only a queue is used to send broadcast events in the background, but more importantly, a separate queue is used to avoid other queue items (ex import jobs) from slowing down real-time event broadcast (but to make it easy to run for the demo I forced the notifications queue to run in sync mode)
* CSV progressing: I have written a simple CSV reader where it reads batches of records with the help of a PHP generator, and each batch is bulk inserted in the DB.
## Reliability:
* DB Transactions: Operations that affect user balance (i.e Create/Update/Delete) are encapsulated in transactions with user balance update. This is to avoid having the individual entries sum out of sync with the cached total balance.
* When generating the API tokens, although it's very unlikely to generate a duplicate token, but to protect the system's data from being in a corrupted state, you'll find me handling that case too. The consequence if a duplicate token is generated that the system won't be usable for that user.
* Detailed validation is done on the client-side to make sure the mime type is text/csv, size is maximum 1MB, non-empty field...etc. The same validations are done on the Laravel side.
## Design:
* Responsive mobile-first.
* There are minor differences compared to mockups, like the colors. For example, I preferred to use the default Tailw design palette. As well as other minor changes
## Security:
* Mechanism: I used a simple API token approach. It’s not much different from a username and password: there’s a single token assigned to each user that clients can pass along with a request to authenticate that request for that user.
* API token is rotated with every session for added security
* Validation in client-side and serverside, with effects to highlight the error
## Future work & Known issues:
* I really wanted to implement some automated tests (in both JS and PHP) but favored to finish the simple app faster.
* Introduce progress indicator while interacting with the API
* Better caching: For example while paginating more items can be cached to move forward and backward faster.
* I have made a simple CI pipeline using Github Actions that runs the default Laravel tests, just to get the green tick in Github ;). But in reality, it should do more such as code style enforcement, as well as PHP static analysis, and running the front-end tests...etc
* The code can benefit from some refactoring.
* And maybe extract the CSV reader in its own plain PHP package.
