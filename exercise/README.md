# Finance App Trial Project

This project proposal has been put together to help developers who are applying for new positions but don't have any sample code to provide during the hiring process. The mockups are courtesy of [@DeliciousBrains](https://github.com/deliciousbrains)

## Requirements

The trial project is split into two phases. This helps to break the project into smaller, more manageable chunks. 

The trial project should be built using Laravel and Vue.js.

### Phase 1

A list of the user's balance entries should be shown by default. Entries should be grouped by date. Although pagination is missing from the mockups, please add basic pagination for when more than 100 entries exist.

![](mockups/yourbalance-1-default@2x.png)

A user should be able to add single balance entries. Adding a new entry should update the balance list and the total balance.

![](mockups/yourbalance-2-add-item-modal@2x.png)

Hovering over an entry should show the edit and delete links.

![](mockups/yourbalance-3-rollover-actions@2x.png)

Clicking 'Delete' should remove the entry from the list and update the total balance. Clicking 'Edit' should reveal the edit form.

![](mockups/yourbalance-4-edit-item@2x.png)

Clicking 'Update Entry' should update the balance list and update the total balance.

### Phase 2

A [CSV file](data/5000-balance-entries.csv) of entries can be imported. The import should happen in the background. The 'Add Entry' and 'Import CSV' buttons should be disabled while the import is working, however, existing entries can be edited or deleted.

![](mockups/yourbalance-6-import-csv-file-selected@2x.png)

Imported entries should not appear in the balance list, until the entire import has completed. When the import is running, a notice should be shown with the count of entries currently being imported.

![](mockups/yourbalance-7-csv-uploading@2x.png)
